<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 20)->unique();
            $table->string('customer_name');
            $table->string('customer_whatsapp', 25);
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('barber_id')->constrained('barbers')->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('booking_time');
            $table->unsignedInteger('total_price');
            $table->enum('booking_type', ['online', 'walk_in'])->default('online');
            $table->string('payment_method')->default('midtrans_qris'); // midtrans_qris, cash, transfer
            $table->enum('payment_status', ['unpaid', 'paid', 'expired', 'failed'])->default('unpaid');
            $table->enum('booking_status', ['pending', 'arrived', 'completed', 'cancelled'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->timestamps();

            // Index for fast anti double-booking checks
            $table->index(['barber_id', 'booking_date', 'booking_time']);
            $table->index(['booking_date', 'booking_status']);
            $table->index(['payment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
