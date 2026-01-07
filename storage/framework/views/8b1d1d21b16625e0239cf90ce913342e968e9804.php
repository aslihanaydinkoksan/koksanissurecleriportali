

<?php $__env->startSection('title', 'Form Alanını Düzenle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1">Alanı Düzenle</h2>
                        <p class="text-muted small mb-0">
                            <span class="fw-bold text-primary">"<?php echo e($field->label); ?>"</span> alanının özelliklerini
                            güncelliyorsunuz.
                        </p>
                    </div>
                    <a href="<?php echo e(route('admin.custom-fields.index')); ?>"
                        class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center">
                        <svg style="width: 16px; height: 16px;" class="me-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Listeye Dön
                    </a>
                </div>

                
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 small">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('admin.custom-fields.update', $field->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Modül</label>
                                    <input type="text" class="form-control bg-light"
                                        value="<?php echo e(class_basename($field->model_scope)); ?>" disabled>
                                    <div class="form-text text-muted small">
                                        Veri güvenliği için modül değiştirilemez.
                                    </div>
                                </div>

                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Veri Tipi</label>
                                    
                                    <?php
                                        $typeLabel = match ($field->type) {
                                            'text' => 'Kısa Metin',
                                            'textarea' => 'Uzun Metin',
                                            'number' => 'Sayı',
                                            'date' => 'Tarih',
                                            'boolean' => 'Evet/Hayır',
                                            'select' => 'Seçim Kutusu',
                                            default => $field->type,
                                        };
                                    ?>
                                    <input type="text" class="form-control bg-light" value="<?php echo e($typeLabel); ?>"
                                        disabled>
                                    <div class="form-text text-muted small">
                                        Veri yapısı bozulmaması için tip değiştirilemez.
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label for="label" class="form-label fw-bold text-secondary small text-uppercase">Alan
                                    Adı (Label)</label>
                                <input type="text" name="label" id="label"
                                    class="form-control <?php $__errorArgs = ['label'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('label', $field->label)); ?>" required>
                                <div class="form-text text-muted">
                                    Formlarda görünen isimdir.
                                </div>
                                <?php $__errorArgs = ['label'];
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

                            
                            <?php if($field->type === 'select'): ?>
                                <div class="mb-4 p-3 bg-light rounded border">
                                    <label for="options_text" class="form-label fw-bold text-dark">Seçenekler</label>
                                    <textarea name="options_text" id="options_text" rows="3"
                                        class="form-control <?php $__errorArgs = ['options_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Seçenekleri virgül ile ayırın."><?php echo e(old('options_text', $optionsText ?? '')); ?></textarea>
                                    <div class="form-text text-muted">
                                        Mevcut seçenekleri virgül ile ayırarak güncelleyebilirsiniz.
                                    </div>
                                    <?php $__errorArgs = ['options_text'];
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
                            <?php endif; ?>

                            
                            <div class="mb-4 bg-white border rounded p-3">
                                <h6 class="fw-bold text-secondary small text-uppercase mb-3">Ayarlar</h6>

                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required"
                                        name="is_required" value="1"
                                        <?php echo e(old('is_required', $field->is_required) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_required">
                                        Zorunlu Alan (Kullanıcı bu alanı boş geçemez)
                                    </label>
                                </div>

                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                        name="is_active" value="1"
                                        <?php echo e(old('is_active', $field->is_active) ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_active">
                                        Aktif (Formlarda gösterilir)
                                    </label>
                                </div>

                                
                                <div class="row align-items-center g-2 mt-2">
                                    <div class="col-auto">
                                        <label for="order" class="col-form-label">Sıralama:</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="number" name="order" id="order"
                                            class="form-control form-control-sm" style="width: 80px;"
                                            value="<?php echo e(old('order', $field->order)); ?>">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="<?php echo e(route('admin.custom-fields.index')); ?>" class="btn btn-light border">İptal</a>
                                <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center">
                                    <svg style="width: 18px; height: 18px;" class="me-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/custom_fields/edit.blade.php ENDPATH**/ ?>