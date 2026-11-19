<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today('Asia/Jakarta');
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // 1. Total Pesanan Keseluruhan
        $totalOrders = Booking::count();

        // 2. Antrean Hari Ini (yang aktif, belum dibatalkan)
        $todayQueue = Booking::whereDate('booking_date', $today)
            ->whereNotIn('booking_status', ['cancelled'])
            ->count();

        // 3. Tingkat Keterisian Kursi (Occupancy Rate %) Hari Ini
        $activeBarberCount = Barber::where('is_active', true)->count();
        $activeBarberCount = max($activeBarberCount, 1);
        $totalDailyCapacity = $activeBarberCount * 11; // 11 slots between 10:00 and 20:00
        $occupancyRate = min(100, round(($todayQueue / $totalDailyCapacity) * 100, 1));

        // 4. Total Pendapatan Hari Ini & Bulan Ini (Strictly paid / completed)
        $todayRevenue = (int) Booking::paidOrCompleted()
            ->whereDate('booking_date', $today)
            ->sum('total_price');

        $monthlyRevenue = (int) Booking::paidOrCompleted()
            ->whereBetween('booking_date', [$startOfMonth, $endOfMonth])
            ->sum('total_price');

        // Chart 1: Tren Pendapatan & Kunjungan 6 Bulan Terakhir
        $monthlyLabels = [];
        $monthlyRevenueData = [];
        $monthlyOrdersData = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = $today->copy()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyLabels[] = $month->translatedFormat('M Y');
            $monthlyRevenueData[] = (int) Booking::paidOrCompleted()
                ->whereBetween('booking_date', [$monthStart, $monthEnd])
                ->sum('total_price');

            $monthlyOrdersData[] = Booking::whereBetween('booking_date', [$monthStart, $monthEnd])
                ->whereNotIn('booking_status', ['cancelled'])
                ->count();
        }

        // Chart 2: Statistik Layanan Terpopuler (Donut Chart)
        $popularServicesRaw = Booking::select('service_id', DB::raw('count(*) as total'))
            ->whereNotIn('booking_status', ['cancelled'])
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('service')
            ->get();

        $serviceLabels = [];
        $serviceCounts = [];
        foreach ($popularServicesRaw as $item) {
            $serviceLabels[] = $item->service->name ?? 'Layanan Lain';
            $serviceCounts[] = $item->total;
        }

        // Chart 3: Statistik Jam Tersibuk (Peak Hours 10:00 - 20:00)
        $peakHoursLabels = ['10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
        $peakHoursCounts = [];

        foreach ($peakHoursLabels as $hourStr) {
            $count = Booking::whereTime('booking_time', 'like', $hourStr . '%')
                ->whereNotIn('booking_status', ['cancelled'])
                ->count();
            $peakHoursCounts[] = $count;
        }

        // Antrean hari ini
        $recentBookings = Booking::with(['service', 'barber'])
            ->whereDate('booking_date', $today)
            ->orderBy('booking_time', 'asc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalOrders',
            'todayQueue',
            'occupancyRate',
            'todayRevenue',
            'monthlyRevenue',
            'monthlyLabels',
            'monthlyRevenueData',
            'monthlyOrdersData',
            'serviceLabels',
            'serviceCounts',
            'peakHoursLabels',
            'peakHoursCounts',
            'recentBookings'
        ));
    }
}
