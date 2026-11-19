@extends('layouts.admin')

@section('title', 'Dashboard & Business Analytics')
@section('page_title', 'Ringkasan & Analitik Bisnis')

@section('content')
<div class="space-y-8">

    <!-- 4 KPI CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- KPI 1: Total Pesanan -->
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Total Pesanan</span>
                <span class="text-3xl font-black text-zinc-900 mt-1 block">{{ number_format($totalOrders, 0, ',', '.') }}</span>
                <span class="text-[11px] font-semibold text-emerald-600 flex items-center gap-1 mt-1">
                    <span>●</span> Keseluruhan Reservasi
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center font-bold text-2xl">
                📋
            </div>
        </div>

        <!-- KPI 2: Antrean Hari Ini -->
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Antrean Hari Ini</span>
                <span class="text-3xl font-black text-zinc-900 mt-1 block">{{ $todayQueue }}</span>
                <span class="text-[11px] font-semibold text-blue-600 flex items-center gap-1 mt-1">
                    <span>●</span> Sesi Terjadwal
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-2xl">
                ⏳
            </div>
        </div>

        <!-- KPI 3: Occupancy Rate % -->
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Keterisian Kursi</span>
                <span class="text-3xl font-black text-zinc-900 mt-1 block">{{ $occupancyRate }}%</span>
                <span class="text-[11px] font-semibold text-zinc-500 flex items-center gap-1 mt-1">
                    <span>Kapasitas Harian</span>
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-2xl">
                💺
            </div>
        </div>

        <!-- KPI 4: Pendapatan Hari Ini & Bulan Ini (Strictly paid / completed) -->
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Pendapatan Bersih</span>
                <span class="text-2xl font-black text-zinc-900 mt-1 block">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</span>
                <span class="text-[11px] font-semibold text-yellow-600 block mt-1">
                    Bulan Ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                </span>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-2xl">
                💰
            </div>
        </div>
    </div>

    <!-- 3 CHART.JS VISUALIZATIONS -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Chart 1: Tren Pendapatan & Kunjungan 6 Bulan (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-7 border border-zinc-200 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900">Tren Pendapatan & Kunjungan Bulanan</h3>
                    <p class="text-xs text-zinc-500">Performa transaksi lunas dalam 6 bulan terakhir</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-zinc-100 text-zinc-600">IDR (Rupiah)</span>
            </div>
            <div class="h-72">
                <canvas id="monthlyTrendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Layanan Terpopuler (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 sm:p-7 border border-zinc-200 shadow-xs flex flex-col justify-between">
            <div>
                <h3 class="text-lg font-bold text-zinc-900 mb-1">Layanan Terpopuler</h3>
                <p class="text-xs text-zinc-500 mb-4">Distribusi preferensi haircut pelanggan</p>
            </div>
            <div class="h-60 flex items-center justify-center">
                <canvas id="popularServicesChart"></canvas>
            </div>
            <div class="text-center text-xs text-zinc-400 pt-3 border-t border-zinc-100 mt-2">
                Top 5 Layanan Paling Sering Dipesan
            </div>
        </div>

        <!-- Chart 3: Peak Hours 10:00 - 20:00 (12 cols) -->
        <div class="lg:col-span-12 bg-white rounded-3xl p-6 sm:p-7 border border-zinc-200 shadow-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900">Statistik Jam Tersibuk (Peak Hours 10:00 - 21:00 WIB)</h3>
                    <p class="text-xs text-zinc-500">Kepadatan pengunjung per slot jam untuk alokasi penugasan kapster</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-yellow-100 text-yellow-800">Analisis Keterisian Kursi</span>
            </div>
            <div class="h-64">
                <canvas id="peakHoursChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Antrean Hari Ini Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-zinc-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-zinc-900">Jadwal Antrean Hari Ini</h3>
                <p class="text-xs text-zinc-500">Daftar pelanggan yang terdaftar untuk hari ini</p>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-yellow-600 hover:underline">
                Kelola Semua Antrean →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-xs font-bold uppercase text-zinc-500">
                    <tr>
                        <th class="py-3 px-6">Jam</th>
                        <th class="py-3 px-6">Kode</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Layanan</th>
                        <th class="py-3 px-6">Kapster</th>
                        <th class="py-3 px-6">Pembayaran</th>
                        <th class="py-3 px-6">Status Booking</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse($recentBookings as $b)
                        <tr class="hover:bg-zinc-50/80 transition">
                            <td class="py-4 px-6 font-bold text-zinc-900">{{ substr($b->booking_time, 0, 5) }} WIB</td>
                            <td class="py-4 px-6 font-mono text-xs font-bold text-yellow-600">{{ $b->booking_code }}</td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-zinc-900 block">{{ $b->customer_name }}</span>
                                <span class="text-xs text-zinc-400">{{ $b->customer_whatsapp }}</span>
                            </td>
                            <td class="py-4 px-6 font-medium text-zinc-800">{{ $b->service->name ?? '-' }}</td>
                            <td class="py-4 px-6 font-medium text-zinc-800">{{ $b->barber->name ?? '-' }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $b->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($b->payment_status) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $b->booking_status === 'completed' ? 'bg-blue-100 text-blue-800' : ($b->booking_status === 'arrived' ? 'bg-purple-100 text-purple-800' : 'bg-zinc-100 text-zinc-800') }}">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-400 text-sm">
                                Belum ada antrean untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Monthly Trend Chart
        const ctxMonthly = document.getElementById('monthlyTrendChart').getContext('2d');
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyLabels) !!},
                datasets: [
                    {
                        type: 'line',
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($monthlyRevenueData) !!},
                        borderColor: '#FFCC00',
                        backgroundColor: 'rgba(255, 204, 0, 0.2)',
                        borderWidth: 3,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        type: 'bar',
                        label: 'Jumlah Kunjungan',
                        data: {!! json_encode($monthlyOrdersData) !!},
                        backgroundColor: '#18181B',
                        borderRadius: 8,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(val) {
                                return 'Rp ' + (val / 1000).toLocaleString('id-ID') + 'k';
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });

        // 2. Popular Services Donut Chart
        const ctxDonut = document.getElementById('popularServicesChart').getContext('2d');
        new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($serviceLabels) !!},
                datasets: [{
                    data: {!! json_encode($serviceCounts) !!},
                    backgroundColor: ['#FFCC00', '#18181B', '#3B82F6', '#10B981', '#8B5CF6'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                }
            }
        });

        // 3. Peak Hours Bar Chart
        const ctxPeak = document.getElementById('peakHoursChart').getContext('2d');
        new Chart(ctxPeak, {
            type: 'bar',
            data: {
                labels: {!! json_encode($peakHoursLabels) !!},
                datasets: [{
                    label: 'Jumlah Reservasi Terjadwal',
                    data: {!! json_encode($peakHoursCounts) !!},
                    backgroundColor: '#FFCC00',
                    hoverBackgroundColor: '#E6B800',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    });
</script>
@endpush
