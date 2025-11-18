

<?php $__env->startSection('title', 'Yeni Üretim Planı Oluştur'); ?>

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

        /* === GÜNCELLENDİ (create-plan-card) === */
        .create-plan-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .create-plan-card .card-header,
        .create-plan-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .create-plan-card .card-header {
            color: #000;
        }

        .create-plan-card .form-control,
        .create-plan-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* YENİ EKLENDİ: Plan detayları satırı için stiller */
        .plan-detail-row {
            display: flex;
            gap: 0.75rem;
            /* 12px */
            margin-bottom: 0.75rem;
            align-items: center;
        }

        /* GÜNCELLENDİ: .form-select de flex: 1 almalı */
        .plan-detail-row .form-control,
        .plan-detail-row .form-select {
            flex: 1;
            /* Alanların eşit büyümesini sağlar */
        }

        .plan-detail-row .btn-danger {
            flex-shrink: 0;
            /* Butonun küçülmesini engeller */
            padding: 0.375rem 0.75rem;
            /* Bootstrap btn-sm boyutu */
        }

        /* YENİ EKLENDİ BİTİŞ */

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
            <div class="col-md-10">
                
                <div class="card create-plan-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Üretim Planı Oluştur')); ?></div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('production.plans.store')); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>
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
                                            id="plan_title" name="plan_title" value="<?php echo e(old('plan_title')); ?>" required
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
                                            id="week_start_date" name="week_start_date" value="<?php echo e(old('week_start_date')); ?>"
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
                                    
                                    <legend class="form-legend">Plan Detayları (Makine, Ürün, Miktar, Birim)</legend>

                                    
                                    <?php if($errors->has('plan_details.*')): ?>
                                        <div class="alert alert-danger p-2 small">
                                            Plan detaylarında hatalar var. Lütfen kırmızı ile işaretli alanları kontrol
                                            edin.
                                        </div>
                                    <?php endif; ?>

                                    
                                    <div id="plan-details-wrapper">

                                        
                                        <?php if(old('plan_details')): ?>
                                            <?php $__currentLoopData = old('plan_details'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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

                                                    
                                                    <input type="number"
                                                        name="plan_details[<?php echo e($index); ?>][quantity]"
                                                        class="form-control <?php $__errorArgs = ['plan_details.' . $index . '.quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        placeholder="Miktar" value="<?php echo e($details['quantity'] ?? ''); ?>"
                                                        required min="1" autocomplete="off">

                                                    
                                                    <select name="plan_details[<?php echo e($index); ?>][birim_id]"
                                                        class="form-select <?php $__errorArgs = ['plan_details.' . $index . '.birim_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                        required>
                                                        <option value="" disabled>Birim Seçin</option>
                                                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($birim->id); ?>"
                                                                <?php echo e(isset($details['birim_id']) && $details['birim_id'] == $birim->id ? 'selected' : ''); ?>>
                                                                <?php echo e($birim->ad); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>

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
                                                
                                                <?php $__errorArgs = ['plan_details.' . $index . '.birim_id'];
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
                                        <?php endif; ?>
                                    </div>

                                    <button type="button" id="add-plan-row" class="btn btn-success btn-sm mt-2">+ Satır
                                        Ekle</button>
                                </fieldset>
                            </div>

                            <div class="text-end mt-4">
                                
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Planı
                                    Oluştur</button>
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
        // Controller'dan gelen $birimler değişkenini JavaScript'e JSON olarak aktarıyoruz
        const allBirimler = <?php echo json_encode($birimler, 15, 512) ?>;

        document.addEventListener('DOMContentLoaded', function() {

            // Satır ekleme ve silme işlemleri için benzersiz bir index tutucu.
            // Eğer form validasyondan dönerse, mevcut satır sayısından başlar.
            let rowIndex = <?php echo e(old('plan_details') ? count(old('plan_details')) : 0); ?>;

            const wrapper = document.getElementById('plan-details-wrapper');
            const addButton = document.getElementById('add-plan-row');

            // Birimler için HTML <option> listesini oluşturan yardımcı fonksiyon
            function getBirimOptions() {
                let optionsHtml = '<option value="" selected disabled>Birim Seçin</option>';
                allBirimler.forEach(birim => {
                    optionsHtml += `<option value="${birim.id}">${birim.ad}</option>`;
                });
                return optionsHtml;
            }

            // "Satır Ekle" butonuna tıklandığında
            addButton.addEventListener('click', function() {
                // Her defasında taze bir birim <option> listesi al
                const birimOptions = getBirimOptions();

                // Yeni satırın HTML şablonu
                // GÜNCELLENDİ: placeholder="Miktar" ve <select> eklendi
                const newRow = `
            <div class="plan-detail-row">
                <input type="text" name="plan_details[${rowIndex}][machine]" class="form-control" placeholder="Makine Adı" required autocomplete="off">
                <input type="text" name="plan_details[${rowIndex}][product]" class="form-control" placeholder="Ürün Kodu/Adı" required autocomplete="off">
                <input type="number" name="plan_details[${rowIndex}][quantity]" class="form-control" placeholder="Miktar" required min="1" autocomplete="off">
                <select name="plan_details[${rowIndex}][birim_id]" class="form-select" required>
                    ${birimOptions}
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-plan-row">&times;</button>
            </div>
            `;

                // Yeni satırı kapsayıcıya ekle
                wrapper.insertAdjacentHTML('beforeend', newRow);

                // Bir sonraki satır için index'i artır
                rowIndex++;
            });

            // "Sil" (X) butonuna tıklandığında (Event Delegation)
            // Kapsayıcıya bir tıklama dinleyicisi ekliyoruz, bu sayede sonradan eklenen
            // butonlar da çalışır.
            wrapper.addEventListener('click', function(e) {
                // Tıklanan eleman 'remove-plan-row' class'ına sahip bir buton mu?
                if (e.target && e.target.classList.contains('remove-plan-row')) {
                    // Butonun en yakın 'plan-detail-row' class'lı ebeveynini bul ve kaldır
                    e.target.closest('.plan-detail-row').remove();
                }
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/production/plans/create.blade.php ENDPATH**/ ?>