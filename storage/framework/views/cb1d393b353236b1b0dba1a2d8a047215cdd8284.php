<?php $__env->startSection('title', 'Yeni Rol Ekle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem;">
                    <div class="card-header bg-transparent border-0 text-center pt-4 pb-2">
                        <h4 class="fw-bold text-secondary">Yeni Rol Tanımla</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('roles.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold">Rol Adı</label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Örn: Muhasebe Müdürü" required>
                                <small class="text-muted">Slug (kod) otomatik oluşturulacaktır.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">Kaydet</button>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo e(route('roles.index')); ?>" class="text-decoration-none text-muted">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/roles/create.blade.php ENDPATH**/ ?>