<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - AD Barbershop Bandung</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            DEFAULT: '#FFCC00',
                            dark: '#E6B800',
                            light: '#FFF0B3',
                        },
                        jet: '#18181B',
                    }
                }
            }
        }
    </script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .btn-brand {
            background-color: #FFCC00;
            color: #18181B;
            font-weight: 700;
            transition: all 0.2s;
        }
        .btn-brand:hover {
            background-color: #E6B800;
            transform: translateY(-1px);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-zinc-50 text-zinc-900 min-h-screen flex antialiased" x-data="{ sidebarOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-cloak
         class="fixed inset-0 z-40 bg-black/50 lg:hidden backdrop-blur-sm"></div>

    <!-- Jet Black Sidebar (#18181B) -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-50 w-64 bg-jet text-zinc-300 transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static flex flex-col justify-between shrink-0 shadow-xl border-r border-zinc-800">
        
        <div>
            <!-- Admin Brand Logo -->
            <div class="h-20 flex items-center gap-3 px-6 border-b border-zinc-800">
                <div class="w-10 h-10 rounded-xl bg-brand text-zinc-950 font-black text-lg flex items-center justify-center shadow-md shadow-yellow-500/10">
                    AD
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-white tracking-tight leading-tight">AD Barbershop</h2>
                    <span class="text-[11px] font-bold text-yellow-400 uppercase tracking-widest">Admin Workspace</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-sm font-semibold">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                <!-- Antrean & Walk-In -->
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.bookings.*') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.bookings.*') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Antrean & Walk-In
                </a>

                <!-- Laporan Keuangan -->
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.reports.*') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Laporan Keuangan
                </a>

                <!-- Layanan Barbershop -->
                <a href="{{ route('admin.services.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.services.*') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.services.*') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879a3 3 0 11-4.242-4.242L10.757 7.757m0 0l-2.878-2.878a3 3 0 10-4.243 4.242L6.515 12"></path></svg>
                    Katalog Layanan
                </a>

                <!-- Kapster / Barber -->
                <a href="{{ route('admin.barbers.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.barbers.*') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.barbers.*') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Kapster & Kursi
                </a>

                <!-- Web Customization / Mini-CMS -->
                <a href="{{ route('admin.cms.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.cms.*') ? 'bg-yellow-400 text-zinc-950 font-bold shadow-sm' : 'hover:bg-zinc-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.cms.*') ? 'text-zinc-950' : 'text-zinc-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Web Mini-CMS
                </a>
            </nav>
        </div>

        <!-- Sidebar Bottom Footer -->
        <div class="p-4 border-t border-zinc-800 space-y-2">
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-zinc-400 hover:text-white hover:bg-zinc-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                Buka Website Publik
            </a>

            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold text-rose-400 hover:bg-rose-950/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-zinc-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-xl text-zinc-600 hover:bg-zinc-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-extrabold text-zinc-900 tracking-tight">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <span class="text-sm font-bold text-zinc-900 block leading-tight">{{ auth()->user()->name ?? 'Admin AD Barber' }}</span>
                    <span class="text-xs text-yellow-600 font-semibold">{{ auth()->user()->email ?? 'admin@adbarber.com' }}</span>
                </div>
                <div class="w-10 h-10 rounded-xl bg-yellow-400 text-zinc-950 font-black text-sm flex items-center justify-center shadow-sm">
                    {{ substr(auth()->user()->name ?? 'AD', 0, 2) }}
                </div>
            </div>
        </header>

        <!-- Body Content -->
        <main class="p-6 sm:p-8 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('info'))
                <div class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
                    <span class="font-bold block">Terdapat kesalahan validasi:</span>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
