<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Services\BookingAvailabilityService;
use App\Services\MidtransService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * AJAX endpoint to check available slots
     */
    public function availableSlots(Request $request, BookingAvailabilityService $availabilityService)
    {
        $request->validate([
            'barber_id' => 'required|exists:barbers,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $slots = $availabilityService->getAvailableSlots(
            (int) $request->barber_id,
            $request->date
        );

        return response()->json([
            'success' => true,
            'slots' => $slots,
        ]);
    }

    /**
     * Store new customer online reservation
     */
    public function store(
        Request $request,
        BookingAvailabilityService $availabilityService,
        MidtransService $midtransService
    ) {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_whatsapp' => 'required|string|min:9|max:20',
            'service_id' => 'required|exists:services,id',
            'barber_id' => 'required|exists:barbers,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
        ], [
            'customer_name.required' => 'Nama lengkap wajib diisi.',
            'customer_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'service_id.required' => 'Silakan pilih layanan yang diinginkan.',
            'barber_id.required' => 'Silakan pilih kapster/barber.',
            'booking_date.required' => 'Pilih tanggal reservasi.',
            'booking_date.after_or_equal' => 'Tanggal reservasi tidak boleh sebelum hari ini.',
            'booking_time.required' => 'Pilih slot jam yang tersedia.',
        ]);

        // Normalize time e.g. "14:00" -> "14:00:00"
        $timeStr = substr($validated['booking_time'], 0, 5) . ':00';

        // Check Anti Double-Booking in real-time
        $existing = Booking::where('barber_id', $validated['barber_id'])
            ->whereDate('booking_date', $validated['booking_date'])
            ->where('booking_time', $timeStr)
            ->whereNotIn('booking_status', ['cancelled'])
            ->whereNotIn('payment_status', ['expired', 'failed'])
            ->exists();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, slot jam tersebut baru saja dipesan oleh pelanggan lain. Silakan pilih jam atau kapster lain.',
            ], 422);
        }

        $service = Service::findOrFail($validated['service_id']);

        // Generate unique booking code
        do {
            $code = 'ADB-' . strtoupper(Str::random(6));
        } while (Booking::where('booking_code', $code)->exists());

        $booking = Booking::create([
            'booking_code' => $code,
            'customer_name' => $validated['customer_name'],
            'customer_whatsapp' => $validated['customer_whatsapp'],
            'service_id' => $service->id,
            'barber_id' => $validated['barber_id'],
            'booking_date' => $validated['booking_date'],
            'booking_time' => $timeStr,
            'total_price' => $service->price,
            'booking_type' => 'online',
            'payment_method' => 'midtrans_qris',
            'payment_status' => 'unpaid',
            'booking_status' => 'pending',
        ]);

        // Request Midtrans Snap Token
        $snapToken = $midtransService->createSnapToken($booking);

        return response()->json([
            'success' => true,
            'message' => 'Reservasi berhasil dibuat! Silakan selesaikan pembayaran.',
            'booking_code' => $booking->booking_code,
            'snap_token' => $snapToken,
            'redirect_url' => route('public.ticket', ['search' => $booking->booking_code]),
        ]);
    }

    /**
     * Public page to search and view digital ticket
     */
    public function showTicket(Request $request)
    {
        $search = trim($request->get('search', ''));
        $booking = null;
        $searched = false;

        if (!empty($search)) {
            $searched = true;
            $booking = Booking::with(['service', 'barber'])
                ->where('booking_code', $search)
                ->orWhere('customer_whatsapp', $search)
                ->latest()
                ->first();
        }

        $settings = SiteSetting::getAllSettings();
        $settings['site_name'] = $settings['site_name'] ?? 'AD Barbershop Bandung';
        $settings['primary_color'] = $settings['primary_color'] ?? '#FFCC00';
        $settings['theme_mode'] = $settings['theme_mode'] ?? 'light';

        return view('ticket', compact('booking', 'search', 'searched', 'settings'));
    }

    /**
     * Fallback Sandbox Payment Simulator for local offline testing
     */
    public function simulatePayment(string $code)
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();

        $booking->update([
            'payment_status' => 'paid',
            'midtrans_transaction_id' => 'SANDBOX-SIM-' . strtoupper(Str::random(8)),
        ]);

        return redirect()->route('public.ticket', ['search' => $booking->booking_code])
            ->with('success', 'Simulasi Pembayaran Berhasil! Status pembayaran kini telah LUNAS.');
    }
}
