

<?php $__env->startSection('title', 'Üretim Planını Düzenle'); ?>

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

        /* === GÜNCELLENDİ (plan-edit-card) === */
        .plan-edit-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .plan-edit-card .card-header,
        .plan-edit-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .plan-edit-card .card-header {
            color: #000;
        }

        .plan-edit-card .form-control,
        .plan-edit-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* Plan detayları satırı için stiller (create.blade.php ile aynı) */
        .plan-detail-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            align-items: center;
        }

        .plan-detail-row .form-control {
            flex: 1;
        }

        .plan-detail-row .btn-danger {
            flex-shrink: 0;
            padding: 0.375rem 0.75rem;
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

    
    <?php
        $details_data = old('plan_details', $productionPlan->plan_details ?? []);
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                
                <div class="card plan-edit-card">

                    
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span><?php echo e(__('Üretim Planını Düzenle')); ?></span>

                        
                        <?php if(Auth::user()->role === 'admin'): ?>
                            <form method="POST" action="<?php echo e(route('production.plans.destroy', $productionPlan->id)); ?>"
                                onsubmit="return confirm('Bu üretim planını silmek istediğinizden emin misiniz?');"
                                autocomplete="off">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Planı Sil</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('production.plans.update', $productionPlan->id)); ?>"
                            autocomplete="off">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?> 

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="plan_title" class="form-label">Plan Başlığı (*)</label>
                                        
                                        <input type="text" class="form-control <?php $__errorArgs = ['plan_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="plan_title" name="plan_title"
                                            value="<?php echo e(old('plan_title', $productionPlan->plan_title)); ?>" required
                                            autocomplete="off">
                                        <?php $__errorArgs = ['plan_title'];
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
                                        <label for="week_start_date" class="form-label">Hafta Başlangıç Tarihi (*)</label>
                                        
                                        <input type="date"
                                            class="form-control <?php $__errorArgs = ['week_start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="week_start_date" name="week_start_date"
                                            value="<?php echo e(old('week_start_date', $productionPlan->week_start_date->format('Y-m-d'))); ?>"
                                            required autocomplete="off">
                                        <?php $__errorArgs = ['week_start_date'];
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
                                </div>

                                
                                <fieldset class="col-md-6">
                                    <legend class="form-label">Plan Detayları (Makine, Ürün, Adet)</legend>

                                    <?php if($errors->has('plan_details.*')): ?>
                                        <div class="alert alert-danger p-2 small">
                                            Plan detaylarında hatalar var. Lütfen kırmızı ile işaretli alanları kontrol
                                            edin.
                                        </div>
                                    <?php endif; ?>

                                    <div id="plan-details-wrapper">

                                        
                                        <?php $__currentLoopData = $details_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="plan-detail-row">
                                                <input type="text" name="plan_details[<?php echo e($index); ?>][machine]"
                                                    class="form-control <?php $__errorArgs = ['plan_details.' . $index . '.machine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    placeholder="Makine Adı" value="<?php echo e($details['machine'] ?? ''); ?>"
                                                    required autocomplete="off">

                                                <input type="text" name="plan_details[<?php echo e($index); ?>][product]"
                                                    class="form-control <?php $__errorArgs = ['plan_details.' . $index . '.product'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    placeholder="Ürün Kodu/Adı" value="<?php echo e($details['product'] ?? ''); ?>"
                                                    required autocomplete="off">

                                                <input type="number" name="plan_details[<?php echo e($index); ?>][quantity]"
                                                    class="form-control <?php $__errorArgs = ['plan_details.' . $index . '.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    placeholder="Adet" value="<?php echo e($details['quantity'] ?? ''); ?>" required
                                                    min="1" autocomplete="off">

                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-plan-row">&times;</button>
                                            </div>
                                            
                                            <?php $__errorArgs = ['plan_details.' . $index . '.machine'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block mb-2"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <?php $__errorArgs = ['plan_details.' . $index . '.product'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block mb-2"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <?php $__errorArgs = ['plan_details.' . $index . '.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <div class="invalid-feedback d-block mb-2"><?php echo e($message); ?></div>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <button type="button" id="add-plan-row" class="btn btn-success btn-sm mt-2">+ Satır
                                        Ekle</button>
                                </fieldset>
                            </div>


                            <div class="text-end mt-4">
                                
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
                                    Kaydet</button>
                                <a href="<?php echo e(route('production.plans.index')); ?>"
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

            // Satır index'ini, sayfada halihazırda bulunan satır sayısından başlatıyoruz.
            let rowIndex = <?php echo e(count($details_data)); ?>;

            const wrapper = document.getElementById('plan-details-wrapper');
            const addButton = document.getElementById('add-plan-row');

            addButton.addEventListener('click', function() {
                const newRow = `
            <div class="plan-detail-row">
                <input type="text" name="plan_details[${rowIndex}][machine]" class="form-control" placeholder="Makine Adı" required autocomplete="off">
                <input type="text" name="plan_details[${rowIndex}][product]" class="form-control" placeholder="Ürün Kodu/Adı" required autocomplete="off">
                <input type="number" name="plan_details[${rowIndex}][quantity]" class="form-control" placeholder="Adet" required min="1" autocomplete="off">
                <button type="button" class="btn btn-danger btn-sm remove-plan-row">&times;</button>
            </div>
        `;
                wrapper.insertAdjacentHTML('beforeend', newRow);
                rowIndex++;
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-plan-row')) {
                    e.target.closest('.plan-detail-row').remove();
                }
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/production/plans/edit.blade.php ENDPATH**/ ?>