<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Gallery;
use App\Models\SiteSetting;
use App\Models\PageSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@adbarber.com'],
            [
                'name' => 'Admin AD Barber',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 2. Services
        $servicesData = [
            [
                'name' => 'Gentlemen Classic Haircut',
                'category' => 'Haircut',
                'price' => 50000,
                'duration_minutes' => 45,
                'description' => 'Potong rambut presisi, konsultasi model rambut, keramas segar, dan styling dengan pomade premium.',
                'is_active' => true,
            ],
            [
                'name' => 'Premium Haircut & Wash Deluxe',
                'category' => 'Haircut',
                'price' => 70000,
                'duration_minutes' => 60,
                'description' => 'Paket potong rambut lengkap dengan hair tonic, pijat kepala rileks, cuci rambut air hangat, dan styling pomade.',
                'is_active' => true,
            ],
            [
                'name' => 'Beard Sculpting & Hot Towel Shave',
                'category' => 'Shaving',
                'price' => 35000,
                'duration_minutes' => 30,
                'description' => 'Cukur jenggot & kumis rapi dengan handuk hangat aromaterapi, busa cukur lembut, dan aftershave menyegarkan.',
                'is_active' => true,
            ],
            [
                'name' => 'Hair Spa & Scalp Detox Treatment',
                'category' => 'Treatment',
                'price' => 85000,
                'duration_minutes' => 60,
                'description' => 'Perawatan kulit kepala mendalam, pembersihan ketombe & minyak berlebih, creambath bernutrisi plus pijat bahu.',
                'is_active' => true,
            ],
            [
                'name' => 'Fashion Hair Coloring / Highlight',
                'category' => 'Coloring',
                'price' => 150000,
                'duration_minutes' => 90,
                'description' => 'Pewarnaan rambut trendy (Ash Grey, Brown, Bleaching, dll.) dengan produk berkualitas tinggi tanpa merusak rambut.',
                'is_active' => true,
            ],
            [
                'name' => 'Junior Haircut (Anak < 12 Tahun)',
                'category' => 'Haircut',
                'price' => 40000,
                'duration_minutes' => 35,
                'description' => 'Potong rambut ramah anak dengan kapster sabar dan teliti agar si kecil tampil percaya diri.',
                'is_active' => true,
            ],
            [
                'name' => 'Full Grooming Ultimate VIP',
                'category' => 'Package',
                'price' => 120000,
                'duration_minutes' => 75,
                'description' => 'Paket komplit: Haircut + Shave + Hair Spa Scalp Massage + Black Mask Komedo + Styling Pomade.',
                'is_active' => true,
            ],
        ];

        $services = [];
        foreach ($servicesData as $data) {
            $services[] = Service::updateOrCreate(['name' => $data['name']], $data);
        }

        // 3. Barbers (Kapster)
        $barbersData = [
            [
                'name' => 'Kang Dwi (Lead Barber)',
                'chair_number' => 1,
                'specialty' => 'Fade, Textured Crop & Modern Beard',
                'photo_path' => 'barbers/barber1.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Bro Rizki',
                'chair_number' => 2,
                'specialty' => 'Classic Undercut & Pompadour Master',
                'photo_path' => 'barbers/barber2.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Kang Asep Sukarna',
                'chair_number' => 3,
                'specialty' => 'Hot Towel Shave & Traditional Grooming',
                'photo_path' => 'barbers/barber3.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Mas Dimas Alamsyah',
                'chair_number' => 4,
                'specialty' => 'Two Block, Mullet & Korean Perm Styles',
                'photo_path' => 'barbers/barber4.jpg',
                'is_active' => true,
            ],
        ];

        $barbers = [];
        foreach ($barbersData as $data) {
            $barbers[] = Barber::updateOrCreate(['name' => $data['name']], $data);
        }

        // 4. Site Settings
        $settings = [
            'site_name' => 'AD Barbershop Bandung',
            'tagline' => '#KamiSolusi',
            'hero_title' => 'Solusi Gaya Rambut Terbaik, Percaya Diri Maksimal',
            'hero_subtitle' => 'Rasakan sensasi grooming premium bersama kapster profesional Bandung. Suasana modern, alat steril, dan reservasi jadwal online anti antre.',
            'hero_image' => '',
            'logo_path' => '',
            'primary_color' => '#FFCC00',
            'theme_mode' => 'light',
            'operating_hours' => 'Open Daily: 10:00 - 21:00 WIB',
            'google_rating' => '4.9',
            'address' => 'Jl. Dipati Ukur No. 42, Coblong, Kota Bandung, Jawa Barat 40132',
            'map_lat' => '-6.8932',
            'map_lng' => '107.6171',
            'instagram_url' => 'https://instagram.com/ad_barber',
            'tiktok_url' => 'https://tiktok.com/@ad_barber',
            'whatsapp_number' => '6281234567890',
            'announcement_text' => '🔥 Promo Spesial Persib Day: Dapatkan potongan harga Rp 10.000 untuk semua layanan setiap hari pertandingan!',
            'announcement_active' => '1',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 5. Page Sections
        $sections = [
            ['section_key' => 'hero', 'title' => 'Hero Banner Utama', 'display_order' => 1, 'is_visible' => true],
            ['section_key' => 'services', 'title' => 'Daftar Layanan & Pricelist', 'display_order' => 2, 'is_visible' => true],
            ['section_key' => 'gallery', 'title' => 'Inspirasi Gaya Rambut', 'display_order' => 3, 'is_visible' => true],
            ['section_key' => 'booking_form', 'title' => 'Formulir Reservasi Jadwal', 'display_order' => 4, 'is_visible' => true],
            ['section_key' => 'map_location', 'title' => 'Lokasi & Kontak Interaktif', 'display_order' => 5, 'is_visible' => true],
        ];

        foreach ($sections as $sec) {
            PageSection::updateOrCreate(['section_key' => $sec['section_key']], $sec);
        }

        // 6. Galleries ("Inspirasi Gaya Rambut")
        $galleries = [
            ['title' => 'Low Taper Fade with Textured Top', 'image_path' => 'https://images.unsplash.com/photo-1622286342621-4bd786c2447c?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
            ['title' => 'Modern French Crop Clean Edge', 'image_path' => 'https://images.unsplash.com/photo-1599351431202-1e0f0137899a?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
            ['title' => 'Korean Two-Block Natural Flow', 'image_path' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
            ['title' => 'Classic Executive Pompadour', 'image_path' => 'https://images.unsplash.com/photo-1517832606589-7629c339590a?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
            ['title' => 'Buzz Cut Fade Sharp Contour', 'image_path' => 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
            ['title' => 'Modern Mullet Streetwear Vibe', 'image_path' => 'https://images.unsplash.com/photo-1605497788044-5a32c7078486?auto=format&fit=crop&w=600&q=80', 'is_published' => true],
        ];

        foreach ($galleries as $gal) {
            Gallery::updateOrCreate(['title' => $gal['title']], $gal);
        }

        // 7. Realistic Indonesian Bookings Data for Today and Past Months
        $customerNames = [
            'Fajar Nugraha', 'Dimas Pratama', 'Indra Wijaya', 'Budi Santoso', 
            'Rian Hidayat', 'Aditya Kurnia', 'Reza Maulana', 'Farhan Ramadhan', 
            'Gilang Saputra', 'Deni Setiawan', 'Bayu Anggoro', 'Wahyu Firmansyah',
            'Alif Rahman', 'Taufik Ismail', 'Rangga Sasongko', 'Ilham Prasetyo'
        ];

        $today = Carbon::today('Asia/Jakarta');

        // Past 30 days bookings
        for ($i = 30; $i >= 1; $i--) {
            $date = $today->copy()->subDays($i);
            $dailyCount = rand(3, 8);

            for ($j = 0; $j < $dailyCount; $j++) {
                $service = $services[array_rand($services)];
                $barber = $barbers[array_rand($barbers)];
                $hour = rand(10, 20);
                $time = sprintf('%02d:00:00', $hour);
                $isWalkIn = (rand(1, 10) <= 4); // 40% walk-in
                $code = 'ADB-' . strtoupper(substr(md5(uniqid()), 0, 6));

                Booking::create([
                    'booking_code' => $code,
                    'customer_name' => $customerNames[array_rand($customerNames)],
                    'customer_whatsapp' => '0812' . rand(10000000, 99999999),
                    'service_id' => $service->id,
                    'barber_id' => $barber->id,
                    'booking_date' => $date->format('Y-m-d'),
                    'booking_time' => $time,
                    'total_price' => $service->price,
                    'booking_type' => $isWalkIn ? 'walk_in' : 'online',
                    'payment_method' => $isWalkIn ? 'cash' : 'midtrans_qris',
                    'payment_status' => 'paid',
                    'booking_status' => 'completed',
                    'snap_token' => $isWalkIn ? null : 'snap-token-' . rand(10000, 99999),
                    'midtrans_transaction_id' => $isWalkIn ? null : 'TRX-' . rand(100000, 999999),
                    'created_at' => $date->copy()->setTime($hour, 0),
                    'updated_at' => $date->copy()->setTime($hour, 45),
                ]);
            }
        }

        // Today's bookings with mixed statuses (Pending, Arrived, Completed)
        $todaySlots = [10, 11, 13, 14, 15, 16, 17, 19, 20];
        foreach ($todaySlots as $idx => $slotHour) {
            $service = $services[$idx % count($services)];
            $barber = $barbers[$idx % count($barbers)];
            $code = 'ADB-' . strtoupper(substr(md5("today_" . $idx), 0, 6));

            $status = match (true) {
                $slotHour < 12 => 'completed',
                $slotHour >= 12 && $slotHour <= 14 => 'arrived',
                default => 'pending',
            };

            $payStatus = ($status === 'completed' || $status === 'arrived') ? 'paid' : (rand(0, 1) ? 'paid' : 'unpaid');

            Booking::create([
                'booking_code' => $code,
                'customer_name' => $customerNames[$idx % count($customerNames)],
                'customer_whatsapp' => '0813' . rand(10000000, 99999999),
                'service_id' => $service->id,
                'barber_id' => $barber->id,
                'booking_date' => $today->format('Y-m-d'),
                'booking_time' => sprintf('%02d:00:00', $slotHour),
                'total_price' => $service->price,
                'booking_type' => ($idx % 3 === 0) ? 'walk_in' : 'online',
                'payment_method' => ($idx % 3 === 0) ? 'cash' : 'midtrans_qris',
                'payment_status' => $payStatus,
                'booking_status' => $status,
                'snap_token' => 'snap-today-' . $idx,
                'midtrans_transaction_id' => 'TRX-TODAY-' . $idx,
                'created_at' => $today->copy()->setTime(8, 30),
                'updated_at' => $today->copy()->setTime(9, 0),
            ]);
        }
    }
}
