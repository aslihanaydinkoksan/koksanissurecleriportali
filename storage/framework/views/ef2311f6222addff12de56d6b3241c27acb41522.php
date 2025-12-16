
<?php $__env->startSection('title', 'Bakım Planları'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- ANA TEMEL STİLLER (Üretim Modülü ile Eşitlendi) --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            /* Üretim modülündeki gradient */
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
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

        .modern-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Başlık ve Kart Yapıları */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* Alert Stilleri */
        .alert {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: rgba(40, 199, 111, 0.15);
            color: #1e7e34;
        }

        .alert-danger {
            background: rgba(235, 87, 87, 0.15);
            color: #c82333;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
            color: #856404;
        }

        /* Filtre Buton ve Kartları */
        .btn-filter-toggle {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            color: #667eea;
            font-weight: 600;
            padding: 1rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .btn-filter-toggle:hover,
        .btn-filter-toggle[aria-expanded="true"] {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-apply-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-apply-filter:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-clear-filter {
            background: white;
            border: 2px solid #e9ecef;
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-clear-filter:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        /* Ana Liste Kartı ve Tablo */
        .list-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .list-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            border: none;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(102, 126, 234, 0.05);
        }

        .table thead th {
            color: #495057;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            padding: 1.25rem 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.08) !important;
            transform: scale(1.005);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            z-index: 10;
            position: relative;
        }

        .table td {
            vertical-align: middle;
            padding: 1.25rem 1rem;
            color: #495057;
            font-size: 0.95rem;
        }

        /* Aksiyon Butonları */
        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            margin: 0 0.25rem;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-action.btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        /* Yetkisiz buton için gri tonlama */
        .btn-action.btn-danger-disabled {
            background: linear-gradient(135deg, #a4a6a8 0%, #838587 100%);
            color: white;
            cursor: not-allowed;
        }

        .btn-action.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* --- BAKIM MODÜLÜNE ÖZEL STİLLER --- */
        /* Öncelik Göstergesi (Priority Dot) */
        .priority-dot {
            height: 10px;
            width: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .priority-critical {
            background-color: #e74c3c;
            box-shadow: 0 0 5px #e74c3c;
        }

        .priority-high {
            background-color: #e67e22;
        }

        .priority-normal {
            background-color: #3498db;
        }

        .priority-low {
            background-color: #95a5a6;
        }

        /* Pagination */
        .card-footer {
            background: rgba(255, 255, 255, 0.5);
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
            padding: 1.5rem 2rem !important;
        }

        /* Yeşil Excel Butonu */
        .btn-export-global {
            background: linear-gradient(135deg, #28c76f 0%, #1e7e34 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-export-global:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid modern-container">
        <div class="row justify-content-center">
            <div class="col-12">

                
                <div class="page-header d-flex justify-content-between align-items-center">
                    <div>
                        <h1><i class="fas fa-tools"></i> Bakım Planları</h1>
                        <p>Bakım ve arıza planlarını görüntüleyin, yönetin ve takip edin.</p>
                    </div>
                    <div>
                        <a href="<?php echo e(route('maintenance.export_list')); ?>" class="btn btn-export-global shadow-sm">
                            <i class="fas fa-file-excel me-2"></i>Listeyi Excel'e Aktar
                        </a>
                        <a href="<?php echo e(route('maintenance.create')); ?>" class="btn btn-apply-filter shadow-sm">
                            <i class="fas fa-plus me-2"></i> Yeni Bakım Planı
                        </a>
                    </div>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                
                <div class="mb-4">
                    <div class="d-grid">
                        <button class="btn btn-filter-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-sliders-h me-2"></i> Filtre Seçenekleri
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card">
                            <form method="GET" action="<?php echo e(route('maintenance.index')); ?>">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="fas fa-search me-1"></i>Başlık Ara</label>
                                        <input type="text" class="form-control" name="search"
                                            placeholder="Plan başlığı..." value="<?php echo e(request('search')); ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="fas fa-tasks me-1"></i>Durum</label>
                                        <select class="form-select" name="status">
                                            <option value="">Tümü</option>
                                            <option value="pending">Bekliyor</option>
                                            <option value="in_progress">İşlemde</option>
                                            <option value="completed">Tamamlandı</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label"><i class="fas fa-calendar-alt me-1"></i>Tarih</label>
                                        <input type="date" class="form-control" name="date"
                                            value="<?php echo e(request('date')); ?>">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="<?php echo e(route('maintenance.index')); ?>" class="btn btn-clear-filter">
                                        <i class="fas fa-times me-2"></i>Temizle
                                    </a>
                                    <button type="submit" class="btn btn-apply-filter">
                                        <i class="fas fa-check me-2"></i>Filtrele
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                
                <div class="card list-card">
                    <div class="card-header">
                        <div>
                            <i class="fas fa-list me-2"></i>
                            <span>Bakım Listesi</span>
                        </div>
                        <span class="badge bg-white text-primary rounded-pill fs-6"><?php echo e($plans->count()); ?> Kayıt</span>
                    </div>

                    <div class="card-body p-0">
                        <?php if($plans->isEmpty()): ?>
                            <div class="alert alert-warning m-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Henüz kayıtlı bir bakım planı bulunamadı.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-4">Durum</th>
                                            <th scope="col">Öncelik</th>
                                            <th scope="col">Başlık</th>
                                            <th scope="col">Tür </th>
                                            <th scope="col">İlgili Varlık</th>
                                            <th scope="col">Tarih</th>
                                            <th scope="col">Planlayan</th>
                                            <th scope="col" class="text-end pe-4">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-4">
                                                    
                                                    <?php $statusBadge = $plan->status_badge; ?>
                                                    <span
                                                        class="badge rounded-pill <?php echo e($statusBadge['class']); ?> shadow-sm px-3 py-2">
                                                        <?php echo e($statusBadge['text']); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    
                                                    <?php $priorityBadge = $plan->priority_badge; ?>
                                                    <span
                                                        class="badge rounded-pill <?php echo e($priorityBadge['class']); ?> shadow-sm px-3 py-2">
                                                        <?php echo e($priorityBadge['text']); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <strong class="text-dark"><?php echo e($plan->title); ?></strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-tag me-1 text-secondary"></i>
                                                        <?php echo e($plan->type ? $plan->type->name : '-'); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="fas fa-microchip text-primary me-2 opacity-50"></i>
                                                        <span
                                                            class="text-dark"><?php echo e($plan->asset ? $plan->asset->name : '-'); ?></span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <small class="text-muted">
                                                            <i class="fas fa-play me-1 text-success"></i>
                                                            <?php echo e($plan->planned_start_date ? $plan->planned_start_date->format('d.m.Y H:i') : '-'); ?>

                                                        </small>
                                                        <small class="text-muted">
                                                            <i class="fas fa-stop me-1 text-danger"></i>
                                                            <?php echo e($plan->planned_end_date ? $plan->planned_end_date->format('d.m.Y H:i') : '-'); ?>

                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if($plan->user): ?>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2 shadow-sm"
                                                                style="width: 32px; height: 32px; font-size: 0.85rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                                <?php echo e(substr($plan->user->name, 0, 1)); ?>

                                                            </div>
                                                            <span
                                                                class="small fw-semibold text-dark"><?php echo e($plan->user->name); ?></span>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Atanmamış</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group" role="group">

                                                        
                                                        <a href="<?php echo e(route('maintenance.show', $plan->id)); ?>"
                                                            class="btn btn-action btn-info" title="Plan Detayları">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        
                                                        
                                                        <?php if($plan->status === 'completed' && Auth::user()->cannot('approve', $plan)): ?>
                                                            <button type="button"
                                                                class="btn btn-action btn-danger-disabled"
                                                                style="background: #a4a6a8; cursor: not-allowed;"
                                                                onclick="alert('İşlem Engellendi!\n\nBu plan tamamlanmıştır. Değişiklik yapmak için yöneticinizle görüşün.')"
                                                                title="Tamamlandığı için kilitli">
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $plan)): ?>
                                                                <a href="<?php echo e(route('maintenance.edit', $plan->id)); ?>"
                                                                    class="btn btn-action btn-warning text-white"
                                                                    title="Düzenle">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <button type="button"
                                                                    class="btn btn-action btn-danger-disabled"
                                                                    style="background: #a4a6a8; cursor: not-allowed;"
                                                                    onclick="alert('Bu işlemi yapmaya yetkiniz yok!\nSadece Admin, Yönetici veya Kaydı Oluşturan kişi düzenleyebilir.')"
                                                                    title="Yetkiniz Yok">
                                                                    <i class="fas fa-ban"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        
                                                        
                                                        <?php if($plan->status === 'completed'): ?>
                                                            <button type="button"
                                                                class="btn btn-action btn-danger-disabled"
                                                                style="background: #a4a6a8; cursor: not-allowed;"
                                                                onclick="alert('İşlem Engellendi!\n\nTamamlanmış bakım planları arşiv güvenliği nedeniyle silinemez.')"
                                                                title="Tamamlandığı için silinemez">
                                                                <i class="fas fa-lock"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            
                                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $plan)): ?>
                                                                <form action="<?php echo e(route('maintenance.destroy', $plan->id)); ?>"
                                                                    method="POST" class="d-inline"
                                                                    onsubmit="return confirm('Bu planı silmek istediğinize emin misiniz?');">
                                                                    <?php echo csrf_field(); ?>
                                                                    <?php echo method_field('DELETE'); ?>
                                                                    <button type="submit" class="btn btn-action btn-danger"
                                                                        title="Sil">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            <?php else: ?>
                                                                <button type="button"
                                                                    class="btn btn-action btn-danger-disabled"
                                                                    style="background: #a4a6a8; cursor: not-allowed;"
                                                                    onclick="alert('Bu işlemi yapmaya yetkiniz yok!\nSadece Admin, Yönetici veya Kaydı Oluşturan kişi silebilir.')"
                                                                    title="Yetkiniz Yok">
                                                                    <i class="fas fa-trash"></i> 
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            
                            <?php if(method_exists($plans, 'links')): ?>
                                <div class="card-footer">
                                    <?php echo e($plans->links('pagination::bootstrap-5')); ?>

                                </div>
                            <?php endif; ?>
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
            // Filtre toggle animasyonu
            const filterCollapse = document.getElementById('filterCollapse');
            const filterButton = document.querySelector('.btn-filter-toggle');
            if (filterButton) {
                const chevronIcon = filterButton.querySelector('.fa-chevron-down');

                if (filterCollapse) {
                    filterCollapse.addEventListener('show.bs.collapse', function() {
                        chevronIcon.style.transform = 'rotate(180deg)';
                    });

                    filterCollapse.addEventListener('hide.bs.collapse', function() {
                        chevronIcon.style.transform = 'rotate(0deg)';
                    });
                }
            }

            // Alert otomatik kapanma
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Tablo satırı animasyonu
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animation = `slideDown 0.3s ease ${index * 0.05}s forwards`;
                row.style.opacity = '0';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/maintenance/index.blade.php ENDPATH**/ ?>