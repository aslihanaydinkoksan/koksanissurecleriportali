<?php $__env->startSection('title', 'Yeni Form Alanı Oluştur'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1">Yeni Dinamik Alan</h2>
                        <p class="text-muted small mb-0">Modüllere eklenecek yeni veri alanını tanımlayın.</p>
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

                        <form action="<?php echo e(route('admin.custom-fields.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-4">
                                <label for="model_scope"
                                    class="form-label fw-bold text-secondary small text-uppercase">Hangi Modül İçin?</label>
                                <select name="model_scope" id="model_scope"
                                    class="form-select <?php $__errorArgs = ['model_scope'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="" disabled selected>Lütfen bir modül seçin</option>
                                    <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($class); ?>"
                                            <?php echo e(old('model_scope') == $class ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['model_scope'];
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

                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Geçerli İş Birimi
                                    (Fabrika)</label>
                                <select name="business_unit_id"
                                    class="form-select <?php $__errorArgs = ['business_unit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">Tüm Birimler (Genel)</option>
                                    <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($unit->id); ?>"
                                            <?php echo e(old('business_unit_id') == $unit->id ? 'selected' : ''); ?>>
                                            <?php echo e($unit->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="form-text text-muted">
                                    Eğer "Tüm Birimler" seçerseniz, bu alan her fabrikada görünür.
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
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('label')); ?>"
                                    placeholder="Örn: Konteyner No, Raf Ömrü vb." required>
                                <div class="form-text text-muted">
                                    Bu isim formlarda görünecek başlıktır. Veritabanı anahtarı (key) bundan otomatik
                                    üretilir.
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

                            <div class="row">
                                
                                <div class="col-md-6 mb-4">
                                    <label for="typeSelect"
                                        class="form-label fw-bold text-secondary small text-uppercase">Veri Tipi</label>
                                    <select name="type" id="typeSelect"
                                        class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" onchange="toggleOptions()">
                                        <option value="text" <?php echo e(old('type') == 'text' ? 'selected' : ''); ?>>Kısa Metin
                                            (Text)</option>
                                        <option value="textarea" <?php echo e(old('type') == 'textarea' ? 'selected' : ''); ?>>Uzun
                                            Metin (Textarea)</option>
                                        <option value="number" <?php echo e(old('type') == 'number' ? 'selected' : ''); ?>>Sayı
                                            (Number)</option>
                                        <option value="date" <?php echo e(old('type') == 'date' ? 'selected' : ''); ?>>Tarih (Date)
                                        </option>
                                        <option value="boolean" <?php echo e(old('type') == 'boolean' ? 'selected' : ''); ?>>Evet/Hayır
                                            (Checkbox)</option>
                                        <option value="select" <?php echo e(old('type') == 'select' ? 'selected' : ''); ?>>Seçim Kutusu
                                            (Select)</option>
                                    </select>
                                    <?php $__errorArgs = ['type'];
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

                                
                                <div class="col-md-6 mb-4">
                                    <label for="order"
                                        class="form-label fw-bold text-secondary small text-uppercase">Sıralama</label>
                                    <input type="number" name="order" id="order"
                                        class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('order', 0)); ?>">
                                </div>
                            </div>

                            
                            
                            <div class="mb-4 d-none p-3 bg-light rounded border" id="optionsDiv">
                                <label for="options_text" class="form-label fw-bold text-dark">Seçenekler</label>
                                <textarea name="options_text" id="options_text" rows="3"
                                    class="form-control <?php $__errorArgs = ['options_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Seçenekleri virgül ile ayırarak yazın.&#10;Örn: Kırmızı, Mavi, Yeşil, Sarı"><?php echo e(old('options_text')); ?></textarea>
                                <div class="form-text text-muted">
                                    Kullanıcının seçebileceği değerleri virgül (,) ile ayırın.
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

                            
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required"
                                        name="is_required" value="1" <?php echo e(old('is_required') ? 'checked' : ''); ?>>
                                    <label class="form-check-label fw-medium text-dark" for="is_required">
                                        Bu alanın doldurulması zorunlu olsun
                                    </label>
                                </div>
                            </div>

                            
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="<?php echo e(route('admin.custom-fields.index')); ?>" class="btn btn-light border">İptal</a>
                                <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center">
                                    <svg style="width: 18px; height: 18px;" class="me-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Kaydet
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        function toggleOptions() {
            const typeSelect = document.getElementById('typeSelect');
            const optionsDiv = document.getElementById('optionsDiv');

            if (typeSelect.value === 'select') {
                optionsDiv.classList.remove('d-none'); // Bootstrap class'ını kaldır
                // Kullanıcı deneyimi: Focus yap
                document.getElementById('options_text').focus();
            } else {
                optionsDiv.classList.add('d-none'); // Bootstrap class'ını ekle
            }
        }

        // Sayfa yüklendiğinde (Validation hatasıyla geri dönerse) eski durumu korumak için çalıştır
        document.addEventListener('DOMContentLoaded', function() {
            toggleOptions();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/admin/custom_fields/create.blade.php ENDPATH**/ ?>