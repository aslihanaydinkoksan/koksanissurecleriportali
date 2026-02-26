<?php if(session('success') || session('error') || $errors->any()): ?>
    <div class="px-4 mt-3">
        <?php if(session('success')): ?>
            <div class="alert alert-success d-flex align-items-center mb-0"><i class="fa-solid fa-circle-check me-3 fs-4"></i>
                <div><?php echo e(session('success')); ?></div>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger d-flex align-items-center mb-0"><i class="fa-solid fa-circle-xmark me-3 fs-4"></i>
                <div><?php echo e(session('error')); ?></div>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger mb-0">
                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Kayıt eklenirken bir hata oluştu:</strong>
                <ul class="mb-0 mt-2">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/partials/alerts.blade.php ENDPATH**/ ?>