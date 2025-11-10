
<?php $__env->startSection('title', 'Departmanı Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">"<?php echo e($department->name); ?>" Düzenle</h4>
                    </div>
                    <div class="card-body px-4">
                        <form action="<?php echo e(route('departments.update', $department)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <?php
                                // Ana sistem departmanlarının slug'ları
$coreSlugs = ['lojistik', 'uretim', 'hizmet'];
                                $isCore = in_array($department->slug, $coreSlugs);
                            ?>
                            <?php echo $__env->make('departments._form', [
                                'department' => $department,
                                'isCore' => $isCore,
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="text-end mt-3">
                                <a href="<?php echo e(route('departments.index')); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4 me-2">İptal</a>
                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/departments/edit.blade.php ENDPATH**/ ?>