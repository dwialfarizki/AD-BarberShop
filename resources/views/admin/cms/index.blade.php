@extends('layouts.admin')

@section('title', 'Web Customization & Mini-CMS')
@section('page_title', 'Web Customization & Mini-CMS')

@section('content')
<div x-data="{ activeTab: 'theme' }" class="space-y-6">

    <!-- CMS Tab Navigation -->
    <div class="bg-white p-2 rounded-2xl border border-zinc-200 shadow-xs flex flex-wrap gap-2">
        <button @click="activeTab = 'theme'"
                :class="activeTab === 'theme' ? 'bg-yellow-400 text-zinc-950 font-black shadow-xs' : 'text-zinc-600 hover:bg-zinc-100 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition">
            🎨 Tab 1: Theme Manager
        </button>
        <button @click="activeTab = 'layout'"
                :class="activeTab === 'layout' ? 'bg-yellow-400 text-zinc-950 font-black shadow-xs' : 'text-zinc-600 hover:bg-zinc-100 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition">
            📐 Tab 2: Layout Configurator
        </button>
        <button @click="activeTab = 'content'"
                :class="activeTab === 'content' ? 'bg-yellow-400 text-zinc-950 font-black shadow-xs' : 'text-zinc-600 hover:bg-zinc-100 font-bold'"
                class="px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition">
            ✍️ Tab 3: Content & Lookbook Gallery
        </button>
    </div>

    <!-- TAB 1: THEME MANAGER -->
    <div x-show="activeTab === 'theme'" x-cloak class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-xs space-y-8">
        <div>
            <h3 class="text-xl font-bold text-zinc-900">Theme Manager & Brand Styling</h3>
            <p class="text-xs text-zinc-500 mt-1">Ubah identitas warna utama website, mode tampilan (Light/Dark), serta aset banner hero.</p>
        </div>

        <form action="{{ route('admin.cms.theme') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
            @csrf
            
            <!-- Primary Color Picker -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-2">
                    Warna Utama Brand (Primary Color HEX) *
                </label>
                <div class="flex items-center gap-4">
                    <input type="color" name="primary_color" id="color_picker"
                           value="{{ $settings['primary_color'] ?? '#FFCC00' }}"
                           oninput="document.getElementById('color_hex').value = this.value"
                           class="w-14 h-14 rounded-2xl border-2 border-zinc-300 cursor-pointer p-1">
                    <input type="text" id="color_hex" name="primary_color"
                           value="{{ $settings['primary_color'] ?? '#FFCC00' }}"
                           oninput="document.getElementById('color_picker').value = this.value"
                           class="px-4 py-3 rounded-xl border border-zinc-300 font-mono text-sm uppercase font-bold focus:ring-2 focus:ring-yellow-400">
                    <span class="text-xs text-zinc-400">Default: #FFCC00 (Kuning AD Barber)</span>
                </div>
            </div>

            <!-- Mode Tema (Light / Dark) -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-2">
                    Mode Tema Tampilan Default *
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center gap-3 {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'border-yellow-400 bg-yellow-50/50' : 'border-zinc-200' }}">
                        <input type="radio" name="theme_mode" value="light" {{ ($settings['theme_mode'] ?? 'light') === 'light' ? 'checked' : '' }} class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <span class="block text-sm font-bold text-zinc-900">Light Mode (Rekomendasi)</span>
                            <span class="text-[11px] text-zinc-500">Latar bersih putih & soft gray enerjik</span>
                        </div>
                    </label>

                    <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex items-center gap-3 {{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'border-yellow-400 bg-yellow-50/50' : 'border-zinc-200' }}">
                        <input type="radio" name="theme_mode" value="dark" {{ ($settings['theme_mode'] ?? 'light') === 'dark' ? 'checked' : '' }} class="text-yellow-400 focus:ring-yellow-400">
                        <div>
                            <span class="block text-sm font-bold text-zinc-900">Dark Mode</span>
                            <span class="text-[11px] text-zinc-500">Latar hitam pekat aksen kuning neon</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Upload Hero Image -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 mb-2">
                    Unggah Gambar Hero Banner Baru
                </label>
                <input type="file" name="hero_image" accept="image/*"
                       class="w-full text-xs text-zinc-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-yellow-400 file:text-zinc-950">
                <span class="text-[11px] text-zinc-400 mt-1 block">Format: JPG, PNG, WEBP (Maksimal 3MB)</span>
            </div>

            <div class="pt-4 border-t border-zinc-100">
                <button type="submit" class="btn-brand px-7 py-3 rounded-2xl text-sm font-black shadow-sm">
                    Simpan Konfigurasi Tema
                </button>
            </div>
        </form>
    </div>

    <!-- TAB 2: LAYOUT CONFIGURATOR -->
    <div x-show="activeTab === 'layout'" x-cloak class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-xs space-y-6">
        <div>
            <h3 class="text-xl font-bold text-zinc-900">Layout Configurator Landing Page</h3>
            <p class="text-xs text-zinc-500 mt-1">Atur urutan tampilan seksi di halaman depan dan sembunyikan/tampilkan seksi sesuai kebutuhan promosi.</p>
        </div>

        <div class="space-y-3 max-w-2xl">
            @foreach($sections as $sec)
                <div class="p-4 rounded-2xl border border-zinc-200 flex items-center justify-between bg-zinc-50 hover:bg-zinc-100/80 transition">
                    <div class="flex items-center gap-4">
                        <span class="w-8 h-8 rounded-xl bg-zinc-900 text-yellow-400 font-mono font-bold text-xs flex items-center justify-center">
                            {{ $sec->display_order }}
                        </span>
                        <div>
                            <span class="font-bold text-zinc-900 block text-sm">{{ $sec->title }}</span>
                            <span class="font-mono text-[11px] text-zinc-400">section_key: {{ $sec->section_key }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Up Button -->
                        <form action="{{ route('admin.cms.sections.order', $sec->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="direction" value="up">
                            <button type="submit" class="p-2 rounded-xl bg-white border border-zinc-200 hover:bg-zinc-200 text-zinc-700 text-xs font-bold" title="Pindah Ke Atas">
                                ▲
                            </button>
                        </form>

                        <!-- Down Button -->
                        <form action="{{ route('admin.cms.sections.order', $sec->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="direction" value="down">
                            <button type="submit" class="p-2 rounded-xl bg-white border border-zinc-200 hover:bg-zinc-200 text-zinc-700 text-xs font-bold" title="Pindah Ke Bawah">
                                ▼
                            </button>
                        </form>

                        <!-- Toggle Visible -->
                        <form action="{{ route('admin.cms.sections.toggle', $sec->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $sec->is_visible ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-200 text-zinc-600' }}">
                                {{ $sec->is_visible ? 'Tampil' : 'Sembunyi' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- TAB 3: CONTENT AUTHORING & GALLERY CRUD -->
    <div x-show="activeTab === 'content'" x-cloak class="space-y-8">
        
        <!-- Content Authoring Form -->
        <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-xs space-y-6">
            <div>
                <h3 class="text-xl font-bold text-zinc-900">Content Authoring & Informasi Resmi</h3>
                <p class="text-xs text-zinc-500 mt-1">Ubah teks utama, jam buka, Google rating, dan pengumuman promo.</p>
            </div>

            <form action="{{ route('admin.cms.content') }}" method="POST" class="space-y-5 max-w-3xl">
                @csrf

                <!-- Announcement Banner -->
                <div class="p-5 rounded-2xl bg-yellow-50 border border-yellow-200 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold uppercase text-yellow-900">Banner Pengumuman / Promo Topbar</span>
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-zinc-800">
                            <input type="checkbox" name="announcement_active" value="1" {{ ($settings['announcement_active'] ?? '0') === '1' ? 'checked' : '' }} class="rounded text-yellow-400 focus:ring-yellow-400">
                            Aktifkan Banner Promo
                        </label>
                    </div>
                    <input type="text" name="announcement_text" value="{{ $settings['announcement_text'] ?? '' }}"
                           placeholder="Contoh: 🔥 Promo Spesial Persib Day: Diskon 20% untuk semua Haircut..."
                           class="w-full px-4 py-2.5 rounded-xl border border-yellow-300 text-sm bg-white focus:ring-2 focus:ring-yellow-400">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Barbershop *</label>
                        <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'AD Barbershop Bandung' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Tagline Slogan *</label>
                        <input type="text" name="tagline" value="{{ $settings['tagline'] ?? '#KamiSolusi' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Judul Hero (Headline) *</label>
                    <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? '' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Subjudul Hero (Deskripsi) *</label>
                    <textarea name="hero_subtitle" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">{{ $settings['hero_subtitle'] ?? '' }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Jam Operasional *</label>
                        <input type="text" name="operating_hours" value="{{ $settings['operating_hours'] ?? 'Open Daily: 10:00 - 21:00 WIB' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Google Rating (1-5) *</label>
                        <input type="number" step="0.1" min="1" max="5" name="google_rating" value="{{ $settings['google_rating'] ?? '4.9' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Alamat Lengkap Barbershop *</label>
                    <input type="text" name="address" value="{{ $settings['address'] ?? '' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Latitude Peta *</label>
                        <input type="text" name="map_lat" value="{{ $settings['map_lat'] ?? '-6.8932' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Longitude Peta *</label>
                        <input type="text" name="map_lng" value="{{ $settings['map_lng'] ?? '107.6171' }}" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Instagram URL</label>
                        <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">TikTok URL</label>
                        <input type="url" name="tiktok_url" value="{{ $settings['tiktok_url'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">WhatsApp Kasir</label>
                        <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>

                <div class="pt-4 border-t border-zinc-100">
                    <button type="submit" class="btn-brand px-7 py-3 rounded-2xl text-sm font-black shadow-sm">
                        Simpan Konten Website
                    </button>
                </div>
            </form>
        </div>

        <!-- Inspirasi Gaya Rambut Gallery Items CRUD -->
        <div class="bg-white rounded-3xl p-8 border border-zinc-200 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold text-zinc-900">Katalog Inspirasi Gaya Rambut (Lookbook)</h3>
                    <p class="text-xs text-zinc-500 mt-1">Kelola foto portofolio model rambut yang tampil di landing page.</p>
                </div>
            </div>

            <!-- Upload New Gallery Item -->
            <form action="{{ route('admin.cms.gallery.store') }}" method="POST" enctype="multipart/form-data" class="p-5 rounded-2xl bg-zinc-50 border border-zinc-200 flex flex-col md:flex-row items-center gap-4">
                @csrf
                <div class="flex-1 w-full">
                    <input type="text" name="title" required placeholder="Judul model (contoh: Low Taper Fade with Texture)..."
                           class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm bg-white focus:ring-2 focus:ring-yellow-400">
                </div>
                <div class="w-full md:w-auto">
                    <input type="file" name="image" required accept="image/*"
                           class="text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-yellow-400 file:text-zinc-950">
                </div>
                <button type="submit" class="btn-brand px-6 py-2.5 rounded-xl text-xs font-black shadow-xs shrink-0 w-full md:w-auto">
                    + Upload Foto
                </button>
            </form>

            <!-- Gallery Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
                @forelse($galleries as $g)
                    <div class="relative rounded-2xl overflow-hidden aspect-[3/4] bg-zinc-900 border border-zinc-200 group">
                        <img src="{{ str_starts_with($g->image_path, 'http') ? $g->image_path : asset('storage/' . $g->image_path) }}"
                             alt="{{ $g->title }}" class="w-full h-full object-cover">
                        
                        <div class="absolute inset-0 bg-black/70 opacity-0 group-hover:opacity-100 transition-opacity p-3 flex flex-col justify-between text-white">
                            <span class="text-xs font-bold leading-tight">{{ $g->title }}</span>
                            <div class="flex items-center justify-between pt-2 border-t border-zinc-700">
                                <form action="{{ route('admin.cms.gallery.toggle', $g->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded {{ $g->is_published ? 'bg-emerald-500 text-black' : 'bg-zinc-600 text-white' }}">
                                        {{ $g->is_published ? 'Publish' : 'Draft' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.cms.gallery.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-bold">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-8 text-center text-zinc-400 text-sm">
                        Belum ada foto inspirasi gaya rambut.
                    </div>
                @endforelse
            </div>

            @if($galleries->hasPages())
                <div class="pt-4 border-t border-zinc-100">
                    {{ $galleries->links() }}
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
