


<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* Arka plan dalgalanma animasyonu */
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

    /* Cam Kart Stili (Liste için) */
    .list-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    /* Kart Başlığı (Liste için) */
    .list-card .card-header {
        background: rgba(255, 255, 255, 0.5);
        color: #333;
        font-weight: bold;
        font-size: 1.25rem;
        border-bottom: none;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1rem 1.5rem;
    }

    /* Tablo Stilleri */
    .table {
        background-color: transparent;
        margin-bottom: 0;
    }

    .table thead th {
        color: #333;
        border-bottom-width: 2px;
        border-color: rgba(0, 0, 0, 0.15);
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.4);
        color: #212529;
    }

    .table-striped>tbody>tr:nth-of-type(even)>* {
        --bs-table-accent-bg: transparent;
        color: #212529;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.8);
        color: #000;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    /* Filtre Stilleri */
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

    /* ... (mevcut genel stil kurallarınız) ... */

    /* YENİ VE GELİŞTİRİLMİŞ: Önemli satırları vurgulamak için */
    .row-important {
        /* Daha belirgin bir arka plan rengi, ancak yine de hafif */
        --bs-table-accent-bg: rgba(255, 235, 238, 0.8);
        /* Açık pembe tonu, biraz şeffaf */
        background-color: var(--bs-table-accent-bg) !important;
        /* Önemli! Striped override */

        /* Metin rengini belirginleştir ama çok koyu yapma */
        color: #c0392b;
        /* Koyu kırmızımsı ton */
        font-weight: 600;

        /* Hafif bir gölge efekti (isteğe bağlı, kaldırabilirsiniz) */
        box-shadow: inset 0 0 5px rgba(252, 98, 117, 0.1);
        /* İç gölge */

        transition: all 0.2s ease-in-out;
        /* Animasyonlu geçişler */
    }

    /* Önemli satırın üzerine gelindiğinde (hover) arka planı daha belirgin yap */
    .table-hover>tbody>tr.row-important:hover {
        --bs-table-accent-bg: rgba(255, 220, 224, 0.95);
        /* Biraz daha koyu pembe */
        background-color: var(--bs-table-accent-bg) !important;
        /* Önemli! Striped override */
        transform: translateY(-2px);
        /* Hafif yukarı kayma efekti */
        box-shadow: inset 0 0 8px rgba(252, 98, 117, 0.2), 0 2px 5px rgba(0, 0, 0, 0.05);
        /* Daha belirgin gölge */
    }

    /* Önemli satırlardaki hücrelerin metin rengini korumak için */
    .row-important td {
        color: #c0392b;
        /* Metin rengini koru */
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?> <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
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
                            
                            <form method="GET" action="<?php echo e(route('service.events.index')); ?>">
                                <div class="row">
                                    <?php
                                        $isAdminOrManager = in_array(Auth::user()->role, ['admin', 'yönetici']);
                                    ?>

                                    
                                    <div class="<?php echo e($isAdminOrManager ? 'col-md-3' : 'col-md-4'); ?>">
                                        <label for="title" class="form-label">Etkinlik Başlığı (Ara)</label>
                                        <input type="text" class="form-control form-control-sm" id="title"
                                            name="title" value="<?php echo e($filters['title'] ?? ''); ?>"
                                            placeholder="Etkinlik başlığı girin...">
                                    </div>

                                    
                                    <div class="<?php echo e($isAdminOrManager ? 'col-md-3' : 'col-md-4'); ?>">
                                        <label for="event_type" class="form-label">Etkinlik Tipi</label>
                                        <select class="form-select form-select-sm" id="event_type" name="event_type">
                                            <option value="all"
                                                <?php echo e(($filters['event_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>
                                                Tümü
                                            </option>
                                            <?php $__currentLoopData = $eventTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(($filters['event_type'] ?? '') == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($value); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <?php if($isAdminOrManager): ?>
                                        <div class="col-md-2">
                                            <label for="is_important" class="form-label" style="color: #dc3545;">
                                                <i class="fas fa-bell"></i> Önem Durumu
                                            </label>
                                            <select class="form-select form-select-sm" id="is_important"
                                                name="is_important">
                                                <option value="all"
                                                    <?php echo e(($filters['is_important'] ?? 'all') == 'all' ? 'selected' : ''); ?>>
                                                    Tümü
                                                </option>
                                                <option value="yes"
                                                    <?php echo e(($filters['is_important'] ?? '') == 'yes' ? 'selected' : ''); ?>>
                                                    Sadece Önemliler
                                                </option>
                                                <option value="no"
                                                    <?php echo e(($filters['is_important'] ?? '') == 'no' ? 'selected' : ''); ?>>
                                                    Önemli Olmayanlar
                                                </option>
                                            </select>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <div class="col-md-2">
                                        <label for="date_from" class="form-label">Başlangıç Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_from"
                                            name="date_from" value="<?php echo e($filters['date_from'] ?? ''); ?>">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_to" class="form-label">Bitiş Tarihi</label>
                                        <input type="date" class="form-control form-control-sm" id="date_to"
                                            name="date_to" value="<?php echo e($filters['date_to'] ?? ''); ?>">
                                    </div>

                                    
                                    <div class="col-md-12 d-flex align-items-end justify-content-end gap-2 mt-3">
                                        <a href="<?php echo e(route('service.events.index')); ?>"
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
                


                
                <div class="card list-card">
                    
                    <div class="card-header">Etkinlik Listesi</div>

                    <div class="card-body p-0">
                        
                        <?php if($events->isEmpty()): ?>
                            <div class="alert alert-warning m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> Filtrelere uygun etkinlik bulunamadı.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        
                                        <tr>
                                            <th scope="col" class="ps-3">Etkinlik Başlığı</th>
                                            <th scope="col">Tipi</th>
                                            <th scope="col">Konum</th>
                                            <th scope="col">Başlangıç</th>
                                            <th scope="col">Bitiş</th>
                                            <th scope="col" class="text-end pe-3">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="<?php echo e($event->is_important ? 'row-important' : ''); ?>">
                                                
                                                <td class="ps-3"><?php echo e($event->title); ?></td>
                                                <td>
                                                    
                                                    <span
                                                        class="badge bg-secondary"><?php echo e($eventTypes[$event->event_type] ?? ucfirst($event->event_type)); ?></span>
                                                </td>
                                                <td><?php echo e($event->location ?? '-'); ?></td>
                                                <td><?php echo e($event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->format('d.m.Y H:i') : '-'); ?>

                                                </td>
                                                <td><?php echo e($event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->format('d.m.Y H:i') : '-'); ?>

                                                </td>

                                                
                                                <td class="text-end pe-3">
                                                    <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                        <a href="<?php echo e(route('service.events.edit', $event)); ?>"
                                                            class="btn btn-sm btn-primary" title="Düzenle"><i
                                                                class="fas fa-edit"></i></a>
                                                    <?php endif; ?>

                                                    <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                        <form action="<?php echo e(route('service.events.destroy', $event)); ?>"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Sil"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                                
                                <?php if($events->hasPages()): ?>
                                    <div class="card-footer bg-transparent border-top-0 pt-3 pb-2 px-3">
                                        
                                        <?php echo e($events->appends($filters ?? [])->links('pagination::bootstrap-5')); ?>

                                    </div>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/service/events/index.blade.php ENDPATH**/ ?>