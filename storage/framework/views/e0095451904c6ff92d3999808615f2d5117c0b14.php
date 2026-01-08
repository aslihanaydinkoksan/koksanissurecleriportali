

<?php $__env->startSection('title', 'Yeni Makine Ekle'); ?>

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
            <a href="<?php echo e(route('maintenance.assets.index')); ?>" class="btn btn-light rounded-circle shadow-sm me-3"
                style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left text-primary"></i>
            </a>
            <div>
                <h4 class="fw-bold text-white mb-0" style="text-shadow: 0 2px 4px rgba(0,0,0,0.1);">Yeni Makine Oluştur</h4>
                <small class="text-white-50">Sisteme yeni bir makine veya bölge tanımlayın</small>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card form-card">
                    <div class="card-header">
                        <h4><i class="fas fa-cube me-2"></i>Makine Bilgileri</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Dikkat!</strong> Lütfen aşağıdaki hataları kontrol edin.
                                </div>
                                <ul class="mb-0 ps-3">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('maintenance.assets.store')); ?>" method="POST" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-4">
                                <label for="name" class="form-label">
                                    <i class="fas fa-font me-1 text-primary opacity-75"></i> Makine Adı <span
                                        class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name"
                                    value="<?php echo e(old('name')); ?>" placeholder="Örn: Husky HyPET 300 Enjeksiyon" required>
                            </div>

                            <div class="row g-4">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">
                                            <i class="fas fa-barcode me-1 text-primary opacity-75"></i> Varlık Kodu <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="code" name="code"
                                            value="<?php echo e(old('code')); ?>" placeholder="Örn: MACH-001" required>
                                        <div class="form-text">Demirbaş kodu veya dahili takip kodu.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category" class="form-label">
                                            <i class="fas fa-layer-group me-1 text-primary opacity-75"></i> Kategori <span
                                                class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="category" name="category" required>
                                            <option value="">Seçiniz...</option>
                                            <option value="machine" <?php echo e(old('category') == 'machine' ? 'selected' : ''); ?>>
                                                Makine (Üretim/Yardımcı)</option>
                                            <option value="zone" <?php echo e(old('category') == 'zone' ? 'selected' : ''); ?>>Bölge
                                                (Zone/Oda)</option>
                                            <option value="facility" <?php echo e(old('category') == 'facility' ? 'selected' : ''); ?>>
                                                Tesisat / Sistem</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="brand" class="form-label">Marka</label>
                                        <input type="text" class="form-control" id="brand" name="brand"
                                            value="<?php echo e(old('brand')); ?>" placeholder="Örn: Netstal, Husky">
                                    </div>

                                    <div class="mb-3">
                                        <label for="model" class="form-label">Model</label>
                                        <input type="text" class="form-control" id="model" name="model"
                                            value="<?php echo e(old('model')); ?>" placeholder="Örn: S7-1200">
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="location" class="form-label">
                                            <i class="fas fa-map-marker-alt me-1 text-primary opacity-75"></i> Lokasyon /
                                            Konum
                                        </label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="<?php echo e(old('location')); ?>" placeholder="Örn: Preform">
                                    </div>

                                    <div class="mb-3">
                                        <label for="serial_number" class="form-label">Seri Numarası</label>
                                        <input type="text" class="form-control" id="serial_number" name="serial_number"
                                            value="<?php echo e(old('serial_number')); ?>" placeholder="Cihaz üzerindeki seri no">
                                    </div>

                                    <div class="mb-3">
                                        <label for="manufacturing_year" class="form-label">Üretim Yılı</label>
                                        <input type="number" class="form-control" id="manufacturing_year"
                                            name="manufacturing_year" value="<?php echo e(old('manufacturing_year')); ?>"
                                            min="1950" max="<?php echo e(date('Y') + 1); ?>" placeholder="YYYY">
                                    </div>

                                    
                                    <div class="mb-3">
                                        <label for="is_active" class="form-label">
                                            <i class="fas fa-toggle-on me-1 text-primary opacity-75"></i> Durum
                                        </label>
                                        <select class="form-select" id="is_active" name="is_active">
                                            <option value="1" <?php echo e(old('is_active', 1) == 1 ? 'selected' : ''); ?>>Aktif
                                            </option>
                                            <option value="0" <?php echo e(old('is_active') == 0 ? 'selected' : ''); ?>>Pasif
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mt-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1 text-primary opacity-75"></i> Teknik Açıklama / Notlar
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    placeholder="Makine hakkında teknik detaylar veya özel notlar..."><?php echo e(old('description')); ?></textarea>
                            </div>

                            
                            <div class="d-flex justify-content-end align-items-center mt-5 pt-3 border-top">
                                <a href="<?php echo e(route('maintenance.assets.index')); ?>"
                                    class="btn btn-cancel me-3 text-decoration-none">
                                    İptal Et
                                </a>
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save me-2"></i>Makineyi Kaydet
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/maintenance/assets/create.blade.php ENDPATH**/ ?>