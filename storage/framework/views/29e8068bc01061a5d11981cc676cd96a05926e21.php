<div class="card create-event-card shadow-sm border-0"
    style="background: rgba(255,255,255,0.85); backdrop-filter: blur(10px);">
    <div class="card-body p-4">

        <div class="row g-4">

            
            <div class="col-12">
                <label for="name" class="form-label text-dark fw-bold">
                    <i class="fa-solid fa-flag me-2 text-primary"></i> Seyahat Başlığı / Tanımı
                </label>
                <input type="text" name="name" id="name"
                    class="form-control form-control-lg <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e(old('name', $travel->name ?? '')); ?>" placeholder="Örn: Ankara Müşteri Ziyareti" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-6">
                <label for="start_date" class="form-label text-dark fw-bold">
                    <i class="fa-regular fa-calendar me-2 text-success"></i> Başlangıç Tarihi
                </label>
                <input type="date" name="start_date" id="start_date"
                    class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e(old('start_date', isset($travel->start_date) ? $travel->start_date->format('Y-m-d') : '')); ?>"
                    required>
                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label for="end_date" class="form-label text-dark fw-bold">
                    <i class="fa-regular fa-calendar-check me-2 text-danger"></i> Bitiş Tarihi
                </label>
                <input type="date" name="end_date" id="end_date"
                    class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    value="<?php echo e(old('end_date', isset($travel->end_date) ? $travel->end_date->format('Y-m-d') : '')); ?>"
                    required>
                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-6">
                <label for="status" class="form-label text-dark fw-bold">
                    <i class="fa-solid fa-bars-progress me-2 text-info"></i> Durum
                </label>
                <select name="status" id="status" class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="planned" <?php echo e(old('status', $travel->status ?? '') == 'planned' ? 'selected' : ''); ?>>
                        Planlandı</option>
                    <option value="completed"
                        <?php echo e(old('status', $travel->status ?? '') == 'completed' ? 'selected' : ''); ?>>Tamamlandı</option>
                    
                </select>
                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6 d-flex align-items-end">
                
                <div class="form-check form-switch p-3 w-100 border rounded bg-white">
                    <input class="form-check-input" type="checkbox" name="is_important" id="is_important" value="1"
                        <?php echo e(old('is_important', $travel->is_important ?? false) ? 'checked' : ''); ?>>
                    <label class="form-check-label fw-bold ms-2 text-danger" for="is_important">
                        <i class="fa-solid fa-star me-1"></i> Önemli / Acil
                    </label>
                    <div class="form-text small mt-1">
                        Bu seçenek işaretlenirse, seyahat listelerde öne çıkarılır.
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/_form.blade.php ENDPATH**/ ?>