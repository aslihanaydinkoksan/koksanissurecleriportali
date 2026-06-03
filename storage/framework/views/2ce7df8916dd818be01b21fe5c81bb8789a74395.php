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

<div class="timeline mt-4 pt-3 border-top timeline-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-secondary mb-0"><i class="fa-solid fa-clock-rotate-left me-2"></i>Son Hareketler</h6>
        <?php if(count($activities) > 5): ?>
            <span class="badge bg-light text-muted border total-count-badge"><?php echo e(count($activities)); ?> Kayıt</span>
        <?php endif; ?>
    </div>
    
    <div class="timeline-items-wrapper">
        <?php $__empty_1 = true; $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="card mb-2 border-0 shadow-sm timeline-item" 
                 style="background: rgba(248, 250, 252, 0.5); <?php echo e($index >= 5 ? 'display: none;' : ''); ?>">
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
    </div>

    <?php if(count($activities) > 5): ?>
        <div class="text-center mt-3">
            <button type="button" class="btn btn-sm btn-outline-primary btn-load-more px-3" data-step="5">
                <i class="fa-solid fa-chevron-down me-1"></i> Devamını Görüntüle
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary btn-collapse px-3" style="display: none;">
                <i class="fa-solid fa-chevron-up me-1"></i> Gizle
            </button>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('click', function(e) {
    // Daha Fazla Yükle Butonu
    if (e.target.closest('.btn-load-more')) {
        const btn = e.target.closest('.btn-load-more');
        const wrapper = btn.closest('.timeline-wrapper');
        const items = wrapper.querySelectorAll('.timeline-item');
        const step = parseInt(btn.getAttribute('data-step') || 5);
        
        let shownCount = 0;
        let visibleItems = 0;
        
        items.forEach(item => {
            if (item.style.display !== 'none') {
                visibleItems++;
            }
        });

        items.forEach((item, index) => {
            if (item.style.display === 'none' && shownCount < step) {
                item.style.display = 'block';
                item.classList.add('animate__animated', 'animate__fadeIn'); // Şık görünüm için
                shownCount++;
            }
        });

        // Hepsini gösterdik mi?
        const totalHidden = wrapper.querySelectorAll('.timeline-item[style*="display: none"]').length;
        if (totalHidden === 0) {
            btn.style.display = 'none';
            wrapper.querySelector('.btn-collapse').style.display = 'inline-block';
        }
    }

    // Gizle Butonu
    if (e.target.closest('.btn-collapse')) {
        const btn = e.target.closest('.btn-collapse');
        const wrapper = btn.closest('.timeline-wrapper');
        const items = wrapper.querySelectorAll('.timeline-item');

        items.forEach((item, index) => {
            if (index >= 5) {
                item.style.display = 'none';
                item.classList.remove('animate__fadeIn');
            }
        });

        btn.style.display = 'none';
        wrapper.querySelector('.btn-load-more').style.display = 'inline-block';
        
        // Kaybolmasın diye en başa odakla (Opsiyonel)
        wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script><?php /**PATH /var/www/koksan-takvim/resources/views/components/history-timeline.blade.php ENDPATH**/ ?>