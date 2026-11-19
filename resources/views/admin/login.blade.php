<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - AD Barbershop Bandung</title>

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
                        },
                        jet: '#18181B',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-zinc-900 text-zinc-100 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md bg-zinc-800/90 rounded-3xl border border-zinc-700 shadow-2xl p-8 sm:p-10 relative overflow-hidden backdrop-blur-md">
        <!-- Glow accent -->
        <div class="absolute -top-12 -right-12 w-40 h-40 bg-yellow-400/20 blur-3xl rounded-full pointer-events-none"></div>

        <div class="text-center mb-8">
            <div class="w-16 h-16 rounded-2xl bg-brand text-zinc-950 font-black text-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-yellow-500/20">
                AD
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight">Portal Manajemen Admin</h1>
            <p class="text-zinc-400 text-xs mt-1">AD Barbershop Bandung • #KamiSolusi</p>
        </div>

        @if(session('info'))
            <div class="mb-6 p-4 rounded-xl bg-blue-950/60 border border-blue-800 text-blue-200 text-xs">
                {{ session('info') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-800 text-rose-200 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', 'admin@adbarber.com') }}" required autofocus
                       class="w-full px-4 py-3.5 rounded-xl border border-zinc-700 bg-zinc-900 text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none text-sm transition">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-300 mb-2">Kata Sandi</label>
                <input type="password" name="password" value="password" required
                       class="w-full px-4 py-3.5 rounded-xl border border-zinc-700 bg-zinc-900 text-white focus:ring-2 focus:ring-yellow-400 focus:outline-none text-sm transition">
            </div>

            <div class="flex items-center justify-between text-xs text-zinc-400">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-zinc-700 bg-zinc-900 text-yellow-400 focus:ring-yellow-400">
                    <span>Ingat Sesi Saya</span>
                </label>
                <span class="text-yellow-400 font-semibold">Demo: password</span>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-yellow-400 hover:bg-yellow-500 text-zinc-950 font-black text-sm tracking-wide shadow-lg shadow-yellow-500/20 transition duration-200">
                Masuk ke Dashboard
            </button>
        </form>

        <div class="mt-8 text-center border-t border-zinc-700/60 pt-6">
            <a href="{{ route('home') }}" class="text-xs text-zinc-400 hover:text-white transition flex items-center justify-center gap-1">
                <span>← Kembali ke Halaman Depan</span>
            </a>
        </div>
    </div>

</body>
</html>
