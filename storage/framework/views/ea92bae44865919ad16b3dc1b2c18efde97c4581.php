

<?php $__env->startSection('title', 'Sistem Aktivite Logları'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card shadow-sm">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">Sistem Aktivite Logları</h4>
                        <small>Projedeki son 50 aktivite gösteriliyor.</small>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kim (Causer)</th>
                                        <th>Ne Yaptı (Description)</th>
                                        <th>Neyi Etkiledi (Subject)</th>
                                        <th>Zaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                
                                                <?php echo e($activity->causer->name ?? 'Sistem/Silinmiş Kullanıcı'); ?>

                                            </td>
                                            <td>
                                                
                                                <strong><?php echo e($activity->description); ?></strong>
                                            </td>
                                            <td>
                                                
                                                <?php if($activity->subject): ?>
                                                    <?php echo e($activity->subject_type); ?> (ID: <?php echo e($activity->subject_id); ?>)
                                                <?php else: ?>
                                                    
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                
                                                <?php echo e($activity->created_at->tz('Europe/Istanbul')->format('d/m/Y H:i:s')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Görüntülenecek hiç log bulunamadı.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-3 d-flex justify-content-center">
                            <?php echo e($activities->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/logs/index.blade.php ENDPATH**/ ?>