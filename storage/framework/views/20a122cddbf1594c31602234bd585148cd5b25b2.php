
<?php $__env->startSection('title', 'Sevkiyat Listesi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- General Tasks Sayfasından Alınan UI Stilleri --- */
        .page-header-gradient {
            background: linear-gradient(135deg, #0f4c75 0%, #3282b8 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(15, 76, 117, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header-gradient::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .page-header-gradient h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-header-gradient .icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .page-header-gradient .icon-wrapper i {
            font-size: 1.8rem;
            color: white;
        }

        .page-header-gradient .stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            position: relative;
            z-index: 1;
        }

        .page-header-gradient .stat-item {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
        }

        .page-header-gradient .stat-item strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        /* Modern Kart Yapısı */
        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        /* Modern Butonlar */
        .modern-btn {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .modern-btn-sm {
            padding: 0.3rem 0.7rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        .modern-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modern-btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .modern-btn-view {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }

        .modern-btn-view:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
        }

        .modern-btn-edit {
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            color: white;
        }

        .modern-btn-edit:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
        }

        .modern-btn-danger {
            background: linear-gradient(135deg, #fc8181 0%, #e53e3e 100%);
            color: white;
        }

        .modern-btn-danger:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(229, 62, 62, 0.4);
        }

        .modern-btn-export {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .modern-btn-export:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
        }

        .modern-btn-secondary {
            background: #e2e8f0;
            color: #4a5568;
        }

        .modern-btn-secondary:hover {
            background: #cbd5e0;
            color: #2d3748;
        }

        /* Modern Görüntüle Butonu */
        .modern-btn-view {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(66, 153, 225, 0.2);
        }

        .modern-btn-view:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
            text-decoration: none;
        }

        .modern-btn-view:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(66, 153, 225, 0.3);
        }

        /* Küçük boyut */
        .modern-btn-view.modern-btn-sm {
            padding: 0.3rem 0.7rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }

        /* İkon ile birlikte kullanım */
        .modern-btn-view i {
            font-size: 0.9em;
        }

        /* Disabled durumu */
        .modern-btn-view:disabled,
        .modern-btn-view.disabled {
            background: #cbd5e0;
            color: #a0aec0;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .modern-btn-view:disabled:hover,
        .modern-btn-view.disabled:hover {
            background: #cbd5e0;
            transform: none;
            box-shadow: none;
        }

        /* Filtre Bölümü Stilleri */
        .filters-container {
            padding: 1.5rem;
            background: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .form-control,
        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Tablo İyileştirmeleri */
        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 2px solid #edf2f7;
        }

        .table tbody td {
            padding: 1rem;
            color: #2d3748;
            font-weight: 500;
            border-bottom: 1px solid #edf2f7;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(247, 250, 252, 0.5);
        }

        /* Önemli Satır Vurgusu */
        .row-important {
            background-color: rgba(255, 235, 238, 0.4) !important;
            border-left: 4px solid #e53e3e;
        }

        .row-important td {
            color: #c53030 !important;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        /* Animasyon */
        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-4 py-4">

        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3 border-0 mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3 border-0 mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo e(session('error')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="page-header-gradient fade-in">
            <div class="header-content">
                <h4>
                    <div class="icon-wrapper">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div>
                        Sevkiyat Yönetimi
                        <small
                            style="display: block; font-size: 0.9rem; font-weight: 400; opacity: 0.9; margin-top: 0.25rem;">
                            İthalat ve İhracat süreç takibi
                        </small>
                    </div>
                </h4>
                <div class="stats">
                    <div class="stat-item">
                        <strong><?php echo e($shipments->count()); ?></strong>
                        Listelenen Sevkiyat
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="modern-card fade-in" style="animation-delay: 0.1s;">
            <div class="filters-container">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 text-secondary" style="font-weight: 700; font-size: 1rem;">
                        <i class="fas fa-filter me-2 text-primary"></i>Filtreleme Seçenekleri
                    </h5>
                    <button class="modern-btn modern-btn-secondary modern-btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fas fa-chevron-down"></i> Göster/Gizle
                    </button>
                </div>

                <div class="collapse <?php echo e(request()->hasAny(['shipment_type', 'vehicle_type', 'cargo_content', 'date_from']) ? 'show' : ''); ?>"
                    id="filterCollapse">
                    <form method="GET" action="<?php echo e(route('products.list')); ?>">
                        <div class="row g-3">
                            
                            <div class="col-md-3">
                                <label for="shipment_type" class="form-label">Sevkiyat Türü</label>
                                <select class="form-select" id="shipment_type" name="shipment_type">
                                    <option value="all"
                                        <?php echo e(($filters['shipment_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü</option>
                                    <option value="import"
                                        <?php echo e(($filters['shipment_type'] ?? '') == 'import' ? 'selected' : ''); ?>>İthalat
                                    </option>
                                    <option value="export"
                                        <?php echo e(($filters['shipment_type'] ?? '') == 'export' ? 'selected' : ''); ?>>İhracat
                                    </option>
                                </select>
                            </div>

                            
                            <div class="col-md-3">
                                <label for="vehicle_type" class="form-label">Araç Tipi</label>
                                <select class="form-select" id="vehicle_type" name="vehicle_type">
                                    <option value="all"
                                        <?php echo e(($filters['vehicle_type'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü</option>
                                    <?php $__currentLoopData = $vehicleTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($type); ?>"
                                            <?php echo e(($filters['vehicle_type'] ?? '') == $type ? 'selected' : ''); ?>>
                                            <?php echo e(ucfirst($type)); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="col-md-6">
                                <label for="cargo_content" class="form-label">Kargo İçeriği Ara</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i
                                            class="fas fa-search text-muted"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="cargo_content"
                                        name="cargo_content" value="<?php echo e($filters['cargo_content'] ?? ''); ?>"
                                        placeholder="İçerik adı girin...">
                                </div>
                            </div>

                            
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Başlangıç Tarihi</label>
                                <input type="date" class="form-control" id="date_from" name="date_from"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Bitiş Tarihi</label>
                                <input type="date" class="form-control" id="date_to" name="date_to"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>">
                            </div>

                            
                            <?php if(in_array(Auth::user()->role, ['admin', 'yönetici'])): ?>
                                <div class="col-md-3">
                                    <label for="is_important" class="form-label text-danger">
                                        <i class="fas fa-bell me-1"></i> Önem Durumu
                                    </label>
                                    <select class="form-select" id="is_important" name="is_important">
                                        <option value="all"
                                            <?php echo e(($filters['is_important'] ?? 'all') == 'all' ? 'selected' : ''); ?>>Tümü
                                        </option>
                                        <option value="yes"
                                            <?php echo e(($filters['is_important'] ?? '') == 'yes' ? 'selected' : ''); ?>>Sadece
                                            Önemliler</option>
                                        <option value="no"
                                            <?php echo e(($filters['is_important'] ?? '') == 'no' ? 'selected' : ''); ?>>Önemli
                                            Olmayanlar</option>
                                    </select>
                                </div>
                            <?php endif; ?>

                            
                            <?php
                                $buttonCol = in_array(Auth::user()->role, ['admin', 'yönetici'])
                                    ? 'col-md-3'
                                    : 'col-md-6';
                            ?>
                            <div class="<?php echo e($buttonCol); ?> d-flex align-items-end justify-content-end gap-2">
                                <a href="<?php echo e(route('products.list')); ?>" class="modern-btn modern-btn-secondary">
                                    <i class="fas fa-times"></i> Temizle
                                </a>
                                <button type="submit" class="modern-btn modern-btn-primary">
                                    <i class="fas fa-filter"></i> Filtrele
                                </button>
                                <button type="submit" formaction="<?php echo e(route('shipments.export_list')); ?>"
                                    class="modern-btn modern-btn-export">
                                    <i class="fas fa-file-excel"></i> Listeyi Excel'e Aktar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modern-card fade-in" style="animation-delay: 0.2s;">
            <?php if($shipments->isEmpty()): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h5>Kayıt Bulunamadı</h5>
                    <p class="text-muted">Seçilen filtrelere uygun sevkiyat kaydı bulunmamaktadır.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            
                            <tr class="text-center align-middle">
                                <th scope="col">Sevkiyat Türü</th>
                                <th scope="col">Kargo İçeriği</th>
                                <th scope="col">Araç Tipi</th>
                                <th scope="col">Kalkış</th>
                                <th scope="col">Varış</th>
                                <th scope="col">Çıkış Tarihi</th>
                                <th scope="col">Tahmini Varış</th>
                                <th scope="col" class="text-end pe-4" style="width: 1%; white-space: nowrap;">İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $shipments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <tr class="text-center align-middle <?php echo e($shipment->is_important ? 'row-important' : ''); ?>">

                                    
                                    <td>
                                        <?php if($shipment->shipment_type == 'import'): ?>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                                                <i class="fas fa-arrow-down me-1"></i> İthalat
                                            </span>
                                        <?php elseif($shipment->shipment_type == 'export'): ?>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">
                                                <i class="fas fa-arrow-up me-1"></i> İhracat
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="fw-bold"><?php echo e($shipment->kargo_icerigi); ?></td>

                                    
                                    <td>
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <div class="icon-wrapper bg-light rounded-circle p-2 d-flex justify-content-center align-items-center"
                                                style="width: 32px; height: 32px;">
                                                <?php if($shipment->arac_tipi == 'gemi'): ?>
                                                    <i class="fas fa-ship text-primary small"></i>
                                                <?php elseif($shipment->arac_tipi == 'ucak'): ?>
                                                    <i class="fas fa-plane text-info small"></i>
                                                <?php elseif($shipment->arac_tipi == 'tir'): ?>
                                                    <i class="fas fa-truck text-warning small"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-truck-loading text-secondary small"></i>
                                                <?php endif; ?>
                                            </div>
                                            <?php echo e(ucfirst($shipment->arac_tipi)); ?>

                                        </div>
                                    </td>

                                    
                                    <td><?php echo e($shipment->arac_tipi == 'gemi' ? $shipment->kalkis_limani : $shipment->kalkis_noktasi); ?>

                                    </td>

                                    
                                    <td><?php echo e($shipment->arac_tipi == 'gemi' ? $shipment->varis_limani : $shipment->varis_noktasi); ?>

                                    </td>

                                    
                                    <td>
                                        <?php if($shipment->cikis_tarihi): ?>
                                            <?php echo e(\Carbon\Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y')); ?>

                                            <br>
                                            <span class="text-muted small">Saat:
                                                <?php echo e(\Carbon\Carbon::parse($shipment->cikis_tarihi)->format('H:i')); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td>
                                        <?php if($shipment->tahmini_varis_tarihi): ?>
                                            <?php echo e(\Carbon\Carbon::parse($shipment->tahmini_varis_tarihi)->format('d.m.Y')); ?>

                                            <br>
                                            <span class="text-muted small">Saat:
                                                <?php echo e(\Carbon\Carbon::parse($shipment->tahmini_varis_tarihi)->format('H:i')); ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <?php if(!in_array(Auth::user()->role, ['izleyici'])): ?>
                                                
                                                <a href="<?php echo e(route('shipments.edit', $shipment)); ?>"
                                                    class="modern-btn modern-btn-edit modern-btn-sm d-inline-flex align-items-center justify-content-center"
                                                    style="width: 135px;" title="Düzenle">
                                                    <i class="fas fa-edit me-2"></i> Düzenle
                                                </a>

                                                
                                                <form action="<?php echo e(route('shipments.destroy', $shipment)); ?>" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Bu sevkiyatı silmek istediğinizden emin misiniz?');">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="modern-btn modern-btn-danger modern-btn-sm d-inline-flex align-items-center justify-content-center"
                                                        style="width: 135px;" title="Sil">
                                                        <i class="fas fa-trash me-2"></i> Sil
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            
                                            <a href="<?php echo e(route('shipments.export', $shipment->id)); ?>"
                                                class="modern-btn modern-btn-export modern-btn-sm d-inline-flex align-items-center justify-content-center"
                                                style="width: 135px;" title="Excel İndir">
                                                <i class="fas fa-file-excel me-2"></i> Excel
                                            </a>

                                            
                                            <a href="<?php echo e(route('shipments.show', $shipment->id)); ?>"
                                                class="modern-btn modern-btn-view modern-btn-sm d-inline-flex align-items-center justify-content-center"
                                                style="min-width: 100px;">
                                                <i class="fas fa-eye me-2"></i> Görüntüle
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterCollapse = document.getElementById('filterCollapse');
            var filterButtonIcon = document.querySelector('.modern-btn[data-bs-target="#filterCollapse"] i');

            if (filterCollapse && filterButtonIcon) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-up');
                    filterButtonIcon.classList.add('fa-chevron-down');
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/shipments/list.blade.php ENDPATH**/ ?>