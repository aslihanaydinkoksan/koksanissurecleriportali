<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\StayController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\ReportSettingController;

// --- MİSAFİR ROTALARI (Giriş yapmamışlar görebilir) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// --- KORUNMUŞ ROTALAR (Sadece giriş yapanlar görebilir) ---
Route::middleware(['auth'])->group(function () {

    // Anasayfa Rotaları
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index']);
    Route::get('/locations/{parentId?}', [LocationController::class, 'index'])
        ->where('parentId', '[0-9]+') // Sadece rakam kabul et (çakışmayı önler)
        ->name('locations.index');

    Route::get('/locations/create/{parentId?}', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations/store', [LocationController::class, 'store'])->name('locations.store');
    // Usta Atama Rotaları
    Route::post('/locations/{id}/assign', [LocationController::class, 'assignService'])->name('locations.assign');
    Route::delete('/assignments/{id}', [LocationController::class, 'removeAssignment'])->name('assignments.destroy');
    Route::get('/locations/{id}/show', [LocationController::class, 'show'])->name('locations.show');
    Route::get('/locations/{id}/edit', [LocationController::class, 'edit'])->name('locations.edit');
    Route::get('/locations/{id}/print', [LocationController::class, 'print'])->name('locations.print');
    Route::put('/locations/{id}', [LocationController::class, 'update'])->name('locations.update');
    Route::post('/locations/{id}/subscription', [LocationController::class, 'addSubscription'])->name('locations.addSubscription');
    Route::delete('/locations/{id}', [LocationController::class, 'destroy'])->name('locations.destroy');

    // --- MİSAFİR (RESIDENT) ROTALARI ---
    Route::get('/residents', [ResidentController::class, 'index'])->name('residents.index');
    Route::get('/residents/create', [ResidentController::class, 'create'])->name('residents.create');
    Route::get('/residents/{id}/edit', [ResidentController::class, 'edit'])->name('residents.edit');
    Route::put('/residents/{id}', [ResidentController::class, 'update'])->name('residents.update');
    Route::post('/residents/store', [ResidentController::class, 'store'])->name('residents.store');
    Route::post('/residents/store-ajax', [ResidentController::class, 'storeAjax'])->name('residents.storeAjax');
    Route::delete('/residents/{id}', [ResidentController::class, 'destroy'])->name('residents.destroy');

    // --- KONAKLAMA (STAY/CHECK-IN) ROTALARI ---
    // Bir odaya giriş yapmak için form aç
    Route::get('/stays/create/{locationId}', [StayController::class, 'create'])->name('stays.create');
    // Girişi kaydet
    Route::post('/stays/store', [StayController::class, 'store'])->name('stays.store');
    // Çıkış Formu
    Route::get('/stays/checkout/{stayId}', [StayController::class, 'checkout'])->name('stays.checkout');
    // Çıkışı Kaydet
    Route::post('/stays/checkout/{stayId}', [StayController::class, 'processCheckout'])->name('stays.processCheckout');

    // --- DEMİRBAŞ (ASSET) ROTALARI ---
    Route::get('/assets/create/{locationId}', [AssetController::class, 'create'])->name('assets.create');
    Route::post('/assets/store', [AssetController::class, 'store'])->name('assets.store');
    Route::delete('/assets/{id}', [AssetController::class, 'destroy'])->name('assets.destroy');

    // --- REHBER (CONTACT) ROTALARI ---
    Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    // --- KULLANICI YÖNETİMİ ROTALARI ---
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('users', UserController::class);
        // Hareket Geçmişini Temizleme
        Route::delete('/stays/clear-all', [StayController::class, 'clearAll'])->name('stays.clearAll');
        // Tekli Hareket Silme
        Route::delete('/stays/{id}', [StayController::class, 'destroy'])->name('stays.destroy');

    });

    // --- RAPORLAMA ROTALARI ---
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // --- MAIL ---
    Route::resource('report-settings', ReportSettingController::class);
    // Çıkış Yap
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});