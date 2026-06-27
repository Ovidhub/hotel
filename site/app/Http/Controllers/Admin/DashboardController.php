<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Revenue ──────────────────────────────────────────────────────────
        // Total collected revenue: sum of commitment fees across all bookings.
        $totalRevenue = Booking::sum('commitment_fee') ?? 0;

        // Today's collected revenue.
        $todayRevenue = Booking::whereDate('created_at', today())->sum('commitment_fee') ?? 0;

        // ── Occupancy % ──────────────────────────────────────────────────────
        // Formula: (confirmed/checked-in bookings whose check-in ≤ today AND
        //           check-out ≥ today) ÷ total active units × 100.
        $confirmedToday = Booking::whereIn('status', ['Confirmed', 'Checked In'])
            ->whereDate('check_in', '<=', today())
            ->whereDate('check_out', '>=', today())
            ->count();

        $totalUnitsCount = Room::where('is_active', true)->count()
            + Apartment::where('is_active', true)->count();

        $occupancyPct = $totalUnitsCount > 0
            ? round(($confirmedToday / $totalUnitsCount) * 100)
            : 0;

        // ── Open / pending bookings ──────────────────────────────────────────
        $openBookings  = Booking::where('status', 'Pending Payment')->count();
        $totalBookings = Booking::count();

        // ── Available units ──────────────────────────────────────────────────
        $availableUnits = Room::where('is_active', true)->count()
            + Apartment::where('is_active', true)->where('status', 'Available')->count();

        // ── Recent bookings ──────────────────────────────────────────────────
        $recentBookings = Booking::with('bookable')->latest()->take(8)->get();

        // ── Chart: bookings & revenue per day for the last 7 days ───────────
        $last7Days = collect(range(6, 0))->map(fn ($i) => today()->subDays($i));

        $chartLabels = $last7Days->map(fn ($d) => $d->format('M j'))->values()->toArray();

        // Revenue per day (commitment_fee sums grouped by date)
        $revenueByDay = Booking::select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('SUM(commitment_fee) as total')
            )
            ->where('created_at', '>=', today()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $chartRevenue = $last7Days->map(
            fn ($d) => (float) ($revenueByDay[$d->toDateString()] ?? 0)
        )->values()->toArray();

        // Booking count per day
        $countByDay = Booking::select(
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', today()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $chartCounts = $last7Days->map(
            fn ($d) => (int) ($countByDay[$d->toDateString()] ?? 0)
        )->values()->toArray();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'todayRevenue',
            'occupancyPct',
            'openBookings',
            'totalBookings',
            'availableUnits',
            'recentBookings',
            'chartLabels',
            'chartRevenue',
            'chartCounts',
        ));
    }
}
