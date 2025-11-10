

<?php $__env->startSection('title', 'Rezervasyon Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Rezervasyon Düzenle</h4>
                            <span class="text-muted">
                                <a href="<?php echo e(route('travels.show', $booking->travel)); ?>">&larr; "<?php echo e($booking->travel->name); ?>"
                                    planına geri dön</a>
                            </span>
                        </div>
                    </div>
                    <div class="card-body px-4">
                        
                        <form action="<?php echo e(route('bookings.update', $booking)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <?php echo $__env->make('bookings._form', ['booking' => $booking], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <div class="text-end mt-3">
                                <a href="<?php echo e(route('travels.show', $booking->travel)); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4 me-2">
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    <i class="fa-solid fa-save me-1"></i> Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/bookings/edit.blade.php ENDPATH**/ ?>