
<?php $__env->startSection('title', 'Hoş Geldiniz'); ?>
<?php $__env->startPush('styles'); ?>
    <style>
        /* ... (Mevcut gradientWave, create-shipment-card, ikon vb. stilleriniz aynı kalır) ... */

        #app>main.py-4 {

            padding: 2.5rem 0 !important;

            min-height: calc(100vh - 72px);

            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);

            background-size: 400% 400%;

            animation: gradientWave 18s ease infinite;

        }



        @keyframes gradientWave {

            0% {

                background-position: 0% 50%;

            }



            50% {

                background-position: 100% 50%;

            }



            100% {

                background-position: 0% 50%;

            }

        }



        .create-shipment-card {

            border-radius: 1rem;

            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);

            border: 1px solid rgba(255, 255, 255, 0.3);

            background-color: rgba(255, 255, 255, 0.75);

            backdrop-filter: blur(5px);

            -webkit-backdrop-filter: blur(5px);

        }



        .create-shipment-card .card-header,

        .create-shipment-card .form-label,

        .create-shipment-card .card-body {

            color: #000;

            font-weight: 500;

        }



        .create-shipment-card .card-header {

            font-weight: bold;

        }



        .create-shipment-card .list-group-item {

            background-color: transparent;

            border-bottom: 1px solid rgba(0, 0, 0, 0.1);

        }



        .create-shipment-card .list-group-item:last-child {

            border-bottom: 0;

        }



        .icon-gemi {

            color: #9DECF9 !important;

        }



        .icon-tır,

        .icon-tir {

            color: #FFB84D !important;

        }



        .icon-kamyon {

            color: #60D9A0 !important;

        }



        .icon-uretim {

            color: #4FD1C5 !important;

        }



        .icon-aracgorevi {

            color: #FBD38D !important;

        }



        .icon-etkinlik-genel {

            color: #F093FB !important;

        }



        .icon-egitim {

            color: #B794F4 !important;

        }



        .icon-toplanti {

            color: #667EEA !important;

        }



        .icon-misafir {

            color: #4FD1C5 !important;

        }



        .icon-musteri {

            color: #0456f9 !important;

        }



        .icon-fuar {

            color: #F6AD55 !important;

        }



        .icon-gezi {

            color: #68D391 !important;

        }

        .icon-seyahat {

            color: #68D391 !important;

        }

        .vehicle-icon {

            width: 40px;

            text-align: center;

        }



        .btn-animated-gradient {

            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);

            background-size: 400% 400%;

            animation: gradientWave 18s ease infinite;

            border: none;

            color: white;

            font-weight: bold;

            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;

        }



        .btn-animated-gradient:hover {

            color: white;

            transform: scale(1.05);

            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);

        }



        .event-important-pulse-welcome {

            border-radius: 0.75rem;

            margin-bottom: 0.5rem;

            border: 2px solid #ff4136 !important;

            box-shadow: 0 0 0 rgba(255, 65, 54, 0.4);

            animation: pulse-animation 2s infinite;

            transition: background-color 0.2s ease-in-out;

        }



        .event-important-pulse-welcome:hover {

            background-color: rgba(255, 65, 54, 0.05) !important;

        }



        @keyframes pulse-animation {

            0% {

                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0.7);

            }



            70% {

                box-shadow: 0 0 0 10px rgba(255, 65, 54, 0);

            }



            100% {

                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0);

            }

        }



        .list-group-item-action .fa-bell {

            animation: ring 2s ease-in-out infinite;

        }



        @keyframes ring {

            0% {

                transform: rotate(0);

            }



            10% {

                transform: rotate(14deg);

            }



            20% {

                transform: rotate(-8deg);

            }



            30% {

                transform: rotate(14deg);

            }



            40% {

                transform: rotate(-4deg);

            }



            50% {

                transform: rotate(10deg);

            }



            60% {

                transform: rotate(0);

            }



            100% {

                transform: rotate(0);

            }

        }



        /* ========================================================= */

        /* YENİ EKLENEN KPI KART STİLLERİ */

        /* ========================================================= */

        .kpi-card {

            background-color: rgba(255, 255, 255, 0.85);

            /* Hafif daha opak */

            border-radius: 0.75rem;

            border: 1px solid rgba(255, 255, 255, 0.5);

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);

            backdrop-filter: blur(3px);

            -webkit-backdrop-filter: blur(3px);

            padding: 1.25rem;

            text-align: center;

            transition: transform 0.2s ease, box-shadow 0.2s ease;

        }



        .kpi-card:hover {

            transform: translateY(-5px);

            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);

        }



        .kpi-icon {

            font-size: 2rem;

            margin-bottom: 0.75rem;

        }



        .kpi-value {

            font-size: 2.25rem;

            font-weight: 700;

            color: #000;

        }



        .kpi-label {

            font-size: 0.9rem;

            font-weight: 500;

            color: #333;

            text-transform: uppercase;

            letter-spacing: 0.5px;

        }



        /* Renkler */

        .kpi-lojistik .kpi-icon {

            color: #667EEA;

        }



        .kpi-uretim .kpi-icon {

            color: #4FD1C5;

        }



        .kpi-hizmet .kpi-icon {

            color: #F093FB;

        }



        .kpi-sistem .kpi-icon {

            color: #FBD38D;

        }
    </style>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card create-shipment-card mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-7">
                                <h2 class="card-title mb-0">Hoş Geldiniz, <?php echo e(Auth::user()->name); ?>!</h2>
                                <?php if(Auth::user()->department): ?>
                                <?php else: ?>
                                    <p class="mb-0 text-muted fs-5" style="color: #707D88  !important;">

                                        <strong><?php echo e(ucfirst(Auth::user()->role)); ?></strong>

                                    </p>
                                <?php endif; ?>

                            </div>
                            <div class="row g-3 mb-4">
                                
                                <div class="col-md-4">
                                    <a href="#" class="text-decoration-none">
                                        <div class="card create-shipment-card h-100 hover-effect">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-3"
                                                    style="background: rgba(102, 126, 234, 0.2);">
                                                    <i class="fa-solid fa-plus fa-xl" style="color: #667EEA;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">Yeni Kayıt Oluştur</h6>
                                                    <small class="text-muted">Sisteme veri girişi yap</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                
                                <div class="col-md-4">
                                    <a href="<?php echo e(route('statistics.index')); ?>" class="text-decoration-none">
                                        <div class="card create-shipment-card h-100 hover-effect">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-3"
                                                    style="background: rgba(240, 147, 251, 0.2);">
                                                    <i class="fa-solid fa-chart-pie fa-xl" style="color: #F093FB;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">Detaylı Raporlar</h6>
                                                    <small class="text-muted">Geçmiş verileri analiz et</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                
                                <div class="col-md-4">
                                    <a href="<?php echo e(route('home')); ?>" class="text-decoration-none">
                                        <div class="card create-shipment-card h-100 hover-effect">
                                            <div class="card-body d-flex align-items-center">
                                                <div class="rounded-circle p-3 me-3"
                                                    style="background: rgba(79, 209, 197, 0.2);">
                                                    <i class="fa-solid fa-calendar-check fa-xl" style="color: #4FD1C5;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark">Takvim & Planlama</h6>
                                                    <small class="text-muted">Haftalık planı görüntüle</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <hr>

                            
                            <style>
                                .hover-effect {
                                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                                }

                                .hover-effect:hover {
                                    transform: translateY(-3px);
                                    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
                                }
                            </style>
                        </div>

                        <?php if(isset($importantItems) && $importantItems->isNotEmpty()): ?>

                            <div class="card create-shipment-card mb-4" id="important-items-card">

                                <div class="card-header"
                                    style="background: linear-gradient(135deg, rgba(255, 65, 54, 0.1), rgba(255, 100, 80, 0.1)); border-bottom: 1px solid rgba(255, 65, 54, 0.2);">

                                    <h5 class="mb-0" style="color: #dc3545; font-weight: 700;">

                                        <i class="fas fa-bell"></i> Önemli Bildirimler

                                    </h5>

                                </div>

                                <div class="card-body" style="padding: 1rem 1.5rem;">

                                    <div class="list-group list-group-flush">

                                        <?php $__currentLoopData = $importantItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php

                                                $params = [];

                                                if ($item->date) {
                                                    $params['date'] = $item->date->format('Y-m-d');
                                                }

                                                $params['open_modal_id'] = $item->model_id;

                                                $params['open_modal_type'] = $item->model_type;

                                                $url = route('general.calendar', $params);

                                            ?>
                                            <a href="<?php echo e($url); ?>"
                                                class="list-group-item list-group-item-action event-important-pulse-welcome"
                                                style="background: transparent; border: none; padding: 0.75rem 0.5rem;"
                                                title="Takvimde görmek ve detayı açmak için tıklayın...">
                                                <strong><?php echo e($item->title); ?></strong>
                                                <?php if($item->date): ?>
                                                    <span class="badge bg-danger rounded-pill float-end">
                                                        <?php echo e($item->date->format('d.m.Y')); ?>

                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <?php if($importantItemsCount > 3): ?>
                                        <div class="text-center mt-3">
                                            <a href="<?php echo e(route('important.all')); ?>" class="btn btn-outline-danger btn-sm">
                                                Tüm (<?php echo e($importantItemsCount); ?>) Veriyi Görüntüle...
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <hr>
                        <?php if(isset($kpiData) && !empty($kpiData)): ?>
                            <h4 class="mt-4">
                                <i class="fa-solid fa-chart-line me-1" style="color: #667EEA;"></i>
                                Sistem Geneli (Bugün)
                            </h4>
                            <div class="row g-4 mt-2 mb-3">
                                <div class="col-lg-3 col-md-6">
                                    <div class="kpi-card kpi-lojistik">
                                        <div class="kpi-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['sevkiyat_sayisi']); ?></div>
                                        <div class="kpi-label">Yaklaşan Sevkiyat</div>
                                    </div>

                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kpi-card kpi-uretim">
                                        <div class="kpi-icon"><i class="fa-solid fa-industry"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['plan_sayisi']); ?></div>
                                        <div class="kpi-label">Başlayan Plan</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kpi-card kpi-hizmet">
                                        <div class="kpi-icon"><i class="fa-solid fa-car-side"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['gorev_sayisi']); ?></div>
                                        <div class="kpi-label">Görev & Etkinlik</div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="kpi-card kpi-sistem">
                                        <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['kullanici_sayisi']); ?></div>
                                        <div class="kpi-label">Toplam Kullanıcı</div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if($todayItems->isNotEmpty()): ?>
                            <h4 class="mt-4">
                                <i class="fa-solid fa-clock me-1" style="color: #A78BFA;"></i>
                                <?php echo e($welcomeTitle); ?>

                            </h4>

                            <?php
                                // İkon haritası
                                $iconMap = [
                                    'gemi' => ['icon' => 'fa-ship', 'class' => 'icon-gemi'],
                                    'tır' => ['icon' => 'fa-truck-moving', 'class' => 'icon-tir'],
                                    'tir' => ['icon' => 'fa-truck-moving', 'class' => 'icon-tir'],
                                    'kamyon' => ['icon' => 'fa-truck', 'class' => 'icon-kamyon'],
                                    'uretim' => ['icon' => 'fa-industry', 'class' => 'icon-uretim'],
                                    'aracgorevi' => ['icon' => 'fa-car-side', 'class' => 'icon-aracgorevi'],
                                    'etkinlik' => ['icon' => 'fa-calendar-star', 'class' => 'icon-etkinlik-genel'],
                                    'egitim' => ['icon' => 'fa-chalkboard-user', 'class' => 'icon-egitim'],
                                    'toplanti' => ['icon' => 'fa-users', 'class' => 'icon-toplanti'],
                                    'misafir_karsilama' => ['icon' => 'fa-people-arrows', 'class' => 'icon-misafir'],
                                    'musteri_ziyareti' => ['icon' => 'fa-hands-helping', 'class' => 'icon-musteri'],
                                    'fuar' => ['icon' => 'fa-store', 'class' => 'icon-fuar'],
                                    'gezi' => ['icon' => 'fa-map-signs', 'class' => 'icon-gezi'],
                                    'diger' => ['icon' => 'fa-calendar-star', 'class' => 'icon-etkinlik-genel'],
                                ];
                            ?>

                            <div class="list-group mt-3">
                                <?php $__empty_1 = true; $__currentLoopData = $todayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($departmentSlug === 'uretim'): ?>
                                        <?php $iconInfo = $iconMap['uretim']; ?>
                                        <div class="list-group-item d-flex align-items-center py-3">
                                            <div class="me-3"><i
                                                    class="fa-solid <?php echo e($iconInfo['icon']); ?> fa-2x vehicle-icon <?php echo e($iconInfo['class']); ?>"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <h5 class="mb-1 fw-bold"><?php echo e($item->plan_title); ?></h5>
                                                <p class="mb-0 text-muted"><strong>Başlangıç:</strong>
                                                    <?php echo e($item->week_start_date->format('d.m.Y')); ?></p>
                                            </div>
                                            <a href="<?php echo e(route('home')); ?>?open_modal_id=<?php echo e($item->id); ?>&open_modal_type=production_plan"
                                                class="btn btn-outline-secondary btn-sm">Detay <i
                                                    class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                        </div>
                                    <?php elseif($departmentSlug === 'hizmet'): ?>
                                        <?php
                                            $isEvent = $item instanceof \App\Models\Event;
                                            $isAssignment = $item instanceof \App\Models\VehicleAssignment;
                                            $isTravel = $item instanceof \App\Models\Travel;

                                            $iconInfo = ['icon' => 'fa-question-circle', 'class' => 'text-muted'];
                                            $saat = '-';
                                            $baslik = 'Bilinmeyen Kayıt';
                                            $detay = '';
                                            $modalId = $item->id;
                                            $modalType = 'unknown';

                                            if ($isEvent) {
                                                $itemType = $item->event_type;
                                                $iconInfo = $iconMap[$itemType] ?? $iconMap['etkinlik'];
                                                $saat = $item->start_datetime->format('H:i');
                                                $baslik = $item->title;
                                                $detay = '<strong>Konum:</strong> ' . ($item->location ?? '-');
                                                $modalType = 'event';
                                            } elseif ($isAssignment) {
                                                $iconInfo = $iconMap['aracgorevi'];
                                                $saat = $item->start_time->format('H:i');
                                                $baslik = 'Araç Görevi: ' . $item->task_description;
                                                $detay =
                                                    '<strong>Araç:</strong> ' .
                                                    ($item->vehicle->plate_number ?? 'Bilinmiyor');
                                                $modalType = 'vehicle_assignment';
                                            } elseif ($isTravel) {
                                                $iconInfo = ['icon' => 'fa-route', 'class' => 'icon-seyahat'];
                                                $saat = $item->start_date->format('d/m/Y');
                                                $baslik = 'Seyahat: ' . $item->name;
                                                $detay =
                                                    '<strong>Durum:</strong> ' .
                                                    ($item->status == 'planned' ? 'Planlandı' : 'Tamamlandı');
                                                $modalType = 'travel';
                                            }
                                        ?>
                                        <div class="list-group-item d-flex align-items-center py-3">
                                            <div class="me-3"><i
                                                    class="fa-solid <?php echo e($iconInfo['icon']); ?> fa-2x vehicle-icon <?php echo e($iconInfo['class']); ?>"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <h5 class="mb-1 fw-bold"><?php echo e($baslik); ?></h5>
                                                <p class="mb-0 text-muted"><?php echo $detay; ?> <span
                                                        class="mx-2">|</span>
                                                    <strong><?php echo e($isTravel ? 'Başlangıç' : 'Saat'); ?>:</strong> <span
                                                        class="fw-bold text-dark"><?php echo e($saat); ?></span>
                                                </p>
                                            </div>
                                            <a href="<?php echo e(route('home')); ?>?open_modal_id=<?php echo e($modalId); ?>&open_modal_type=<?php echo e($modalType); ?>"
                                                class="btn btn-outline-secondary btn-sm">Detay <i
                                                    class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                        </div>

                                        
                                    <?php elseif($departmentSlug === 'lojistik'): ?>
                                        <?php
                                            $aracTipi = strtolower($item->arac_tipi ?? '');
                                            $iconInfo = $iconMap[$aracTipi] ?? [
                                                'icon' => 'fa-question-circle',
                                                'class' => 'text-muted',
                                            ];
                                            // Tarihi ve saati parse et
                                            $tarih = \Carbon\Carbon::parse($item->tahmini_varis_tarihi);
                                        ?>
                                        <div class="list-group-item d-flex align-items-center py-3">
                                            <div class="me-3"><i
                                                    class="fa-solid <?php echo e($iconInfo['icon']); ?> fa-2x vehicle-icon <?php echo e($iconInfo['class']); ?>"></i>
                                            </div>
                                            <div class="d-flex flex-column flex-grow-1">
                                                <h5 class="mb-1 fw-bold">Yaklaşan <?php echo e($item->kargo_icerigi); ?> Sevkiyatı
                                                </h5>

                                                
                                                <p class="mb-0 text-muted">
                                                    <strong>Araç:</strong> <?php echo e(ucfirst($aracTipi)); ?>

                                                    <span class="mx-2">|</span>

                                                    
                                                    <i class="fa-regular fa-calendar me-1" style="color: #667EEA;"></i>
                                                    <strong><?php echo e($tarih->format('d.m.Y')); ?></strong>

                                                    <span class="mx-2">-</span>

                                                    
                                                    <i class="fa-regular fa-clock me-1" style="color: #F093FB;"></i>
                                                    <span class="fw-bold text-dark"><?php echo e($tarih->format('H:i')); ?></span>
                                                </p>
                                            </div>
                                            <a href="<?php echo e(route('home')); ?>?open_modal_id=<?php echo e($item->id); ?>&open_modal_type=shipment"
                                                class="btn btn-outline-secondary btn-sm">Detay <i
                                                    class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
                <?php if(isset($chartData) && !empty($chartData)): ?>
                    <div class="card create-shipment-card">

                        <div class="card-header">

                            📊 <?php echo e($chartTitle ?? 'Genel Veri Akışı'); ?>


                        </div>

                        <div class="card-body">

                            <div id="sankey-chart" data-sankey='<?php echo json_encode($chartData, 15, 512) ?>'
                                style="width: 100%; height: 500px;">

                                <p class="text-center text-muted p-5">Grafik yükleniyor...</p>

                            </div>

                        </div>

                    </div>
                <?php endif; ?>

                



            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('page_scripts'); ?>

    

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            console.log('DOM yüklendi, Sankey grafiği hazırlanıyor...');



            const sankeyChartEl = document.getElementById('sankey-chart');



            if (!sankeyChartEl) {

                console.log(

                    'Sankey chart elementi DOM\'da bulunamadı (Koşul nedeniyle render edilmemiş olabilir).');

                return;

            }



            console.log('sankey-chart elementi bulundu');



            let sankeyData = [];

            try {

                const dataAttr = sankeyChartEl.dataset.sankey;

                if (!dataAttr) {

                    console.error('data-sankey attribute bulunamadı!');

                    sankeyChartEl.innerHTML =

                        '<p class="text-center text-danger p-5">Veri yüklenemedi (attribute eksik)</p>';

                    return;

                }

                sankeyData = JSON.parse(dataAttr);

                if (!Array.isArray(sankeyData) || sankeyData.length === 0) {

                    console.warn('Sankey verisi boş veya geçersiz');

                }

                console.log('Veri geçerli, Google Charts yükleniyor...');

            } catch (error) {

                console.error('JSON parse hatası:', error);

                sankeyChartEl.innerHTML = '<p class="text-center text-danger p-5">Veri parse hatası: ' + error

                    .message + '</p>';

                return;

            }



            google.charts.load('current', {

                packages: ['sankey']

            });

            google.charts.setOnLoadCallback(function() {

                console.log('Google Charts yüklendi, grafik çiziliyor...');

                drawChart(sankeyData, sankeyChartEl);

            });



            function drawChart(sankeyData, chartElement) {

                if (sankeyData.length === 1 && (sankeyData[0][0] === 'Veri Yok' || sankeyData[0][0] === 'Sistem' ||

                        sankeyData[0][1] === 'Henüz Kayıt Yok')) {

                    console.warn('Sankey verisi placeholder olarak geldi.');

                    sankeyChartEl.innerHTML =

                        '<p class="text-center text-muted p-5">Grafik için yeterli veri bulunamadı.</p>';

                    return;

                }



                try {

                    console.log('drawChart fonksiyonu çalışıyor');

                    var data = new google.visualization.DataTable();

                    data.addColumn('string', 'Kaynak');

                    data.addColumn('string', 'Hedef');

                    data.addColumn('number', 'Değer');

                    data.addColumn({

                        type: 'string',

                        role: 'tooltip'

                    });

                    const chart = new google.visualization.Sankey(chartElement);

                    const options = {

                        width: '100%',

                        height: 500,

                        sankey: {

                            node: {

                                label: {

                                    fontName: 'Arial',

                                    fontSize: 14,

                                    color: '#000'

                                },

                                interactivity: true,

                                nodePadding: 20

                            },

                            link: {

                                colorMode: 'gradient',

                                colors: ['#667EEA', '#F093FB', '#4FD1C5', '#FBD38D', '#FC8181'],

                                highlightOnMouseOver: false

                            }

                        }

                    };

                    let steps = 40;

                    let currentStep = 0;

                    const initialData = sankeyData.map(row => {

                        const from = row[0];

                        const to = row[1];

                        const finalValue = row[2];

                        const tooltipText = `${from} -> ${to}\nDeğer: ${finalValue}`;

                        const initialValue = 0.00001;

                        return [from, to, initialValue, tooltipText];

                    });

                    data.addRows(initialData);

                    console.log('Başlangıç verisi eklendi, ilk çizim yapılıyor...');



                    function animateFlow() {

                        if (currentStep >= steps) {

                            console.log('Animasyon tamamlandı, son kez tam değerlerle çiziliyor.');

                            sankeyData.forEach((row, i) => {

                                data.setValue(i, 2, row[2]);

                            });

                            chart.draw(data, options);

                            setTimeout(addGlowEffect, 400);

                            return;

                        }

                        sankeyData.forEach((row, i) => {

                            const progress = currentStep / steps;

                            const wave = 0.1 * Math.sin(progress * Math.PI * 2 + i);

                            const finalValue = row[2];

                            const animatedProgress = progress + wave * 0.2;

                            let value = finalValue * animatedProgress;

                            value = Math.max(0.00001, value);

                            data.setValue(i, 2, value);

                        });

                        chart.draw(data, options);

                        if (currentStep < steps) {

                            currentStep++;

                            requestAnimationFrame(animateFlow);

                        } else {

                            console.log('Animasyon tamamlandı');

                            setTimeout(addGlowEffect, 400);

                        }

                    }



                    function addGlowEffect() {

                        const svg = chartElement.querySelector('svg');

                        if (!svg) {

                            console.warn('SVG elementi bulunamadı, glow efekti eklenemedi');

                            return;

                        }

                        if (!svg.querySelector('#glow')) {

                            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');

                            const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');

                            filter.setAttribute('id', 'glow');

                            filter.innerHTML =

                                `<feGaussianBlur stdDeviation="4" result="coloredBlur"></feGaussianBlur><feMerge><feMergeNode in="coloredBlur"></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge>`;

                            defs.appendChild(filter);

                            svg.prepend(defs);

                        }

                        if (!document.getElementById('flowGlowStyle')) {

                            const style = document.createElement('style');

                            style.id = 'flowGlowStyle';

                            style.textContent = `

                                @keyframes flowGlow { 0%, 100% { opacity: 0.9; stroke: #33a02c; } 25% { opacity: 1; stroke: #1f78b4; } 50% { opacity: 0.9; stroke: #e31a1c; } 75% { opacity: 1; stroke: #ff7f00; } }

                                #sankey-chart svg path { filter: url(#glow); stroke-width: 1.5px; transition: stroke 2s ease-in-out; animation-name: flowGlow; animation-timing-function: ease-in-out; animation-iteration-count: infinite; }

                                #sankey-chart svg path:nth-child(3n+1) { animation-duration: 4s; }

                                #sankey-chart svg path:nth-child(3n+2) { animation-duration: 5s; }

                                #sankey-chart svg path:nth-child(3n) { animation-duration: 3s; }

                            `;

                            document.head.appendChild(style);

                        }

                        console.log('Glow efekti eklendi');

                    }

                    chart.draw(data, options);

                    console.log('İlk çizim başarılı, animasyon başlatılıyor...');

                    setTimeout(animateFlow, 100);

                } catch (error) {

                    console.error('Grafik çizim hatası:', error);

                    chartElement.innerHTML = '<p class="text-center text-danger p-5">Grafik çizim hatası: ' + error

                        .message + '</p>';

                }

            }

        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/welcome.blade.php ENDPATH**/ ?>