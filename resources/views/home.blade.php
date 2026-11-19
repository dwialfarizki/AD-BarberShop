@extends('layouts.app')

@section('content')
<div class="space-y-24 md:space-y-32">

    @foreach($sections as $section)
        @if($section->section_key === 'hero')
            <!-- 1. HERO SECTION -->
            <section id="hero" class="relative pt-12 pb-20 md:pt-20 md:pb-28 overflow-hidden">
                <!-- Background Glow -->
                <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[350px] bg-yellow-400/15 dark:bg-yellow-400/10 blur-[130px] rounded-full pointer-events-none -z-10"></div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                        <!-- Hero Left Content -->
                        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                            <!-- Badges -->
                            <div class="inline-flex flex-wrap items-center justify-center lg:justify-start gap-2.5">
                                <span class="px-3.5 py-1.5 rounded-full bg-yellow-400/20 text-yellow-800 dark:text-yellow-300 font-extrabold text-xs uppercase tracking-wider border border-yellow-400/40">
                                    {{ $settings['tagline'] ?? '#KamiSolusi' }}
                                </span>
                                <span class="px-3 py-1.5 rounded-full bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold text-xs flex items-center gap-1.5 border border-zinc-200 dark:border-zinc-700">
                                    <svg class="w-3.5 h-3.5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                    {{ $settings['operating_hours'] ?? 'Open Daily: 10:00 - 21:00 WIB' }}
                                </span>
                            </div>

                            <!-- Title & Subtitle -->
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-zinc-900 dark:text-white tracking-tight leading-[1.15]">
                                {!! nl2br(e($settings['hero_title'] ?? 'Solusi Gaya Rambut Terbaik, Percaya Diri Maksimal')) !!}
                            </h1>
                            <p class="text-lg text-zinc-600 dark:text-zinc-300 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                                {{ $settings['hero_subtitle'] ?? 'Rasakan sensasi grooming premium bersama kapster profesional Bandung. Suasana modern, alat steril, dan reservasi jadwal online anti antre.' }}
                            </p>

                            <!-- CTAs -->
                            <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                                <a href="#booking" class="w-full sm:w-auto btn-brand px-8 py-4 rounded-2xl text-base shadow-lg shadow-yellow-500/25 flex items-center justify-center gap-2">
                                    <span>Reservasi Sekarang</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                                <a href="#services" class="w-full sm:w-auto px-7 py-4 rounded-2xl bg-white dark:bg-zinc-800 text-zinc-800 dark:text-zinc-100 font-bold border border-zinc-200 dark:border-zinc-700 hover:bg-zinc-50 dark:hover:bg-zinc-700/80 transition flex items-center justify-center gap-2">
                                    Lihat Pricelist
                                </a>
                            </div>

                            <!-- Social Proof & Google Rating Card -->
                            <div class="pt-6 flex flex-wrap items-center justify-center lg:justify-start gap-6 border-t border-zinc-200 dark:border-zinc-800">
                                <div class="flex items-center gap-3">
                                    <div class="flex -space-x-2">
                                        <div class="w-10 h-10 rounded-full border-2 border-white dark:border-zinc-900 bg-zinc-300 dark:bg-zinc-700 overflow-hidden flex items-center justify-center text-xs font-bold text-zinc-700 dark:text-zinc-300">KD</div>
                                        <div class="w-10 h-10 rounded-full border-2 border-white dark:border-zinc-900 bg-yellow-400 text-black font-black flex items-center justify-center text-xs">AD</div>
                                        <div class="w-10 h-10 rounded-full border-2 border-white dark:border-zinc-900 bg-zinc-800 text-white font-bold flex items-center justify-center text-xs">+4k</div>
                                    </div>
                                    <div class="text-left">
                                        <div class="flex items-center gap-1 text-yellow-500 text-sm">
                                            <span>★★★★★</span>
                                            <span class="font-extrabold text-zinc-900 dark:text-white">{{ $settings['google_rating'] ?? '4.9' }}/5.0</span>
                                        </div>
                                        <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">Google Rating Resmi Bandung</span>
                                    </div>
                                </div>
                                <div class="h-8 w-px bg-zinc-200 dark:bg-zinc-800 hidden sm:block"></div>
                                <div class="text-left">
                                    <span class="block text-sm font-black text-zinc-900 dark:text-white">Anti Double-Booking</span>
                                    <span class="text-xs text-zinc-500">Sistem reservasi slot realtime per kapster</span>
                                </div>
                            </div>
                        </div>

                        <!-- Hero Right Visual Card -->
                        <div class="lg:col-span-5">
                            <div class="relative mx-auto max-w-md lg:max-w-none">
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-zinc-800 bg-zinc-900">
                                    <img src="{{ !empty($settings['hero_image']) ? asset('storage/' . $settings['hero_image']) : 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=800&q=80' }}"
                                         alt="AD Barbershop Suasana"
                                         class="w-full h-[450px] object-cover hover:scale-105 transition-transform duration-700">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-8 text-white">
                                        <span class="text-xs font-bold uppercase tracking-wider text-brand">AD Barbershop Experience</span>
                                        <h3 class="text-2xl font-black mt-1">Mastery, Style & Comfort</h3>
                                        <p class="text-zinc-300 text-xs mt-2 leading-relaxed">
                                            Dikerjakan oleh kapster berpengalaman dengan teknik cukur modern (Fade, Taper, Crop, Korean style).
                                        </p>
                                    </div>
                                </div>
                                <!-- Floating Badge -->
                                <div class="absolute -bottom-6 -left-6 bg-white dark:bg-zinc-800 p-4 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-700 hidden sm:flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-brand text-zinc-900 flex items-center justify-center font-black text-xl">
                                        ✂️
                                    </div>
                                    <div>
                                        <span class="text-xs text-zinc-500 block">Kursi & Kapster</span>
                                        <span class="text-sm font-black text-zinc-900 dark:text-white">{{ $barbers->count() }} Kapster Siap Melayani</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        @elseif($section->section_key === 'services')
            <!-- 2. SERVICES & PRICELIST -->
            <section id="services" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full bg-brand/20 text-yellow-800 dark:text-yellow-300 font-extrabold text-xs uppercase tracking-wider">
                        Pricelist & Layanan
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-zinc-900 dark:text-white tracking-tight">
                        Pilihan Layanan Grooming Terbaik
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 text-base">
                        Semua layanan menggunakan perlengkapan steril, produk perawatan rambut berkualitas, dan dikerjakan oleh kapster ahli.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($services as $service)
                        <div class="group relative bg-white dark:bg-zinc-800/80 rounded-3xl p-7 border border-zinc-200 dark:border-zinc-700/80 shadow-sm hover:shadow-xl hover:border-yellow-400 transition-all flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-zinc-100 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300">
                                        {{ $service->category }}
                                    </span>
                                    <span class="text-xs font-semibold text-zinc-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $service->duration_minutes }} menit
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2 group-hover:text-yellow-600 dark:group-hover:text-brand transition-colors">
                                    {{ $service->name }}
                                </h3>
                                <p class="text-zinc-600 dark:text-zinc-400 text-sm leading-relaxed mb-6">
                                    {{ $service->description }}
                                </p>
                            </div>
                            <div class="pt-6 border-t border-zinc-100 dark:border-zinc-700 flex items-center justify-between">
                                <div>
                                    <span class="text-xs text-zinc-400 block">Biaya</span>
                                    <span class="text-2xl font-extrabold text-zinc-900 dark:text-white">
                                        {{ $service->formatted_price }}
                                    </span>
                                </div>
                                <a href="#booking" onclick="document.getElementById('service_select').value='{{ $service->id }}'; document.getElementById('service_select').dispatchEvent(new Event('change'));"
                                   class="btn-brand px-4 py-2 rounded-xl text-xs flex items-center gap-1">
                                    Pilih
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        @elseif($section->section_key === 'gallery')
            <!-- 3. LOOKBOOK / INSPIRASI GAYA RAMBUT -->
            <section id="gallery" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
                    <span class="px-3.5 py-1.5 rounded-full bg-brand/20 text-yellow-800 dark:text-yellow-300 font-extrabold text-xs uppercase tracking-wider">
                        Lookbook AD Barber
                    </span>
                    <h2 class="text-3xl sm:text-4xl font-black text-zinc-900 dark:text-white tracking-tight">
                        Inspirasi Gaya Rambut
                    </h2>
                    <p class="text-zinc-600 dark:text-zinc-400 text-base">
                        Pilih model potongan favoritmu dari portofolio karya kapster kami sebelum memulai sesi cukur.
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach($galleries as $item)
                        <div class="group relative rounded-2xl overflow-hidden aspect-[3/4] bg-zinc-900 shadow-sm border border-zinc-200 dark:border-zinc-800">
                            <img src="{{ str_starts_with($item->image_path, 'http') ? $item->image_path : asset('storage/' . $item->image_path) }}"
                                 alt="{{ $item->title }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-90 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-3 text-white">
                                <span class="text-xs font-bold leading-tight line-clamp-2">{{ $item->title }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

        @elseif($section->section_key === 'booking_form')
            <!-- 4. REAL-TIME BOOKING FORM WITH ANTI DOUBLE-BOOKING -->
            <section id="booking" class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-zinc-800 rounded-3xl border border-zinc-200 dark:border-zinc-700 shadow-xl overflow-hidden"
                     x-data="bookingApp()"
                     x-init="init()">
                    
                    <!-- Header Form -->
                    <div class="bg-zinc-900 text-white p-8 md:p-10 border-b border-zinc-800">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <span class="text-brand text-xs font-extrabold uppercase tracking-wider">Reservasi Online Anti Ribet</span>
                                <h2 class="text-2xl md:text-3xl font-black tracking-tight mt-1">Formulir Booking Jadwal</h2>
                                <p class="text-zinc-400 text-sm mt-1">Pilih kapster dan slot jam yang Anda inginkan. Sistem kami secara instan mencegah jadwal ganda.</p>
                            </div>
                            <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl bg-zinc-800 border border-zinc-700 text-xs text-yellow-400 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                Live Slot Detection
                            </div>
                        </div>
                    </div>

                    <!-- Form Body -->
                    <form @submit.prevent="submitBooking()" class="p-8 md:p-10 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-2">Nama Lengkap *</label>
                                <input type="text" x-model="form.customer_name" required
                                       placeholder="Contoh: Dwi Alfa Rizki"
                                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-2">Nomor WhatsApp *</label>
                                <input type="tel" x-model="form.customer_whatsapp" required
                                       placeholder="Contoh: 081234567890"
                                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>

                            <!-- Pilih Layanan -->
                            <div>
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-2">Pilih Layanan *</label>
                                <select id="service_select" x-model="form.service_id" @change="onServiceChange()" required
                                        class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                                    <option value="">-- Pilih Layanan Barbershop --</option>
                                    @foreach($services as $s)
                                        <option value="{{ $s->id }}" data-price="{{ $s->price }}" data-name="{{ $s->name }}">
                                            {{ $s->name }} ({{ $s->formatted_price }} • {{ $s->duration_minutes }} mnt)
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilih Kapster / Kursi -->
                            <div>
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-2">Pilih Kapster / Barber *</label>
                                <select x-model="form.barber_id" @change="fetchSlots()" required
                                        class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                                    <option value="">-- Pilih Kapster Favorit --</option>
                                    @foreach($barbers as $b)
                                        <option value="{{ $b->id }}">
                                            Kursi {{ $b->chair_number }} - {{ $b->name }} ({{ $b->specialty }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pilih Tanggal -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200 mb-2">Pilih Tanggal Reservasi *</label>
                                <input type="date" x-model="form.booking_date" @change="fetchSlots()" required
                                       min="{{ $minDate }}" max="{{ $maxDate }}"
                                       class="w-full px-4 py-3 rounded-xl border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none transition">
                            </div>
                        </div>

                        <!-- Pilih Slot Jam (AJAX Real-Time Anti Double-Booking) -->
                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-bold text-zinc-800 dark:text-zinc-200">
                                    Pilih Slot Jam Tersedia (10:00 - 21:00 WIB) *
                                </label>
                                <span x-show="loadingSlots" class="text-xs text-yellow-600 dark:text-brand font-semibold flex items-center gap-1.5">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                                    Mengecek ketersediaan kursi...
                                </span>
                            </div>

                            <div x-show="!form.barber_id || !form.booking_date" class="p-6 rounded-2xl bg-zinc-50 dark:bg-zinc-900/50 text-center text-sm text-zinc-500 border border-dashed border-zinc-300 dark:border-zinc-700">
                                Silakan pilih <strong>Kapster</strong> dan <strong>Tanggal</strong> terlebih dahulu untuk melihat ketersediaan jam.
                            </div>

                            <div x-show="form.barber_id && form.booking_date" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                <template x-for="slot in slots" :key="slot.time">
                                    <button type="button"
                                            :disabled="!slot.is_available"
                                            @click="form.booking_time = slot.time"
                                            :class="{
                                                'bg-brand text-zinc-950 font-black shadow-md border-transparent': form.booking_time === slot.time && slot.is_available,
                                                'bg-white dark:bg-zinc-900 text-zinc-800 dark:text-zinc-200 hover:border-yellow-400': form.booking_time !== slot.time && slot.is_available,
                                                'bg-zinc-100 dark:bg-zinc-900/60 text-zinc-400 border-zinc-200 dark:border-zinc-800 cursor-not-allowed opacity-50': !slot.is_available
                                            }"
                                            class="p-3 rounded-xl border text-center transition-all">
                                        <span class="block text-base font-bold" x-text="slot.time"></span>
                                        <span class="block text-[10px] mt-0.5" x-text="slot.reason"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Ringkasan & Total Biaya -->
                        <div class="p-6 rounded-2xl bg-yellow-50 dark:bg-zinc-900/80 border border-yellow-200 dark:border-yellow-500/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <span class="text-xs text-yellow-800 dark:text-yellow-400 font-semibold block">Total Pembayaran Layanan</span>
                                <span class="text-3xl font-black text-zinc-900 dark:text-white" x-text="formattedPrice">Rp 0</span>
                            </div>
                            <button type="submit"
                                    :disabled="isSubmitting || !form.booking_time"
                                    class="w-full sm:w-auto btn-brand px-8 py-4 rounded-xl text-base shadow-md flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!isSubmitting">Lanjut ke Pembayaran Snap</span>
                                <span x-show="isSubmitting">Memproses...</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </section>

        @elseif($section->section_key === 'map_location')
            <!-- 5. INTERACTIVE MAPBOX LOCATION & CONTACT -->
            <section id="location" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    <!-- Info Box -->
                    <div class="lg:col-span-5 bg-white dark:bg-zinc-800 rounded-3xl p-8 md:p-10 border border-zinc-200 dark:border-zinc-700 shadow-sm flex flex-col justify-between">
                        <div>
                            <span class="px-3.5 py-1.5 rounded-full bg-brand/20 text-yellow-800 dark:text-yellow-300 font-extrabold text-xs uppercase tracking-wider">
                                Kunjungi Barbershop
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-zinc-900 dark:text-white tracking-tight mt-3 mb-4">
                                Lokasi & Akses
                            </h2>
                            <p class="text-zinc-600 dark:text-zinc-300 text-sm leading-relaxed mb-6">
                                Terletak di lokasi strategis pusat kota Bandung. Akses mudah, parkir luas, dan fasilitas ruang tunggu ber-AC.
                            </p>

                            <div class="space-y-4 text-sm">
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-yellow-100 dark:bg-zinc-700 text-yellow-600 dark:text-brand flex items-center justify-center shrink-0">
                                        📍
                                    </div>
                                    <div>
                                        <span class="font-bold text-zinc-900 dark:text-white block">Alamat Resmi</span>
                                        <span class="text-zinc-500">{{ $settings['address'] ?? 'Jl. Dipati Ukur No. 42, Coblong, Kota Bandung' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-yellow-100 dark:bg-zinc-700 text-yellow-600 dark:text-brand flex items-center justify-center shrink-0">
                                        🕙
                                    </div>
                                    <div>
                                        <span class="font-bold text-zinc-900 dark:text-white block">Jam Operasional</span>
                                        <span class="text-zinc-500">{{ $settings['operating_hours'] ?? 'Open Daily: 10:00 - 21:00 WIB' }}</span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-yellow-100 dark:bg-zinc-700 text-yellow-600 dark:text-brand flex items-center justify-center shrink-0">
                                        💬
                                    </div>
                                    <div>
                                        <span class="font-bold text-zinc-900 dark:text-white block">WhatsApp Kasir</span>
                                        <span class="text-zinc-500">{{ $settings['whatsapp_number'] ?? '081234567890' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-zinc-100 dark:border-zinc-700">
                            <a href="https://maps.google.com/?q={{ $settings['map_lat'] }},{{ $settings['map_lng'] }}" target="_blank" rel="noopener"
                               class="w-full btn-brand py-3 rounded-xl text-xs flex items-center justify-center gap-2">
                                <span>Buka Petunjuk Arah di Google Maps</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Mapbox Container -->
                    <div class="lg:col-span-7 rounded-3xl overflow-hidden border border-zinc-200 dark:border-zinc-700 shadow-sm min-h-[380px] relative">
                        <div id="mapbox-container" class="w-full h-full min-h-[380px]"></div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach

</div>
@endsection

@push('scripts')
<script>
    // Booking Form Alpine Component
    function bookingApp() {
        return {
            form: {
                customer_name: '',
                customer_whatsapp: '',
                service_id: '',
                barber_id: '',
                booking_date: '{{ $minDate }}',
                booking_time: ''
            },
            formattedPrice: 'Rp 0',
            slots: [],
            loadingSlots: false,
            isSubmitting: false,

            init() {
                if (this.form.barber_id && this.form.booking_date) {
                    this.fetchSlots();
                }
            },

            onServiceChange() {
                const select = document.getElementById('service_select');
                const selectedOpt = select.options[select.selectedIndex];
                if (selectedOpt && selectedOpt.dataset.price) {
                    const price = parseInt(selectedOpt.dataset.price);
                    this.formattedPrice = 'Rp ' + price.toLocaleString('id-ID');
                } else {
                    this.formattedPrice = 'Rp 0';
                }
            },

            async fetchSlots() {
                if (!this.form.barber_id || !this.form.booking_date) return;
                this.loadingSlots = true;
                this.form.booking_time = '';

                try {
                    const res = await fetch(`{{ route('api.available_slots') }}?barber_id=${this.form.barber_id}&date=${this.form.booking_date}`);
                    const data = await res.json();
                    if (data.success) {
                        this.slots = data.slots;
                    }
                } catch (err) {
                    console.error('Error fetching slots:', err);
                } finally {
                    this.loadingSlots = false;
                }
            },

            async submitBooking() {
                if (!this.form.booking_time) {
                    alert('Silakan pilih slot jam terlebih dahulu.');
                    return;
                }
                this.isSubmitting = true;

                try {
                    const res = await fetch(`{{ route('api.bookings.store') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });

                    const result = await res.json();

                    if (!res.ok) {
                        alert(result.message || 'Terjadi kesalahan saat reservasi.');
                        this.isSubmitting = false;
                        return;
                    }

                    // Open Midtrans Snap or Redirect
                    if (window.snap && result.snap_token && !result.snap_token.startsWith('sandbox-snap-')) {
                        window.snap.pay(result.snap_token, {
                            onSuccess: function(resultSnap) {
                                window.location.href = result.redirect_url;
                            },
                            onPending: function(resultSnap) {
                                window.location.href = result.redirect_url;
                            },
                            onError: function(resultSnap) {
                                window.location.href = result.redirect_url;
                            },
                            onClose: function() {
                                window.location.href = result.redirect_url;
                            }
                        });
                    } else {
                        // Fallback directly to digital ticket page with simulation button
                        window.location.href = result.redirect_url;
                    }

                } catch (err) {
                    console.error('Submit booking error:', err);
                    alert('Gagal terhubung ke server. Silakan coba lagi.');
                    this.isSubmitting = false;
                }
            }
        }
    }

    // Mapbox GL JS Initialization with Theme Auto-Switch
    document.addEventListener('DOMContentLoaded', function() {
        const mapContainer = document.getElementById('mapbox-container');
        if (!mapContainer) return;

        const token = "{{ $settings['mapbox_token'] ?? '' }}";
        const lat = parseFloat("{{ $settings['map_lat'] ?? '-6.8932' }}");
        const lng = parseFloat("{{ $settings['map_lng'] ?? '107.6171' }}");
        const isDark = "{{ ($settings['theme_mode'] ?? 'light') }}" === 'dark';

        if (token && typeof mapboxgl !== 'undefined') {
            mapboxgl.accessToken = token;
            const style = isDark ? 'mapbox://styles/mapbox/dark-v11' : 'mapbox://styles/mapbox/light-v11';

            try {
                const map = new mapboxgl.Map({
                    container: 'mapbox-container',
                    style: style,
                    center: [lng, lat],
                    zoom: 15
                });

                // Add marker
                const el = document.createElement('div');
                el.className = 'w-9 h-9 rounded-full bg-yellow-400 border-4 border-black flex items-center justify-center font-black text-xs text-black shadow-xl';
                el.innerHTML = 'AD';

                new mapboxgl.Marker(el)
                    .setLngLat([lng, lat])
                    .setPopup(new mapboxgl.Popup({ offset: 25 }).setHTML('<div class="p-2 text-zinc-900 font-bold">AD Barbershop Bandung<br><span class="text-xs font-normal text-zinc-600">Open Daily 10:00 - 21:00</span></div>'))
                    .addTo(map);

                map.addControl(new mapboxgl.NavigationControl(), 'top-right');
            } catch (e) {
                console.warn('Mapbox initialization fallback:', e);
                renderIframeMap(mapContainer, lat, lng);
            }
        } else {
            renderIframeMap(mapContainer, lat, lng);
        }

        function renderIframeMap(container, lat, lng) {
            container.innerHTML = `
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    scrolling="no"
                    marginheight="0"
                    marginwidth="0"
                    src="https://maps.google.com/maps?q=${lat},${lng}&hl=id&z=15&output=embed"
                    class="w-full h-full min-h-[380px] rounded-3xl"
                ></iframe>
            `;
        }
    });
</script>
@endpush
