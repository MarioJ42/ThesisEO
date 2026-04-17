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
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    private function calculateTotalPrice(Event $event)
    {
        // 1. Ambil Base Price dari paket
        $package = $event->weddingPackage ?? $event->package;
        $totalPrice = $package ? $package->base_price : 0;

        $packageId = $event->wedding_package_id ?? $event->package_id;

        // 2. Kalkulasi Base Cost (Jatah Budget) persis seperti di EventController
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

        // 3. Ambil slot vendor yang sudah Verified langsung dari database pivot
        $verifiedSlots = DB::table('event_vendor')
            ->where('event_id', $event->id)
            ->where('status', 'verified')
            ->get();

        foreach ($verifiedSlots as $slot) {
            $dealPrice = $slot->deal_price;
            $isIncluded = $slot->is_included;
            $categoryId = $slot->vendor_category_id;

            // 4. Terapkan Logika Bisnis
            if ($isIncluded) {
                // Jika Included: Cari selisih Upgrade
                $baseCost = $baseCosts[$categoryId] ?? 0;
                $upgradeFee = max(0, $dealPrice - $baseCost);
                $totalPrice += $upgradeFee;
            } else {
                // Jika Custom (Tidak Included): Tambahkan seluruh deal_price
                $totalPrice += $dealPrice;
            }
        }

        return $totalPrice;
    }

    public function index(Event $event)
    {
        if ($event->client_id !== Auth::id()) {
            abort(403);
        }

        $totalPrice = $this->calculateTotalPrice($event);
        $successfulPayments = $event->payments()->where('status', 'success')->sum('amount');
        $remainingBalance = max(0, $totalPrice - $successfulPayments);
        $payments = $event->payments()->orderBy('created_at', 'desc')->get();

        return view('client.payment', compact('event', 'totalPrice', 'successfulPayments', 'remainingBalance', 'payments'));
    }

    public function pay(Request $request, Event $event)
    {
        if ($event->client_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_type' => 'required|in:dp,termin_2,termin_3,settlement',
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->amount;
        $pendingPayment = $event->payments()->where('status', 'pending')->first();

        if ($pendingPayment && $pendingPayment->midtrans_snap_token) {
            return response()->json(['snap_token' => $pendingPayment->midtrans_snap_token]);
        }

        $orderId = 'PAY-' . $event->id . '-' . time();

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
                    'name' => 'Payment ' . strtoupper(str_replace('_', ' ', $request->payment_type)) . ' - ' . $event->title
                ]
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $payment = Payment::where('midtrans_order_id', $request->order_id)->first();

            if ($payment) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $payment->update(['status' => 'success']);
                } elseif ($request->transaction_status == 'expire') {
                    $payment->update(['status' => 'expired']);
                } elseif ($request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $payment->update(['status' => 'failed']);
                }
            }
        }

        return response()->json(['message' => 'Callback handled successfully']);
    }
}
