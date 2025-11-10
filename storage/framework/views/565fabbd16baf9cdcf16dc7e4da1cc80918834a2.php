

<?php $__env->startSection('title', 'Yeni Araç Ataması Oluştur'); ?>

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

    /* === GÜNCELLENDİ (create-assignment-card) === */
    .create-assignment-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-assignment-card .card-header,
    .create-assignment-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-assignment-card .card-header {
        color: #000;
    }

    .create-assignment-card .form-control,
    .create-assignment-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* === YENİ Bilgi kutusu stili === */
    .alert-info-custom {
        position: relative;
        padding: 1.25rem 1.5rem;
        border-radius: 1rem;
        background: linear-gradient(135deg,
                rgba(209, 236, 241, 0.95) 0%,
                rgba(184, 231, 241, 0.95) 100%);
        border: 2px solid rgba(12, 84, 96, 0.2);
        box-shadow: 0 4px 15px rgba(12, 84, 96, 0.15);
        overflow: hidden;
        animation: infoPulse 3s ease-in-out infinite;
    }

    /* Yaklaşıp uzaklaşma animasyonu */
    @keyframes infoPulse {

        0%,
        100% {
            transform: scale(1);
            box-shadow: 0 4px 15px rgba(12, 84, 96, 0.15);
        }

        50% {
            transform: scale(1.02);
            box-shadow: 0 6px 25px rgba(12, 84, 96, 0.25);
        }
    }

    /* İkon container */
    .alert-info-custom::before {
        content: "ℹ️";
        position: absolute;
        left: 0.01rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 2.6rem;
        animation: iconBounce 2s ease-in-out infinite;
    }

    @keyframes iconBounce {

        0%,
        100% {
            transform: translateY(-50%) scale(1);
        }

        50% {
            transform: translateY(-50%) scale(1.1);
        }
    }

    /* İçerik alanı - ikonu sola kaydırmak için */
    .alert-info-custom .info-content {
        margin-left: 2.5rem;
        color: #0c5460;
        font-weight: 500;
    }

    .alert-info-custom strong {
        color: #073942;
        font-weight: 700;
        display: inline-block;
        margin-right: 0.5rem;
    }

    /* Dekoratif parlama efekti */
    .alert-info-custom::after {
        content: "";
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg,
                transparent 30%,
                rgba(255, 255, 255, 0.3) 50%,
                transparent 70%);
        animation: shine 4s ease-in-out infinite;
    }

    @keyframes shine {
        0% {
            transform: translateX(-100%) translateY(-100%) rotate(45deg);
        }

        50%,
        100% {
            transform: translateX(100%) translateY(100%) rotate(45deg);
        }
    }

    /* Animasyonlu buton (Değişiklik yok) */
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

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            
            <div class="col-md-8">
                
                <div class="card create-assignment-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Araç Görevi Ekle')); ?></div>
                    <div class="card-body p-4">

                        
                        <?php $__errorArgs = ['vehicle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert alert-danger" role="alert"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('service.assignments.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Araç (*)</label>
                                <select name="vehicle_id" id="vehicle_id"
                                    class="form-select <?php $__errorArgs = ['vehicle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Araç Seçiniz...</option>
                                    
                                    <?php $__currentLoopData = $vehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($vehicle->id); ?>"
                                            <?php echo e(old('vehicle_id') == $vehicle->id ? 'selected' : ''); ?>>
                                            <?php echo e($vehicle->plate_number); ?> (<?php echo e($vehicle->type); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                
                            </div>

                            <div class="mb-3">
                                <label for="task_description" class="form-label">Görev Açıklaması (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['task_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="task_description" name="task_description" value="<?php echo e(old('task_description')); ?>"
                                    required placeholder="Örn: Merkeze kargo götürme, 3 adet paket">
                                <?php $__errorArgs = ['task_description'];
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
                                <label for="destination" class="form-label">Yer / Gidilecek Nokta</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="destination" name="destination" value="<?php echo e(old('destination')); ?>"
                                    placeholder="Örn: Merkez Ofis, Tedarikçi X">
                                <?php $__errorArgs = ['destination'];
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
                                <label for="requester_name" class="form-label">Talep Eden Kişi / Departman</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['requester_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="requester_name" name="requester_name" value="<?php echo e(old('requester_name')); ?>"
                                    placeholder="Örn: Ali Yılmaz, Lojistik">
                                <?php $__errorArgs = ['requester_name'];
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
                                <label for="notes" class="form-label">Ek Notlar</label>
                                <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="notes" name="notes" rows="3"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
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
                            
                            <div class="alert alert-info-custom mt-3">
                                <div class="info-content">
                                    <strong>Bilgi:</strong> Araçlar 09:30 ve 13:30 saatlerinde firmadan
                                    ayrılmaktadır.
                                    Bu görevin ilgili sefere atanabilmesi için görev girişinizi sefer saatinden en az 30
                                    dakika önce (en geç 09:00 veya 13:00’e kadar) yapmanız gerekmektedir.
                                    Belirtilen saatlerden sonra yapılan görev girişleri, bir sonraki sefere otomatik olarak
                                    aktarılacaktır.
                                </div>
                            </div>


                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Görevi
                                    Ekle</button>
                                <a href="<?php echo e(route('service.assignments.index')); ?>"
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
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/service/assignments/create.blade.php ENDPATH**/ ?>