

<?php $__env->startSection('title', 'Rol Yönetimi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">🎭 Rol Listesi</h5>
                <a href="<?php echo e(route('roles.create')); ?>" class="btn btn-primary btn-sm rounded-pill px-3">
                    + Yeni Rol Ekle
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Rol Adı</th>
                            <th>Slug (Kod)</th>
                            <th>Kullanıcı Sayısı</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo e($role->name); ?></td>
                                <td><span class="badge bg-secondary text-light"><?php echo e($role->slug); ?></span></td>
                                <td><?php echo e($role->users()->count()); ?> Kişi</td>
                                <td class="text-end pe-4">
                                    
                                    <?php if($role->slug !== 'admin'): ?>
                                        <a href="<?php echo e(route('roles.edit', $role)); ?>"
                                            class="btn btn-sm btn-outline-primary me-1">
                                            ✏️ Düzenle
                                        </a>

                                        
                                        <?php if(!in_array($role->slug, ['admin', 'kullanici'])): ?>
                                            <form action="<?php echo e(route('roles.destroy', $role)); ?>" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bu rolü silmek istediğinize emin misiniz?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑️
                                                    Sil</button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic text-small">Sistem Rolü</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/roles/index.blade.php ENDPATH**/ ?>