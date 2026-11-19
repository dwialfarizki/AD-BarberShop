<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBarberController extends Controller
{
    public function index()
    {
        $barbers = Barber::orderBy('chair_number', 'asc')->paginate(15);
        return view('admin.barbers.index', compact('barbers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'chair_number' => 'required|integer|min:1|max:20',
            'specialty' => 'nullable|string|max:150',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama kapster wajib diisi.',
            'chair_number.required' => 'Nomor kursi wajib ditentukan.',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('barbers', 'public');
        }

        Barber::create([
            'name' => $validated['name'],
            'chair_number' => $validated['chair_number'],
            'specialty' => $validated['specialty'] ?? null,
            'photo_path' => $photoPath,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Kapster baru berhasil ditambahkan.');
    }

    public function update(Request $request, Barber $barber)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'chair_number' => 'required|integer|min:1|max:20',
            'specialty' => 'nullable|string|max:150',
            'photo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $photoPath = $barber->photo_path;
        if ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('barbers', 'public');
        }

        $barber->update([
            'name' => $validated['name'],
            'chair_number' => $validated['chair_number'],
            'specialty' => $validated['specialty'] ?? null,
            'photo_path' => $photoPath,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', "Data kapster {$barber->name} berhasil diperbarui.");
    }

    public function toggle(Barber $barber)
    {
        $barber->update(['is_active' => !$barber->is_active]);
        $status = $barber->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Status kapster {$barber->name} berhasil {$status}.");
    }

    public function destroy(Barber $barber)
    {
        if ($barber->bookings()->count() > 0) {
            $barber->update(['is_active' => false]);
            return back()->with('info', "Kapster {$barber->name} memiliki riwayat pengerjaan pesanan, sehingga dinonaktifkan alih-alih dihapus permanen.");
        }

        if ($barber->photo_path && Storage::disk('public')->exists($barber->photo_path)) {
            Storage::disk('public')->delete($barber->photo_path);
        }

        $barber->delete();
        return back()->with('success', "Data kapster berhasil dihapus.");
    }
}
