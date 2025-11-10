

<?php $__env->startSection('title', 'Seyahat Planı Listesi'); ?>


<style>
    .customer-card {
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #667EEA, #5a6ed0);
        color: white;
        border: none;
        font-weight: 500;
    }

    .filter-card {
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* ---------------------------------------------------- */
    /* YENİ EKLENDİ: MODERN BUTON STİLLERİ (HOVER EFEKTİ) */
    /* ---------------------------------------------------- */
    .btn.btn-modern {
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
    }

    .btn.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Seyahat Planı Listesi</h4>

                    
                    <a href="<?php echo e(route('travels.create')); ?>" class="btn btn-primary-gradient rounded-pill px-4 btn-modern">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Seyahat Planı
                    </a>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                <?php endif; ?>

                
                <div class="filter-card">
                    <form method="GET" action="<?php echo e(route('travels.index')); ?>">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="name" class="form-label">Plan Adı Ara</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Plan adı girin..." value="<?php echo e($filters['name'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="status" class="form-label">Durum</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="all" <?php if($filters['status'] == 'all'): ?> selected <?php endif; ?>>Tümü</option>
                                    <option value="planned" <?php if($filters['status'] == 'planned'): ?> selected <?php endif; ?>>Planlanan
                                    </option>
                                    <option value="completed" <?php if($filters['status'] == 'completed'): ?> selected <?php endif; ?>>Tamamlanan
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="is_important" class="form-label">Önem</label>
                                <select name="is_important" id="is_important" class="form-select">
                                    <option value="all" <?php if($filters['is_important'] == 'all'): ?> selected <?php endif; ?>>Tümü</option>
                                    <option value="yes" <?php if($filters['is_important'] == 'yes'): ?> selected <?php endif; ?>>Önemli Olanlar
                                    </option>
                                    <option value="no" <?php if($filters['is_important'] == 'no'): ?> selected <?php endif; ?>>Önemli
                                        Olmayanlar</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Tarih (Başlangıç)</label>
                                <input type="date" name="date_from" id="date_from" class="form-control"
                                    value="<?php echo e($filters['date_from'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Tarih (Bitiş)</label>
                                <input type="date" name="date_to" id="date_to" class="form-control"
                                    value="<?php echo e($filters['date_to'] ?? ''); ?>">
                            </div>
                        </div>

                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('is-global-manager')): ?>
                            <div class="row g-3 mt-1 align-items-end">
                                <div class="col-md-4">
                                    <label for="user_id" class="form-label">Kullanıcıya Göre Filtrele</label>
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

                        <div class="row mt-3">
                            <div class="col-12 text-end">

                                
                                <a href="<?php echo e(route('travels.index')); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4 btn-modern">Temizle</a>

                                
                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4 ms-2 btn-modern">
                                    <i class="fa-solid fa-filter me-1"></i> Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                
                <div class="customer-card shadow-sm">
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Önemli</th>
                                        <th>Seyahat Adı</th>
                                        <th>Oluşturan</th>
                                        <th>Başlangıç Tarihi</th>
                                        <th>Bitiş Tarihi</th>
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
                                                    <span class="badge bg-warning text-dark">Planlandı</span>
                                                <?php else: ?>
                                                    <span class="badge bg-success">Tamamlandı</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">

                                                
                                                <a href="<?php echo e(route('travels.show', $travel)); ?>"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-modern">
                                                    <i class="fa-solid fa-eye me-1"></i> Detay
                                                </a>

                                                
                                                <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                                    
                                                    <a href="<?php echo e(route('travels.edit', $travel)); ?>"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-modern">
                                                        <i class="fa-solid fa-pen me-1"></i> Düzenle
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                Filtrelerinize uyan kayıtlı seyahat planı bulunamadı.
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