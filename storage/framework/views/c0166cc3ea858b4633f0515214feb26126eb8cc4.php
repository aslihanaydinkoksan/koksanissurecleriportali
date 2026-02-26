<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['activities', 'emptyMessage' => 'Kayıtlı geçmiş işlem bulunamadı.']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['activities', 'emptyMessage' => 'Kayıtlı geçmiş işlem bulunamadı.']); ?>
<?php foreach (array_filter((['activities', 'emptyMessage' => 'Kayıtlı geçmiş işlem bulunamadı.']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<div class="timeline mt-4 pt-3 border-top">
    <h6 class="fw-bold text-secondary mb-3"><i class="fa-solid fa-clock-rotate-left me-2"></i>Son Hareketler</h6>
    
    <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="card mb-2 border-0 shadow-sm" style="background: rgba(248, 250, 252, 0.5);">
            <div class="card-body py-2 px-3 position-relative">
                
                <div class="position-absolute top-0 start-0 bottom-0 rounded-start"
                     style="width: 4px; background: <?php echo e($activity->description == 'created' ? '#10b981' : ($activity->description == 'deleted' ? '#ef4444' : '#3b82f6')); ?>;">
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-white text-dark border me-2">
                            <?php echo e(class_basename($activity->subject_type)); ?>

                        </span>
                        <span class="text-dark small">
                            
                            <?php if($activity->event == 'updated'): ?>
                                Güncelleme
                            <?php elseif($activity->event == 'created'): ?>
                                Yeni Kayıt
                            <?php elseif($activity->event == 'deleted'): ?>
                                Silme
                            <?php else: ?>
                                <?php echo e($activity->description); ?>

                            <?php endif; ?>
                        </span>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">
                        <?php echo e($activity->created_at->diffForHumans()); ?>

                    </small>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted fst-italic">
                        <i class="fa-solid fa-user-circle me-1"></i>
                        <?php echo e($activity->causer->name ?? 'Sistem'); ?>

                    </small>
                    
                    
                    <?php if($activity->event == 'updated' && $activity->properties->has('attributes')): ?>
                        <span class="text-primary small" style="cursor: help;" 
                              title="Değişenler: <?php echo e(implode(', ', array_keys($activity->properties['attributes']))); ?>">
                            <i class="fa-solid fa-info-circle"></i> Detay
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center text-muted small py-2">
            <i class="fa-solid fa-history opacity-50 mb-1"></i><br>
            <?php echo e($emptyMessage); ?>

        </div>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/components/history-timeline.blade.php ENDPATH**/ ?>