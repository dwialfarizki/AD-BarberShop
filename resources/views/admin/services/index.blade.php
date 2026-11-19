@extends('layouts.admin')

@section('title', 'Katalog Layanan Barbershop')
@section('page_title', 'Katalog Layanan & Pricelist')

@section('content')
<div x-data="{ addModal: false, editModal: false, editService: {} }" class="space-y-6">

    <!-- Top Action -->
    <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-zinc-200 shadow-xs">
        <div>
            <h3 class="text-lg font-bold text-zinc-900">Daftar Layanan</h3>
            <p class="text-xs text-zinc-500">Kelola menu potongan rambut, treatment, dan paket grooming</p>
        </div>
        <button @click="addModal = true"
                class="btn-brand px-6 py-3 rounded-2xl text-sm font-bold flex items-center gap-2 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            + Tambah Layanan Baru
        </button>
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-3xl border border-zinc-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-zinc-50 border-b border-zinc-200 text-xs font-bold uppercase text-zinc-500">
                    <tr>
                        <th class="py-3.5 px-6">Nama Layanan</th>
                        <th class="py-3.5 px-6">Kategori</th>
                        <th class="py-3.5 px-6">Harga (IDR)</th>
                        <th class="py-3.5 px-6">Durasi</th>
                        <th class="py-3.5 px-6">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200">
                    @forelse($services as $s)
                        <tr class="hover:bg-zinc-50/80 transition">
                            <td class="py-4 px-6">
                                <span class="font-bold text-zinc-900 block">{{ $s->name }}</span>
                                <span class="text-xs text-zinc-400 line-clamp-1">{{ $s->description }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-zinc-100 text-zinc-700">
                                    {{ $s->category }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-bold text-zinc-900">{{ $s->formatted_price }}</td>
                            <td class="py-4 px-6 text-zinc-600">{{ $s->duration_minutes }} menit</td>
                            <td class="py-4 px-6">
                                <form action="{{ route('admin.services.toggle', $s->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-2.5 py-1 rounded-full text-xs font-bold cursor-pointer transition {{ $s->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-200 text-zinc-600' }}">
                                        {{ $s->is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="editService = {{ $s->toJson() }}; editModal = true"
                                            class="p-2 rounded-xl text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('admin.services.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl text-rose-500 hover:bg-rose-50" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-zinc-400">Belum ada data layanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($services->hasPages())
            <div class="p-6 border-t border-zinc-200">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    <!-- MODAL ADD SERVICE -->
    <div x-show="addModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.away="addModal = false" class="bg-white rounded-3xl max-w-lg w-full p-8 border border-zinc-200 shadow-2xl">
            <h3 class="text-xl font-black text-zinc-900 mb-4">+ Tambah Layanan Baru</h3>
            <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Layanan *</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Kategori *</label>
                        <select name="category" required class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                            <option value="Haircut">Haircut</option>
                            <option value="Shaving">Shaving</option>
                            <option value="Treatment">Treatment</option>
                            <option value="Coloring">Coloring</option>
                            <option value="Package">Package</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Harga (Rp) *</label>
                        <input type="number" name="price" required min="0" step="1000" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Estimasi Durasi (Menit) *</label>
                    <input type="number" name="duration_minutes" value="45" required min="5" max="240" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="active_check" value="1" checked class="rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400">
                    <label for="active_check" class="text-sm font-semibold text-zinc-700">Aktifkan layanan ini sekarang</label>
                </div>
                <div class="pt-4 flex justify-end gap-3 border-t border-zinc-100">
                    <button type="button" @click="addModal = false" class="px-5 py-2.5 rounded-xl text-zinc-600 font-semibold text-sm">Batal</button>
                    <button type="submit" class="btn-brand px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT SERVICE -->
    <div x-show="editModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
        <div @click.away="editModal = false" class="bg-white rounded-3xl max-w-lg w-full p-8 border border-zinc-200 shadow-2xl">
            <h3 class="text-xl font-black text-zinc-900 mb-4">Edit Layanan</h3>
            <form :action="'{{ url('admin/services') }}/' + editService.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Nama Layanan *</label>
                    <input type="text" name="name" x-model="editService.name" required class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Kategori *</label>
                        <select name="category" x-model="editService.category" required class="w-full px-3 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                            <option value="Haircut">Haircut</option>
                            <option value="Shaving">Shaving</option>
                            <option value="Treatment">Treatment</option>
                            <option value="Coloring">Coloring</option>
                            <option value="Package">Package</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Harga (Rp) *</label>
                        <input type="number" name="price" x-model="editService.price" required min="0" step="1000" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Estimasi Durasi (Menit) *</label>
                    <input type="number" name="duration_minutes" x-model="editService.duration_minutes" required min="5" max="240" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-zinc-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="editService.description" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-zinc-300 text-sm focus:ring-2 focus:ring-yellow-400"></textarea>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" id="edit_active_check" value="1" :checked="editService.is_active" class="rounded border-zinc-300 text-yellow-400 focus:ring-yellow-400">
                    <label for="edit_active_check" class="text-sm font-semibold text-zinc-700">Layanan Aktif</label>
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
