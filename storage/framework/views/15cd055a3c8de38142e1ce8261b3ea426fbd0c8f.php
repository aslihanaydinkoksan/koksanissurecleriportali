

<?php $__env->startSection('title', 'Rezervasyon Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">

        
        <?php
            $backRoute = '#';
            $planTitle = 'Belirsiz Plan';
            $contextLabel = 'Bağlı Olduğu Plan';

            if ($booking->bookable) {
                if ($booking->bookable_type === 'App\Models\Travel') {
                    $backRoute = route('travels.show', $booking->bookable_id);
                    $planTitle = $booking->bookable->title ?? 'İsimsiz Seyahat';
                    $contextLabel = 'Seyahat';
                } else {
                    $backRoute = route('service.events.show', $booking->bookable_id);
                    $planTitle = $booking->bookable->name ?? 'İsimsiz Etkinlik';
                    $contextLabel = 'Etkinlik';
                }
            }
        ?>

        <div class="row justify-content-center">
            <div class="col-lg-10">

                
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">
                            <?php echo e($contextLabel); ?> YÖNETİMİ
                        </h6>
                        <h2 class="fw-bold text-dark mb-0">Rezervasyon Düzenle</h2>
                        <div class="d-flex align-items-center text-secondary mt-2">
                            <i class="fa-solid fa-layer-group me-2"></i>
                            <span><?php echo e($planTitle); ?></span>
                        </div>
                    </div>
                    <div>
                        <a href="<?php echo e($backRoute); ?>" class="btn btn-light border bg-white shadow-sm text-muted">
                            <i class="fa-solid fa-arrow-left me-1"></i> Vazgeç ve Dön
                        </a>
                    </div>
                </div>

                
                <div class="card border-0 shadow-sm rounded-3">
                    
                    <div class="card-header bg-white border-0 pt-0"></div>
                    <div class="card-body p-4 p-md-5">

                        <form action="<?php echo e(route('bookings.update', $booking)); ?>" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <?php echo $__env->make('bookings._form', ['booking' => $booking], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <hr class="my-4 text-muted opacity-25">

                            
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <a href="<?php echo e($backRoute); ?>" class="btn btn-link text-decoration-none text-muted">
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-primary px-4 py-2 fw-medium">
                                    <i class="fa-regular fa-floppy-disk me-2"></i> Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/bookings/edit.blade.php ENDPATH**/ ?>