

<?php $__env->startSection('title', 'Yeni Bakım Planı'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- ANA TASARIM (Diğer sayfalarla ortak) --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
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

        .modern-container {
            max-width: 1000px;
            /* Form için biraz daha dar container idealdir */
            margin: 0 auto;
        }

        /* Form Kartı */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 2rem;
            border-bottom: none;
        }

        .form-card .card-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }

        /* Form Elemanları */
        .form-label {
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-text {
            font-size: 0.8rem;
            color: #8898aa;
            margin-top: 0.4rem;
        }

        /* Butonlar */
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-cancel {
            padding: 1rem 2rem;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            color: #343a40;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid modern-container">

        
        <div class="d-flex align-items-center mb-4">
            <a href="<?php echo e(route('maintenance.index')); ?>" class="btn btn-light rounded-circle shadow-sm me-3"
                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left text-primary"></i>
            </a>
            <div>
                <h4 class="fw-bold text-white mb-0" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Yeni Plan Oluştur</h4>
                <small class="text-white-50">Yeni bir bakım veya arıza kaydı oluşturun</small>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card form-card">
                    <div class="card-header">
                        <h4><i class="fas fa-calendar-plus me-2"></i>Plan Bilgileri</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                                    <h6 class="mb-0 fw-bold">Lütfen aşağıdaki hataları düzeltin:</h6>
                                </div>
                                <ul class="mb-0 ps-3">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('maintenance.store')); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-1 text-primary opacity-75"></i> Plan Başlığı / Arıza Tanımı
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title"
                                    value="<?php echo e(old('title')); ?>" placeholder="Örn: CNC Lazer Yıllık Bakımı" required>
                                <div class="form-text">Yapılacak işi özetleyen kısa ve anlaşılır bir başlık giriniz.</div>
                            </div>

                            <div class="row g-4">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="maintenance_type_id" class="form-label">
                                            <i class="fas fa-tags me-1 text-primary opacity-75"></i> Bakım Türü / Departman
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="maintenance_type_id" name="maintenance_type_id"
                                            required>
                                            <option value="">Seçiniz...</option>
                                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($type->id); ?>"
                                                    <?php echo e(old('maintenance_type_id') == $type->id ? 'selected' : ''); ?>>
                                                    <?php echo e($type->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="planned_start_date" class="form-label">
                                            <i class="fas fa-calendar-alt me-1 text-primary opacity-75"></i> Planlanan
                                            Başlangıç <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" class="form-control" id="planned_start_date"
                                            name="planned_start_date" value="<?php echo e(old('planned_start_date')); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="priority" class="form-label">
                                            <i class="fas fa-thermometer-half me-1 text-primary opacity-75"></i> Öncelik
                                            Durumu
                                        </label>
                                        <select class="form-select" id="priority" name="priority">
                                            <option value="low" <?php echo e(old('priority') == 'low' ? 'selected' : ''); ?>>
                                                🟢 Düşük (Planlı Rutin)
                                            </option>
                                            <option value="normal"
                                                <?php echo e(old('priority', 'normal') == 'normal' ? 'selected' : ''); ?>>
                                                🔵 Normal
                                            </option>
                                            <option value="high" <?php echo e(old('priority') == 'high' ? 'selected' : ''); ?>>
                                                🟠 Yüksek (Üretimi Etkiliyor)
                                            </option>
                                            <option value="critical" <?php echo e(old('priority') == 'critical' ? 'selected' : ''); ?>>
                                                🔴 KRİTİK (Acil Müdahale)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="maintenance_asset_id" class="form-label">
                                            <i class="fas fa-microchip me-1 text-primary opacity-75"></i> İlgili Varlık
                                            (Makine/Zone) <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="maintenance_asset_id" name="maintenance_asset_id"
                                            required>
                                            <option value="">Seçiniz...</option>
                                            <?php $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($asset->id); ?>"
                                                    <?php echo e(old('maintenance_asset_id') == $asset->id ? 'selected' : ''); ?>>
                                                    [<?php echo e(strtoupper($asset->category)); ?>] <?php echo e($asset->name); ?>

                                                    (<?php echo e($asset->code); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="form-text">Bakım yapılacak makineyi veya bölgeyi seçiniz.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="planned_end_date" class="form-label">
                                            <i class="fas fa-calendar-check me-1 text-primary opacity-75"></i> Tahmini Bitiş
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" class="form-control" id="planned_end_date"
                                            name="planned_end_date" value="<?php echo e(old('planned_end_date')); ?>" required>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mt-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1 text-primary opacity-75"></i> Detaylı Açıklama / İş
                                    Emri Notları
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="Yapılacak işlemlerin detaylarını, parça gereksinimlerini veya özel notları buraya giriniz..."><?php echo e(old('description')); ?></textarea>
                            </div>

                            
                            <div class="d-flex justify-content-end align-items-center mt-5 pt-3 border-top">
                                <a href="<?php echo e(route('maintenance.index')); ?>"
                                    class="btn btn-cancel me-3 text-decoration-none">
                                    İptal Et
                                </a>
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save me-2"></i>Planı Oluştur
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/maintenance/create.blade.php ENDPATH**/ ?>