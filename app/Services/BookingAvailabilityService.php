<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

class BookingAvailabilityService
{
    /**
     * Standard operating slot hours: 10:00 to 20:00 (closing at 21:00)
     */
    public const OPERATING_SLOTS = [
        '10:00', '11:00', '12:00', '13:00',
        '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00'
    ];

    /**
     * Get slot status for a specific date and barber
     */
    public function getAvailableSlots(int $barberId, string $dateString): array
    {
        $date = Carbon::parse($dateString, 'Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $isToday = $date->isToday();

        // Get booked slots for this barber on this date, excluding cancelled/failed
        $bookedTimes = Booking::where('barber_id', $barberId)
            ->whereDate('booking_date', $date->format('Y-m-d'))
            ->whereNotIn('booking_status', ['cancelled'])
            ->whereNotIn('payment_status', ['expired', 'failed'])
            ->pluck('booking_time')
            ->map(fn($time) => substr($time, 0, 5))
            ->toArray();

        $slots = [];
        foreach (self::OPERATING_SLOTS as $slot) {
            $isBooked = in_array($slot, $bookedTimes);
            $isPast = false;

            if ($isToday) {
                $slotTime = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $slot, 'Asia/Jakarta');
                $isPast = $now->greaterThanOrEqualTo($slotTime);
            }

            $slots[] = [
                'time' => $slot,
                'is_available' => (!$isBooked && !$isPast),
                'reason' => $isBooked ? 'Sudah Dipesan' : ($isPast ? 'Waktu Terlewat' : 'Tersedia'),
            ];
        }

        return $slots;
    }
}
