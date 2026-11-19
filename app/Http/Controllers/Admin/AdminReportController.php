<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));

        $query = Booking::with(['service', 'barber'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc');

        $bookings = (clone $query)->paginate(20)->withQueryString();

        // Financial aggregates (Strictly paid or completed)
        $paidQuery = Booking::paidOrCompleted()
            ->whereBetween('booking_date', [$startDate, $endDate]);

        $totalRevenue = (int) (clone $paidQuery)->sum('total_price');
        $onlineRevenue = (int) (clone $paidQuery)->where('booking_type', 'online')->sum('total_price');
        $walkInRevenue = (int) (clone $paidQuery)->where('booking_type', 'walk_in')->sum('total_price');
        $paidCount = (clone $paidQuery)->count();
        $avgTicket = $paidCount > 0 ? round($totalRevenue / $paidCount) : 0;

        return view('admin.reports.index', compact(
            'bookings',
            'startDate',
            'endDate',
            'totalRevenue',
            'onlineRevenue',
            'walkInRevenue',
            'paidCount',
            'avgTicket'
        ));
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));

        $bookings = Booking::with(['service', 'barber'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->orderBy('booking_date', 'asc')
            ->get();

        $filename = "Laporan_Keuangan_AD_Barber_{$startDate}_sd_{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $columns = [
            'Kode Booking',
            'Tanggal',
            'Jam',
            'Nama Pelanggan',
            'No. WhatsApp',
            'Layanan',
            'Kapster',
            'Tipe Reservasi',
            'Metode Pembayaran',
            'Total Harga (Rp)',
            'Status Pembayaran',
            'Status Pesanan'
        ];

        $callback = function () use ($bookings, $columns) {
            $file = fopen('php://output', 'w');
            // Write UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->booking_code,
                    $b->booking_date->format('d/m/Y'),
                    substr($b->booking_time, 0, 5),
                    $b->customer_name,
                    $b->customer_whatsapp,
                    $b->service->name ?? '-',
                    $b->barber->name ?? '-',
                    $b->booking_type === 'online' ? 'Online' : 'Walk-In',
                    strtoupper($b->payment_method),
                    $b->total_price,
                    strtoupper($b->payment_status),
                    strtoupper($b->booking_status),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::today('Asia/Jakarta')->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::today('Asia/Jakarta')->endOfMonth()->format('Y-m-d'));

        $bookings = Booking::with(['service', 'barber'])
            ->whereBetween('booking_date', [$startDate, $endDate])
            ->orderBy('booking_date', 'asc')
            ->get();

        $paidQuery = Booking::paidOrCompleted()
            ->whereBetween('booking_date', [$startDate, $endDate]);

        $totalRevenue = (int) (clone $paidQuery)->sum('total_price');
        $onlineRevenue = (int) (clone $paidQuery)->where('booking_type', 'online')->sum('total_price');
        $walkInRevenue = (int) (clone $paidQuery)->where('booking_type', 'walk_in')->sum('total_price');
        $paidCount = (clone $paidQuery)->count();

        $settings = SiteSetting::getAllSettings();

        return view('admin.reports.print', compact(
            'bookings',
            'startDate',
            'endDate',
            'totalRevenue',
            'onlineRevenue',
            'walkInRevenue',
            'paidCount',
            'settings'
        ));
    }
}
