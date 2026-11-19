<!DOCTYPE html>
<html lang="id" class="{{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'dark' : '' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['site_name'] ?? 'AD Barbershop Bandung' }} - {{ $settings['tagline'] ?? '#KamiSolusi' }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN with Custom Color Palette -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: 'var(--brand-primary, #FFCC00)',
                            dark: '#E6B800',
                            light: '#FFF0B3',
                        },
                        dark: {
                            DEFAULT: '#18181B',
                            surface: '#27272A',
                            border: '#3F3F46',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Dynamic CSS Variables from Mini-CMS Site Settings -->
    <style>
        :root {
            --brand-primary: {{ $settings['primary_color'] ?? '#FFCC00' }};
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-brand {
            background-color: var(--brand-primary, #FFCC00);
            color: #18181B;
            font-weight: 700;
            transition: all 0.2s ease-in-out;
        }
        .btn-brand:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }
        .bg-brand {
            background-color: var(--brand-primary, #FFCC00);
        }
        .text-brand {
            color: var(--brand-primary, #FFCC00);
        }
        .border-brand {
            border-color: var(--brand-primary, #FFCC00);
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Mapbox GL JS -->
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.js"></script>

    <!-- Midtrans Snap JS (Sandbox Mode) -->
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}">
    </script>

    @stack('styles')
</head>
<body class="bg-[#F8F9FA] dark:bg-[#121214] text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col antialiased selection:bg-brand selection:text-black">

    <!-- Top Announcement Banner (Dynamic from Mini-CMS) -->
    @if(($settings['announcement_active'] ?? '0') === '1' && !empty($settings['announcement_text']))
        <div class="bg-zinc-900 dark:bg-black text-white text-xs md:text-sm py-2 px-4 border-b border-yellow-500/30">
            <div class="max-w-7xl mx-auto flex items-center justify-center text-center gap-2">
                <span class="inline-block animate-pulse text-brand font-bold">●</span>
                <span>{{ $settings['announcement_text'] }}</span>
            </div>
        </div>
    @endif

    <!-- Main Navigation Header -->
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-[#18181B]/90 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo & Name -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-brand flex items-center justify-center font-black text-zinc-900 text-xl shadow-md shadow-yellow-500/20 group-hover:scale-105 transition-transform">
                    AD
                </div>
                <div>
                    <span class="text-lg font-extrabold tracking-tight block text-zinc-900 dark:text-white leading-none">
                        {{ $settings['site_name'] ?? 'AD Barbershop' }}
                    </span>
                    <span class="text-xs font-semibold text-yellow-600 dark:text-brand tracking-wider uppercase">
                        Bandung • {{ $settings['tagline'] ?? '#KamiSolusi' }}
                    </span>
                </div>
            </a>

            <!-- Desktop Menu Navigation -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                <a href="{{ route('home') }}#services" class="hover:text-yellow-600 dark:hover:text-brand transition-colors">Layanan & Harga</a>
                <a href="{{ route('home') }}#gallery" class="hover:text-yellow-600 dark:hover:text-brand transition-colors">Inspirasi Rambut</a>
                <a href="{{ route('home') }}#location" class="hover:text-yellow-600 dark:hover:text-brand transition-colors">Lokasi & Jam Buka</a>
                <a href="{{ route('public.ticket') }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-800 dark:text-zinc-200 transition-colors">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    Cek Tiket / Pesanan
                </a>
            </nav>

            <!-- Action CTA & Admin Link -->
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}#booking" class="btn-brand px-5 py-2.5 rounded-xl text-sm shadow-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Booking Jadwal
                </a>
                <a href="{{ route('admin.login') }}" class="hidden sm:inline-flex p-2 rounded-xl text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" title="Login Admin">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </a>
            </div>
        </div>
    </header>

    <!-- Page Content Container -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-zinc-900 text-zinc-400 border-t border-zinc-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-zinc-800">
                <!-- Col 1: Brand Info -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center font-black text-zinc-900 text-lg">
                            AD
                        </div>
                        <span class="text-xl font-bold text-white tracking-tight">
                            {{ $settings['site_name'] ?? 'AD Barbershop Bandung' }}
                        </span>
                    </div>
                    <p class="text-zinc-400 text-sm leading-relaxed max-w-md mb-6">
                        Layanan pangkas rambut dan grooming pria profesional di Bandung dengan kenyamanan modern, alat higienis terstandarisasi, dan reservasi jadwal anti double-booking.
                    </p>
                    <div class="flex items-center gap-4 text-white">
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-zinc-800 hover:bg-zinc-700 flex items-center justify-center transition">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                            </a>
                        @endif
                        @if(!empty($settings['whatsapp_number']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings['whatsapp_number']) }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-lg bg-zinc-800 hover:bg-emerald-600 flex items-center justify-center transition">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Col 2: Jam Operasional -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Jam Operasional</h4>
                    <p class="text-zinc-300 font-semibold mb-1">{{ $settings['operating_hours'] ?? 'Open Daily: 10:00 - 21:00 WIB' }}</p>
                    <p class="text-zinc-500 text-xs leading-relaxed">Buka setiap hari termasuk akhir pekan dan hari libur nasional.</p>
                    <div class="mt-4 p-3 rounded-lg bg-zinc-800/60 border border-zinc-700/50 text-xs">
                        <span class="text-yellow-400 font-bold block mb-0.5">Rating Google</span>
                        <span class="text-white font-extrabold text-base">{{ $settings['google_rating'] ?? '4.9' }} ★★★★★</span>
                        <span class="text-zinc-400 block mt-0.5">Berdasarkan ulasan pelanggan setia Bandung</span>
                    </div>
                </div>

                <!-- Col 3: Lokasi Cabang -->
                <div>
                    <h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Lokasi</h4>
                    <p class="text-zinc-300 text-sm leading-relaxed mb-3">
                        {{ $settings['address'] ?? 'Jl. Dipati Ukur No. 42, Coblong, Kota Bandung' }}
                    </p>
                    <a href="{{ route('home') }}#location" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand hover:underline">
                        <span>Lihat Peta Interaktif</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-zinc-500 gap-4">
                <p>&copy; {{ date('Y') }} {{ $settings['site_name'] ?? 'AD Barbershop' }}. All rights reserved.</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('public.ticket') }}" class="hover:text-zinc-300">Cek Tiket</a>
                    <span>•</span>
                    <a href="{{ route('admin.login') }}" class="hover:text-zinc-300">Portal Admin</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
