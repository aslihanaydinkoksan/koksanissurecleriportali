
<?php $__env->startSection('title', 'Hoş Geldiniz'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- GENEL STİLLER --- */
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

        /* Kart Tasarımları */
        .create-shipment-card {
            border-radius: 1rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            background-color: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-effect:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .create-shipment-card .card-header {
            color: #000;
            font-weight: bold;
        }

        .create-shipment-card .card-body {
            color: #000;
            font-weight: 500;
        }

        .create-shipment-card .list-group-item {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .create-shipment-card .list-group-item:last-child {
            border-bottom: 0;
        }

        /* KPI Kartları */
        .kpi-card {
            background-color: rgba(255, 255, 255, 0.85);
            border-radius: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            backdrop-filter: blur(3px);
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

        .kpi-hizmet-etkinlik .kpi-icon {
            color: #F56565;
        }

        .kpi-bakim .kpi-icon {
            color: #ED8936;
        }

        .kpi-sistem .kpi-icon {
            color: #FBD38D;
        }

        .kpi-ulastirma .kpi-icon {
            color: #3182CE;
        }

        /* Yeni Ulaştırma Rengi */

        /* Önemli Bildirim Animasyonu */
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

        /* Sankey Grafiği */
        .sankey-container-wrapper {
            overflow-x: auto;
            padding-bottom: 10px;
        }

        #sankey-chart {
            min-width: 100%;
        }
    </style>
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
                        // Departman slug'ını al (ilişki yoksa null)
$userDept = $currentUser->department ? $currentUser->department->slug : null;
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

                        
                        <?php if(Route::has('service.assignments.create') && ($isAdmin || $userDept === 'hizmet' || $userDept === 'ulastirma')): ?>
                            <a href="<?php echo e(route('service.assignments.create')); ?>"
                                class="btn btn-lg btn-outline-info d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-car-side me-2"></i> Yeni Araç Görevi</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                        
                        <?php if(Route::has('maintenance.create') && ($isAdmin || $userDept === 'bakim')): ?>
                            <a href="<?php echo e(route('maintenance.create')); ?>"
                                class="btn btn-lg btn-outline-secondary d-flex align-items-center justify-content-between p-3"
                                style="border-color: #ED8936; color: #C05621; background-color: rgba(237, 137, 54, 0.05);">
                                <span><i class="fa-solid fa-screwdriver-wrench me-2"></i> Yeni Bakım Planı</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>

                        <?php if(
                            !$isAdmin &&
                                $userDept !== 'uretim' &&
                                $userDept !== 'lojistik' &&
                                $userDept !== 'hizmet' &&
                                $userDept !== 'bakim' &&
                                $userDept !== 'ulastirma'): ?>
                            <div class="alert alert-warning d-flex align-items-center mb-0">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                <div>Bu alanda yapabileceğiniz hızlı bir işlem bulunmuyor.</div>
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
                                <h2 class="card-title mb-0 fw-bold">Hoş Geldiniz, <?php echo e(Auth::user()->name); ?>!</h2>
                                <p class="mb-0 text-muted fs-5" style="color: #707D88 !important;">
                                    <strong><?php echo e(ucfirst(Auth::user()->role)); ?></strong>
                                    <?php if(Auth::user()->department): ?>
                                        - <?php echo e(Auth::user()->department->name); ?>

                                    <?php endif; ?>
                                </p>
                            </div>

                            
                            <div class="row g-3 mb-4 mt-2">
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
                                                $isOverdue = $item->is_overdue ?? false;
                                            ?>
                                            <a href="<?php echo e($url); ?>"
                                                class="list-group-item list-group-item-action event-important-pulse-welcome d-flex align-items-center justify-content-between"
                                                style="background: transparent; border: none; padding: 0.75rem 0.5rem;"
                                                title="Takvimde görmek için tıklayın...">
                                                <div class="d-flex align-items-center overflow-hidden">
                                                    <i
                                                        class="fas <?php echo e($isOverdue ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'); ?> text-danger me-2 flex-shrink-0"></i>
                                                    <strong class="text-truncate"
                                                        style="max-width: 100%;"><?php echo e($item->title); ?></strong>
                                                </div>
                                                <?php if($item->date): ?>
                                                    <div class="d-flex flex-column align-items-end ms-3 flex-shrink-0">
                                                        <span
                                                            class="fw-bold text-danger fs-6"><?php echo e($item->date->format('H:i')); ?></span>
                                                        <span class="text-muted small"
                                                            style="font-size: 0.7rem;"><?php echo e($item->date->format('d.m.Y')); ?></span>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php if($importantItemsCount > 4): ?>
                                        <div class="text-center mt-3">
                                            <a href="<?php echo e(route('important.all')); ?>"
                                                class="btn btn-outline-danger btn-sm">Tüm (<?php echo e($importantItemsCount); ?>)
                                                Veriyi Görüntüle...</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                        <?php endif; ?>

                        
                        <?php if(isset($kpiData) && !empty($kpiData)): ?>
                            <h4 class="mt-4"><i class="fa-solid fa-chart-line me-1" style="color: #667EEA;"></i>
                                Operasyon Özeti (Bugün)</h4>
                            <div class="row g-4 mt-2 mb-3">

                                
                                <?php
                                    $showAll = $isAdmin || empty($userDept);
                                ?>

                                
                                <?php if($showAll || $userDept === 'ulastirma'): ?>
                                    <?php if(isset($kpiData['aktif_gorev'])): ?>
                                        <div class="col-lg col-md-4 col-6">
                                            <div class="kpi-card kpi-ulastirma h-100">
                                                <div class="kpi-icon"><i class="fa-solid fa-road"></i></div>
                                                <div class="kpi-value"><?php echo e($kpiData['aktif_gorev']); ?></div>
                                                <div class="kpi-label">Yoldaki Araçlar</div>
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-6">
                                            <div class="kpi-card kpi-ulastirma h-100">
                                                <div class="kpi-icon"><i class="fa-solid fa-clock"></i></div>
                                                <div class="kpi-value"><?php echo e($kpiData['bekleyen_talep']); ?></div>
                                                <div class="kpi-label">Bekleyen Talepler</div>
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-6">
                                            <div class="kpi-card kpi-ulastirma h-100">
                                                <div class="kpi-icon"><i class="fa-solid fa-car"></i></div>
                                                <div class="kpi-value"><?php echo e($kpiData['toplam_arac']); ?></div>
                                                <div class="kpi-label">Toplam Araç</div>
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-4 col-6">
                                            <div class="kpi-card kpi-ulastirma h-100">
                                                <div class="kpi-icon"><i class="fa-solid fa-calendar-check"></i></div>
                                                <div class="kpi-value"><?php echo e($kpiData['bugunku_gorev']); ?></div>
                                                <div class="kpi-label">Bugünkü Görevler</div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>

                                
                                <?php if($showAll): ?>
                                    <div class="col-lg col-md-4 col-6">
                                        <div class="kpi-card kpi-lojistik h-100">
                                            <div class="kpi-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                            <div class="kpi-value"><?php echo e($kpiData['sevkiyat_sayisi'] ?? 0); ?></div>
                                            <div class="kpi-label">Yaklaşan Sevkiyat</div>
                                        </div>
                                    </div>
                                    <div class="col-lg col-md-4 col-6">
                                        <div class="kpi-card kpi-uretim h-100">
                                            <div class="kpi-icon"><i class="fa-solid fa-industry"></i></div>
                                            <div class="kpi-value"><?php echo e($kpiData['plan_sayisi'] ?? 0); ?></div>
                                            <div class="kpi-label">Başlayan Plan</div>
                                        </div>
                                    </div>
                                    <div class="col-lg col-md-4 col-6">
                                        <div class="kpi-card kpi-hizmet-etkinlik h-100">
                                            <div class="kpi-icon"><i class="fa-solid fa-calendar-day"></i></div>
                                            <div class="kpi-value"><?php echo e($kpiData['etkinlik_sayisi'] ?? 0); ?></div>
                                            <div class="kpi-label">Bugünkü Etkinlik</div>
                                        </div>
                                    </div>
                                    <div class="col-lg col-md-4 col-6">
                                        <div class="kpi-card kpi-bakim h-100">
                                            <div class="kpi-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                                            <div class="kpi-value"><?php echo e($kpiData['bakim_sayisi'] ?? 0); ?></div>
                                            <div class="kpi-label">Planlanan Bakım</div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            
            <?php if(isset($chartData) && !empty($chartData)): ?>
                <div class="col-12 mb-4">
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            📊 <?php echo e($chartTitle ?? 'Genel Veri Akışı'); ?>

                        </div>
                        <div class="card-body">
                            <div class="sankey-container-wrapper">
                                <div id="sankey-chart" data-sankey='<?php echo json_encode($chartData, 15, 512) ?>'
                                    style="width: 100%; height: 500px;">
                                    <p class="text-center text-muted p-5">Grafik yükleniyor...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const sankeyChartEl = document.getElementById('sankey-chart');
            if (sankeyChartEl) {
                let sankeyData = [];
                try {
                    const dataAttr = sankeyChartEl.dataset.sankey;
                    if (dataAttr) sankeyData = JSON.parse(dataAttr);
                } catch (error) {
                    console.error('JSON hatası:', error);
                }

                if (sankeyData.length > 0) {
                    google.charts.load('current', {
                        'packages': ['sankey']
                    });
                    google.charts.setOnLoadCallback(() => drawChart(sankeyData, sankeyChartEl));
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

                    const initialData = sankeyData.map(row => [row[0], row[1], row[2],
                        `${row[0]} -> ${row[1]}\nDeğer: ${row[2]}`
                    ]);
                    data.addRows(initialData);
                    const chart = new google.visualization.Sankey(chartElement);
                    chart.draw(data, options);
                } catch (error) {
                    chartElement.innerHTML = '<p class="text-center text-danger">Grafik çizilemedi.</p>';
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/welcome.blade.php ENDPATH**/ ?>