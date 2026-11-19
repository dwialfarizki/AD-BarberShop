<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['service', 'barber'])->latest();

        // Filter date
        if ($request->filled('date')) {
            $query->whereDate('booking_date', $request->date);
        }

        // Filter booking status
        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        // Filter payment status
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }

        // Search code, name, or phone
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('booking_code', 'like', "%{$s}%")
                  ->orWhere('customer_name', 'like', "%{$s}%")
                  ->orWhere('customer_whatsapp', 'like', "%{$s}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();
        $services = Service::where('is_active', true)->get();
        $barbers = Barber::where('is_active', true)->get();

        return view('admin.bookings.index', compact('bookings', 'services', 'barbers'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'booking_status' => 'required|in:pending,arrived,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,expired,failed',
        ]);

        $booking->update($validated);

        return back()->with('success', "Status pesanan {$booking->booking_code} berhasil diperbarui.");
    }

    public function storeWalkIn(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_whatsapp' => 'nullable|string|max:20',
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'payment_method' => 'required|in:cash,midtrans_qris,transfer',
            'payment_status' => 'required|in:paid,unpaid',
            'booking_status' => 'required|in:pending,arrived,completed',
        ], [
            'customer_name.required' => 'Nama pelanggan wajib diisi.',
            'service_id.required' => 'Layanan wajib dipilih.',
            'barber_id.required' => 'Kapster wajib dipilih.',
            'booking_date.required' => 'Tanggal wajib dipilih.',
            'booking_time.required' => 'Jam wajib dipilih.',
        ]);

        $timeStr = substr($validated['booking_time'], 0, 5) . ':00';

        // Check Anti Double-Booking
        $existing = Booking::where('barber_id', $validated['barber_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $timeStr)
            ->whereNotIn('booking_status', ['cancelled'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['booking_time' => 'Kapster tersebut sudah memiliki pesanan di jam yang dipilih. Silakan pilih jam atau kapster lain.'])->withInput();
        }

        $service = Service::findOrFail($validated['service_id']);

        do {
            $code = 'ADB-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        Booking::create([
            'booking_code' => $code,
            'customer_name' => $validated['customer_name'],
            'customer_whatsapp' => $validated['customer_whatsapp'] ?: '-',
            'service_id' => $service->id,
            'barber_id' => $validated['barber_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $timeStr,
            'total_price' => $service->price,
            'booking_type' => 'walk_in',
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_status'],
            'booking_status' => $validated['booking_status'],
        ]);

        return back()->with('success', "Pesanan Walk-In ({$code}) berhasil ditambahkan ke antrean!");
    }
}
