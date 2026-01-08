
<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="create-event-card">
    
    <div class="mb-4">
        <label for="name" class="form-label">
            <i class="fa-solid fa-flag me-1 text-primary"></i> Seyahat Başlığı / Tanımı
        </label>
        <input type="text" name="name" id="name" class="form-control form-control-lg"
            value="<?php echo e(old('name', $travel->name ?? '')); ?>" placeholder="Örn: Ankara Müşteri Ziyareti" required>
    </div>

    
    <div class="row mb-4">
        
        <div class="col-md-6">
            <label class="form-label fw-bold text-success">
                <i class="fa-regular fa-calendar me-1"></i> Başlangıç Zamanı
            </label>
            <div class="input-group">
                
                <input type="date" name="start_date" class="form-control"
                    value="<?php echo e(old('start_date', isset($travel->start_date) ? \Carbon\Carbon::parse($travel->start_date)->format('Y-m-d') : '')); ?>"
                    required>
                
                <input type="time" name="start_time" class="form-control"
                    value="<?php echo e(old('start_time', isset($travel->start_time) ? \Carbon\Carbon::parse($travel->start_time)->format('H:i') : '')); ?>"
                    required>
            </div>
            <small class="text-muted">Tarih ve Saat</small>
        </div>

        
        <div class="col-md-6">
            <label class="form-label fw-bold text-danger">
                <i class="fa-regular fa-calendar-check me-1"></i> Bitiş Zamanı
            </label>
            <div class="input-group">
                
                <input type="date" name="end_date" class="form-control"
                    value="<?php echo e(old('end_date', isset($travel->end_date) ? \Carbon\Carbon::parse($travel->end_date)->format('Y-m-d') : '')); ?>"
                    required>
                
                <input type="time" name="end_time" class="form-control"
                    value="<?php echo e(old('end_time', isset($travel->end_time) ? \Carbon\Carbon::parse($travel->end_time)->format('H:i') : '')); ?>"
                    required>
            </div>
            <small class="text-muted">Tarih ve Saat</small>
        </div>
    </div>

    
    <div class="row align-items-center">
        
        <div class="col-md-6">
            <label for="status" class="form-label">
                <i class="fa-solid fa-list-check me-1 text-info"></i> Durum
            </label>
            <select name="status" id="status" class="form-select">
                <option value="planned" <?php echo e(old('status', $travel->status ?? '') == 'planned' ? 'selected' : ''); ?>>
                    Planlandı</option>
                <option value="completed" <?php echo e(old('status', $travel->status ?? '') == 'completed' ? 'selected' : ''); ?>>
                    Tamamlandı</option>
                
            </select>
        </div>

        
        <div class="col-md-6">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" role="switch" id="is_important" name="is_important"
                    value="1" <?php echo e(old('is_important', $travel->is_important ?? false) ? 'checked' : ''); ?>>
                <label class="form-check-label fw-bold text-danger ms-2" for="is_important">
                    <i class="fa-solid fa-star me-1"></i> Önemli / Acil
                </label>
            </div>
            <small class="text-muted d-block mt-1">İşaretlenirse listelerde öne çıkarılır.</small>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/_form.blade.php ENDPATH**/ ?>