<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['model', 'entity' => null]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['model', 'entity' => null]); ?>
<?php foreach (array_filter((['model', 'entity' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    // Sizin orijinal mantığınız: Model sınıf adından alanları çekiyoruz.
    // Örn: \App\Models\MaintenancePlan::getCustomFields() çalışır.
    $fields = collect();
    if (class_exists($model) && method_exists($model, 'getCustomFields')) {
        $fields = $model::getCustomFields();
    }
?>

<?php if($fields->count() > 0): ?>
    
    <div class="mt-4 mb-3 pb-2 border-bottom">
        <h5 class="text-primary fw-bold d-flex align-items-center">
            <svg style="width: 20px; height: 20px;" class="me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
            Ek Bilgiler
        </h5>
        <p class="text-muted small mb-0">Bu kayıt için tanımlanmış özel alanlar.</p>
    </div>

    
    <div class="row g-3">
        <?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Form name: extras[plaka_no]
                $inputName = "extras[{$field->key}]";
                // Hata yakalama key'i: extras.plaka_no
$errorKey = "extras.{$field->key}";

// Değer önceliği: 1. Old input -> 2. DB'deki veri (Edit) -> 3. Boş
                // Sizin mantığınız korunuyor:
                $value = old($errorKey, $entity ? $entity->extras[$field->key] ?? '' : '');
            ?>

            <div class="col-md-6">
                <label for="<?php echo e($field->key); ?>" class="form-label fw-bold text-secondary small text-uppercase">
                    <?php echo e($field->label); ?>

                    <?php if($field->is_required): ?>
                        <span class="text-danger">*</span>
                    <?php endif; ?>
                </label>

                
                <?php switch($field->type):
                    
                    case ('select'): ?>
                        <select name="<?php echo e($inputName); ?>" id="<?php echo e($field->key); ?>"
                            class="form-select <?php $__errorArgs = [$errorKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            <?php echo e($field->is_required ? 'required' : ''); ?>>
                            <option value="">Seçiniz...</option>
                            <?php if(!empty($field->options)): ?>
                                <?php $__currentLoopData = $field->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>" <?php echo e($value == $option ? 'selected' : ''); ?>>
                                        <?php echo e($option); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    <?php break; ?>

                    
                    <?php case ('textarea'): ?>
                        <textarea name="<?php echo e($inputName); ?>" id="<?php echo e($field->key); ?>" rows="3"
                            class="form-control <?php $__errorArgs = [$errorKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="<?php echo e($field->label); ?> giriniz..."
                            <?php echo e($field->is_required ? 'required' : ''); ?>><?php echo e($value); ?></textarea>
                    <?php break; ?>

                    
                    <?php case ('boolean'): ?>
                    <?php case ('checkbox'): ?>
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="<?php echo e($inputName); ?>" value="0">
                            <input type="checkbox" name="<?php echo e($inputName); ?>" id="<?php echo e($field->key); ?>" value="1"
                                class="form-check-input <?php $__errorArgs = [$errorKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                <?php echo e($value == '1' ? 'checked' : ''); ?>>
                            <label class="form-check-label text-dark" for="<?php echo e($field->key); ?>">
                                <?php echo e($field->label); ?> (Evet/Hayır)
                            </label>
                        </div>
                    <?php break; ?>

                    

                    <?php default: ?>
                        <input type="<?php echo e($field->type); ?>" name="<?php echo e($inputName); ?>" id="<?php echo e($field->key); ?>"
                            value="<?php echo e($value); ?>" class="form-control <?php $__errorArgs = [$errorKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            placeholder="<?php echo e($field->label); ?>" <?php echo e($field->is_required ? 'required' : ''); ?>>
                <?php endswitch; ?>

                
                <?php $__errorArgs = [$errorKey];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback d-block">
                        <?php echo e($message); ?>

                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/components/dynamic-fields.blade.php ENDPATH**/ ?>