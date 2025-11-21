
<?php $__env->startSection('title', 'Hoş Geldiniz'); ?>


<?php
    $isTvUser = Auth::check() && Auth::user()->email === 'tv@koksan.com';
?>

<?php $__env->startPush('styles'); ?>
    
    <style>
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

        /* İkon Renkleri */
        .icon-gemi {
            color: #9DECF9 !important;
        }

        .icon-tir,
        .icon-tır {
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

        .icon-gezi,
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

        /* KPI Kartları */
        .kpi-card {
            background-color: rgba(255, 255, 255, 0.85);
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

        /* KPI Renkleri */
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

        .hover-effect {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-effect:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    
    <?php if(request('mode') == 'tv' || $isTvUser): ?>
        <style>
            /* Navbar, Sidebar, Footer Gizle */
            nav.navbar,
            .sidebar,
            footer,
            .breadcrumb,
            #kt_header_mobile {
                display: none !important;
            }

            /* Full Screen Ayarı */
            #app>main.py-4,
            body,
            .content {
                padding: 1rem 0 !important;
                min-height: 100vh;
                width: 100vw;
                overflow-x: hidden;
                /* Yana taşmayı engelle */
                margin: 0 !important;
            }

            /* Container Genişletme */
            .container {
                max-width: 98% !important;
            }

            /* BUTONLARI GİZLEME (Kiosk Modu) */
            .create-shipment-card a[data-bs-toggle="modal"],
            /* Hızlı işlem kartı linki */
            .btn-outline-secondary,
            /* Detay butonları */
            .btn-outline-danger,
            /* Tümünü gör butonu */
            .fa-arrow-right-long,
            .fa-arrow-right {
                display: none !important;
            }

            /* Hızlı İşlem Kartını (Sol Üstteki) Tamamen Yok Et */
            .col-md-4:has(.fa-plus) {
                display: none !important;
            }

            /* Kart gizlenince düzen bozulmasın, ortala */
            .row.g-3.mb-4 {
                justify-content: center;
            }

            /* TV için Fontları Büyüt */
            .kpi-value {
                font-size: 3rem;
            }

            h4 {
                font-size: 1.6rem;
            }

            /* Mouse imlecini gizle */
            body {
                cursor: none;
            }

            a {
                pointer-events: none !important;
                /* Tıklamayı engeller */
                cursor: default !important;
                /* İmleci normal ok yapar */
                text-decoration: none !important;
                /* Alt çizgiyi kaldırır */
            }

            /* Kartların üzerine gelince oluşan efektleri kaldır */
            .hover-effect:hover {
                transform: none !important;
                box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1) !important;
            }

            /* Link görünümlü butonları pasif renk yap */
            .kpi-card,
            .create-shipment-card {
                cursor: default !important;
            }

            /* YENİ: Sankey Diyagramı İçin Taşma Yönetimi ve Genişlik Düzeltmesi */
            .sankey-container-wrapper {
                /* Taşan içeriği yatay kaydırılabilir yapar */
                overflow-x: auto;
                padding-bottom: 10px;
            }

            /* EK DÜZELTME: Sankey Chart'ın içindeki içeriğin mobil görünümde taşmasını önle */
            #sankey-chart {
                /* Bu kural Google Charts'ın zorladığı minimum genişlikleri esnetebilir. */
                min-width: 100% !important;
            }

            @media (max-width: 768px) {
                #sankey-chart {
                    /* Sadece chart div'inin yüksekliğini mobil için optimize ettik, genişliği JS ayarlıyor */
                    height: 400px !important;
                }

                /* KRİTİK EKLEME: Google Charts SVG'sine müdahale */
                #sankey-chart svg {
                    /* SVG'nin kapsayıcı genişliğini aşmamasını sağlar */
                    max-width: 100% !important;
                    /* Diyagramın içindeki linklerin ve notların daha az yer kaplamasını sağlar */
                    min-width: 450px;
                    /* Min genişlik zorlayarak yatay kaydırmayı garanti eder */
                }
            }
        </style>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    
    
    <div class="modal fade" id="createSelectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border:none; border-radius: 1rem;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Ne Oluşturmak İstersiniz?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <?php
                        $currentUser = Auth::user();
                        $userDept = $currentUser->department ? $currentUser->department->slug : null;
                        // Admin veya Yönetici ise her şeyi görsün
                        $isAdmin = in_array($currentUser->role, ['admin', 'yönetici']);
                    ?>

                    <div class="d-grid gap-3">

                        
                        <?php if(Route::has('production.plans.create') && ($isAdmin || $userDept === 'uretim')): ?>
                            <a href="<?php echo e(route('production.plans.create')); ?>"
                                class="btn btn-lg btn-outline-success d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-industry me-2"></i> Yeni Üretim Planı</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                        
                        <?php if(Route::has('shipments.create') && ($isAdmin || $userDept === 'lojistik')): ?>
                            <a href="<?php echo e(route('shipments.create')); ?>"
                                class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-truck-fast me-2"></i> Yeni Sevkiyat</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                        
                        <?php
                            $eventRoute = Route::has('service.events.create')
                                ? route('service.events.create')
                                : (Route::has('events.create')
                                    ? route('events.create')
                                    : '#');
                        ?>

                        <?php if($eventRoute !== '#' && ($isAdmin || $userDept === 'hizmet')): ?>
                            <a href="<?php echo e($eventRoute); ?>"
                                class="btn btn-lg btn-outline-warning d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-calendar-plus me-2"></i> Yeni Etkinlik</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                        
                        
                        <?php if(Route::has('service.assignments.create')): ?>
                            <a href="<?php echo e(route('service.assignments.create')); ?>"
                                class="btn btn-lg btn-outline-info d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-car-side me-2"></i> Yeni Araç Görevi</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                        
                        <?php if(!$isAdmin && $userDept !== 'uretim' && $userDept !== 'lojistik' && $userDept !== 'hizmet'): ?>
                            <div class="alert alert-warning d-flex align-items-center mb-0">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                <div>
                                    Bu alanda yapabileceğiniz hızlı bir işlem bulunmuyor.
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card create-shipment-card mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center mb-3">
                            <div class="col-md-7">
                                <?php if(Auth::user()->email === 'tv@koksan.com' || Auth::user()->name === 'İzleme Panosu'): ?>
                                    <h2 class="card-title mb-0 fw-bold">KÖKSAN GENEL İŞ AKIŞI</h2>
                                    <p class="mb-0 text-muted fs-5" style="color: #707D88  !important;">
                                        Canlı Veri Yayını
                                    </p>
                                <?php else: ?>
                                    <h2 class="card-title mb-0 fw-bold">Hoş Geldiniz, <?php echo e(Auth::user()->name); ?>!</h2>
                                <?php endif; ?>
                                
                                <?php if(Auth::user()->department && !$isTvUser): ?>
                                    
                                <?php elseif(!$isTvUser): ?>
                                    <p class="mb-0 text-muted fs-5" style="color: #707D88 !important;">
                                        <strong><?php echo e(ucfirst(Auth::user()->role)); ?></strong>
                                    </p>
                                <?php endif; ?>
                            </div>

                            
                            <div class="row g-3 mb-4">
                                <?php if(!$isTvUser && request('mode') != 'tv'): ?>
                                    
                                    <div class="col-md-4">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#createSelectionModal"
                                            class="text-decoration-none">
                                            <div class="card create-shipment-card h-100 hover-effect">
                                                <div class="card-body d-flex align-items-center">
                                                    <div class="rounded-circle p-3 me-3"
                                                        style="background: rgba(102, 126, 234, 0.2);">
                                                        <i class="fa-solid fa-plus fa-xl" style="color: #667EEA;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">Hızlı İşlem Menüsü</h6>
                                                        <small class="text-muted">Yeni kayıt oluştur...</small>
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
                                                        <i class="fa-solid fa-calendar-check fa-xl"
                                                            style="color: #4FD1C5;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">Takvim & Planlama</h6>
                                                        <small class="text-muted">Haftalık planı görüntüle</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    
                                    <div class="col-md-4">
                                        <div class="card create-shipment-card h-100">
                                            <div class="card-body d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Yerel Saat</h6>
                                                    <h2 class="mb-0 fw-bold text-dark" id="live-clock-display">--:--:--</h2>
                                                </div>
                                                <div class="rounded-circle p-3"
                                                    style="background: rgba(102, 126, 234, 0.1);">
                                                    <i class="fa-regular fa-clock fa-2x" style="color: #667EEA;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-4">
                                        <div class="card create-shipment-card h-100">
                                            <div class="card-body d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Tarih</h6>
                                                    <h3 class="mb-0 fw-bold text-dark">
                                                        <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></h3>
                                                    <small
                                                        class="text-muted"><?php echo e(\Carbon\Carbon::now()->translatedFormat('l')); ?></small>
                                                </div>
                                                <div class="rounded-circle p-3"
                                                    style="background: rgba(240, 147, 251, 0.1);">
                                                    <i class="fa-solid fa-calendar-day fa-2x" style="color: #F093FB;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div class="col-md-4">
                                        <div class="card create-shipment-card h-100">
                                            <div class="card-body d-flex align-items-center justify-content-between">
                                                <div>
                                                    <h6 class="text-muted mb-1 text-uppercase small fw-bold">Sistem Durumu
                                                    </h6>
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-success me-2 pulse-dot">AKTİF</span>
                                                        <small class="text-muted">Veriler Güncel</small>
                                                    </div>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem">
                                                        Son Yenileme: <?php echo e(now()->format('H:i')); ?>

                                                    </small>
                                                </div>
                                                <div class="rounded-circle p-3"
                                                    style="background: rgba(79, 209, 197, 0.1);">
                                                    <i class="fa-solid fa-rotate fa-2x" style="color: #4FD1C5;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <hr>
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

                                                        <span class="ms-2">| Saat:
                                                            <?php echo e($item->date->format('H:i')); ?></span>
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
                            <h4 class="mt-4"><i class="fa-solid fa-chart-line me-1" style="color: #667EEA;"></i> Sistem
                                Geneli (Bugün)</h4>
                            <div class="row g-4 mt-2 mb-3">
                                
                                <div class="col-lg-2 col-md-4 col-6"> 
                                    <div class="kpi-card kpi-lojistik">
                                        <div class="kpi-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['sevkiyat_sayisi']); ?></div>
                                        <div class="kpi-label">Yaklaşan Sevkiyat</div>
                                    </div>
                                </div>

                                
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="kpi-card kpi-uretim">
                                        <div class="kpi-icon"><i class="fa-solid fa-industry"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['plan_sayisi']); ?></div>
                                        <div class="kpi-label">Başlayan Plan</div>
                                    </div>
                                </div>

                                
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="kpi-card kpi-hizmet">
                                        <div class="kpi-icon"><i class="fa-solid fa-calendar-star"></i></div>
                                        
                                        <div class="kpi-value"><?php echo e($kpiData['etkinlik_sayisi']); ?></div>
                                        
                                        <div class="kpi-label">Bugünkü Etkinlik</div>
                                    </div>
                                </div>

                                
                                <div class="col-lg-3 col-md-4 col-6">
                                    <div class="kpi-card kpi-hizmet"> 
                                        <div class="kpi-icon"><i class="fa-solid fa-car-side"></i></div>
                                        <div class="kpi-value"><?php echo e($kpiData['arac_gorevi_sayisi']); ?></div>
                                        <div class="kpi-label">Aktif Araç Görevi</div>
                                    </div>
                                </div>

                                
                                <div class="col-lg-3 col-md-6 col-12">
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
                                    'seyahat' => ['icon' => 'fa-route', 'class' => 'icon-seyahat'],
                                    'diger' => ['icon' => 'fa-calendar-star', 'class' => 'icon-etkinlik-genel'],
                                ];
                            ?>

                            <div class="list-group mt-3">
                                <?php $__empty_1 = true; $__currentLoopData = $todayItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    
                                    <?php
                                        $icon = 'fa-calendar-day';
                                        $iconClass = 'text-muted';
                                        $baslik = 'Kayıt';
                                        $detay = '';
                                        $saat = '';
                                        $modalId = $item->id;
                                        $modalType = 'unknown';

                                        if ($item instanceof \App\Models\ProductionPlan) {
                                            $icon = 'fa-industry';
                                            $iconClass = 'icon-uretim';
                                            $baslik = $item->plan_title;
                                            $saat = $item->week_start_date->format('d.m.Y');
                                            $detay = 'Üretim Planı';
                                            $modalType = 'production_plan';
                                        } elseif ($item instanceof \App\Models\Shipment) {
                                            $type = strtolower($item->arac_tipi ?? 'tir');
                                            $iconData = $iconMap[$type] ?? $iconMap['tir'];
                                            $icon = $iconData['icon'];
                                            $iconClass = $iconData['class'];
                                            $baslik = 'Sevkiyat: ' . $item->kargo_icerigi;
                                            $saat = $item->tahmini_varis_tarihi
                                                ? $item->tahmini_varis_tarihi->format('H:i')
                                                : '-';
                                            $detay = 'Araç: ' . ($item->arac_tipi ?? '-');
                                            $modalType = 'shipment';
                                        } elseif ($item instanceof \App\Models\Event) {
                                            $type = $item->event_type ?? 'diger';
                                            $iconData = $iconMap[$type] ?? $iconMap['etkinlik'];
                                            $icon = $iconData['icon'];
                                            $iconClass = $iconData['class'];
                                            $baslik = $item->title;
                                            $saat = $item->start_datetime->format('H:i');
                                            $detay = $item->location;
                                            $modalType = 'event';
                                        } elseif ($item instanceof \App\Models\VehicleAssignment) {
                                            $icon = 'fa-car-side';
                                            $iconClass = 'icon-aracgorevi';
                                            $baslik = $item->task_description;
                                            $saat = $item->start_time->format('H:i');
                                            $detay = $item->vehicle->plate_number ?? '-';
                                            $modalType = 'vehicle_assignment';
                                        } elseif ($item instanceof \App\Models\Travel) {
                                            $icon = 'fa-route';
                                            $iconClass = 'icon-seyahat';
                                            $baslik = $item->name;
                                            $saat = $item->start_date->format('d.m');
                                            $detay = $item->status == 'planned' ? 'Planlı' : 'Tamamlandı';
                                            $modalType = 'travel';
                                        }
                                    ?>

                                    <div class="list-group-item d-flex align-items-center py-3"
                                        style="background-color: transparent;">
                                        <div class="me-3">
                                            <i
                                                class="fa-solid <?php echo e($icon); ?> fa-2x vehicle-icon <?php echo e($iconClass); ?>"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h5 class="mb-1 fw-bold"><?php echo e($baslik); ?></h5>
                                            <p class="mb-0 text-muted"><?php echo e($detay); ?></p>
                                        </div>
                                        <span class="fw-bold text-dark ms-3"><?php echo e($saat); ?></span>
                                        <a href="<?php echo e(route('home')); ?>?open_modal_id=<?php echo e($modalId); ?>&open_modal_type=<?php echo e($modalType); ?>"
                                            class="btn btn-outline-secondary btn-sm ms-3">
                                            <i class="fa-solid fa-arrow-right-long"></i>
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="alert alert-info">Kayıt bulunamadı.</div>
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
                            <div class="sankey-container-wrapper">
                                <div id="sankey-chart" data-sankey='<?php echo json_encode($chartData, 15, 512) ?>'
                                    style="width: 100%; height: 500px;">
                                    <p class="text-center text-muted p-5">Grafik
                                        yükleniyor...</p>
                                </div>
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
            console.log('DOM yüklendi...');
            // TV Modu Saati (Sadece element varsa çalışır)
            function updateClock() {
                const clockEl = document.getElementById('live-clock-display');
                if (clockEl) {
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const seconds = String(now.getSeconds()).padStart(2, '0');
                    clockEl.textContent = `${hours}:${minutes}:${seconds}`;
                }
            }
            // Saati her saniye güncelle
            setInterval(updateClock, 1000);
            updateClock(); // İlk açılışta hemen çalıştır

            // --- 1. TV MODU OTOMASYONU ---
            // Değişkenleri PHP'den al
            const isTvUser = <?php echo json_encode($isTvUser, 15, 512) ?>;
            const urlParams = new URLSearchParams(window.location.search);
            const isTvModeUrl = urlParams.get('mode') === 'tv';

            // Hata Düzeltildi: Artık isTvModeUrl kullanılıyor
            if (isTvModeUrl || isTvUser) {
                console.log('TV Modu Aktif: Otomasyon başlatılıyor...');

                // A. Otomatik Yenileme (5 Dakika)
                // Verilerin güncel kalması için
                setTimeout(function() {
                    window.location.reload();
                }, 300000);

                // B. Otomatik Kaydırma (Auto-Scroll)
                let scrollSpeed = 1;
                let scrollDelay = 50;
                let holdTime = 5000; // 5 saniye bekle
                let scrollingDown = true;
                let scrollInterval;

                function startScrolling() {
                    scrollInterval = setInterval(() => {
                        const totalHeight = document.body.scrollHeight;
                        const visibleHeight = window.innerHeight;
                        const currentScroll = window.scrollY;

                        if (scrollingDown) {
                            // Aşağı inerken
                            if (currentScroll + visibleHeight >= totalHeight - 10) {
                                clearInterval(scrollInterval);
                                scrollingDown = false;
                                setTimeout(startScrolling, holdTime);
                            } else {
                                window.scrollBy(0, scrollSpeed);
                            }
                        } else {
                            // Yukarı çıkarken (Hızlıca başa dön)
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                            clearInterval(scrollInterval);
                            scrollingDown = true;
                            setTimeout(startScrolling, holdTime);
                        }
                    }, scrollDelay);
                }
                // Sayfa yüklendikten 3sn sonra kaydırmaya başla
                setTimeout(startScrolling, 3000);
            }


            // --- 2. SANKEY GRAFİĞİ ---
            const sankeyChartEl = document.getElementById('sankey-chart');

            if (sankeyChartEl) {
                let sankeyData = [];
                try {
                    const dataAttr = sankeyChartEl.dataset.sankey;
                    if (dataAttr) {
                        sankeyData = JSON.parse(dataAttr);
                    }
                } catch (error) {
                    console.error('JSON hatası:', error);
                }

                if (sankeyData.length > 0) {
                    google.charts.load('current', {
                        packages: ['sankey']
                    });
                    google.charts.setOnLoadCallback(() => {
                        drawChart(sankeyData, sankeyChartEl);
                    });
                }
            }

            function drawChart(sankeyData, chartElement) {
                if (sankeyData.length === 1 && (sankeyData[0][0] === 'Veri Yok' || sankeyData[0][0] === 'Sistem')) {
                    chartElement.innerHTML =
                        '<p class="text-center text-muted p-5">Grafik için yeterli veri yok.</p>';
                    return;
                }

                try {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Kaynak');
                    data.addColumn('string', 'Hedef');
                    data.addColumn('number', 'Değer');
                    data.addColumn({
                        type: 'string',
                        role: 'tooltip'
                    });

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
                                colors: ['#667EEA', '#F093FB', '#4FD1C5', '#FBD38D', '#FC8181']
                            }
                        }
                    };

                    // Animasyon ve Glow Efekti
                    let steps = 40;
                    let currentStep = 0;
                    const initialData = sankeyData.map(row => [row[0], row[1], 0.00001,
                        `${row[0]} -> ${row[1]}\nDeğer: ${row[2]}`
                    ]);
                    data.addRows(initialData);

                    const chart = new google.visualization.Sankey(chartElement);
                    chart.draw(data, options);

                    // Glow Stilini Ekle
                    if (!document.getElementById('flowGlowStyle')) {
                        const style = document.createElement('style');
                        style.id = 'flowGlowStyle';
                        style.textContent =
                            `@keyframes flowGlow { 0%, 100% { opacity: 0.9; stroke: #33a02c; } 50% { opacity: 0.9; stroke: #e31a1c; } } #sankey-chart svg path { filter: url(#glow); stroke-width: 1.5px; animation: flowGlow 4s infinite ease-in-out; }`;
                        document.head.appendChild(style);
                    }

                    // SVG Filtre Ekle (Glow için)
                    setTimeout(() => {
                        const svg = chartElement.querySelector('svg');
                        if (svg && !svg.querySelector('#glow')) {
                            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
                            const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
                            filter.setAttribute('id', 'glow');
                            filter.innerHTML =
                                `<feGaussianBlur stdDeviation="4" result="coloredBlur"></feGaussianBlur><feMerge><feMergeNode in="coloredBlur"></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge>`;
                            defs.appendChild(filter);
                            svg.prepend(defs);
                        }
                    }, 500);

                    function animateFlow() {
                        if (currentStep >= steps) {
                            sankeyData.forEach((row, i) => data.setValue(i, 2, row[2]));
                            chart.draw(data, options);
                            return;
                        }
                        sankeyData.forEach((row, i) => {
                            const progress = currentStep / steps;
                            data.setValue(i, 2, Math.max(0.00001, row[2] * progress));
                        });
                        chart.draw(data, options);
                        currentStep++;
                        requestAnimationFrame(animateFlow);
                    }

                    setTimeout(animateFlow, 100);

                } catch (error) {
                    console.error('Grafik hatası:', error);
                    chartElement.innerHTML = '<p class="text-center text-danger">Grafik çizilemedi.</p>';
                }
            }
        });
    </script>
    
    <?php if(request('mode') == 'tv' || $isTvUser): ?>
        
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
        </form>

        
        <div onclick="document.getElementById('logout-form').submit();" class="emergency-exit-btn" title="Çıkış Yap">
            <i class="fa-solid fa-power-off"></i>
        </div>

        
        <script>
            document.addEventListener('keydown', function(event) {
                // ESC tuşuna basılırsa çıkış yap
                if (event.key === "Escape") {
                    alert('TV Modundan Çıkılıyor...');
                    document.getElementById('logout-form').submit();
                }
            });
        </script>

        <style>
            .emergency-exit-btn {
                position: fixed;
                bottom: 0;
                right: 0;
                width: 60px;
                height: 60px;
                background-color: rgba(220, 53, 69, 0.3);
                /* Hafif Kırmızı */
                color: white;
                border-top-left-radius: 100%;
                /* Çeyrek daire şekli */
                z-index: 2147483647;
                /* En, en, en üstte */
                display: flex;
                align-items: flex-end;
                justify-content: flex-end;
                padding: 10px;
                cursor: pointer !important;
                /* Mouse görünür olsun */
                pointer-events: auto !important;
                /* Tıklanabilir */
                transition: all 0.3s ease;
            }

            /* Üzerine gelince belirginleşsin */
            .emergency-exit-btn:hover {
                background-color: rgba(220, 53, 69, 1);
                /* Tam Kırmızı */
                width: 80px;
                height: 80px;
                box-shadow: 0 0 20px rgba(220, 53, 69, 0.8);
            }

            .emergency-exit-btn i {
                font-size: 1.5rem;
                margin-right: 5px;
                margin-bottom: 5px;
            }

            /* Mouse'u bu butonun üzerindeyken göster */
            .emergency-exit-btn:hover {
                cursor: pointer !important;
            }
        </style>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/welcome.blade.php ENDPATH**/ ?>