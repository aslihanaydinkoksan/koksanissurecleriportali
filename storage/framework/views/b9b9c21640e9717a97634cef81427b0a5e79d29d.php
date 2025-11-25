
<?php $__env->startSection('title', 'Departman Yönetimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .customer-card {
            /* Customer'daki stili kullanalım */
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
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Departman Yönetimi</h4>
                    <a href="<?php echo e(route('departments.create')); ?>" class="btn btn-primary-gradient rounded-pill px-4">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Departman Ekle
                    </a>
                </div>

                <?php if(session('success')): ?>
                    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                    <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <div class="customer-card shadow-sm">
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Departman Adı</th>
                                        <th scope="col">Kısa Kod (slug)</th>
                                        <th scope="col">Kullanıcı Sayısı</th>
                                        <th scope="col" class="text-end">Eylemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($department->id); ?></td>
                                            <td><strong><?php echo e($department->name); ?></strong></td>
                                            <td><code><?php echo e($department->slug); ?></code></td>
                                            <td><?php echo e($department->users_count); ?></td>
                                            <td class="text-end">
                                                <a href="<?php echo e(route('departments.edit', $department)); ?>"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                                </a>

                                                <form action="<?php echo e(route('departments.destroy', $department)); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bu departmanı silmek istediğinizden emin misiniz? Bu departmandaki kullanıcılar birimsiz kalacaktır (Eğer izin verilmişse).');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                        
                                                        <?php if(in_array($department->slug, ['lojistik', 'uretim', 'hizmet'])): ?> disabled 
                                                            title="Ana departmanlar sistemden silinemez." <?php endif; ?>>
                                                        <i class="fa-solid fa-trash-alt me-1"></i> Sil
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Kayıtlı departman bulunamadı.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/departments/index.blade.php ENDPATH**/ ?>