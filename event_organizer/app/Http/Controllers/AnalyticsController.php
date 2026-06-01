<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function ownerDashboard(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfYear()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        $monthlyFinances = [];

        $tempStart = $start->copy();
        while ($tempStart <= $end) {
            $monthKey = $tempStart->format('M Y');
            $monthlyFinances[$monthKey] = ['month' => $monthKey, 'gross' => 0, 'net' => 0];
            $tempStart->addMonth();
        }

        $eventsInPeriod = Event::whereBetween('event_date', [$startDate, $endDate])
            ->whereIn('status', ['ongoing', 'completed'])
            ->with(['package', 'vendors'])
            ->get();

        foreach ($eventsInPeriod as $event) {
            $monthKey = Carbon::parse($event->event_date)->format('M Y');
            if (!isset($monthlyFinances[$monthKey])) continue;

            $basePackagePrice = $event->package ? $event->package->base_price : 0;
            $additionalSellingPrice = 0;
            $eventVendorCost = 0;

            $baseCosts = [];
            if ($event->package_id) {
                $allowedVendors = DB::table('package_vendor_pivot')
                    ->where('package_id', $event->package_id)
                    ->get()
                    ->groupBy('vendor_category_id');

                $templateCategories = DB::table('package_templates')
                    ->where('package_id', $event->package_id)
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

            $vendorPackageIds = [];
            foreach ($event->vendors as $slot) {
                if ($slot->pivot->vendor_package_id) {
                    $vendorPackageIds[] = $slot->pivot->vendor_package_id;
                }
            }
            $packages = DB::table('vendor_packages')->whereIn('id', $vendorPackageIds)->get()->keyBy('id');

            foreach ($event->vendors as $slot) {
                $pkg = isset($packages[$slot->pivot->vendor_package_id]) ? $packages[$slot->pivot->vendor_package_id] : null;

                $dPrice = $slot->pivot->deal_price > 0 ? $slot->pivot->deal_price : ($pkg->price ?? 0);
                $nPrice = $slot->pivot->net_price > 0 ? $slot->pivot->net_price : ($pkg->net_price ?? 0);

                $eventVendorCost += $nPrice;

                if ($slot->pivot->is_included) {
                    $baseAllowance = $baseCosts[$slot->pivot->vendor_category_id] ?? 0;
                    if ($dPrice > $baseAllowance) {
                        $upgradeFee = $dPrice - $baseAllowance;
                        $additionalSellingPrice += $upgradeFee;
                    }
                } else {
                    $additionalSellingPrice += $dPrice;
                }
            }

            $sellingPrice = $basePackagePrice + $additionalSellingPrice;

            $monthlyFinances[$monthKey]['gross'] += $sellingPrice;
            $monthlyFinances[$monthKey]['net'] += ($sellingPrice - $eventVendorCost);
        }

        $monthlyFinances = array_values($monthlyFinances);

        $expenditures = DB::table('event_vendor')
            ->join('events', 'event_vendor.event_id', '=', 'events.id')
            ->join('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendor_packages', 'event_vendor.vendor_package_id', '=', 'vendor_packages.id')
            ->whereBetween('events.event_date', [$startDate, $endDate])
            ->whereIn('events.status', ['ongoing', 'completed'])
            ->select(
                'vendor_categories.name as category',
                DB::raw('SUM(CASE WHEN event_vendor.net_price > 0 THEN event_vendor.net_price ELSE COALESCE(vendor_packages.net_price, 0) END) as total_spent')
            )
            ->groupBy('vendor_categories.name')
            ->orderByDesc('total_spent')
            ->get();

        $eventStatuses = DB::table('events')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')->toArray();

        $plPerformances = DB::table('events')
            ->join('users', 'events.pl_id', '=', 'users.id')
            ->whereBetween('events.event_date', [$startDate, $endDate])
            ->whereNotNull('events.pl_id')
            ->select('users.name', DB::raw('count(events.id) as total_events'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_events')
            ->limit(5)
            ->get();

        $topVendors = DB::table('event_vendor')
            ->join('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->join('events', 'event_vendor.event_id', '=', 'events.id')
            ->whereBetween('events.event_date', [$startDate, $endDate])
            ->whereIn('event_vendor.status', ['verified', 'signed'])
            ->select('vendors.name', DB::raw('count(event_vendor.id) as jobs_assigned'))
            ->groupBy('vendors.id', 'vendors.name')
            ->orderByDesc('jobs_assigned')
            ->limit(5)
            ->get();

        $totalRevenueYTD = array_sum(array_column($monthlyFinances, 'gross'));
        $totalProfitYTD = array_sum(array_column($monthlyFinances, 'net'));
        $totalEventsYTD = array_sum($eventStatuses);

        return view('owner.dashboard', compact(
            'monthlyFinances',
            'expenditures',
            'eventStatuses',
            'plPerformances',
            'topVendors',
            'totalRevenueYTD',
            'totalProfitYTD',
            'totalEventsYTD',
            'startDate',
            'endDate'
        ));
    }

    public function plDashboard()
    {
        $plId = Auth::id();
        $myEvents = Event::where('pl_id', $plId)->orderBy('event_date', 'asc')->get();
        $totalManagedEvents = $myEvents->count();

        return view('pl.dashboard', compact('myEvents', 'totalManagedEvents'));
    }

    public function eventAnalytics(Event $event)
    {
        $role = Auth::user()->role;

        if ($role === 'klien' && $event->client_id !== Auth::id()) abort(403);
        if ($role === 'pl' && $event->pl_id !== Auth::id()) abort(403);

        $hasFenixGuestbook = DB::table('event_vendor')
            ->leftJoin('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->where('event_vendor.event_id', $event->id)
            ->where('vendor_categories.name', 'like', '%Guest Book%')
            ->where('vendors.name', 'like', '%Fenix EO%')
            ->whereIn('event_vendor.status', ['verified', 'signed'])
            ->exists();

        if (!$hasFenixGuestbook) {
            return redirect()->route($role . '.events.manage', $event->id)->with('error', 'Analytics feature requires Fenix EO Digital Guestbook to be verified first.');
        }

        $guests = DB::table('guests')->where('event_id', $event->id)->get();

        $totalInvited = $guests->count();
        $totalCheckedIn = $guests->where('status', 'checked_in')->count();
        $totalDeclined = $guests->where('status', 'not_attending')->count();
        $totalPending = $guests->where('status', 'pending')->count();
        $totalAttendingNoCheckin = $guests->where('status', 'attending')->count();

        $paxExpected = $guests->sum('pax_invited');
        $paxActual = $guests->where('status', 'checked_in')->sum('pax_actual');

        $giftFisik = $guests->where('angpao_type', 'fisik')->sum('angpao_count');
        $giftDigital = $guests->where('angpao_type', 'digital')->sum('angpao_count');
        $totalTitipan = $guests->where('angpao_titipan', 1)->count();

        if ($role === 'klien') {
            return view('client.analytics', compact(
                'event',
                'role',
                'totalInvited',
                'totalCheckedIn',
                'totalDeclined',
                'totalPending',
                'totalAttendingNoCheckin',
                'paxExpected',
                'paxActual',
                'giftFisik',
                'giftDigital',
                'totalTitipan'
            ));
        }

        $vendorAllocations = DB::table('event_vendor')
            ->leftJoin('vendor_categories', 'event_vendor.vendor_category_id', '=', 'vendor_categories.id')
            ->leftJoin('vendors', 'event_vendor.vendor_id', '=', 'vendors.id')
            ->where('event_vendor.event_id', $event->id)
            ->select('vendor_categories.name as category', 'vendors.name as vendor_name', 'event_vendor.deal_price', 'event_vendor.status')
            ->get();

        $trafficData = DB::table('guests')
            ->where('event_id', $event->id)
            ->where('status', 'checked_in')
            ->whereNotNull('check_in_time')
            ->select(DB::raw("CONCAT(DATE_FORMAT(check_in_time, '%H:'), IF(MINUTE(check_in_time) < 30, '00', '30')) as time_slot"), DB::raw('COUNT(*) as total'))
            ->groupBy('time_slot')
            ->orderBy('time_slot')
            ->get();

        return view('analytics.event_analytics', compact(
            'event',
            'role',
            'totalInvited',
            'totalCheckedIn',
            'totalDeclined',
            'totalPending',
            'totalAttendingNoCheckin',
            'paxExpected',
            'paxActual',
            'giftFisik',
            'giftDigital',
            'totalTitipan',
            'vendorAllocations',
            'trafficData'
        ));
    }
}
