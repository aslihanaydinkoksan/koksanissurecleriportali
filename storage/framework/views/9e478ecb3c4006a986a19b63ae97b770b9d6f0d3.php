

<?php $__env->startPush('styles'); ?>
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

        /* Sayfa başlığı */
        .page-header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .page-header h4 {
            margin: 0;
            color: #2d3748;
            font-weight: 700;
        }

        /* Filtre Kartı */
        .filter-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .filter-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .filter-header i {
            color: #667EEA;
            font-size: 1.2rem;
        }

        .filter-header h6 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
        }

        .filter-card .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 0.5rem;
            border: 1px solid #dee2e6;
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        /* Tablo kartı */
        .list-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .list-card .card-header {
            background: rgba(255, 255, 255, 0.5);
            color: #2d3748;
            font-weight: 700;
            font-size: 1.1rem;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem 1.5rem;
        }

        /* Tablo iyileştirmeleri */
        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
            transform: scale(1.002);
        }

        .table-striped>tbody>tr:nth-of-type(odd)>* {
            --bs-table-accent-bg: rgba(255, 255, 255, 0.4);
        }

        /* Önemli satır stilleri */
        .row-important {
            background: linear-gradient(90deg, rgba(255, 229, 236, 0.6), rgba(255, 244, 224, 0.6)) !important;
            font-weight: 600;
            box-shadow: inset 0 0 5px rgba(252, 98, 117, 0.1);
            transition: all 0.2s ease-in-out;
        }

        .row-important:hover {
            background: linear-gradient(90deg, rgba(255, 229, 236, 0.8), rgba(255, 244, 224, 0.8)) !important;
            transform: translateY(-2px);
            box-shadow: inset 0 0 8px rgba(252, 98, 117, 0.2), 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .row-important td {
            color: #c0392b;
        }

        /* Badge iyileştirmeleri */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 0.5rem;
        }

        /* Pastel badge'ler */
        .badge-planned {
            background: linear-gradient(135deg, #E3F2FD, #C9F0E8);
            color: #2C5F5F;
            border: 1px solid rgba(195, 240, 232, 0.5);
        }

        .badge-completed {
            background: linear-gradient(135deg, #C9F0E8, #E3F2FD);
            color: #2C5F5F;
            border: 1px solid rgba(201, 240, 232, 0.5);
        }

        .badge-postponed {
            background: linear-gradient(135deg, #FFE5EC, #FFF4E0);
            color: #8B5E34;
            border: 1px solid rgba(255, 229, 236, 0.5);
        }

        .badge-cancelled {
            background: linear-gradient(135deg, #FFE5EC, #E8D5F2);
            color: #8B2C5F;
            border: 1px solid rgba(255, 229, 236, 0.5);
        }

        .badge-type {
            background: linear-gradient(135deg, #E8D5F2, #E3F2FD);
            color: #6B4C8A;
            border: 1px solid rgba(232, 213, 242, 0.5);
        }

        /* Eylem butonları */
        .action-btn-group {
            display: inline-flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.2s ease;
            padding: 0;
        }

        .btn-action i {
            font-size: 0.9rem;
        }

        .btn-action-edit {
            border-color: #E8D5F2;
            color: #6B4C8A;
        }

        .btn-action-edit:hover {
            background: linear-gradient(135deg, #E8D5F2, #FFE5EC);
            border-color: #E8D5F2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(232, 213, 242, 0.4);
        }

        .btn-action-delete {
            border-color: #FFE5EC;
            color: #c0392b;
        }

        .btn-action-delete:hover {
            background: linear-gradient(135deg, #FFE5EC, #FFF4E0);
            border-color: #FFE5EC;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 229, 236, 0.4);
        }

        /* Modern buton stilleri */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            background: transparent;
            font-weight: 600;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        /* Divider çizgisi */
        .filter-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 1.25rem 0;
        }

        /* Alert iyileştirmeleri */
        .alert {
            border-radius: 0.75rem;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(201, 240, 232, 0.9);
            color: #2C5F5F;
        }

        .alert-danger {
            background: rgba(255, 229, 236, 0.9);
            color: #c0392b;
        }

        .alert-warning {
            background: rgba(255, 244, 224, 0.9);
            color: #8B5E34;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <!-- Sayfa Başlığı -->
                <div class="page-header">
                    <h4>
                        <i class="fa-solid fa-calendar-days me-2" style="color: #667EEA;"></i>
                        Etkinlik Listesi
                    </h4>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="filter-card">
                    <div class="filter-header">
                        <i class="fa-solid fa-filter"></i>
                        <h6>Filtreleme Seçenekleri</h6>
                    </div>

                    <form method="GET" action="<?php echo e(route('service.events.index')); ?>">
                        <?php
                            $isAdminOrManager = in_array(Auth::user()->role, ['admin', 'yönetici']);
                        ?>

                        <div class="row g-3">
                            
                            <div class="col-lg-3 col-md-6">
                                <label for="title" class="form-label">
                                    <i class="fa-solid fa-magnifying-glass me-1" style="color: #E8D5F2"></i> Etkinlik
                                    Başlığı
                                </label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="<?php echo e($filters['title'] ?? ''); ?>" placeholder="Etkinlik başlığı girin...">
                            </div>

                            
                            <div class="col-lg-2 col-md-6">
                                <label for="event_type" class="form-label">
                                    <i class="fa-solid fa-list me-1" style="color: #FFE5EC"></i> Etkinlik Tipi
                                </label>
                                <select class="form-select" id="event_type" name="event_type">
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

                            
                            <div class="col-lg-2 col-md-6">
                                <label for="visit_status" class="form-label">
                                    <i class="fa-solid fa-circle-check me-1" style="color: #C9F0E8"></i> Ziyaret Durumu
                                </label>
                                <select class="form-select" id="visit_status" name="visit_status">
                                    <option value="all"
                                        <?php echo e(($filters['visit_status'] ?? 'all') == 'all' ? 'selected' : ''); ?>>
                                        Tümü
                                    </option>
                                    <option value="planlandi"
                                        <?php echo e(($filters['visit_status'] ?? '') == 'planlandi' ? 'selected' : ''); ?>>
                                        Planlandı
                                    </option>
                                    <option value="gerceklesti"
                                        <?php echo e(($filters['visit_status'] ?? '') == 'gerceklesti' ? 'selected' : ''); ?>>
                                        Gerçekleşti
                                    </option>
                                    <option value="ertelendi"
                                        <?php echo e(($filters['visit_status'] ?? '') == 'ertelendi' ? 'selected' : ''); ?>>
                                        Ertelendi
                                    </option>
                                    <option value="iptal"
                                        <?php echo e(($filters['visit_status'] ?? '') == 'iptal' ? 'selected' : ''); ?>>
                                        İptal Edildi
                                    </option>
                                </select>
                            </div>

                            
                            <?php if($isAdminOrManager): ?>
                                <div class="col-lg-2 col-md-6">
                                    <label for="is_important" class="form-label">
                                        <i class="fa-solid fa-star me-1" style="color: #FFF4E0"></i> Önem Durumu
                                    </label>
                                    <select class="form-select" id="is_important" name="is_important">
                                        <option value="all"
                                            <?php echo e(($filters['is_important'] ?? 'all') == 'all' ? 'selected' : ''); ?>>
                                            Tümü
                                        </option>
                                        <option value="yes"
                                            <?php echo e(($filters['is_important'] ?? '') == 'yes' ? 'selected' : ''); ?>>
                                            Önemliler
                                        </option>
                                        <option value="no"
                                            <?php echo e(($filters['is_important'] ?? '') == 'no' ? 'selected' : ''); ?>>
                                            Normal
                                        </option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            
                            <div class="col-lg-<?php echo e($isAdminOrManager ? '1' : '2'); ?> col-md-6">
                                <label for="date_from" class="form-label">
                                    <i class="fa-solid fa-calendar-days me-1" style="color: #E3F2FD"></i> Başlangıç
                                </label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>">
                            </div>

                            <div class="col-lg-<?php echo e($isAdminOrManager ? '1' : '2'); ?> col-md-6">
                                <label for="date_to" class="form-label">
                                    <i class="fa-solid fa-calendar-check me-1" style="color: #E3F2FD"></i> Bitiş
                                </label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>">
                            </div>
                        </div>

                        
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('service.events.index')); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Temizle
                                </a>
                                <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="card list-card">
                    <div class="card-header">
                        <i class="fa-solid fa-list me-2"></i> Etkinlik Listesi
                    </div>

                    <div class="card-body p-0">
                        <?php if($events->isEmpty()): ?>
                            <div class="alert alert-warning m-3" role="alert">
                                <i class="fa-solid fa-inbox fa-2x mb-2 d-block" style="opacity: 0.5;"></i>
                                Filtrelere uygun etkinlik bulunamadı.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-3">Etkinlik Başlığı</th>
                                            <th scope="col">Tipi</th>
                                            <th scope="col">Etkinlik Durumu</th>
                                            <th scope="col">Konum</th>
                                            <th scope="col">Başlangıç</th>
                                            <th scope="col">Bitiş</th>
                                            <th scope="col" class="text-end pe-3">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="<?php echo e($event->is_important ? 'row-important' : ''); ?>">
                                                <td class="ps-3">
                                                    <?php if($event->is_important): ?>
                                                        <i class="fa-solid fa-star text-danger me-1" title="Önemli"></i>
                                                    <?php endif; ?>
                                                    <strong><?php echo e($event->title); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-type">
                                                        <?php echo e($eventTypes[$event->event_type] ?? ucfirst($event->event_type)); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <?php switch($event->visit_status):
                                                        case ('planlandi'): ?>
                                                            <span class="badge badge-planned">⏳ Planlandı</span>
                                                        <?php break; ?>

                                                        <?php case ('gerceklesti'): ?>
                                                            <span class="badge badge-completed">✅ Gerçekleşti</span>
                                                        <?php break; ?>

                                                        <?php case ('ertelendi'): ?>
                                                            <span class="badge badge-postponed">📅 Ertelendi</span>
                                                        <?php break; ?>

                                                        <?php case ('iptal'): ?>
                                                            <span class="badge badge-cancelled">❌ İptal</span>
                                                        <?php break; ?>

                                                        <?php default: ?>
                                                            <span class="text-muted small">Bilinmiyor</span>
                                                    <?php endswitch; ?>

                                                </td>
                                                <td><?php echo e($event->location ?? '-'); ?></td>
                                                <td><?php echo e($event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->format('d.m.Y H:i') : '-'); ?>

                                                </td>
                                                <td><?php echo e($event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->format('d.m.Y H:i') : '-'); ?>

                                                </td>
                                                <td class="text-end pe-3">
                                                    <div class="action-btn-group">
                                                        <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                            <a href="<?php echo e(route('service.events.edit', $event)); ?>"
                                                                class="btn btn-action btn-action-edit" title="Düzenle">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                            <form action="<?php echo e(route('service.events.destroy', $event)); ?>"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit"
                                                                    class="btn btn-action btn-action-delete"
                                                                    title="Sil">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/events/index.blade.php ENDPATH**/ ?>