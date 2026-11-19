@extends('layouts.admin')

@section('title', 'Manajemen Antrean & Walk-In')
@section('page_title', 'Manajemen Antrean & Walk-In')

@section('content')
<div x-data="{ walkInModal: false }" class="space-y-6">

    <!-- Header Actions & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-zinc-200 shadow-xs">
        <!-- Search & Filter Form -->
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama/WA..."
                   class="px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none min-w-[200px]">

            <input type="date" name="date" value="{{ request('date') }}"
                   class="px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">

            <select name="status" class="px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                <option value="">-- Semua Status --</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="arrived" {{ request('status') === 'arrived' ? 'selected' : '' }}>Di Tempat (Arrived)</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <select name="payment" class="px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                <option value="">-- Semua Pembayaran --</option>
                <option value="paid" {{ request('payment') === 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="unpaid" {{ request('payment') === 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
            </select>

            <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-900 text-white font-bold text-sm hover:bg-zinc-800 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'date', 'status', 'payment']))
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-zinc-500 hover:text-zinc-800 underline">Reset</a>
            @endif
        </form>

        <!-- Button Walk-In Modal Trigger -->
        <button @click="walkInModal = true"
                class="btn-brand px-6 py-3 rounded-2xl text-sm font-black flex items-center justify-center gap-2 shadow-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Tambah Pesanan Walk-In
        </button>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-xs font-bold uppercase text-zinc-500">
                    <tr>
                        <th class="py-3.5 px-6">Kode & Tipe</th>
                        <th class="py-3.5 px-6">Tanggal & Jam</th>
                        <th class="py-3.5 px-6">Pelanggan</th>
                        <th class="py-3.5 px-6">Layanan & Harga</th>
                        <th class="py-3.5 px-6">Kapster</th>
                        <th class="py-3.5 px-6">Status Bayar</th>
                        <th class="py-3.5 px-6">Update Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse($bookings as $b)
                        <tr class="hover:bg-zinc-50/80 transition">
                            <td class="py-4 px-6">
                                <span class="font-mono font-bold text-yellow-600 block">{{ $b->booking_code }}</span>
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $b->booking_type === 'walk_in' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $b->booking_type === 'walk_in' ? 'Walk-In (Kasir)' : 'Online (Web)' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-zinc-900 block">{{ $b->booking_date->format('d/m/Y') }}</span>
                                <span class="text-xs text-zinc-500">{{ substr($b->booking_time, 0, 5) }} WIB</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-zinc-900 block">{{ $b->customer_name }}</span>
                                <span class="text-xs text-zinc-400">{{ $b->customer_whatsapp }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-medium text-zinc-800 block">{{ $b->service->name ?? '-' }}</span>
                                <span class="text-xs font-bold text-zinc-900">{{ $b->formatted_price }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-semibold text-zinc-800 block">Kursi {{ $b->barber->chair_number ?? '-' }}</span>
                                <span class="text-xs text-zinc-500">{{ $b->barber->name ?? '-' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $b->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ strtoupper($b->payment_status) }}
                                </span>
                                <span class="block text-[10px] text-zinc-400 mt-0.5 uppercase">{{ $b->payment_method }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.bookings.update_status', $b->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="booking_status" class="text-xs py-1.5 px-2 rounded-lg border border-zinc-300 font-semibold focus:ring-1 focus:ring-yellow-400">
                                        <option value="pending" {{ $b->booking_status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="arrived" {{ $b->booking_status === 'arrived' ? 'selected' : '' }}>Arrived</option>
                                        <option value="completed" {{ $b->booking_status === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $b->booking_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <select name="payment_status" class="text-xs py-1.5 px-2 rounded-lg border border-zinc-300 font-semibold focus:ring-1 focus:ring-yellow-400">
                                        <option value="unpaid" {{ $b->payment_status === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $b->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                    <button type="submit" class="p-1.5 rounded-lg bg-zinc-900 text-white hover:bg-zinc-800" title="Simpan Perubahan">
                                        ✓
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-400 text-sm">
                                Tidak ada data pesanan yang sesuai filter.
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

    <!-- MODAL FORM + TAMBAH PESANAN WALK-IN -->
    <div x-show="walkInModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.away="walkInModal = false"
             class="bg-white rounded-3xl max-w-lg w-full p-8 border border-zinc-200 shadow-2xl relative">
            
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100 mb-6">
                <div>
                    <h3 class="text-xl font-black text-zinc-900">+ Catat Pesanan Walk-In</h3>
                    <p class="text-xs text-zinc-500">Input pelanggan langsung di kasir AD Barber</p>
                </div>
                <button @click="walkInModal = false" class="text-zinc-400 hover:text-zinc-600 text-xl font-bold">✕</button>
            </div>

            <form action="{{ route('admin.bookings.store_walk_in') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Pelanggan *</label>
                    <input type="text" name="customer_name" required placeholder="Nama pelanggan..."
                           class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">WhatsApp (Opsional)</label>
                    <input type="tel" name="customer_whatsapp" placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Layanan *</label>
                        <select name="service_id" required class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                            @foreach($services as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->formatted_price }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Kapster *</label>
                        <select name="barber_id" required class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                            @foreach($barbers as $b)
                                <option value="{{ $b->id }}">Kursi {{ $b->chair_number }} - {{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Tanggal *</label>
                        <input type="date" name="booking_date" value="{{ date('Y-m-d') }}" required
                               class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Jam Slot *</label>
                        <select name="booking_time" required class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                            @for($h = 10; $h <= 20; $h++)
                                <option value="{{ sprintf('%02d:00', $h) }}" {{ $h == date('H') ? 'selected' : '' }}>
                                    {{ sprintf('%02d:00', $h) }} WIB
                                </option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Metode Bayar</label>
                        <select name="payment_method" class="w-full px-2 py-2.5 rounded-xl border border-zinc-300 text-xs">
                            <option value="cash">Tunai (Cash)</option>
                            <option value="midtrans_qris">QRIS</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Status Bayar</label>
                        <select name="payment_status" class="w-full px-2 py-2.5 rounded-xl border border-zinc-300 text-xs">
                            <option value="paid">Lunas</option>
                            <option value="unpaid">Belum Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Status Antrean</label>
                        <select name="booking_status" class="w-full px-2 py-2.5 rounded-xl border border-zinc-300 text-xs">
                            <option value="arrived">Di Tempat</option>
                            <option value="completed">Selesai</option>
                            <option value="pending">Pending</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <button type="button" @click="walkInModal = false" class="px-5 py-2.5 rounded-xl text-zinc-600 font-semibold text-sm hover:bg-zinc-100">
                        Batal
                    </button>
                    <button type="submit" class="btn-brand px-6 py-2.5 rounded-xl text-sm font-black shadow-sm">
                        Simpan Transaksi Walk-In
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
