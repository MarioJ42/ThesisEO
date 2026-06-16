<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $eventId = 1;
        $event = DB::table('events')->where('id', $eventId)->first();

        if (!$event) return;

        $package = DB::table('wedding_packages')->where('id', $event->package_id)->first();
        $totalPrice = $package ? $package->base_price : 0;

        $packageId = $event->package_id;
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
            ->where('event_id', $event->id)
            ->whereIn('status', ['verified', 'signed'])
            ->get();

        foreach ($verifiedSlots as $slot) {
            $dealPrice = $slot->deal_price;
            $isIncluded = $slot->is_included;
            $categoryId = $slot->vendor_category_id;

            $addedCost = 0;

            if ($isIncluded && $packageId) {
                $baseCost = $baseCosts[$categoryId] ?? 0;
                $upgradeFee = max(0, $dealPrice - $baseCost);
                if ($upgradeFee > 0) {
                    $addedCost = $upgradeFee;
                }
            } else {
                $addedCost = $dealPrice;
            }
            $totalPrice += $addedCost;
        }

        $dpAmount = $totalPrice * 0.50;
        $termin2Amount = $totalPrice * 0.20;
        $settlementAmount = $totalPrice - $dpAmount - $termin2Amount;

        $now = Carbon::now();

        $payments = [
            [
                'event_id' => $eventId,
                'midtrans_order_id' => 'PAY-' . $eventId . '-' . time() . '1',
                'midtrans_snap_token' => Str::random(30),
                'amount' => $dpAmount,
                'payment_type' => 'dp',
                'payment_method' => 'midtrans',
                'status' => 'success',
                'payment_date' => clone $now->subMonths(6),
                'created_at' => clone $now->subMonths(6),
                'updated_at' => clone $now->subMonths(6),
            ],
            [
                'event_id' => $eventId,
                'midtrans_order_id' => 'PAY-' . $eventId . '-' . time() . '2',
                'midtrans_snap_token' => Str::random(30),
                'amount' => $termin2Amount,
                'payment_type' => 'termin_2',
                'payment_method' => 'transfer',
                'status' => 'success',
                'payment_date' => clone $now->subMonths(3),
                'created_at' => clone $now->subMonths(3),
                'updated_at' => clone $now->subMonths(3),
            ],
            [
                'event_id' => $eventId,
                'midtrans_order_id' => 'PAY-' . $eventId . '-' . time() . '3',
                'midtrans_snap_token' => Str::random(30),
                'amount' => $settlementAmount,
                'payment_type' => 'settlement',
                'payment_method' => 'midtrans',
                'status' => 'success',
                'payment_date' => clone $now->subDays(7),
                'created_at' => clone $now->subDays(7),
                'updated_at' => clone $now->subDays(7),
            ]
        ];

        DB::table('payments')->insert($payments);
    }
}
