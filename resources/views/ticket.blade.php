@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">

    <!-- Search Form -->
    <div class="bg-white dark:bg-zinc-800 rounded-3xl p-6 sm:p-8 border border-zinc-200 dark:border-zinc-700 shadow-sm mb-10">
        <h1 class="text-2xl font-black text-zinc-900 dark:text-white tracking-tight mb-2">Cek Status Tiket & Reservasi</h1>
        <p class="text-zinc-600 dark:text-zinc-400 text-sm mb-6">Masukkan Kode Booking (contoh: ADB-XXXXXX) atau Nomor WhatsApp yang Anda gunakan saat mendaftar.</p>

        <form action="{{ route('public.ticket') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-grow">
                <input type="text" name="search" value="{{ $search }}" required
                       placeholder="Masukkan Kode Booking / No. WhatsApp..."
                       class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none font-semibold">
                <svg class="w-5 h-5 text-zinc-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <button type="submit" class="btn-brand px-7 py-3.5 rounded-2xl text-sm font-bold flex items-center justify-center gap-2">
                Cari Pesanan
            </button>
        </form>
    </div>

    <!-- Result Area -->
    @if($searched && !$booking)
        <div class="bg-white dark:bg-zinc-800 rounded-3xl p-10 text-center border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <div class="w-16 h-16 rounded-full bg-yellow-100 dark:bg-zinc-700 text-yellow-600 dark:text-brand flex items-center justify-center mx-auto mb-4 text-2xl font-black">
                🔍
            </div>
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">Pesanan Tidak Ditemukan</h3>
            <p class="text-zinc-500 text-sm max-w-md mx-auto">
                Tidak ada data reservasi yang cocok dengan kata kunci "<strong>{{ $search }}</strong>". Pastikan kode booking atau nomor WhatsApp sudah benar.
            </p>
        </div>
    @elseif($booking)
        <!-- Digital Reservation Ticket Card -->
        <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-xl overflow-hidden print:border-none print:shadow-none" id="printable-ticket">
            <!-- Ticket Top Header -->
            <div class="bg-zinc-900 text-white p-6 sm:p-8 border-b-2 border-dashed border-zinc-700 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand text-zinc-950 flex items-center justify-center font-black text-xl">
                        AD
                    </div>
                    <div>
                        <span class="text-xs uppercase font-extrabold tracking-wider text-brand">Tiket Reservasi Digital</span>
                        <h2 class="text-xl font-black">{{ $settings['site_name'] ?? 'AD Barbershop' }}</h2>
                    </div>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-xs text-zinc-400 block font-mono">Kode Booking:</span>
                    <span class="text-2xl font-black text-brand tracking-widest font-mono">{{ $booking->booking_code }}</span>
                </div>
            </div>

            <!-- Visual Status Stepper -->
            <div class="p-6 sm:p-8 bg-zinc-50 dark:bg-zinc-900/50 border-b border-zinc-200 dark:border-zinc-700">
                <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-500 mb-4">Progres Status Kedatangan</h4>
                
                @php
                    $steps = [
                        ['key' => 'booked', 'label' => 'Terdipesan', 'done' => true],
                        ['key' => 'paid', 'label' => 'Terbayar', 'done' => ($booking->payment_status === 'paid')],
                        ['key' => 'arrived', 'label' => 'Di Tempat', 'done' => in_array($booking->booking_status, ['arrived', 'completed'])],
                        ['key' => 'completed', 'label' => 'Selesai', 'done' => ($booking->booking_status === 'completed')],
                    ];
                @endphp

                <div class="grid grid-cols-4 gap-2 relative">
                    @foreach($steps as $idx => $step)
                        <div class="text-center">
                            <div class="w-8 h-8 mx-auto rounded-full flex items-center justify-center text-xs font-bold transition-colors {{ $step['done'] ? 'bg-yellow-400 text-zinc-950 font-black shadow-sm' : 'bg-zinc-200 dark:bg-zinc-700 text-zinc-400' }}">
                                {{ $step['done'] ? '✓' : ($idx + 1) }}
                            </div>
                            <span class="block text-[11px] sm:text-xs font-semibold mt-2 {{ $step['done'] ? 'text-zinc-900 dark:text-white font-bold' : 'text-zinc-400' }}">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ticket Body Details -->
            <div class="p-6 sm:p-8 space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Nama Pelanggan</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white">{{ $booking->customer_name }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Nomor WhatsApp</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white">{{ $booking->customer_whatsapp }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Layanan Terpilih</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white">{{ $booking->service->name ?? '-' }}</span>
                        <span class="text-xs text-zinc-500 block">Estimasi {{ $booking->service->duration_minutes ?? 45 }} menit</span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Kapster / Barber</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white">
                            Kursi {{ $booking->barber->chair_number ?? 1 }} - {{ $booking->barber->name ?? '-' }}
                        </span>
                        <span class="text-xs text-yellow-600 dark:text-brand block font-semibold">{{ $booking->barber->specialty ?? '' }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Jadwal Tanggal</span>
                        <span class="text-base font-bold text-zinc-900 dark:text-white">{{ $booking->booking_date->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-xs text-zinc-400 block mb-0.5">Slot Jam Kedatangan</span>
                        <span class="text-base font-black text-yellow-600 dark:text-brand">{{ substr($booking->booking_time, 0, 5) }} WIB</span>
                    </div>
                </div>

                <!-- Status & Payment Row -->
                <div class="p-5 rounded-2xl bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <span class="text-xs text-zinc-400 block">Total Biaya Layanan</span>
                        <span class="text-2xl font-black text-zinc-900 dark:text-white">{{ $booking->formatted_price }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($booking->payment_status === 'paid')
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300">
                                Lunas (QRIS/Midtrans)
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300">
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Actions: Print & Sandbox Payment Simulation Fallback -->
                <div class="pt-4 flex flex-col sm:flex-row items-center gap-3 print:hidden">
                    <button onclick="window.print()" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-zinc-100 dark:bg-zinc-700 hover:bg-zinc-200 text-zinc-800 dark:text-zinc-200 font-bold text-sm flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak / Simpan PDF
                    </button>

                    @if($booking->payment_status !== 'paid')
                        <!-- Sandbox Fallback Simulator Button -->
                        <form action="{{ route('public.simulate_payment', $booking->booking_code) }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <button type="submit" class="w-full btn-brand px-6 py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2 shadow-sm">
                                <span>⚡ Simulasi Bayar Lunas - Sandbox</span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
@endsection
