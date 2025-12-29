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

use App\Models\Location;
use Illuminate\Support\Str;

Route::get('/fix-bulk-locations', function () {
    $report = [];
    $count = 0;

    // 1. DÜZELTİLECEK HEDEFLERİ BELİRLE
    // Type'ı 'site' olan ama aslında daire ismi taşıyanları çekiyoruz.
    // Parent ID'si olmayanları (en tepede duranları) alıyoruz.
    $records = Location::where('type', 'site')
        ->whereNull('parent_id')
        ->get();

    foreach ($records as $record) {
        $name = Str::upper($record->name); // Büyük harfe çevirip kontrol edelim
        $parent = null;
        $cleanName = $record->name;

        // --- SENARYO 1: BEYTEPE ELİF SİTESİ ---
        if (Str::contains($name, 'BEYTEPE ELİF')) {
            // 1. Ana Siteyi Bul/Oluştur
            $site = Location::firstOrCreate(
                ['name' => 'Beytepe Elif Sitesi'],
                ['type' => 'site', 'ownership' => 'rented']
            );

            // 2. Blok Kontrolü (C ve D Blokları Gördüm)
            if (Str::contains($name, 'C BLOK')) {
                $block = Location::firstOrCreate(
                    ['name' => 'C Blok', 'parent_id' => $site->id],
                    ['type' => 'block', 'ownership' => 'rented']
                );
                $parent = $block;
                $cleanName = str_replace(['BEYTEPE ELİF SİTESİ', 'C BLOK'], '', $record->name);
            } elseif (Str::contains($name, 'D BLOK')) {
                $block = Location::firstOrCreate(
                    ['name' => 'D Blok', 'parent_id' => $site->id],
                    ['type' => 'block', 'ownership' => 'rented']
                );
                $parent = $block;
                $cleanName = str_replace(['BEYTEPE ELİF SİTESİ', 'D BLOK'], '', $record->name);
            } else {
                // Blok yazmıyorsa direkt siteye bağlayalım
                $parent = $site;
                $cleanName = str_replace('BEYTEPE ELİF SİTESİ', '', $record->name);
            }
        }

        // --- SENARYO 2: FISTIKLIK TUĞÇE SİTESİ ---
        elseif (Str::contains($name, 'TUĞÇE SİTESİ')) {
            $site = Location::firstOrCreate(
                ['name' => 'Fıstıklık Tuğçe Sitesi'],
                ['type' => 'site', 'ownership' => 'rented', 'address' => 'Fıstıklık Mah.']
            );

            if (Str::contains($name, 'C BLOK')) {
                $block = Location::firstOrCreate(
                    ['name' => 'C Blok', 'parent_id' => $site->id],
                    ['type' => 'block', 'ownership' => 'rented']
                );
                $parent = $block;
                $cleanName = str_replace(['FISTIKLIK MAHALLESI', 'TUĞÇE SİTESİ', 'C BLOK'], '', $record->name);
            } else {
                $parent = $site;
                $cleanName = str_replace(['FISTIKLIK MAHALLESI', 'TUĞÇE SİTESİ'], '', $record->name);
            }
        }

        // --- SENARYO 3: FISTIKLIK ASRIN SİTESİ ---
        elseif (Str::contains($name, 'ASRIN SİTESİ')) {
            $site = Location::firstOrCreate(
                ['name' => 'Fıstıklık Asrın Sitesi'],
                ['type' => 'site', 'ownership' => 'rented', 'address' => 'Fıstıklık Mah.']
            );

            if (Str::contains($name, 'A BLOK')) { // Görselde A Blok gördüm
                $block = Location::firstOrCreate(
                    ['name' => 'A Blok', 'parent_id' => $site->id],
                    ['type' => 'block', 'ownership' => 'rented']
                );
                $parent = $block;
                $cleanName = str_replace(['FISTIKLIK MAHALLESI', 'ASRIN SİTESİ', 'A BLOK'], '', $record->name);
            } else {
                $parent = $site;
                $cleanName = str_replace(['FISTIKLIK MAHALLESI', 'ASRIN SİTESİ'], '', $record->name);
            }
        }

        // --- SENARYO 4: 15 TEMMUZ LOJMANLARI ---
        elseif (Str::contains($name, '15 TEMMUZ')) {
            $site = Location::firstOrCreate(
                ['name' => '15 Temmuz Lojmanları'],
                ['type' => 'site', 'ownership' => 'owned'] // Lojman genelde mülktür
            );
            // Burada blok göremedim, direkt siteye bağlıyoruz
            $parent = $site;
            $cleanName = str_replace(['15 TEMMUZ LOJMAN', '15 TEMMUZ'], '', $record->name);
        }

        // --- SENARYO 5: MURAT APARTMANI ---
        elseif (Str::contains($name, 'MURAT APARTM')) {
            $site = Location::firstOrCreate(
                ['name' => 'Murat Apartmanı'],
                ['type' => 'site', 'ownership' => 'rented', 'address' => 'Binevler Mah.']
            );
            $parent = $site;
            $cleanName = str_replace(['BİNEVLER MAHALLESİ', 'MURAT APARTMANI', 'MURAT APARTM'], '', $record->name);
        }

        // --- EĞER YUKARIDAKİLERDEN BİRİNE UYDUYSA GÜNCELLE ---
        if ($parent) {
            // İsmi temizle (baştaki/sondaki boşlukları sil)
            $cleanName = trim($cleanName);
            // Eğer boş kaldıysa (bazen sadece site adı girilmiş olabilir) eski ad kalsın
            if (empty($cleanName))
                $cleanName = "İsimsiz Daire " . $record->id;

            $oldName = $record->name;

            $record->update([
                'type' => 'apartment', // Artık bunlar birer daire
                'parent_id' => $parent->id, // Yeni babasına bağlandı
                'name' => $cleanName // Kısa ve temiz ismi
            ]);

            $report[] = "$oldName -> <b>{$parent->name} > $cleanName</b> olarak taşındı.";
            $count++;
        }
    }

    echo "<h1>Toplam $count kayıt düzenlendi!</h1>";
    echo "<ul>";
    foreach ($report as $r) {
        echo "<li>$r</li>";
    }
    echo "</ul>";
});

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

    // Çıkış Yap
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

});