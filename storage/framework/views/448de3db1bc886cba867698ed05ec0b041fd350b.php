

<?php $__env->startSection('title', 'Birim Yönetimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    /* #667EEA (Canlı mavi-mor) tonu */
                    #fde2ff,
                    /* #F093FB (Yumuşak pembe) tonu */
                    #d9fcf7,
                    /* #4FD1C5 (Teal/turkuaz) tonu */
                    #fff0d9
                    /* #FBD38D (Sıcak sarı) tonu */
                );
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

        /* Kartlar (Tam Şeffaf) */
        .birim-card {
            /* Bu sayfa için özel class adı */
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
            margin-bottom: 1.5rem;
            /* Kartlar arasına boşluk */
        }

        /* Başlıklar ve Etiketler (Okunabilirlik İçin) */
        .birim-card .card-header,
        .birim-card .form-label

        /* (Bu sayfada label yok ama tutarlılık için) */
            {
            color: #000;
            /* Başlıklar siyah kalsın */
            font-weight: bold;
            /* Kalın Metin */
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        /* Form Elemanları (Yumuşak Köşe + Opak Arka Plan) */
        .birim-card .form-control {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* Input Group için özel yuvarlatma */
        .birim-card .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .birim-card .input-group .btn {
            border-radius: 0.5rem;
            /* Genel */
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Mevcut Birimler Listesi Öğeleri (Okunabilirlik) */
        .birim-card .list-group-item {
            background-color: rgba(255, 255, 255, 0.8);
            /* Hafif opak arka plan */
            border-radius: 0.5rem;
            /* Yumuşak köşeler */
            margin-bottom: 0.5rem;
            /* Öğeler arası boşluk */
            border: none;
            /* Kenarlığı kaldır */
            color: #333;
            /* Metin rengi */
            font-weight: 500;
            /* Biraz daha kalın metin */
        }

        .birim-card .list-group-item:last-child {
            margin-bottom: 0;
            /* Son öğenin alt boşluğunu kaldır */
        }

        .birim-card .list-group-item .btn-danger {
            font-weight: bold;
            /* Sil butonu yazısı kalın olsun */
        }


        /* Animasyonlu Buton */
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
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                
                
                <div class="card birim-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Birim Ekle')); ?></div>
                    
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('birimler.store')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="input-group">
                                
                                <input type="text" class="form-control <?php $__errorArgs = ['ad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="ad"
                                    placeholder="Yeni birim adı (örn: Metreküp)" required value="<?php echo e(old('ad')); ?>">
                                
                                <button class="btn btn-animated-gradient" type="submit">Ekle</button>
                            </div>
                            <?php $__errorArgs = ['ad'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                
                                <div class="text-danger mt-2 fw-bold"><small><?php echo e($message); ?></small></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </form>
                    </div>
                </div>

                
                
                <div class="card birim-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Mevcut Birimler')); ?></div>
                    
                    <div class="card-body p-4">
                        
                        <ul class="list-group">
                            <?php $__empty_1 = true; $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                
                                <li class="list-group-item d-flex justify-content-between align-items-center shadow-sm">
                                    <?php echo e($birim->ad); ?>

                                    <form action="<?php echo e(route('birimler.destroy', $birim->id)); ?>" method="POST"
                                        onsubmit="return confirm('Bu birimi silmek istediğinizden emin misiniz? Silinen birim geri alınamaz.');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                                    </form>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li class="list-group-item text-muted">Sistemde kayıtlı birim bulunamadı.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/birimler/index.blade.php ENDPATH**/ ?>