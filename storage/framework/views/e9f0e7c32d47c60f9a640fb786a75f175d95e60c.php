

<?php $__env->startSection('title', 'Seyahat Planı Listesi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    #fde2ff,
                    #d9fcf7,
                    #fff0d9);
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

        /* Filtre kartı - daha modern ve düzenli */
        .filter-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.5);
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
        .customer-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
        }

        /* Modern buton stilleri */
        .btn-modern {
            font-size: 0.9rem;
            padding: 0.5rem 1.25rem;
            transition: all 0.2s ease;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg,
                    #667EEA, #F093FB, #4FD1C5, #FBD38D);
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

        /* Filtre başlığı */
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

        /* Tablo iyileştirmeleri */
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
            transform: scale(1.005);
        }

        /* Sayfa başlığı */
        .page-header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 1.25rem 1.5rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h4 {
            margin: 0;
            color: #2d3748;
            font-weight: 700;
        }

        /* Divider çizgisi */
        .filter-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 1.25rem 0;
        }

        /* Badge iyileştirmeleri */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            font-size: 0.8rem;
            border-radius: 0.5rem;
        }

        /* Pastel durum badge'leri */
        .badge-planned {
            background: linear-gradient(135deg, #FFE5EC, #FFF4E0);
            color: #8B5E34;
            border: 1px solid rgba(255, 229, 236, 0.5);
        }

        .badge-completed {
            background: linear-gradient(135deg, #C9F0E8, #E3F2FD);
            color: #2C5F5F;
            border: 1px solid rgba(201, 240, 232, 0.5);
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

        .btn-action-view {
            border-color: #E3F2FD;
            color: #2C5F5F;
        }

        .btn-action-view:hover {
            background: linear-gradient(135deg, #E3F2FD, #C9F0E8);
            border-color: #C9F0E8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(195, 240, 232, 0.4);
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
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <!-- Sayfa Başlığı -->
                <div class="page-header">
                    <h4>
                        <i class="fa-solid fa-plane-departure me-2" style="color: #667EEA;"></i>
                        Seyahat Planı Listesi
                    </h4>
                    <a href="<?php echo e(route('travels.create')); ?>" class="btn btn-animated-gradient rounded-pill px-4 btn-modern">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Seyahat Planı
                    </a>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filtre Kartı -->
                <div class="filter-card">
                    <div class="filter-header">
                        <i class="fa-solid fa-filter"></i>
                        <h6>Filtreleme Seçenekleri</h6>
                    </div>

                    <form method="GET" action="<?php echo e(route('travels.index')); ?>" autocomplete="off">
                        <!-- İlk Satır: Temel Filtreler -->
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <label for="name" class="form-label">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Plan Adı Ara
                                </label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Plan adı girin..." value="<?php echo e($filters['name'] ?? ''); ?>" autocomplete="off">
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="status" class="form-label">
                                    <i class="fa-solid fa-circle-check me-1"></i> Durum
                                </label>
                                <select name="status" id="status" class="form-select">
                                    <option value="all" <?php if($filters['status'] == 'all'): ?> selected <?php endif; ?>>Tümü</option>
                                    <option value="planned" <?php if($filters['status'] == 'planned'): ?> selected <?php endif; ?>>Planlanan
                                    </option>
                                    <option value="completed" <?php if($filters['status'] == 'completed'): ?> selected <?php endif; ?>>Tamamlanan
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="is_important" class="form-label">
                                    <i class="fa-solid fa-star me-1"></i> Önem
                                </label>
                                <select name="is_important" id="is_important" class="form-select">
                                    <option value="all" <?php if($filters['is_important'] == 'all'): ?> selected <?php endif; ?>>Tümü</option>
                                    <option value="yes" <?php if($filters['is_important'] == 'yes'): ?> selected <?php endif; ?>>Önemli</option>
                                    <option value="no" <?php if($filters['is_important'] == 'no'): ?> selected <?php endif; ?>>Normal</option>
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="date_from" class="form-label">
                                    <i class="fa-solid fa-calendar-days me-1"></i> Başlangıç
                                </label>
                                <input type="date" name="date_from" id="date_from" class="form-control"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>" autocomplete="off">
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="date_to" class="form-label">
                                    <i class="fa-solid fa-calendar-check me-1"></i> Bitiş
                                </label>
                                <input type="date" name="date_to" id="date_to" class="form-control"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>" autocomplete="off">
                            </div>
                        </div>

                        <!-- Global Manager için kullanıcı filtresi -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('is-global-manager')): ?>
                            <div class="filter-divider"></div>
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-6">
                                    <label for="user_id" class="form-label">
                                        <i class="fa-solid fa-user me-1"></i> Kullanıcı
                                    </label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="all">Tüm Kullanıcılar</option>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>"
                                                <?php if($filters['user_id'] == $user->id): ?> selected <?php endif; ?>>
                                                <?php echo e($user->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Butonlar -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('travels.index')); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4 btn-modern">
                                    <i class="fa-solid fa-rotate-right me-1"></i> Temizle
                                </a>
                                <button type="submit" class="btn btn-animated-gradient rounded-pill px-4 btn-modern">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tablo Kartı -->
                <div class="customer-card shadow-sm">
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60" class="text-center">Önemli</th>
                                        <th>Seyahat Adı</th>
                                        <th>Oluşturan</th>
                                        <th>Başlangıç</th>
                                        <th>Bitiş</th>
                                        <th>Durum</th>
                                        <th class="text-end">Eylemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $travels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $travel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="text-center">
                                                <?php if($travel->is_important): ?>
                                                    <i class="fa-solid fa-star text-danger" title="Önemli"></i>
                                                <?php else: ?>
                                                    <i class="fa-regular fa-star text-muted"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?php echo e($travel->name); ?></strong></td>
                                            <td><?php echo e($travel->user->name ?? 'Bilinmiyor'); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($travel->start_date)->format('d/m/Y')); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($travel->end_date)->format('d/m/Y')); ?></td>
                                            <td>
                                                <?php if($travel->status == 'planned'): ?>
                                                    <span class="badge badge-planned">Planlandı</span>
                                                <?php else: ?>
                                                    <span class="badge badge-completed">Tamamlandı</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="action-btn-group">
                                                    <a href="<?php echo e(route('travels.show', $travel)); ?>"
                                                        class="btn btn-action btn-action-view" title="Detay Görüntüle">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                                        <a href="<?php echo e(route('travels.edit', $travel)); ?>"
                                                            class="btn btn-action btn-action-edit" title="Düzenle">
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="fa-solid fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                                <p class="mb-0">Filtrelerinize uyan kayıtlı seyahat planı bulunamadı.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($travels->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/travels/index.blade.php ENDPATH**/ ?>