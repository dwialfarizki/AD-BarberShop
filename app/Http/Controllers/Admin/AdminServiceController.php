<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(15);
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Nama layanan wajib diisi.',
            'category.required' => 'Kategori layanan wajib diisi.',
            'price.required' => 'Harga wajib diisi angka.',
            'duration_minutes.required' => 'Durasi wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Service::create($validated);

        return back()->with('success', 'Layanan baru berhasil ditambahkan.');
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'price' => 'required|integer|min:0',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        return back()->with('success', "Layanan {$service->name} berhasil diperbarui.");
    }

    public function toggle(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        $status = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Layanan {$service->name} berhasil {$status}.");
    }

    public function destroy(Service $service)
    {
        if ($service->bookings()->count() > 0) {
            $service->update(['is_active' => false]);
            return back()->with('info', "Layanan {$service->name} memiliki riwayat pesanan, sehingga dinonaktifkan alih-alih dihapus permanen.");
        }

        $service->delete();
        return back()->with('success', "Layanan berhasil dihapus.");
    }
}
