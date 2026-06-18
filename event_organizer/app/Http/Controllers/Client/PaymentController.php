<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);

        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTPHEADER => [],
        ];
    }

    private function getBillingDetails(Event $event)
    {
        $package = $event->weddingPackage ?? $event->package;
        $basePackageName = $package ? $package->name : 'Custom Package';
        $basePackagePrice = $package ? $package->base_price : 0;
        $totalPrice = $basePackagePrice;

        $packageId = $event->wedding_package_id ?? $event->package_id;
        $baseCosts = [];

        if ($packageId) {
            $allowedVendors = DB::table('package_vendor_pivot')
                ->where('package_id', $packageId)
                ->get()
                ->groupBy('vendor_category_id');

            $templateCategories = DB::table('package_templates')
                ->where('package_id', $packageId)
                ->where('is_included', true)
                ->pluck('vendor_category_id')
                ->toArray();

            foreach ($templateCategories as $catId) {
                $allowedIds = collect($allowedVendors[$catId] ?? [])->pluck('vendor_id');
                $minPrice = DB::table('vendor_packages')
                    ->whereIn('vendor_id', $allowedIds)
                    ->where('vendor_category_id', $catId)
                    ->min('price');
                $baseCosts[$catId] = $minPrice ?? 0;
            }
        }

        $verifiedSlots = DB::table('event_vendor')
            ->leftJoin('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->where('event_vendor.event_id', $event->id)
            ->whereIn('event_vendor.status', ['verified', 'signed'])
            ->select('event_vendor.*', 'vendor_categories.name as category_name', 'vendors.name as vendor_name')
            ->orderBy('event_vendor.updated_at', 'desc')
            ->get();

        $additionalItems = [];

        foreach ($verifiedSlots as $slot) {
            $dealPrice = $slot->deal_price;
            $isIncluded = $slot->is_included;
            $categoryId = $slot->vendor_category_id;
            $addedCost = 0;
            $type = '';

            if ($isIncluded && $packageId) {
                $baseCost = $baseCosts[$categoryId] ?? 0;
                $upgradeFee = max(0, $dealPrice - $baseCost);
                if ($upgradeFee > 0) {
                    $addedCost = $upgradeFee;
                    $type = 'Upgrade Fee';
                }
            } else {
                $addedCost = $dealPrice;
                $type = 'Custom Add-on';
            }

            if ($addedCost > 0) {
                $additionalItems[] = (object)[
                    'vendor_name' => $slot->vendor_name,
                    'category_name' => $slot->category_name,
                    'added_cost' => $addedCost,
                    'type' => $type,
                    'verified_at' => $slot->updated_at,
                ];
                $totalPrice += $addedCost;
            }
        }

        return [
            'basePackageName' => $basePackageName,
            'basePackagePrice' => $basePackagePrice,
            'additionalItems' => $additionalItems,
            'totalPrice' => $totalPrice
        ];
    }

    public function index(Event $event)
    {
        if ((int)$event->client_id !== (int)Auth::id()) {
            abort(403);
        }

        $billingDetails = $this->getBillingDetails($event);
        $totalPrice = $billingDetails['totalPrice'];

        $successfulPayments = $event->payments()->where('status', 'success')->sum('amount');
        $remainingBalance = max(0, $totalPrice - $successfulPayments);
        $payments = $event->payments()->orderBy('created_at', 'desc')->get();

        return view('client.payment', compact('event', 'totalPrice', 'successfulPayments', 'remainingBalance', 'payments', 'billingDetails'));
    }

    public function pay(Request $request, Event $event)
    {
        if ((int)$event->client_id !== (int)Auth::id()) {
            return response()->json(['error' => 'Unauthorized action.'], 403);
        }

        $request->validate([
            'payment_type' => 'required|in:dp,termin_2,termin_3,settlement',
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = (int) round($request->amount);
        $pendingPayment = $event->payments()->where('status', 'pending')->first();

        if ($pendingPayment && $pendingPayment->midtrans_snap_token) {
            return response()->json(['snap_token' => $pendingPayment->midtrans_snap_token]);
        }

        $orderId = 'PAY-' . $event->id . '-' . time();

        $itemName = substr('Pay ' . strtoupper(str_replace('_', ' ', $request->payment_type)) . ' - ' . $event->title, 0, 50);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [
                [
                    'id' => $request->payment_type,
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => $itemName
                ]
            ],
            'callbacks' => [
                'finish' => route('client.events.billing', $event->id)
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            Payment::create([
                'event_id' => $event->id,
                'midtrans_order_id' => $orderId,
                'midtrans_snap_token' => $snapToken,
                'amount' => $amount,
                'payment_type' => $request->payment_type,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Midtrans Error: ' . $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('Midtrans Webhook Masuk:', $request->all());

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

            if ($payment) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $payment->update(['status' => 'success']);
                    \Illuminate\Support\Facades\Log::info('Sukses! Database diupdate untuk Order: ' . $request->order_id);
                } elseif ($request->transaction_status == 'expire') {
                    $payment->update(['status' => 'expired']);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $payment->update(['status' => 'failed']);
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Gagal: Order ID tidak ditemukan di database.');
            }
        } else {
            \Illuminate\Support\Facades\Log::error('Gagal: Signature Key Keamanan Midtrans Tidak Cocok!');
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }
}
