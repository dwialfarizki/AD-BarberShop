<?php

namespace Tests\Feature;

use App\Models\Barber;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdBarberSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_public_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AD Barbershop');
        $response->assertSee('#KamiSolusi');
    }

    public function test_cek_pesanan_page_can_be_rendered(): void
    {
        $response = $this->get('/cek-pesanan');
        $response->assertStatus(200);
        $response->assertSeeText('Cek Status Tiket');
    }

    public function test_available_slots_api_returns_valid_json(): void
    {
        $barber = Barber::first();
        $date = date('Y-m-d', strtotime('+1 day'));

        $response = $this->getJson("/api/available-slots?barber_id={$barber->id}&date={$date}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'slots' => [
                '*' => ['time', 'is_available', 'reason']
            ]
        ]);
    }

    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Portal Manajemen Admin');
    }

    public function test_admin_authenticated_can_access_dashboard(): void
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ringkasan & Analitik Bisnis');
    }

    public function test_admin_can_access_reports_and_export_csv(): void
    {
        $admin = User::first();
        $response = $this->actingAs($admin)->get('/admin/reports');
        $response->assertStatus(200);

        $csvResponse = $this->actingAs($admin)->get('/admin/reports/export-csv');
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_anti_double_booking_prevention(): void
    {
        $service = Service::first();
        $barber = Barber::first();
        $futureDate = date('Y-m-d', strtotime('+3 days'));
        $time = '14:00';

        // First booking
        $postData = [
            'customer_name' => 'Test Pelanggan 1',
            'customer_whatsapp' => '081234567890',
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $futureDate,
            'booking_time' => $time,
        ];

        $res1 = $this->postJson('/api/bookings', $postData);
        $res1->assertStatus(200);
        $res1->assertJson(['success' => true]);

        // Second booking on exact same barber, date, and time slot
        $postData2 = [
            'customer_name' => 'Test Pelanggan 2 (Bentrok)',
            'customer_whatsapp' => '089876543210',
            'service_id' => $service->id,
            'barber_id' => $barber->id,
            'booking_date' => $futureDate,
            'booking_time' => $time,
        ];

        $res2 = $this->postJson('/api/bookings', $postData2);
        $res2->assertStatus(422);
        $res2->assertJson(['success' => false]);
    }
}
