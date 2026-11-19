<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_whatsapp',
        'service_id',
        'barber_id',
        'booking_date',
        'booking_time',
        'total_price',
        'booking_type',
        'payment_method',
        'payment_status',
        'booking_status',
        'snap_token',
        'midtrans_transaction_id',
    ];

    protected function casts(): array
    {
        return [
            'booking_date' => 'date',
            'total_price' => 'integer',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function scopePaidOrCompleted($query)
    {
        return $query->where(function ($q) {
            $q->where('payment_status', 'paid')
              ->orWhere('booking_status', 'completed');
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->booking_status) {
            'pending' => 'Menunggu Kedatangan',
            'arrived' => 'Sudah Datang / Proses Cukur',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->booking_status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'unpaid' => 'Belum Dibayar',
            'paid' => 'Lunas',
            'expired' => 'Kedaluwarsa',
            'failed' => 'Gagal',
            default => ucfirst($this->payment_status),
        };
    }
}
