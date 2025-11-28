
<?php $__env->startSection('title', 'Tüm Önemli Bildirimler'); ?>

<style>
    /* welcome.blade.php dosyanızdaki stilleri buraya da alalım */
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
        border-radius: 1.25rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.4);
        background-color: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
    }

    .create-shipment-card .card-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border-bottom: 1px solid rgba(102, 126, 234, 0.2);
    }

    /* "Önemli Bildirimler" listesi için pulse animasyonu */
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

    /* Pulse Animasyon Keyframes */
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

    .btn-filter-toggle {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-filter-toggle:hover {
        background-color: rgba(255, 255, 255, 0.95);
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-filter-toggle[aria-expanded="true"] {
        background-color: rgba(230, 235, 255, 0.9);
    }

    .filter-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .filter-card .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 0.5rem;
        background-color: #fff;
    }

    .filter-card .row {
        margin-bottom: -1rem;
    }

    .filter-card .row>div {
        margin-bottom: 1rem;
    }

    .btn-apply-filter {
        background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        padding: 0.5rem 1.25rem;
    }

    .btn-apply-filter:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-clear-filter {
        padding: 0.5rem 1.25rem;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10"> 
                <div class="mb-4">
                    <div class="d-grid">
                        <button class="btn btn-filter-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-filter me-2"></i> Filtre Seçenekleri
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card">
                            <form method="GET" action="<?php echo e(route('important.all')); ?>">
                                <div class="row">
                                    
                                    <div class="col-md-3">
                                        <label for="type" class="form-label">Veri Tipi</label>
                                        <select class="form-select form-select-sm" id="type" name="type">
                                            <option value="all"
                                                <?php echo e(($filters['type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü</option>
                                            <option value="shipment"
                                                <?php echo e(($filters['type'] ?? '') == 'shipment' ? 'selected' : ''); ?>>Sevkiyat
                                            </option>
                                            <option value="production_plan"
                                                <?php echo e(($filters['type'] ?? '') == 'production_plan' ? 'selected' : ''); ?>>Üretim
                                                Planı</option>
                                            <option value="event"
                                                <?php echo e(($filters['type'] ?? '') == 'event' ? 'selected' : ''); ?>>Etkinlik
                                            </option>
                                            <option value="vehicle_assignment"
                                                <?php echo e(($filters['type'] ?? '') == 'vehicle_assignment' ? 'selected' : ''); ?>>
                                                Araç Ataması</option>
                                            <option value="travel"
                                                <?php echo e(($filters['type'] ?? '') == 'travel' ? 'selected' : ''); ?>>
                                                Seyahat Planı
                                            </option>
                                        </select>
                                    </div>

                                    
                                    <div class="col-md-3">
                                        <label for="date_from" class="form-label">Başlangıç Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_from"
                                            name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_to" class="form-label">Bitiş Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_to"
                                            name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>">
                                    </div>

                                    
                                    <div class="col-md-12 d-flex align-items-end justify-content-end gap-2 mt-3">
                                        <a href="<?php echo e(route('important.all')); ?>"
                                            class="btn btn-secondary btn-clear-filter btn-sm">
                                            <i class="fas fa-times me-1"></i> Temizle
                                        </a>
                                        <button type="submit" class="btn btn-apply-filter btn-sm">
                                            <i class="fas fa-check me-1"></i> Filtrele
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card create-shipment-card mb-4">

                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background: linear-gradient(135deg, rgba(255, 65, 54, 0.1), rgba(255, 100, 80, 0.1)); border-bottom: 1px solid rgba(255, 65, 54, 0.2);">
                        <h5 class="mb-0" style="color: #dc3545; font-weight: 700;">
                            <i class="fas fa-bell"></i> Tüm Önemli Bildirimler (<?php echo e($importantItems->count()); ?> adet)
                        </h5>
                        <a href="<?php echo e(route('welcome')); ?>" class="btn btn-sm btn-outline-secondary">← Geri Dön</a>
                    </div>

                    <div class="card-body" style="padding: 1rem 1.5rem;">
                        <div class="list-group list-group-flush">
                            <?php $__empty_1 = true; $__currentLoopData = $importantItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="alert alert-success text-center">
                                    Önemli olarak işaretlenmiş bir kayıt bulunamadı.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_scripts'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterCollapse = document.getElementById('filterCollapse');
            var filterButtonIcon = document.querySelector('.btn-filter-toggle .fa-chevron-down');

            if (filterCollapse && filterButtonIcon) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-up');
                    filterButtonIcon.classList.add('fa-chevron-down');
                });
                if (filterCollapse.classList.contains('show')) {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/important-items.blade.php ENDPATH**/ ?>