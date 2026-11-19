<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminBarberController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminCmsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminServiceController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Customer Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cek-pesanan', [BookingController::class, 'showTicket'])->name('public.ticket');
Route::post('/booking/{code}/simulate-payment', [BookingController::class, 'simulatePayment'])->name('public.simulate_payment');

// AJAX & Webhook Endpoints
Route::get('/api/available-slots', [BookingController::class, 'availableSlots'])->name('api.available_slots');
Route::post('/api/bookings', [BookingController::class, 'store'])->name('api.bookings.store');
Route::post('/api/midtrans-callback', [MidtransWebhookController::class, 'handle'])->name('api.midtrans.callback');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    /*
    |--------------------------------------------------------------------------
    | Protected Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/', fn() => redirect()->route('admin.dashboard'));
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Manajemen Antrean & Walk-In
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('admin.bookings.index');
        Route::patch('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('admin.bookings.update_status');
        Route::post('/bookings/walk-in', [AdminBookingController::class, 'storeWalkIn'])->name('admin.bookings.store_walk_in');

        // Laporan Keuangan
        Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/reports/export-csv', [AdminReportController::class, 'exportCsv'])->name('admin.reports.export_csv');
        Route::get('/reports/print', [AdminReportController::class, 'printReport'])->name('admin.reports.print');

        // Manajemen Layanan
        Route::resource('/services', AdminServiceController::class)->names('admin.services');
        Route::patch('/services/{service}/toggle', [AdminServiceController::class, 'toggle'])->name('admin.services.toggle');

        // Manajemen Kapster
        Route::resource('/barbers', AdminBarberController::class)->names('admin.barbers');
        Route::patch('/barbers/{barber}/toggle', [AdminBarberController::class, 'toggle'])->name('admin.barbers.toggle');

        // Web Customization & Mini-CMS
        Route::get('/cms', [AdminCmsController::class, 'index'])->name('admin.cms.index');
        Route::post('/cms/theme', [AdminCmsController::class, 'updateTheme'])->name('admin.cms.theme');
        Route::post('/cms/content', [AdminCmsController::class, 'updateContent'])->name('admin.cms.content');
        Route::patch('/cms/sections/{section}/order', [AdminCmsController::class, 'updateSectionOrder'])->name('admin.cms.sections.order');
        Route::patch('/cms/sections/{section}/toggle', [AdminCmsController::class, 'toggleSection'])->name('admin.cms.sections.toggle');
        Route::post('/cms/gallery', [AdminCmsController::class, 'storeGallery'])->name('admin.cms.gallery.store');
        Route::patch('/cms/gallery/{gallery}/toggle', [AdminCmsController::class, 'toggleGallery'])->name('admin.cms.gallery.toggle');
        Route::delete('/cms/gallery/{gallery}', [AdminCmsController::class, 'destroyGallery'])->name('admin.cms.gallery.destroy');
    });
});
