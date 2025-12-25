

<?php $__env->startSection('title', 'KÖKSAN Canlı İzleme Paneli'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- 1. TV MODU VE GENEL DÜZEN --- */
        nav.navbar,
        .sidebar,
        footer,
        .breadcrumb,
        #kt_header_mobile {
            display: none !important;
            /* Yönetim paneli menülerini gizle */
        }

        #app>main.py-4,
        body,
        .content {
            padding: 1rem !important;
            min-height: 100vh;
            width: 100vw;
            margin: 0 !important;
            /* Hafif hareketli, göz yormayan arka plan */
            background: linear-gradient(-45deg, #f3f4f6, #e2e8f0, #edf2f7, #e6fffa);
            background-size: 400% 400%;
            animation: gradientWave 60s ease infinite;
            overflow: hidden;
            /* Scroll bar gizlensin */
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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

        .container-fluid {
            max-width: 100% !important;
        }

        /* TV ekranında mouse imlecini gizle */
        body {
            cursor: none;
        }

        /* --- 2. KART TASARIMLARI (Cam Efekti) --- */
        .tv-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(10px);
            padding: 1.25rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* --- 3. ÜST KPI KARTLARI --- */
        .kpi-mini-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
        }

        .kpi-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-icon {
            font-size: 2.2rem;
            opacity: 0.8;
        }

        /* --- 4. FABRİKA BİRİM KARTLARI (Kopet, Preform, Levha) --- */
        .unit-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            height: 100%;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .unit-icon-bg {
            position: absolute;
            top: -15px;
            right: -15px;
            font-size: 7rem;
            opacity: 0.15;
            transform: rotate(10deg);
            z-index: 0;
        }

        .unit-content {
            position: relative;
            z-index: 1;
        }

        .unit-title {
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .unit-count {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .unit-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }

        /* --- 5. BİLDİRİM LİSTESİ --- */
        .important-item {
            background: white;
            border-left: 5px solid #ccc;
            border-radius: 8px;
            padding: 0.9rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* --- 6. GİZLİ ÇIKIŞ --- */
        .emergency-exit-btn {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 80px;
            height: 80px;
            z-index: 9999;
            cursor: pointer;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid d-flex flex-column" style="height: 97vh;">

        
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-layer-group text-primary fa-2x me-3"></i>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size: 2rem; color: #1a202c; letter-spacing: -0.5px;">KÖKSAN
                            HOLDİNG</h1>
                        <span class="text-secondary fw-bold fs-5">Entegre İş Süreçleri Paneli</span>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <h1 class="fw-bold mb-0" id="live-clock"
                    style="font-size: 3.5rem; font-family: 'Courier New', monospace; color: #2d3748; line-height: 1;">--:--
                </h1>
                <span class="text-secondary fw-medium fs-5"><?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y, l')); ?></span>
            </div>
        </div>

        
        <div class="row g-3 mb-3">
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #667EEA;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['sevkiyat_sayisi']); ?></div>
                            <div class="kpi-label">Sevkiyat</div>
                        </div>
                        <i class="fa-solid fa-truck-fast kpi-icon" style="color: #667EEA;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #4FD1C5;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['plan_sayisi']); ?></div>
                            <div class="kpi-label">Toplam Üretim</div>
                        </div>
                        <i class="fa-solid fa-industry kpi-icon" style="color: #4FD1C5;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #F093FB;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['etkinlik_sayisi']); ?></div>
                            <div class="kpi-label">Etkinlik & Ziyaret</div>
                        </div>
                        <i class="fa-solid fa-users-viewfinder kpi-icon" style="color: #F093FB;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #F6E05E;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['arac_gorevi_sayisi']); ?></div>
                            <div class="kpi-label">Araç Görevi</div>
                        </div>
                        <i class="fa-solid fa-car kpi-icon" style="color: #F6E05E;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #ED8936;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['bakim_sayisi']); ?></div>
                            <div class="kpi-label">Bakım & Arıza</div>
                        </div>
                        <i class="fa-solid fa-wrench kpi-icon" style="color: #ED8936;"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-3 mb-3" style="min-height: 180px;">
            <div class="col-md-4">
                <div class="unit-card" style="background: linear-gradient(135deg, #2c7a7b 0%, #319795 100%);">
                    <i class="fa-solid fa-bottle-water unit-icon-bg"></i>
                    <div class="unit-content h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="unit-title">KOPET FABRİKASI</div>
                            <span class="unit-badge"><i class="fa-solid fa-pulse me-2"></i>Aktif</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="unit-count"><?php echo e($kpiData['kopet_count']); ?></div>
                            <div class="text-end" style="opacity: 0.9;">
                                <div class="fs-6 fw-bold">İş Emri</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="unit-card" style="background: linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%);">
                    <i class="fa-solid fa-flask unit-icon-bg"></i>
                    <div class="unit-content h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="unit-title">PREFORM FABRİKASI</div>
                            <span class="unit-badge"><i class="fa-solid fa-bolt me-2"></i>Üretim</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="unit-count"><?php echo e($kpiData['preform_count']); ?></div>
                            <div class="text-end" style="opacity: 0.9;">
                                <div class="fs-6 fw-bold">Planlanan</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="unit-card" style="background: linear-gradient(135deg, #c05621 0%, #dd6b20 100%);">
                    <i class="fa-solid fa-sheet-plastic unit-icon-bg"></i>
                    <div class="unit-content h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="unit-title">LEVHA FABRİKASI</div>
                            <span class="unit-badge"><i class="fa-solid fa-truck-ramp-box me-2"></i>Süreçte</span>
                        </div>
                        <div class="d-flex align-items-end justify-content-between">
                            <div class="unit-count"><?php echo e($kpiData['levha_count']); ?></div>
                            <div class="text-end" style="opacity: 0.9;">
                                <div class="fs-6 fw-bold">Hareket</div>
                            </div>
                        </div>
                        <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.3);">
                            <div class="progress-bar bg-white" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-3 flex-grow-1" style="min-height: 0;">
            
            <div class="col-md-4 h-100">
                <div class="tv-card">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom text-danger d-flex align-items-center">
                        <i class="fas fa-bell fa-shake me-2"></i>GÜNCEL BİLDİRİMLER
                    </h5>

                    <div style="overflow-y: hidden; position: relative; flex-grow: 1;">
                        <?php if($importantItems->isEmpty()): ?>
                            <div
                                class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                                <i class="fa-regular fa-circle-check fa-4x mb-3"></i>
                                <span class="fs-4">Her şey yolunda, işlem yok.</span>
                            </div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-2">
                                <?php $__currentLoopData = $importantItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Dinamik Renk ve İkon Seçimi
                                        $borderColor = match ($item->type) {
                                            'shipment' => '#667EEA',
                                            'productionplan' => '#4FD1C5',
                                            'event' => '#F093FB',
                                            'maintenance' => '#ED8936',
                                            'vehicleassignment' => '#F6E05E',
                                            'travel' => '#E53E3E',
                                            default => '#CBD5E0',
                                        };
                                        $icon = match ($item->type) {
                                            'shipment' => 'fa-truck',
                                            'productionplan' => 'fa-industry',
                                            'event' => 'fa-calendar',
                                            'maintenance' => 'fa-wrench',
                                            'vehicleassignment' => 'fa-car',
                                            'travel' => 'fa-plane',
                                            default => 'fa-info-circle',
                                        };
                                    ?>
                                    <div class="important-item" style="border-left-color: <?php echo e($borderColor); ?>;">
                                        <div class="me-3 text-center" style="width: 35px;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px; background-color: <?php echo e($borderColor); ?>20;">
                                                <i class="fa-solid <?php echo e($icon); ?>"
                                                    style="color: <?php echo e($borderColor); ?>; font-size: 1rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-uppercase"
                                                style="font-size: 0.7rem; color: <?php echo e($borderColor); ?>; letter-spacing: 0.5px;">
                                                <?php echo e($item->category); ?></div>
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">
                                                <?php echo e($item->content); ?></div>
                                        </div>
                                        <div class="text-end ps-2 ms-1">
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                <?php echo e($item->date->format('H:i')); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-8 h-100">
                <div class="tv-card">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <h5 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-project-diagram me-2"></i>FABRİKA İŞ AKIŞI
                        </h5>
                        <span class="badge bg-light text-dark border">
                            <i class="fa-solid fa-circle-info me-1 text-primary"></i>
                            KÖKSAN -> Fabrikalar -> (Üretim / Sevkiyat / Bakım)
                        </span>
                    </div>
                    <div id="sankey-chart" style="width: 100%; height: 92%;"></div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
        <div onclick="document.getElementById('logout-form').submit();" class="emergency-exit-btn" title="Çıkış Yap">
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. OTOMATİK YENİLEME SİSTEMİ ---
            let currentHash = "<?php echo e($currentDataHash); ?>";
            setInterval(() => {
                fetch("<?php echo e(route('tv.check_updates')); ?>")
                    .then(response => response.json())
                    .then(data => {
                        // Eğer sunucudaki hash değiştiyse sayfayı yenile
                        if (data.hash !== currentHash) {
                            console.log('Veri değişikliği algılandı, sayfa yenileniyor...');
                            location.reload();
                        }
                    })
                    .catch(err => console.error('Bağlantı hatası:', err));
            }, 10000); // 10 saniyede bir kontrol et

            // --- 2. CANLI DİJİTAL SAAT ---
            setInterval(() => {
                const now = new Date();
                const timeString = now.toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                document.getElementById('live-clock').textContent = timeString;
            }, 1000);

            // --- 3. SANKEY GRAFİĞİ (Google Charts) ---
            google.charts.load('current', {
                'packages': ['sankey']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Kaynak');
                data.addColumn('string', 'Hedef');
                data.addColumn('number', 'Değer');

                // Controller'dan gelen JSON verisi
                var rawData = <?php echo json_encode($chartData, 15, 512) ?>;
                data.addRows(rawData);

                // Renk Paleti (Fabrika Birimleri için Uyumlu Renkler)
                var colors = [
                    '#1a202c', // KÖKSAN (Siyah/Koyu Gri)
                    '#319795', // KOPET (Teal)
                    '#3182ce', // PREFORM (Blue)
                    '#dd6b20', // LEVHA (Orange)
                    '#805ad5', // DİĞER (Purple)
                    '#e53e3e', // KIRMIZI
                    '#38a169', // YEŞİL
                ];

                var options = {
                    sankey: {
                        node: {
                            colors: colors,
                            label: {
                                fontName: 'Segoe UI',
                                fontSize: 13,
                                bold: true,
                                color: '#2d3748'
                            },
                            nodePadding: 40, // Düğümler arası boşluk
                            width: 15, // Düğüm kalınlığı
                            interactivity: true
                        },
                        link: {
                            colorMode: 'gradient',
                            fillOpacity: 0.4
                        }
                    },
                    tooltip: {
                        isHtml: true
                    }
                };

                var chart = new google.visualization.Sankey(document.getElementById('sankey-chart'));
                chart.draw(data, options);
            }

            // --- 4. SCROLL LOOP (İçerik taşarsa otomatik kaydır) ---
            // Genelde bu tasarım tek ekrana sığar ama taşarsa diye önlem:
            setTimeout(() => {
                if (document.body.scrollHeight > window.innerHeight) {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                    setTimeout(() => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }, 5000);
                }
            }, 15000);

            // Klavye ile çıkış (ESC)
            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape") document.getElementById('logout-form').submit();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/tv/dashboard.blade.php ENDPATH**/ ?>