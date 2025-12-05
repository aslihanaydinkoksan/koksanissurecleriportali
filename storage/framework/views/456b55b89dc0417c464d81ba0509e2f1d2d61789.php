

<?php $__env->startSection('title', 'Araç Bilgilerini Düzenle'); ?>

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

        /* === GÜNCELLENDİ (edit-vehicle-card) === */
        .edit-vehicle-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .edit-vehicle-card .card-header,
        .edit-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .edit-vehicle-card .card-header {
            color: #000;
        }

        .edit-vehicle-card .form-control,
        .edit-vehicle-card .form-select,
        .edit-vehicle-card .form-check-input {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .edit-vehicle-card .form-check-input {
            border: 1px solid rgba(0, 0, 0, .25);
            cursor: pointer;
            position: relative;
            /* Tıklanabilirlik için */
            z-index: 10;
            /* Katman sorunu için */
        }

        .edit-vehicle-card .form-check-label {
            cursor: pointer;
            position: relative;
            z-index: 10;
            user-select: none;
        }

        /* CHECKBOX SEÇİLİ (CHECKED) DURUMU */
        .edit-vehicle-card .form-check-input:checked {
            background-color: #667EEA !important;
            border-color: #667EEA !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
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
                
                <div class="card edit-vehicle-card">
                    
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span><?php echo e(__('Araç Bilgilerini Düzenle')); ?></span>

                        <?php if(Auth::user()->role === 'admin'): ?>
                            <form method="POST" action="<?php echo e(route('service.vehicles.destroy', $vehicle->id)); ?>"
                                onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz? Araca ait tüm geçmiş atamalar da silinebilir!');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Aracı Sil</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('service.vehicles.update', $vehicle->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

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
                                    id="plate_number" name="plate_number"
                                    value="<?php echo e(old('plate_number', $vehicle->plate_number)); ?>" required
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
                                    id="type" name="type" value="<?php echo e(old('type', $vehicle->type)); ?>" required
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
                                    id="brand_model" name="brand_model"
                                    value="<?php echo e(old('brand_model', $vehicle->brand_model)); ?>"
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
                                    rows="3"><?php echo e(old('description', $vehicle->description)); ?></textarea>
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
                                    value="1" <?php echo e(old('is_active', $vehicle->is_active) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Araç Aktif (Kullanımda)</label>
                            </div>

                            <div class="text-end mt-4">
                                
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/vehicles/edit.blade.php ENDPATH**/ ?>