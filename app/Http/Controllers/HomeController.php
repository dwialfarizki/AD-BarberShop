<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Gallery;
use App\Models\PageSection;
use App\Models\Service;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAllSettings();
        
        // Defaults if not set
        $settings['site_name'] = $settings['site_name'] ?? 'AD Barbershop Bandung';
        $settings['tagline'] = $settings['tagline'] ?? '#KamiSolusi';
        $settings['primary_color'] = $settings['primary_color'] ?? '#FFCC00';
        $settings['theme_mode'] = $settings['theme_mode'] ?? 'light';
        $settings['operating_hours'] = $settings['operating_hours'] ?? 'Open Daily: 10:00 - 21:00 WIB';
        $settings['google_rating'] = $settings['google_rating'] ?? '4.9';
        $settings['map_lat'] = $settings['map_lat'] ?? '-6.8932';
        $settings['map_lng'] = $settings['map_lng'] ?? '107.6171';
        $settings['mapbox_token'] = config('services.mapbox.access_token', env('MAPBOX_ACCESS_TOKEN', ''));

        // Load visible sections sorted by display_order
        $sections = PageSection::visible()->get();

        // Load active services grouped or ordered
        $services = Service::where('is_active', true)->orderBy('price', 'asc')->get();

        // Load active barbers
        $barbers = Barber::where('is_active', true)->orderBy('chair_number', 'asc')->get();

        // Load published gallery images
        $galleries = Gallery::where('is_published', true)->latest()->take(8)->get();

        $todayDate = Carbon::today('Asia/Jakarta')->format('Y-m-d');
        $minDate = $todayDate;
        $maxDate = Carbon::today('Asia/Jakarta')->addDays(14)->format('Y-m-d');

        return view('home', compact(
            'settings',
            'sections',
            'services',
            'barbers',
            'galleries',
            'minDate',
            'maxDate'
        ));
    }
}
