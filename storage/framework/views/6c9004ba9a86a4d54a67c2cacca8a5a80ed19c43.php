

<?php $__env->startSection('title', $pageTitle); ?>

<style>
    /* Modern Gradient Background */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }

    #app>main.py-4::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }

    .container-fluid {
        position: relative;
        z-index: 1;
    }

    /* Modern Card Styling */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 48px rgba(0, 0, 0, 0.15);
    }

    /* Page Header */
    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }

    /* Admin Filter Panel */
    .admin-filter-panel {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 2px solid rgba(102, 126, 234, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .filter-section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-section-title i {
        font-size: 1.75rem;
    }

    /* Modern Form Controls */
    .modern-input,
    .modern-select {
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        background: white;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        outline: none;
    }

    .modern-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Modern Buttons */
    .btn-modern {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.875rem;
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-modern-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        color: white;
    }

    .btn-modern-secondary {
        background: white;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .btn-modern-secondary:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
    }

    /* Filter Cards */
    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .filter-card:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    /* Department Badge */
    .dept-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.875rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    /* Chart Container */
    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .chart-header {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f7fafc;
    }

    /* Quick Stats Cards */
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Section Divider */
    .section-divider {
        margin: 3rem 0;
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
    }

    /* Loading State */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }

    .loading-overlay.active {
        opacity: 1;
        pointer-events: all;
    }

    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid rgba(102, 126, 234, 0.2);
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Alert Styling */
    .modern-alert {
        border-radius: 16px;
        border: none;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .modern-alert-info {
        background: linear-gradient(135deg, rgba(52, 211, 153, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%);
        border-left: 4px solid #10b981;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .admin-filter-panel {
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
        }
    }

    /* TV Mode Adjustments */
    <?php if(isset($isTvUser) && $isTvUser): ?>
        body {
            cursor: auto !important;
        }

        a {
            pointer-events: auto !important;
            cursor: pointer !important;
        }
    <?php endif; ?>
</style>

<?php $__env->startSection('content'); ?>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="container-fluid">
        
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="fa-solid fa-chart-line me-2"></i>
                        <?php echo e($pageTitle); ?>

                    </h1>
                    <?php if($departmentSlug !== 'genel'): ?>
                        <div class="mt-2">
                            <span class="dept-badge">
                                <i class="fa-solid fa-building"></i>
                                <?php echo e($departmentName ?? $pageTitle); ?>

                            </span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-modern btn-modern-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Takvime Dön
                    </a>
                </div>
            </div>
        </div>

        
        
        
        <?php if(isset($isSuperUser) && $isSuperUser): ?>
            <div class="admin-filter-panel">
                <div class="filter-section-title">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders" style="color: #667eea;"></i>
                        <span>Yönetici Kontrol Paneli</span>
                    </div>
                    <small class="ms-auto" style="font-size: 0.75rem; font-weight: normal; color: #718096;">
                        <i class="fa-solid fa-bolt text-warning me-1"></i> Anlık Güncellenir
                    </small>
                </div>

                <form method="GET" action="<?php echo e(route('statistics.index')); ?>" id="adminFilterForm">
                    <div class="row g-3 align-items-end">
                        
                        <div class="col-md-3">
                            <label class="modern-label">
                                <i class="fa-solid fa-building me-2"></i> Departman
                            </label>
                            <select name="target_dept" id="deptSelect" class="form-select modern-select">
                                <option value="genel" <?php echo e($departmentSlug == 'genel' ? 'selected' : ''); ?>>
                                    📊 Genel Bakış
                                </option>
                                <?php $__currentLoopData = $allDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->slug); ?>"
                                        <?php echo e($departmentSlug == $dept->slug ? 'selected' : ''); ?>>
                                        <?php echo e($dept->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        
                        <div class="col-md-3">
                            <label class="modern-label">
                                <i class="fa-solid fa-calendar-day me-2"></i> Başlangıç
                            </label>
                            <input type="date" name="date_from" id="adminDateFrom" class="form-control modern-input"
                                value="<?php echo e($filters['date_from']); ?>">
                        </div>

                        
                        <div class="col-md-3">
                            <label class="modern-label">
                                <i class="fa-solid fa-calendar-check me-2"></i> Bitiş
                            </label>
                            <input type="date" name="date_to" id="adminDateTo" class="form-control modern-input"
                                value="<?php echo e($filters['date_to']); ?>">
                        </div>

                        
                        <div class="col-md-3">
                            <a href="<?php echo e(route('statistics.index', ['target_dept' => 'genel'])); ?>"
                                class="btn btn-modern btn-modern-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-rotate-right"></i> Filtreleri Sıfırla
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('adminFilterForm');
                    const deptSelect = document.getElementById('deptSelect');
                    const dateFrom = document.getElementById('adminDateFrom');
                    const dateTo = document.getElementById('adminDateTo');
                    const loadingOverlay = document.getElementById('loadingOverlay');

                    function submitWithLoading() {
                        loadingOverlay.classList.add('active');
                        form.submit();
                    }

                    deptSelect?.addEventListener('change', submitWithLoading);

                    dateFrom?.addEventListener('change', function() {
                        if (dateTo.value) submitWithLoading();
                    });

                    dateTo?.addEventListener('change', function() {
                        if (dateFrom.value) submitWithLoading();
                    });
                });
            </script>
        <?php endif; ?>


        
        
        
        <div id="stats-data-container" style="display: none;" data-chart-data='<?php echo json_encode($chartData ?? [], 15, 512) ?>'
            data-department-slug="<?php echo e($departmentSlug ?? ''); ?>"
            <?php if($departmentSlug === 'lojistik'): ?> data-shipments='<?php echo json_encode($shipmentsForFiltering ?? [], 15, 512) ?>'
            <?php elseif($departmentSlug === 'uretim'): ?>
                data-production-plans='<?php echo json_encode($productionPlansForFiltering ?? [], 15, 512) ?>'
            <?php elseif($departmentSlug === 'hizmet'): ?>
                data-events='<?php echo json_encode($eventsForFiltering ?? [], 15, 512) ?>'
                data-assignments='<?php echo json_encode($assignmentsForFiltering ?? [], 15, 512) ?>'
                data-vehicles='<?php echo json_encode($vehiclesForFiltering ?? [], 15, 512) ?>'
                data-monthly-labels='<?php echo json_encode($monthlyLabels ?? [], 15, 512) ?>' 
            <?php elseif($departmentSlug === 'bakim'): ?>
                data-maintenance-plans='<?php echo json_encode($maintenancePlansForFiltering ?? [], 15, 512) ?>'
                data-maintenance-types='<?php echo json_encode($maintenanceTypes ?? [], 15, 512) ?>'
                data-assets='<?php echo json_encode($assets ?? [], 15, 512) ?>' <?php endif; ?>>
        </div>


        
        
        
        
        <?php if(!isset($isSuperUser) || !$isSuperUser): ?>
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <div class="filter-section-title mb-4 d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa-solid fa-filter"></i> Tarih Filtreleri
                        </div>
                        <small class="text-muted" style="font-size: 0.75rem; font-weight: normal;">
                            <i class="fa-solid fa-bolt text-warning me-1"></i> Otomatik güncellenir
                        </small>
                    </div>

                    
                    <form method="GET" action="<?php echo e(route('statistics.index')); ?>" id="standardFilterForm">
                        
                        <?php if(request()->has('target_dept')): ?>
                            <input type="hidden" name="target_dept" value="<?php echo e($departmentSlug); ?>">
                        <?php endif; ?>

                        <div class="row g-3 align-items-end">
                            
                            <div class="col-md-4">
                                <label class="modern-label">Başlangıç Tarihi</label>
                                
                                <input type="date" name="date_from" id="std_date_from" class="form-control modern-input"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>">
                            </div>

                            
                            <div class="col-md-4">
                                <label class="modern-label">Bitiş Tarihi</label>
                                
                                <input type="date" name="date_to" id="std_date_to" class="form-control modern-input"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>">
                            </div>

                            
                            <div class="col-md-4">
                                <a href="<?php echo e(route('statistics.index')); ?>"
                                    class="btn btn-modern btn-modern-secondary w-100 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-rotate-right me-2"></i> Filtreleri Sıfırla
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        
        
        
        <?php if($departmentSlug !== 'genel'): ?>
            
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <?php if($departmentSlug === 'lojistik'): ?>
                        <h6 class="modern-label mb-3">
                            <i class="fa-solid fa-truck-fast me-2"></i> Lojistik Hızlı Filtreleri (Anlık)
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="modern-label">Sevkiyat Türü</label>
                                <select id="shipmentTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">Araç Tipi</label>
                                <select id="vehicleTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">Kargo İçeriği</label>
                                <select id="cargoContentFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                        </div>
                    <?php elseif($departmentSlug === 'uretim'): ?>
                        <h6 class="modern-label mb-3">
                            <i class="fa-solid fa-gears me-2"></i> Üretim Hızlı Filtreleri (Anlık)
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="modern-label">Makine</label>
                                <select id="machineFilter" class="form-select modern-select">
                                    <option value="all">Tüm Makineler</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">Ürün</label>
                                <select id="productFilter" class="form-select modern-select">
                                    <option value="all">Tüm Ürünler</option>
                                </select>
                            </div>
                        </div>
                    <?php elseif($departmentSlug === 'hizmet'): ?>
                        <h6 class="modern-label mb-3">
                            <i class="fa-solid fa-briefcase me-2"></i> İdari İşler Hızlı Filtreleri (Anlık)
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="modern-label">Etkinlik Tipi</label>
                                <select id="eventTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="modern-label">Araç</label>
                                <select id="vehicleFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                        </div>
                        
                    <?php elseif($departmentSlug === 'bakim'): ?>
                        <h6 class="modern-label mb-3">
                            <i class="fa-solid fa-screwdriver-wrench me-2"></i> Bakım Hızlı Filtreleri
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="modern-label">Bakım Türü</label>
                                <select id="maintenanceTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">Makine / Varlık</label>
                                <select id="assetFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">Durum</label>
                                <select id="statusFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                    <option value="pending">Bekleyenler</option>
                                    <option value="in_progress">Devam Edenler</option>
                                    <option value="completed">Tamamlananlar</option>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        
        <?php if($departmentSlug === 'genel'): ?>
            
            <div class="modern-alert modern-alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-circle-info me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>Genel Bakış Modu</strong>
                        <p class="mb-0 mt-1">Tüm departmanların özet istatistikleri görüntüleniyor. Detaylı analiz için
                            yukarıdan departman seçin.</p>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">📊 Departman Aktivite Özeti</div>
                        <div id="department-summary-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stat-card mb-3">
                        <div class="stat-label">Toplam Aktivite</div>
                        <div class="stat-value">
                            <?php echo e(isset($chartData['departmentSummary']['data']) ? array_sum($chartData['departmentSummary']['data']) : 0); ?>

                        </div>
                    </div>
                    <div class="stat-card mb-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="stat-label">Başlangıç</div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?php echo e($filters['date_from']); ?></div>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="stat-label">Bitiş</div>
                        <div class="stat-value" style="font-size: 1.25rem;"><?php echo e($filters['date_to']); ?></div>
                    </div>
                </div>
            </div>
        <?php elseif($departmentSlug === 'lojistik'): ?>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="vehicle-chart-title">🚛 Araç Tipi Kullanımı</div>
                        <div id="vehicle-type-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="cargo-chart-title">📦 Kargo İçeriği Dağılımı</div>
                        <div id="cargo-content-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <h4 class="mb-4" style="color: white; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-chart-column me-2"></i>
                Detaylı İstatistikler (<?php echo e($filters['date_from']); ?> - <?php echo e($filters['date_to']); ?>)
            </h4>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div id="monthly-chart-lojistik" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <div id="pie-chart-lojistik" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div id="hourly-chart-lojistik" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <div id="daily-chart-lojistik" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-12">
                    <div class="chart-container">
                        <div id="yearly-chart-lojistik" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        <?php elseif($departmentSlug === 'uretim'): ?>
            
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="machine-chart-title">⚙️ Makine Kullanımı</div>
                        <div id="machine-chart-uretim" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="product-chart-title">📊 Ürün Dağılımı</div>
                        <div id="product-chart-uretim" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <h4 class="mb-4" style="color: white; font-weight: 700; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <i class="fa-solid fa-chart-column me-2"></i>
                Detaylı İstatistikler (<?php echo e($filters['date_from']); ?> - <?php echo e($filters['date_to']); ?>)
            </h4>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div id="weekly-prod-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div id="monthly-prod-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        <?php elseif($departmentSlug === 'hizmet'): ?>
            
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="event-pie-chart-title">📅 Etkinlik Dağılımı</div>
                        <div id="event-type-pie-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header" id="assignment-chart-title">🚗 Aylık Araç Atama</div>
                        <div id="monthly-assign-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            
        <?php elseif($departmentSlug === 'bakim'): ?>
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📊 Bakım Türü Dağılımı</div>
                        <div id="maintenance-type-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">⚠️ En Çok Arıza Yapan Makineler (Top 5)</div>
                        <div id="top-assets-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <hr class="section-divider">

            <div class="row g-4">
                <div class="col-12">
                    <div class="chart-container">
                        <div class="chart-header">📅 Aylık Bakım Planlama Yoğunluğu</div>
                        <div id="monthly-maintenance-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="modern-alert modern-alert-info">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-info-circle me-3" style="font-size: 1.5rem;"></i>
                    <div>
                        <strong>Veri Bulunamadı</strong>
                        <p class="mb-0 mt-1">Bu departman için henüz istatistik verisi bulunmamaktadır.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- 1. OTOMATİK FİLTRELEME MANTIĞI (HEM ADMİN HEM STANDART) ---
            const loadingOverlay = document.getElementById('loadingOverlay');

            // Formları ve Inputları Tanımla
            const forms = [{
                    id: 'adminFilterForm',
                    from: 'adminDateFrom',
                    to: 'adminDateTo',
                    select: 'deptSelect'
                },
                {
                    id: 'standardFilterForm',
                    from: 'std_date_from',
                    to: 'std_date_to',
                    select: null // Standart kullanıcıda departman seçimi yok
                }
            ];

            // Fonksiyon: Yükleniyor ekranını aç ve formu gönder
            function submitForm(formId) {
                const form = document.getElementById(formId);
                if (form) {
                    if (loadingOverlay) loadingOverlay.classList.add('active');
                    form.submit();
                }
            }

            // Her bir form seti için dinleyicileri ekle
            forms.forEach(item => {
                const formEl = document.getElementById(item.id);
                if (formEl) {
                    const dateFromEl = document.getElementById(item.from);
                    const dateToEl = document.getElementById(item.to);
                    const selectEl = item.select ? document.getElementById(item.select) : null;

                    // Departman değişirse (Varsa)
                    if (selectEl) {
                        selectEl.addEventListener('change', () => submitWithLoading(item.id));
                    }

                    // Başlangıç tarihi değişirse
                    if (dateFromEl) {
                        dateFromEl.addEventListener('change', function() {
                            // Eğer bitiş tarihi de doluysa gönder, boşsa bekle
                            if (dateToEl && dateToEl.value) {
                                submitForm(item.id);
                            }
                        });
                    }

                    // Bitiş tarihi değişirse
                    if (dateToEl) {
                        dateToEl.addEventListener('change', function() {
                            // Eğer başlangıç tarihi de doluysa gönder
                            if (dateFromEl && dateFromEl.value) {
                                submitForm(item.id);
                            }
                        });
                    }
                }
            });
            const colorPalette = ['#667EEA', '#764BA2', '#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];
            const statsContainer = document.getElementById('stats-data-container');

            if (!statsContainer) return;

            let chartData = {};
            let departmentSlug = '';

            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
                departmentSlug = statsContainer.dataset.departmentSlug || '';
            } catch (error) {
                console.error('Chart data parse error:', error);
                return;
            }

            // Common Chart Options
            const commonChartOptions = {
                chart: {
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    }
                },
                colors: colorPalette,
                legend: {
                    show: false
                },
                title: {
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#2d3748'
                    }
                },
                noData: {
                    text: 'Gösterilecek veri bulunamadı.'
                },
                dataLabels: {
                    enabled: false
                }
            };

            const commonBarOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 8
                    }
                }
            };

            const commonAreaOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'area'
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.1
                    }
                }
            };

            const commonPieOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'donut'
                },
                legend: {
                    position: 'bottom',
                    show: true
                }
            };

            // === GENERAL OVERVIEW ===
            if (departmentSlug === 'genel') {
                if (chartData.departmentSummary) {
                    new ApexCharts(document.querySelector("#department-summary-chart"), {
                        chart: {
                            type: 'bar',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Aktivite Sayısı',
                            data: chartData.departmentSummary.data || []
                        }],
                        xaxis: {
                            categories: chartData.departmentSummary.labels || []
                        },
                        colors: colorPalette,
                        plotOptions: {
                            bar: {
                                distributed: true,
                                borderRadius: 8,
                                columnWidth: '60%'
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            style: {
                                fontSize: '14px',
                                fontWeight: 'bold'
                            }
                        },
                        title: {
                            text: chartData.departmentSummary.title || 'Departman Özeti',
                            align: 'left',
                            style: {
                                fontSize: '16px',
                                fontWeight: 'bold',
                                color: '#2d3748'
                            }
                        },
                        legend: {
                            show: false
                        }
                    }).render();
                }
            }

            // === LOGISTICS ===
            else if (departmentSlug === 'lojistik') {
                const allShipmentsData = JSON.parse(statsContainer.dataset.shipments || '[]');
                const shipmentTypeDropdown = document.getElementById('shipmentTypeFilter');
                const vehicleTypeDropdown = document.getElementById('vehicleTypeFilter');
                const cargoContentDropdown = document.getElementById('cargoContentFilter');

                let vehicleChart = new ApexCharts(document.querySelector("#vehicle-type-chart"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kullanım Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Araç Tipi Kullanımı'
                    }
                });
                vehicleChart.render();

                let cargoChart = new ApexCharts(document.querySelector("#cargo-content-chart"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kargo Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Kargo İçeriği Dağılımı'
                    }
                });
                cargoChart.render();

                function populateLojistikFilters() {
                    if (!allShipmentsData) return;
                    const types = new Set(allShipmentsData.map(s => s.shipment_type));
                    const vehicles = new Set(allShipmentsData.map(s => s.vehicle));
                    const cargos = new Set(allShipmentsData.map(s => s.cargo));

                    types.forEach(type => {
                        if (type) shipmentTypeDropdown.innerHTML +=
                            `<option value="${type}">${type === 'import' ? 'İthalat' : 'İhracat'}</option>`;
                    });
                    vehicles.forEach(vehicle => {
                        if (vehicle) vehicleTypeDropdown.innerHTML +=
                            `<option value="${vehicle}">${vehicle}</option>`;
                    });
                    cargos.forEach(cargo => {
                        if (cargo) cargoContentDropdown.innerHTML +=
                            `<option value="${cargo}">${cargo}</option>`;
                    });
                }

                function updateLojistikCharts() {
                    const selectedType = shipmentTypeDropdown.value;
                    const selectedVehicle = vehicleTypeDropdown.value;
                    const selectedCargo = cargoContentDropdown.value;

                    let filteredData = allShipmentsData;

                    if (selectedType !== 'all') filteredData = filteredData.filter(s => s.shipment_type ===
                        selectedType);
                    if (selectedVehicle !== 'all') filteredData = filteredData.filter(s => s.vehicle ===
                        selectedVehicle);
                    if (selectedCargo !== 'all') filteredData = filteredData.filter(s => s.cargo === selectedCargo);

                    const vehicleCounts = {},
                        cargoCounts = {};
                    filteredData.forEach(shipment => {
                        if (shipment?.vehicle) vehicleCounts[shipment.vehicle] = (vehicleCounts[shipment
                            .vehicle] || 0) + 1;
                        if (shipment?.cargo) cargoCounts[shipment.cargo] = (cargoCounts[shipment.cargo] ||
                            0) + 1;
                    });

                    const sortedVehicles = Object.entries(vehicleCounts).sort((a, b) => b[1] - a[1]);
                    const sortedCargo = Object.entries(cargoCounts).sort((a, b) => b[1] - a[1]);

                    vehicleChart.updateOptions({
                        xaxis: {
                            categories: sortedVehicles.map(([name]) => name)
                        }
                    }, false, false);
                    vehicleChart.updateSeries([{
                        data: sortedVehicles.map(([, count]) => count)
                    }], true);

                    cargoChart.updateOptions({
                        xaxis: {
                            categories: sortedCargo.map(([name]) => name)
                        }
                    }, false, false);
                    cargoChart.updateSeries([{
                        data: sortedCargo.map(([, count]) => count)
                    }], true);
                }

                shipmentTypeDropdown?.addEventListener('change', updateLojistikCharts);
                vehicleTypeDropdown?.addEventListener('change', updateLojistikCharts);
                cargoContentDropdown?.addEventListener('change', updateLojistikCharts);

                populateLojistikFilters();
                updateLojistikCharts();

                // General Charts
                if (chartData.monthly) new ApexCharts(document.querySelector("#monthly-chart-lojistik"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.monthly.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.monthly.title
                    },
                    xaxis: {
                        categories: chartData.monthly.labels || []
                    }
                }).render();

                if (chartData.pie) new ApexCharts(document.querySelector("#pie-chart-lojistik"), {
                    ...commonPieOptions,
                    series: chartData.pie.data || [],
                    labels: chartData.pie.labels || [],
                    title: {
                        ...commonPieOptions.title,
                        text: chartData.pie.title
                    }
                }).render();

                if (chartData.hourly) new ApexCharts(document.querySelector("#hourly-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.hourly.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.hourly.title
                    },
                    xaxis: {
                        categories: chartData.hourly.labels || [],
                        tickAmount: 12
                    }
                }).render();

                if (chartData.daily) new ApexCharts(document.querySelector("#daily-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.daily.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.daily.title
                    },
                    xaxis: {
                        categories: chartData.daily.labels || []
                    }
                }).render();

                if (chartData.yearly) new ApexCharts(document.querySelector("#yearly-chart-lojistik"), {
                    ...commonBarOptions,
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    },
                    series: [{
                        name: 'Sevkiyat Sayısı',
                        data: chartData.yearly.data || []
                    }],
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.yearly.title
                    },
                    xaxis: {
                        categories: chartData.yearly.labels || []
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true,
                            borderRadius: 8
                        }
                    }
                }).render();
            }

            // === PRODUCTION ===
            else if (departmentSlug === 'uretim') {
                const allPlansData = JSON.parse(statsContainer.dataset.productionPlans || '[]');
                const machineDropdown = document.getElementById('machineFilter');
                const productDropdown = document.getElementById('productFilter');

                let machineChart = new ApexCharts(document.querySelector("#machine-chart-uretim"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Kullanım Sayısı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Makine Kullanım Sayısı'
                    }
                });
                machineChart.render();

                let productChart = new ApexCharts(document.querySelector("#product-chart-uretim"), {
                    ...commonBarOptions,
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true,
                            borderRadius: 8
                        }
                    },
                    series: [{
                        name: 'Üretim Miktarı',
                        data: []
                    }],
                    xaxis: {
                        categories: []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: 'Ürün Miktar Dağılımı'
                    }
                });
                productChart.render();

                function populateProductionFilters() {
                    if (!allPlansData) return;
                    const machines = new Set(allPlansData.map(p => p.machine));
                    const products = new Set(allPlansData.map(p => p.product));

                    machines.forEach(machine => {
                        if (machine && machine !== 'Bilinmiyor')
                            machineDropdown.innerHTML += `<option value="${machine}">${machine}</option>`;
                    });
                    products.forEach(product => {
                        if (product && product !== 'Bilinmiyor')
                            productDropdown.innerHTML += `<option value="${product}">${product}</option>`;
                    });
                }

                function updateProductionCharts() {
                    const selectedMachine = machineDropdown.value;
                    const selectedProduct = productDropdown.value;

                    let filteredData = allPlansData;

                    if (selectedMachine !== 'all') filteredData = filteredData.filter(p => p.machine ===
                        selectedMachine);
                    if (selectedProduct !== 'all') filteredData = filteredData.filter(p => p.product ===
                        selectedProduct);

                    const machineCounts = {},
                        productQuantities = {};

                    filteredData.forEach(plan => {
                        if (plan.machine !== 'Bilinmiyor') machineCounts[plan.machine] = (machineCounts[plan
                            .machine] || 0) + 1;
                        if (plan.product !== 'Bilinmiyor') productQuantities[plan.product] = (
                            productQuantities[plan.product] || 0) + plan.quantity;
                    });

                    const sortedMachines = Object.entries(machineCounts).sort((a, b) => b[1] - a[1]);
                    const sortedProducts = Object.entries(productQuantities).sort((a, b) => b[1] - a[1]).slice(0,
                        15);

                    machineChart.updateOptions({
                        xaxis: {
                            categories: sortedMachines.map(([name]) => name)
                        }
                    }, false, false);
                    machineChart.updateSeries([{
                        data: sortedMachines.map(([, count]) => count)
                    }], true);

                    productChart.updateOptions({
                        xaxis: {
                            categories: sortedProducts.map(([name]) => name)
                        }
                    }, false, false);
                    productChart.updateSeries([{
                        data: sortedProducts.map(([, count]) => count)
                    }], true);
                }

                machineDropdown?.addEventListener('change', updateProductionCharts);
                productDropdown?.addEventListener('change', updateProductionCharts);

                populateProductionFilters();
                updateProductionCharts();

                if (chartData.weekly_prod) new ApexCharts(document.querySelector("#weekly-prod-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan Sayısı',
                        data: chartData.weekly_prod.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.weekly_prod.title
                    },
                    xaxis: {
                        categories: chartData.weekly_prod.labels || [],
                        tickAmount: 10,
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    }
                }).render();

                if (chartData.monthly_prod) new ApexCharts(document.querySelector("#monthly-prod-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan Sayısı',
                        data: chartData.monthly_prod.data || []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: chartData.monthly_prod.title
                    },
                    xaxis: {
                        categories: chartData.monthly_prod.labels || [],
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    }
                }).render();
            }

            // === SERVICE ===
            else if (departmentSlug === 'hizmet') {
                const allEventsData = JSON.parse(statsContainer.dataset.events || '[]');
                const allAssignmentsData = JSON.parse(statsContainer.dataset.assignments || '[]');
                const allVehicles = JSON.parse(statsContainer.dataset.vehicles || '[]');
                const monthlyLabels = JSON.parse(statsContainer.dataset.monthlyLabels || '[]');

                const eventTypeDropdown = document.getElementById('eventTypeFilter');
                const vehicleDropdown = document.getElementById('vehicleFilter');

                let eventPieChart = new ApexCharts(document.querySelector("#event-type-pie-chart"), {
                    ...commonPieOptions,
                    series: [],
                    labels: [],
                    title: {
                        ...commonPieOptions.title,
                        text: 'Etkinlik Tipi Dağılımı'
                    }
                });
                eventPieChart.render();

                let assignmentChart = new ApexCharts(document.querySelector("#monthly-assign-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Atama Sayısı',
                        data: []
                    }],
                    title: {
                        ...commonAreaOptions.title,
                        text: 'Aylık Araç Atama Sayısı'
                    },
                    xaxis: {
                        categories: monthlyLabels
                    }
                });
                assignmentChart.render();

                function populateServiceFilters() {
                    const eventTypes = new Map();
                    allEventsData.forEach(e => {
                        if (e.type_name) eventTypes.set(e.type_slug, e.type_name);
                    });

                    eventTypes.forEach((name, slug) => {
                        eventTypeDropdown.innerHTML += `<option value="${slug}">${name}</option>`;
                    });

                    allVehicles.forEach(vehicle => {
                        vehicleDropdown.innerHTML +=
                            `<option value="${vehicle.id}">${vehicle.plate_number}</option>`;
                    });
                }

                function updateServiceCharts() {
                    const selectedEventType = eventTypeDropdown.value;
                    const selectedVehicleId = vehicleDropdown.value;

                    let filteredEvents = allEventsData;
                    if (selectedEventType !== 'all') {
                        filteredEvents = filteredEvents.filter(e => e.type_slug === selectedEventType);
                    }

                    const eventCounts = {};
                    filteredEvents.forEach(event => {
                        eventCounts[event.type_name] = (eventCounts[event.type_name] || 0) + 1;
                    });

                    const sortedEventTypes = Object.entries(eventCounts).sort((a, b) => b[1] - a[1]);

                    eventPieChart.updateOptions({
                        labels: sortedEventTypes.map(([name]) => name),
                        series: sortedEventTypes.map(([, count]) => count)
                    });

                    let filteredAssignments = allAssignmentsData;
                    if (selectedVehicleId !== 'all') {
                        filteredAssignments = filteredAssignments.filter(a => a.vehicle_id == selectedVehicleId);
                    }

                    const monthlyCounts = {};
                    monthlyLabels.forEach(label => monthlyCounts[label] = 0);

                    filteredAssignments.forEach(assignment => {
                        const monthLabel = assignment.start_month_label;
                        if (monthLabel in monthlyCounts) {
                            monthlyCounts[monthLabel]++;
                        }
                    });

                    assignmentChart.updateSeries([{
                        data: Object.values(monthlyCounts)
                    }]);
                }

                eventTypeDropdown?.addEventListener('change', updateServiceCharts);
                vehicleDropdown?.addEventListener('change', updateServiceCharts);

                populateServiceFilters();
                updateServiceCharts();
            }
            // === MAINTENANCE (BAKIM) ===
            else if (departmentSlug === 'bakim') {
                // Verileri HTML'den al
                const allMaintenanceData = JSON.parse(statsContainer.dataset.maintenancePlans || '[]');
                const allTypes = JSON.parse(statsContainer.dataset.maintenanceTypes || '[]');
                const allAssets = JSON.parse(statsContainer.dataset.assets || '[]');
                const chartData = JSON.parse(statsContainer.dataset.chartData || '{}');

                // Dropdownlar
                const typeDropdown = document.getElementById('maintenanceTypeFilter');
                const assetDropdown = document.getElementById('assetFilter');
                const statusDropdown = document.getElementById('statusFilter');

                // 1. Filtreleri Doldur
                allTypes.forEach(t => {
                    typeDropdown.innerHTML += `<option value="${t.id}">${t.name}</option>`;
                });
                allAssets.forEach(a => {
                    assetDropdown.innerHTML += `<option value="${a.id}">${a.name}</option>`;
                });

                // 2. Grafikleri Oluştur
                let typeChart = new ApexCharts(document.querySelector("#maintenance-type-chart"), {
                    ...commonPieOptions,
                    series: chartData.type_dist.data || [],
                    labels: chartData.type_dist.labels || [],
                    colors: ['#ED8936', '#4299E1', '#48BB78', '#F56565', '#ECC94B'],
                    title: {
                        ...commonPieOptions.title,
                        text: 'Bakım Türüne Göre Dağılım'
                    }
                });
                typeChart.render();

                let assetChart = new ApexCharts(document.querySelector("#top-assets-chart"), {
                    ...commonBarOptions,
                    series: [{
                        name: 'Bakım Sayısı',
                        data: chartData.top_assets.data || []
                    }],
                    xaxis: {
                        categories: chartData.top_assets.labels || []
                    },
                    colors: ['#F56565'], // Kırmızı ton (Arıza vurgusu)
                    title: {
                        ...commonBarOptions.title,
                        text: 'En Çok Bakım Gören 5 Makine'
                    }
                });
                assetChart.render();

                let monthlyChart = new ApexCharts(document.querySelector("#monthly-maintenance-chart"), {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan Sayısı',
                        data: chartData.monthly_maintenance.data || []
                    }],
                    xaxis: {
                        categories: chartData.monthly_maintenance.labels || []
                    },
                    colors: ['#ED8936'], // Turuncu
                    title: {
                        ...commonAreaOptions.title,
                        text: 'Aylık Bakım Sayısı'
                    }
                });
                monthlyChart.render();

                // 3. Filtreleme Mantığı (Client-Side)
                function updateMaintenanceCharts() {
                    const selType = typeDropdown.value;
                    const selAsset = assetDropdown.value;
                    const selStatus = statusDropdown.value;

                    let filtered = allMaintenanceData;

                    if (selType !== 'all') filtered = filtered.filter(m => m.type_id == selType);
                    if (selAsset !== 'all') filtered = filtered.filter(m => m.asset_id == selAsset);
                    if (selStatus !== 'all') filtered = filtered.filter(m => m.status === selStatus);

                    // Tür Grafiğini Güncelle (Filtrelenmiş veriye göre yeniden hesapla)
                    const typeCounts = {};
                    filtered.forEach(m => {
                        const tName = allTypes.find(t => t.id == m.type_id)?.name || 'Bilinmiyor';
                        typeCounts[tName] = (typeCounts[tName] || 0) + 1;
                    });
                    typeChart.updateOptions({
                        labels: Object.keys(typeCounts),
                        series: Object.values(typeCounts)
                    });
                }

                // Listener'ları Ekle
                typeDropdown.addEventListener('change', updateMaintenanceCharts);
                assetDropdown.addEventListener('change', updateMaintenanceCharts);
                statusDropdown.addEventListener('change', updateMaintenanceCharts);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/statistics/index.blade.php ENDPATH**/ ?>