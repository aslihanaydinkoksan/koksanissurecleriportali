

<?php $__env->startSection('title', 'Yeni Araç Ekle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    #fde2ff,
                    #d9fcf7,
                    #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        /* Arka plan dalgalanma animasyonu */
        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* === GÜNCELLENDİ (create-vehicle-card) === */
        .create-vehicle-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .create-vehicle-card .card-header,
        .create-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .create-vehicle-card .card-header {
            color: #000;
        }

        .create-vehicle-card .form-control,
        .create-vehicle-card .form-select,
        .create-vehicle-card .form-check-input {
            /* Checkbox eklendi */
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .create-vehicle-card .form-check-input {
            border: 1px solid rgba(0, 0, 0, .25);
            /* Checkbox kenarlığı */
        }

        /* Animasyonlu buton (Değişiklik yok) */
        .btn-animated-gradient {
            background: linear-gradient(-45deg,
                    #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-md-8">
                
                <div class="card create-vehicle-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Araç Ekle')); ?></div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('service.vehicles.store')); ?>">
                            <?php echo csrf_field(); ?>
                            
                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plaka (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['plate_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="plate_number" name="plate_number" value="<?php echo e(old('plate_number')); ?>" required
                                    placeholder="Örn: 34 ABC 123">
                                <?php $__errorArgs = ['plate_number'];
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

                            <div class="mb-3">
                                <label for="type" class="form-label">Araç Tipi (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="type" name="type" value="<?php echo e(old('type')); ?>" required
                                    placeholder="Örn: Kamyonet, Otomobil, Minibüs">
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

                            <div class="mb-3">
                                <label for="brand_model" class="form-label">Marka / Model</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['brand_model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="brand_model" name="brand_model" value="<?php echo e(old('brand_model')); ?>"
                                    placeholder="Örn: Ford Transit, Fiat Doblo">
                                <?php $__errorArgs = ['brand_model'];
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

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama / Notlar</label>
                                <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description"
                                    rows="3"><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
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

                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Araç Aktif (Kullanımda)</label>
                            </div>

                            <div class="mb-3 form-check border-top pt-3 mt-3">
                                <input type="checkbox" class="form-check-input <?php $__errorArgs = ['kvkk_onay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="kvkk_onay" name="kvkk_onay" value="1" <?php echo e(old('kvkk_onay') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="kvkk_onay">
                                    <!--
                                                    !!! DİKKAT !!!
                                                    Aşağıdaki 'href' adresini kendi KVKK metninizin
                                                    bulunduğu sayfanın URL'si ile değiştirin.
                                                -->
                                    <a href="<?php echo e(url('/kvkk-aydinlatma-metni')); ?>" target="_blank"
                                        class="text-decoration-underline" style="color: #0d6efd;">
                                        KVKK Aydınlatma Metni'ni
                                    </a>
                                    okudum, anladım ve araç verilerinin (plaka, marka vb.)
                                    kaydedilmesini ve işlenmesini kabul ediyorum.
                                </label>
                                <?php $__errorArgs = ['kvkk_onay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>


                            <div class="text-end mt-4">
                                
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Aracı
                                    Kaydet</button>
                                <a href="<?php echo e(route('service.vehicles.index')); ?>"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script>
        // Sayfa tamamen yüklendiğinde çalış
        document.addEventListener('DOMContentLoaded', function() {
            // Gerekli elementleri seç
            const kvkkCheckbox = document.getElementById('kvkk_onay');
            const submitButton = document.getElementById('submit-button');

            // Kontrol: Bu elementler sayfada varsa devam et
            if (kvkkCheckbox && submitButton) {

                // Butonun mevcut durumunu ayarlamak için bir fonksiyon
                function toggleSubmitButton() {
                    // Checkbox seçiliyse 'disabled' özelliğini kaldır,
                    // seçili değilse 'disabled' özelliğini ekle (true yap).
                    submitButton.disabled = !kvkkCheckbox.checked;
                }

                // 1. Sayfa ilk yüklendiğinde butonun durumunu ayarla
                //    (Eğer form hatadan dolayı geri dönerse ve checkbox işaretliyse, buton aktif kalır)
                toggleSubmitButton();

                // 2. Checkbox'ın durumunda herhangi bir değişiklik olduğunda
                //    (tıklandığında) fonksiyonu tekrar çalıştır
                kvkkCheckbox.addEventListener('change', toggleSubmitButton);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/service/vehicles/create.blade.php ENDPATH**/ ?>