

<?php $__env->startSection('title', 'Lojistik Aracı Düzenle'); ?>

<?php $__env->startPush('styles'); ?>
    
    <style>
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

        .create-vehicle-card {
            border-radius: 1rem;
            border: 0;
            background-color: transparent;
        }

        .create-vehicle-card .card-header {
            color: #000;
            font-weight: bold;
        }

        .create-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
        }

        .create-vehicle-card .form-control,
        .create-vehicle-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .create-vehicle-card .form-control:focus {
            background-color: #fff;
            border-color: #667EEA;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card create-vehicle-card">
                    <div class="card-header h4 bg-transparent border-0 pt-4">
                        <i class="fas fa-edit me-2"></i><?php echo e(__('Lojistik Aracı Düzenle')); ?>

                    </div>
                    <div class="card-body p-4">
                        
                        <form method="POST" action="<?php echo e(route('service.logistics-vehicles.update', $vehicle->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="plate_number" class="form-label">Plaka (*)</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number"
                                        value="<?php echo e(old('plate_number', $vehicle->plate_number)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Durum</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" <?php echo e($vehicle->status == 'active' ? 'selected' : ''); ?>>🟢 Aktif
                                        </option>
                                        <option value="maintenance"
                                            <?php echo e($vehicle->status == 'maintenance' ? 'selected' : ''); ?>>🟠 Bakımda</option>
                                        <option value="inactive" <?php echo e($vehicle->status == 'inactive' ? 'selected' : ''); ?>>🔴
                                            Pasif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marka (*)</label>
                                    <input type="text" class="form-control" id="brand" name="brand"
                                        value="<?php echo e(old('brand', $vehicle->brand)); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="model" class="form-label">Model (*)</label>
                                    <input type="text" class="form-control" id="model" name="model"
                                        value="<?php echo e(old('model', $vehicle->model)); ?>" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="capacity" class="form-label">Yük Kapasitesi (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="capacity" name="capacity"
                                        value="<?php echo e(old('capacity', $vehicle->capacity)); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="current_km" class="form-label">Güncel KM (*)</label>
                                    <input type="number" step="0.1" class="form-control" id="current_km"
                                        name="current_km" value="<?php echo e(old('current_km', $vehicle->current_km)); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fuel_type" class="form-label">Yakıt Tipi</label>
                                    <select name="fuel_type" id="fuel_type" class="form-select">
                                        <option value="Diesel" <?php echo e($vehicle->fuel_type == 'Diesel' ? 'selected' : ''); ?>>
                                            Dizel</option>
                                        <option value="Gasoline" <?php echo e($vehicle->fuel_type == 'Gasoline' ? 'selected' : ''); ?>>
                                            Benzin</option>
                                        <option value="Electric" <?php echo e($vehicle->fuel_type == 'Electric' ? 'selected' : ''); ?>>
                                            Elektrik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                    Değişiklikleri Kaydet
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/logistics_vehicles/edit.blade.php ENDPATH**/ ?>