@extends('layouts.admin')

@section('title', 'Laporan Keuangan & Pendapatan')
@section('page_title', 'Laporan Keuangan & Pendapatan')

@section('content')
<div class="space-y-6">

    <!-- Date Range Filter & Actions -->
    <div class="bg-white p-6 rounded-3xl border border-zinc-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-zinc-500">Dari:</span>
                <input type="date" name="start_date" value="{{ $startDate }}" class="px-3 py-2 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-zinc-500">Sampai:</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="px-3 py-2 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
            </div>
            <button type="submit" class="px-5 py-2 rounded-xl bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 transition">
                Terapkan
            </button>
        </form>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.reports.export_csv', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center gap-2 shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Ekspor CSV / Excel
            </a>
            <a href="{{ route('admin.reports.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank"
               class="px-5 py-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-800 font-bold text-xs flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Laporan
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs">
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Total Pendapatan Bersih</span>
            <span class="text-2xl font-black text-zinc-900 mt-1 block">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
            <span class="text-[11px] font-semibold text-emerald-600 mt-1 block">Strictly Lunas / Selesai</span>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs">
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Pendapatan Online (QRIS)</span>
            <span class="text-2xl font-black text-blue-600 mt-1 block">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</span>
            <span class="text-[11px] font-semibold text-zinc-400 mt-1 block">Via Midtrans Snap</span>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs">
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Pendapatan Tunai Walk-In</span>
            <span class="text-2xl font-black text-amber-600 mt-1 block">Rp {{ number_format($walkInRevenue, 0, ',', '.') }}</span>
            <span class="text-[11px] font-semibold text-zinc-400 mt-1 block">Kasir Barbershop</span>
        </div>
        <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs">
            <span class="text-xs font-bold text-zinc-400 uppercase tracking-wider block">Transaksi Lunas & Rata-rata</span>
            <span class="text-2xl font-black text-zinc-900 mt-1 block">{{ $paidCount }} Transaksi</span>
            <span class="text-[11px] font-semibold text-zinc-500 mt-1 block">Rata-rata: Rp {{ number_format($avgTicket, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Report Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-zinc-200">
            <h3 class="text-base font-bold text-zinc-900">Rincian Buku Kas Transaksi</h3>
            <p class="text-xs text-zinc-500">Periode: {{ date('d F Y', strtotime($startDate)) }} s/d {{ date('d F Y', strtotime($endDate)) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-xs font-bold uppercase text-zinc-500">
                    <tr>
                        <th class="py-3 px-6">Waktu</th>
                        <th class="py-3 px-6">Kode</th>
                        <th class="py-3 px-6">Pelanggan</th>
                        <th class="py-3 px-6">Layanan</th>
                        <th class="py-3 px-6">Kapster</th>
                        <th class="py-3 px-6">Metode</th>
                        <th class="py-3 px-6">Nominal</th>
                        <th class="py-3 px-6">Status Bayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-zinc-50/80 transition">
                            <td class="py-3.5 px-6 font-medium text-xs text-zinc-600">
                                {{ $b->booking_date->format('d/m/Y') }} {{ substr($b->booking_time, 0, 5) }}
                            </td>
                            <td class="py-3.5 px-6 font-mono text-xs font-bold text-yellow-600">{{ $b->booking_code }}</td>
                            <td class="py-3.5 px-6 font-bold text-zinc-900">{{ $b->customer_name }}</td>
                            <td class="py-3.5 px-6 font-medium text-zinc-800">{{ $b->service->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-zinc-600">{{ $b->barber->name ?? '-' }}</td>
                            <td class="py-3.5 px-6 text-xs uppercase font-semibold text-zinc-600">{{ $b->payment_method }}</td>
                            <td class="py-3.5 px-6 font-bold text-zinc-900">{{ $b->formatted_price }}</td>
                            <td class="py-3.5 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $b->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($b->payment_status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-zinc-400 text-sm">
                                Tidak ada transaksi pada rentang tanggal ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-6 border-t border-zinc-200">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
