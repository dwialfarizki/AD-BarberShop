@extends('layouts.admin')

@section('title', 'Manajemen Kapster & Kursi')
@section('page_title', 'Manajemen Kapster & Kursi')

@section('content')
<div x-data="{ addModal: false, editModal: false, editBarber: {} }" class="space-y-6">

    <!-- Top Action -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-zinc-200 shadow-xs">
        <div>
            <h3 class="text-lg font-bold text-zinc-900">Daftar Kapster & Kursi</h3>
            <p class="text-xs text-zinc-500">Kelola master data barber yang bertugas di AD Barbershop Bandung</p>
        </div>
        <button @click="addModal = true"
                class="btn-brand px-6 py-3 rounded-2xl text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Tambah Kapster Baru
        </button>
    </div>

    <!-- Barbers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($barbers as $b)
            <div class="bg-white rounded-3xl p-6 border border-zinc-200 shadow-xs flex flex-col justify-between relative overflow-hidden group">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-xs font-black bg-yellow-400 text-zinc-950 shadow-xs">
                            Kursi {{ $b->chair_number }}
                        </span>
                        <form action="{{ route('admin.barbers.toggle', $b->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $b->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-200 text-zinc-600' }}">
                                {{ $b->is_active ? 'Aktif Bertugas' : 'Libur' }}
                            </button>
                        </form>
                    </div>

                    <div class="w-20 h-20 rounded-2xl bg-zinc-100 dark:bg-zinc-800 border-2 border-yellow-400 flex items-center justify-center mx-auto text-2xl font-black text-zinc-700 overflow-hidden">
                        @if($b->photo_path && file_exists(public_path('storage/' . $b->photo_path)))
                            <img src="{{ asset('storage/' . $b->photo_path) }}" alt="{{ $b->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($b->name, 0, 2) }}
                        @endif
                    </div>

                    <div class="text-center">
                        <h4 class="text-base font-bold text-zinc-900">{{ $b->name }}</h4>
                        <span class="text-xs text-zinc-500 font-medium block mt-0.5">{{ $b->specialty ?? 'General Haircut Specialist' }}</span>
                    </div>
                </div>

                <div class="pt-5 border-t border-zinc-100 flex items-center justify-between mt-4">
                    <button @click="editBarber = {{ $b->toJson() }}; editModal = true"
                            class="text-xs font-bold text-yellow-600 hover:text-yellow-700 flex items-center gap-1">
                        Edit Data
                    </button>
                    <form action="{{ route('admin.barbers.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Hapus data kapster ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-zinc-400">
                Belum ada data kapster.
            </div>
        @endforelse
    </div>

    <!-- MODAL ADD BARBER -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.away="addModal = false" class="bg-white rounded-3xl max-w-md w-full p-8 border border-zinc-200 shadow-2xl">
            <h3 class="text-xl font-black text-zinc-900 mb-4">+ Tambah Kapster</h3>
            <form action="{{ route('admin.barbers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Kapster *</label>
                    <input type="text" name="name" required placeholder="Contoh: Kang Dwi" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nomor Kursi *</label>
                    <input type="number" name="chair_number" value="1" min="1" max="20" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Spesialisasi / Keahlian</label>
                    <input type="text" name="specialty" placeholder="Contoh: Fade, Taper, Hot Towel" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Foto Profil (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-yellow-400 file:text-zinc-950">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="barber_active" value="1" checked class="rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400">
                    <label for="barber_active" class="text-sm font-semibold text-zinc-700">Status Aktif Bertugas</label>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <button type="button" @click="addModal = false" class="px-5 py-2.5 rounded-xl text-zinc-600 font-semibold text-sm">Batal</button>
                    <button type="submit" class="btn-brand px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT BARBER -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-md w-full p-8 border border-zinc-200 shadow-2xl">
            <h3 class="text-xl font-black text-zinc-900 mb-4">Edit Data Kapster</h3>
            <form :action="'{{ url('admin/barbers') }}/' + editBarber.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Kapster *</label>
                    <input type="text" name="name" x-model="editBarber.name" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nomor Kursi *</label>
                    <input type="number" name="chair_number" x-model="editBarber.chair_number" min="1" max="20" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Spesialisasi / Keahlian</label>
                    <input type="text" name="specialty" x-model="editBarber.specialty" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Ganti Foto Profil (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-zinc-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-yellow-400 file:text-zinc-950">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_barber_active" value="1" :checked="editBarber.is_active" class="rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400">
                    <label for="edit_barber_active" class="text-sm font-semibold text-zinc-700">Status Aktif Bertugas</label>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-xl text-zinc-600 font-semibold text-sm">Batal</button>
                    <button type="submit" class="btn-brand px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
