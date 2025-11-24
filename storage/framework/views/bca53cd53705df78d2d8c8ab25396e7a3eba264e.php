

<?php $__env->startSection('title', 'Yeni Lojistik Aracı Ekle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Animasyonlu Arka Plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

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

        /* Kart Stilleri */
        .create-vehicle-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
        }

        .create-vehicle-card .card-header {
            color: #000;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .create-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
        }

        .create-vehicle-card .form-control,
        .create-vehicle-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, .1);
        }

        .create-vehicle-card .form-control:focus,
        .create-vehicle-card .form-select:focus {
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
            border-color: #667EEA;
        }

        /* Buton Stili */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
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
                    <div class="card-header h4 bg-transparent border-0 pt-4">
                        <i class="fas fa-truck me-2"></i><?php echo e(__('Yeni Lojistik Aracı Ekle')); ?>

                    </div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('service.logistics-vehicles.store')); ?>">
                            <?php echo csrf_field(); ?>

                            
                            <div class="row mb-3">
                                <div class="col-md-6">
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
                                        placeholder="Örn: 34 LJS 01">
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
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Durum</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" selected>🟢 Aktif (Kullanımda)</option>
                                        <option value="maintenance">🟠 Bakımda</option>
                                        <option value="inactive">🔴 Pasif (Hizmet Dışı)</option>
                                    </select>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marka (*)</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['brand'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="brand" name="brand" value="<?php echo e(old('brand')); ?>" required
                                        placeholder="Örn: Mercedes-Benz">
                                </div>
                                <div class="col-md-6">
                                    <label for="model" class="form-label">Model (*)</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['model'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="model" name="model" value="<?php echo e(old('model')); ?>" required
                                        placeholder="Örn: Actros 1845">
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="capacity" class="form-label">Yük Kapasitesi (kg)</label>
                                    <input type="number" step="0.01"
                                        class="form-control <?php $__errorArgs = ['capacity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="capacity"
                                        name="capacity" value="<?php echo e(old('capacity')); ?>" placeholder="Örn: 15000">
                                </div>
                                <div class="col-md-4">
                                    <label for="current_km" class="form-label">Güncel KM (*)</label>
                                    <input type="number" step="0.1"
                                        class="form-control <?php $__errorArgs = ['current_km'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="current_km"
                                        name="current_km" value="<?php echo e(old('current_km', 0)); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fuel_type" class="form-label">Yakıt Tipi</label>
                                    <select name="fuel_type" id="fuel_type" class="form-select">
                                        <option value="Diesel" selected>Dizel</option>
                                        <option value="Gasoline">Benzin</option>
                                        <option value="Electric">Elektrik</option>
                                        <option value="Hybrid">Hibrit</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 form-check border-top pt-3 mt-3">
                                <input type="checkbox" class="form-check-input" id="kvkk_onay" name="kvkk_onay"
                                    value="1" <?php echo e(old('kvkk_onay') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="kvkk_onay">
                                    <a href="#" class="text-decoration-underline" style="color: #0d6efd;">
                                        KVKK Aydınlatma Metni'ni
                                    </a>
                                    okudum ve araç verilerinin işlenmesini kabul ediyorum.
                                </label>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" id="submit-button"
                                    class="btn btn-animated-gradient rounded-3 px-4 py-2" disabled>
                                    Lojistik Aracı Kaydet
                                </button>
                                <a href="<?php echo e(route('service.logistics-vehicles.index')); ?>"
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
        document.addEventListener('DOMContentLoaded', function() {
            const kvkkCheckbox = document.getElementById('kvkk_onay');
            const submitButton = document.getElementById('submit-button');

            if (kvkkCheckbox && submitButton) {
                function toggleSubmitButton() {
                    submitButton.disabled = !kvkkCheckbox.checked;
                }
                toggleSubmitButton();
                kvkkCheckbox.addEventListener('change', toggleSubmitButton);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/logistics_vehicles/create.blade.php ENDPATH**/ ?>