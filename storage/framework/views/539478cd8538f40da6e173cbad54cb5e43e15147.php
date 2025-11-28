

<?php $__env->startSection('title', 'KÖKSAN Canlı İzleme Paneli'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- TV MODU İÇİN ÖZEL STİLLER --- */

        /* Gereksiz Alanları Gizle */
        nav.navbar,
        .sidebar,
        footer,
        .breadcrumb,
        #kt_header_mobile {
            display: none !important;
        }

        /* Tam Ekran ve Arkaplan */
        /* Kaydırmaya izin ver ama çubuğu gizle */
        #app>main.py-4,
        body,
        .content {
            padding: 1.5rem !important;
            min-height: 100vh;
            width: 100vw;
            margin: 0 !important;
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 30s ease infinite;

            /* SCROLLBAR GİZLEME TEKNİĞİ */
            overflow-y: auto;
            /* Kaydırma açık */
            scrollbar-width: none;
            /* Firefox için gizle */
            -ms-overflow-style: none;
            /* IE/Edge için gizle */
        }

        /* Chrome, Safari, Opera için gizle */
        body::-webkit-scrollbar {
            display: none;
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

        /* Mouse İmlecini Gizle */
        body {
            cursor: none;
        }

        /* Kart Stilleri */
        .tv-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(15px);
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* KPI (Sayılar) Kartları */
        .kpi-card-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-value {
            font-size: 4rem;
            font-weight: 800;
            color: #2d3748;
            line-height: 1;
        }

        .kpi-label {
            font-size: 1.4rem;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }

        .kpi-icon {
            font-size: 3.5rem;
            opacity: 0.8;
        }

        /* Renk Sınıfları */
        .text-lojistik {
            color: #667EEA;
        }

        .text-uretim {
            color: #4FD1C5;
        }

        .text-hizmet {
            color: #F093FB;
        }

        .text-bakim {
            color: #ED8936;
        }

        .text-arac {
            color: #F6E05E;
        }

        /* Önemli Bildirimler Listesi */
        .important-item {
            background: white;
            border-left: 6px solid #dc3545;
            border-radius: 10px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Acil Çıkış Butonu */
        .emergency-exit-btn {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background-color: rgba(220, 53, 69, 0.1);
            color: white;
            border-top-left-radius: 100%;
            z-index: 9999;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
            padding: 20px;
            cursor: none;
            transition: 0.3s;
        }

        .emergency-exit-btn:hover {
            background-color: rgba(220, 53, 69, 1);
            cursor: pointer;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid d-flex flex-column" style="height: 95vh;">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold mb-0" style="font-size: 3rem; color: #2d3748; letter-spacing: -1px;">
                    <i class="fa-solid fa-layer-group text-primary me-3"></i>KÖKSAN İŞ SÜREÇLERİ
                </h1>
                <span class="text-muted fs-3 fw-medium ms-1">Canlı İzleme Paneli</span>
            </div>
            <div class="text-end">
                <h1 class="fw-bold mb-0" id="live-clock"
                    style="font-size: 4rem; font-family: monospace; color: #4a5568; line-height: 1;">--:--</h1>
                <span class="text-muted fs-4"><?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y, l')); ?></span>
            </div>
        </div>

        
        <div class="row g-4 mb-4">
            <div class="col">
                <div class="tv-card" style="border-bottom: 5px solid #667EEA;">
                    <div class="kpi-card-inner">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['sevkiyat_sayisi']); ?></div>
                            <div class="kpi-label">Sevkiyat</div>
                        </div>
                        <i class="fa-solid fa-truck-fast kpi-icon text-lojistik"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 5px solid #4FD1C5;">
                    <div class="kpi-card-inner">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['plan_sayisi']); ?></div>
                            <div class="kpi-label">Üretim</div>
                        </div>
                        <i class="fa-solid fa-industry kpi-icon text-uretim"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 5px solid #F093FB;">
                    <div class="kpi-card-inner">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['etkinlik_sayisi']); ?></div>
                            <div class="kpi-label">Etkinlik</div>
                        </div>
                        <i class="fa-solid fa-calendar-day kpi-icon text-hizmet"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 5px solid #F6E05E;">
                    <div class="kpi-card-inner">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['arac_gorevi_sayisi']); ?></div>
                            <div class="kpi-label">Araç Görevi</div>
                        </div>
                        <i class="fa-solid fa-car-side kpi-icon text-arac"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 5px solid #ED8936;">
                    <div class="kpi-card-inner">
                        <div>
                            <div class="kpi-value"><?php echo e($kpiData['bakim_sayisi']); ?></div>
                            <div class="kpi-label">Bakım</div>
                        </div>
                        <i class="fa-solid fa-screwdriver-wrench kpi-icon text-bakim"></i>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="row g-4 flex-grow-1">

            
            <div class="col-md-4 h-100">
                <div class="tv-card" style="justify-content: flex-start;">
                    <h3 class="fw-bold mb-4 text-danger border-bottom pb-3">
                        <i class="fas fa-bell fa-shake me-3"></i>Önemli Bildirimler
                    </h3>

                    <?php if($importantItems->isEmpty()): ?>
                        <div
                            class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                            <i class="fa-regular fa-circle-check fa-5x mb-3"></i>
                            <span class="fs-3">Her şey yolunda.</span>
                        </div>
                    <?php else: ?>
                        <div class="d-flex flex-column gap-2">
                            <?php $__currentLoopData = $importantItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="important-item">
                                    <i class="fas fa-exclamation-circle fa-2x text-danger me-3"></i>
                                    <div>
                                        <h4 class="fw-bold mb-1 text-dark" style="font-size: 1.3rem;"><?php echo e($item->title); ?>

                                        </h4>
                                        <div class="text-muted fs-5">
                                            <i class="far fa-clock me-1"></i> <?php echo e($item->date->format('H:i')); ?>

                                            <span class="mx-2">•</span>
                                            <?php echo e($item->date->format('d.m.Y')); ?>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="col-md-8 h-100">
                <div class="tv-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h3 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-project-diagram me-3"></i>Canlı İş Akışı
                        </h3>
                        <span class="badge bg-light text-dark fs-6">Son 24 Saat</span>
                    </div>

                    
                    <div id="sankey-chart" style="width: 100%; height: 100%; min-height: 400px;"></div>
                </div>
            </div>
        </div>

        
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;"><?php echo csrf_field(); ?></form>
        <div onclick="document.getElementById('logout-form').submit();" class="emergency-exit-btn" title="Çıkış">
            <i class="fa-solid fa-power-off fa-3x"></i>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- AKILLI YENİLEME SİSTEMİ (Polling) ---
            // Controller'dan gelen başlangıç Hash'i
            let currentHash = "<?php echo e($currentDataHash); ?>";

            // Her 10 saniyede bir kontrol et
            setInterval(() => {
                fetch("<?php echo e(route('tv.check_updates')); ?>")
                    .then(response => response.json())
                    .then(data => {
                        // Eğer sunucudaki Hash ile bendeki Hash farklıysa, veri değişmiştir.
                        if (data.hash !== currentHash) {
                            console.log('Yeni veri algılandı! Sayfa yenileniyor...');
                            location.reload(); // Sayfayı yenile
                        }
                    })
                    .catch(error => console.error('Güncelleme kontrolü hatası:', error));
            }, 10000); // 10000 ms = 10 saniye


            // --- SAAT GÜNCELLEME ---
            setInterval(() => {
                const now = new Date();
                document.getElementById('live-clock').textContent = now.toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }, 1000);

            // --- OTO SCROLL (Akıllı Döngü) ---
            let scrollSpeed = 1;
            let scrollDelay = 40;
            let holdTime = 5000;
            let scroller;
            let isHolding = false;

            function startTvScroll() {
                scroller = setInterval(() => {
                    if (isHolding) return;

                    const scrollPos = window.scrollY + window.innerHeight;
                    const docHeight = document.body.scrollHeight;

                    if (scrollPos >= docHeight - 10) {
                        isHolding = true;
                        setTimeout(() => {
                            window.scrollTo({
                                top: 0,
                                behavior: 'auto'
                            });
                            isHolding = false;
                        }, holdTime);
                    } else {
                        window.scrollBy(0, scrollSpeed);
                    }
                }, scrollDelay);
            }
            // Sayfa sığmıyorsa kaydırmayı başlat
            if (document.body.scrollHeight > window.innerHeight) {
                setTimeout(startTvScroll, 3000);
            }

            // --- SANKEY GRAFİĞİ ---
            google.charts.load('current', {
                'packages': ['sankey']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Kaynak');
                data.addColumn('string', 'Hedef');
                data.addColumn('number', 'Ağırlık');

                var rawData = <?php echo json_encode($chartData, 15, 512) ?>;
                data.addRows(rawData);

                var options = {
                    sankey: {
                        node: {
                            label: {
                                fontName: 'Arial',
                                fontSize: 18,
                                bold: true,
                                color: '#2d3748'
                            },
                            nodePadding: 40,
                            width: 20
                        },
                        link: {
                            colorMode: 'gradient',
                            colors: ['#667EEA', '#F093FB', '#4FD1C5', '#ED8936'],
                            fillOpacity: 0.6
                        }
                    },
                    tooltip: {
                        isHtml: true,
                        textStyle: {
                            fontSize: 16
                        }
                    }
                };

                var chart = new google.visualization.Sankey(document.getElementById('sankey-chart'));
                chart.draw(data, options);
            }

            // --- KLAVYE İLE ÇIKIŞ (ESC) ---
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") document.getElementById('logout-form').submit();
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/tv/dashboard.blade.php ENDPATH**/ ?>