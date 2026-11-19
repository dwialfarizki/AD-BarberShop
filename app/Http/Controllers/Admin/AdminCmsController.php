<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\PageSection;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminCmsController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAllSettings();
        $sections = PageSection::orderBy('display_order', 'asc')->get();
        $galleries = Gallery::latest()->paginate(12);

        return view('admin.cms.index', compact('settings', 'sections', 'galleries'));
    }

    /**
     * Tab 1: Theme Manager
     */
    public function updateTheme(Request $request)
    {
        $request->validate([
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'theme_mode' => 'required|in:light,dark',
            'logo' => 'nullable|image|max:2048',
            'hero_image' => 'nullable|image|max:3072',
        ], [
            'primary_color.regex' => 'Format kode warna HEX tidak valid (contoh: #FFCC00).',
        ]);

        SiteSetting::set('primary_color', $request->primary_color);
        SiteSetting::set('theme_mode', $request->theme_mode);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('cms', 'public');
            SiteSetting::set('logo_path', $path);
        }

        if ($request->hasFile('hero_image')) {
            $path = $request->file('hero_image')->store('cms', 'public');
            SiteSetting::set('hero_image', $path);
        }

        return back()->with('success', 'Tema dan identitas visual website berhasil disimpan!');
    }

    /**
     * Tab 2: Layout Configurator - Reorder sections
     */
    public function updateSectionOrder(Request $request, PageSection $section)
    {
        $direction = $request->input('direction'); // 'up' or 'down'
        $currentOrder = $section->display_order;

        if ($direction === 'up' && $currentOrder > 1) {
            $prev = PageSection::where('display_order', '<', $currentOrder)
                ->orderByDesc('display_order')
                ->first();
            if ($prev) {
                $section->update(['display_order' => $prev->display_order]);
                $prev->update(['display_order' => $currentOrder]);
            }
        } elseif ($direction === 'down') {
            $next = PageSection::where('display_order', '>', $currentOrder)
                ->orderBy('display_order')
                ->first();
            if ($next) {
                $section->update(['display_order' => $next->display_order]);
                $next->update(['display_order' => $currentOrder]);
            }
        }

        return back()->with('success', "Urutan tata letak seksi berhasil diperbarui.");
    }

    public function toggleSection(PageSection $section)
    {
        $section->update(['is_visible' => !$section->is_visible]);
        $status = $section->is_visible ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Seksi {$section->title} kini {$status} pada landing page.");
    }

    /**
     * Tab 3: Content Authoring
     */
    public function updateContent(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'tagline' => 'required|string|max:100',
            'hero_title' => 'required|string|max:200',
            'hero_subtitle' => 'required|string|max:500',
            'operating_hours' => 'required|string|max:100',
            'google_rating' => 'required|numeric|min:1|max:5',
            'address' => 'required|string|max:300',
            'map_lat' => 'required|numeric',
            'map_lng' => 'required|numeric',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'whatsapp_number' => 'nullable|string',
            'announcement_text' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $val) {
            SiteSetting::set($key, $val);
        }

        SiteSetting::set('announcement_active', $request->has('announcement_active') ? '1' : '0');

        return back()->with('success', 'Konten website dan pengumuman berhasil diperbarui.');
    }

    /**
     * Inspirasi Gaya Rambut Gallery Items CRUD
     */
    public function storeGallery(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'image' => 'required|image|max:3072',
        ], [
            'title.required' => 'Judul model rambut wajib diisi.',
            'image.required' => 'Foto model rambut wajib diunggah.',
        ]);

        $path = $request->file('image')->store('galleries', 'public');

        Gallery::create([
            'title' => $request->title,
            'image_path' => $path,
            'is_published' => true,
        ]);

        return back()->with('success', 'Foto inspirasi gaya rambut berhasil ditambahkan!');
    }

    public function toggleGallery(Gallery $gallery)
    {
        $gallery->update(['is_published' => !$gallery->is_published]);
        $status = $gallery->is_published ? 'dipublikasikan' : 'diarsipkan (draft)';

        return back()->with('success', "Foto {$gallery->title} kini {$status}.");
    }

    public function destroyGallery(Gallery $gallery)
    {
        if (!str_starts_with($gallery->image_path, 'http') && Storage::disk('public')->exists($gallery->image_path)) {
            Storage::disk('public')->delete($gallery->image_path);
        }

        $gallery->delete();
        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }
}
