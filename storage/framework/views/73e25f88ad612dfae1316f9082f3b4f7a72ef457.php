

<?php $__env->startSection('title', 'Araç Atamasını Düzenle'); ?>

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

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .edit-assignment-card {
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-header .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .section-header h5 {
            margin: 0;
            color: #1f2937;
            font-weight: 600;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.8), rgba(191, 219, 254, 0.8));
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
        }

        .info-box::before {
            content: "💡";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
        }

        .info-box-content {
            margin-left: 2.5rem;
            color: #1e40af;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .warning-box {
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.8), rgba(253, 230, 138, 0.8));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
        }

        .warning-box::before {
            content: "⚠️";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
        }

        .warning-box-content {
            margin-left: 2.5rem;
            color: #92400e;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .readonly-field {
            background: linear-gradient(135deg, rgba(243, 244, 246, 0.8), rgba(229, 231, 235, 0.8));
            border: 2px solid #d1d5db;
            cursor: not-allowed;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
        }

        .status-in_progress {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
        }

        .status-completed {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
        }

        .selection-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .selection-card:hover {
            border-color: #667EEA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .selection-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .selection-card input[type="radio"]:checked~.card-content {
            border-left: 4px solid #667EEA;
            padding-left: 1rem;
        }

        .selection-card input[type="radio"]:checked~.card-content .card-icon {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
        }

        .card-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .card-text h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }

        .card-text p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        [x-cloak] {
            display: none !important;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $canChangeResponsible = Auth::user()->id === $assignment->user_id || Auth::user()->role === 'admin';
        $disableInput = $canChangeResponsible ? '' : 'disabled';
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card edit-assignment-card" x-data="{
                    vehicleType: '<?php echo e(old('vehicle_type', $assignment->isLogistics() ? 'logistics' : 'company')); ?>',
                    responsibleType: '<?php echo e(old('responsible_type', $assignment->responsible_type === App\Models\User::class ? 'user' : ($assignment->responsible_type === App\Models\Team::class ? 'team' : 'user'))); ?>',
                    status: '<?php echo e(old('status', $assignment->status)); ?>',
                    isLogistics() {
                        return this.vehicleType === 'logistics';
                    }
                }"x-cloak>

                    <div class="card-header bg-transparent border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">✏️ Görev Düzenleme</h4>
                                <p class="text-muted mb-0">Görev detaylarını güncelleyin</p>
                            </div>
                            <?php if(Auth::user()->role === 'admin'): ?>
                                <form method="POST" action="<?php echo e(route('service.assignments.destroy', $assignment->id)); ?>"
                                    onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Görevi Sil
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body px-4 py-3">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger rounded-3">
                                <strong>⚠️ Hata!</strong> Lütfen aşağıdaki sorunları düzeltin:
                                <ul class="mb-0 mt-2">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('service.assignments.update', $assignment->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="vehicle_type" :value="vehicleType">
                            <input type="hidden" name="responsible_type" value="<?php echo e($assignment->responsible_type); ?>">
                            <input type="hidden" name="responsible_id" value="<?php echo e($assignment->responsible_id); ?>">

                            <?php if($assignment->requiresVehicle()): ?>
                                <input type="hidden" name="vehicle_id" value="<?php echo e($assignment->vehicle_id); ?>">
                            <?php else: ?>
                                <input type="hidden" name="vehicle_id" value="">
                            <?php endif; ?>

                            
                            <div class="section-header">
                                <div class="icon">📋</div>
                                <h5>Görev Bilgileri</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">📢 Görev Başlığı *</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="title" name="title" value="<?php echo e(old('title', $assignment->title)); ?>"
                                        required>
                                    <?php $__errorArgs = ['title'];
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

                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">🔄 Görev Durumu *</label>
                                    <select name="status" id="status" x-model="status"
                                        class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="pending">⏳ Bekliyor</option>
                                        <option value="in_progress">🔄 Devam Ediyor</option>
                                        <option value="completed">✅ Tamamlandı</option>
                                        <option value="cancelled">❌ İptal Edildi</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
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

                            <div class="info-box mb-4">
                                <div class="info-box-content">
                                    <strong>Sefer Zamanı:</strong> <?php echo e($assignment->start_time->format('d.m.Y H:i')); ?>

                                    <br>
                                    <small>Görev zamanını değiştirmek için görevi silip yeniden oluşturmanız
                                        gerekmektedir.</small>
                                </div>
                            </div>

                            
                            <div class="section-header">
                                <div class="icon">🚗</div>
                                <h5>Araç Bilgileri</h5>
                            </div>
                            <?php if($assignment->vehicle_id): ?> 
                                <div class="mb-4">
                                    <label for="vehicle_id" class="form-label">
                                        <span x-show="vehicleType === 'company'">🚙</span>
                                        <span x-show="vehicleType === 'logistics'">🚚</span>
                                        Araç Seçimi *
                                    </label>

                                    
                                    <div x-show="vehicleType === 'company'">
                                        <select name="vehicle_id" class="form-select"
                                            :disabled="vehicleType !== 'company'">
                                            <option value="">Araç Seçiniz...</option>
                                            <?php $__currentLoopData = $companyVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($vehicle->id); ?>"
                                                    <?php echo e($assignment->vehicle_id == $vehicle->id && !$assignment->isLogistics() ? 'selected' : ''); ?>>
                                                    <?php echo e($vehicle->plate_number); ?> -
                                                    <?php echo e($vehicle->brand_model ?? $vehicle->model); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    
                                    <div x-show="vehicleType === 'logistics'">
                                        <select name="vehicle_id" class="form-select"
                                            :disabled="vehicleType !== 'logistics'">
                                            <option value="">Nakliye Aracı Seçiniz...</option>
                                            <?php $__currentLoopData = $logisticsVehicles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($vehicle->id); ?>"
                                                    <?php echo e($assignment->vehicle_id == $vehicle->id && $assignment->isLogistics() ? 'selected' : ''); ?>>
                                                    <?php echo e($vehicle->plate_number); ?> - <?php echo e($vehicle->brand); ?>

                                                    <?php echo e($vehicle->model); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['vehicle_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="vehicle_id" value="">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i> Bu görev için araç ataması gerekmemektedir.
                                </div>
                            <?php endif; ?>
                            
                            <div x-show="isLogistics()" class="fade-in">
                                <div class="section-header">
                                    <div class="icon">📊</div>
                                    <h5>Nakliye Detayları (KM & Yakıt)</h5>
                                </div>

                                <div class="warning-box mb-4">
                                    <div class="warning-box-content">
                                        <strong>Not:</strong> Başlangıç KM ve yakıt durumu değiştirilemez. Sadece bitiş
                                        değerlerini güncelleyebilirsiniz.
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">📍 Başlangıç KM</label>
                                        <input type="text" class="form-control readonly-field"
                                            value="<?php echo e(number_format($assignment->start_km, 2)); ?> km" disabled readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">⛽ Başlangıç Yakıt Durumu</label>
                                        <input type="text" class="form-control readonly-field"
                                            value="<?php echo e($assignment->start_fuel_level); ?>" disabled readonly>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <label for="final_km" class="form-label">🏁 Bitiş KM</label>
                                        <input type="number" step="0.01" name="final_km" id="final_km"
                                            class="form-control" value="<?php echo e(old('final_km', $assignment->end_km)); ?>"
                                            placeholder="Örn: 125250.75">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="final_fuel" class="form-label">⛽ Bitiş Yakıt Durumu</label>
                                        <select name="final_fuel" id="final_fuel" class="form-select">
                                            <option value="">Seçiniz...</option>
                                            <?php $__currentLoopData = ['full' => 'Dolu (Full)', '3/4' => '3/4', '1/2' => '1/2 (Yarım)', '1/4' => '1/4', 'empty' => 'Boş']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($level); ?>"
                                                    <?php echo e(old('final_fuel', $assignment->end_fuel_level) == $level ? 'selected' : ''); ?>>
                                                    <?php echo e($label); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="fuel_cost" class="form-label">💰 Yakıt Maliyeti (TL)</label>
                                        <input type="number" step="0.01" name="fuel_cost" id="fuel_cost"
                                            class="form-control" value="<?php echo e(old('fuel_cost', $assignment->fuel_cost)); ?>"
                                            placeholder="Örn: 1250.50">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="section-header">
                                <div class="icon">👥</div>
                                <h5>Sorumlu Atama</h5>
                                <?php if(!$canChangeResponsible): ?>
                                    <span class="ms-3 text-muted small">(Sadece Görevi Atayan Değiştirebilir)</span>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sorumlu Tipi</label>
                                <div class="d-flex gap-2">
                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" x-model="responsibleType" value="user"
                                            <?php echo e($disableInput); ?>>
                                        <div class="card-content">
                                            <div class="card-icon">👤</div>
                                            <div class="card-text">
                                                <h6>Tek Kişi</h6>
                                                <p>Bireysel atama</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" x-model="responsibleType" value="team"
                                            <?php echo e($disableInput); ?>>
                                        <div class="card-content">
                                            <div class="card-icon">👥</div>
                                            <div class="card-text">
                                                <h6>Takım</h6>
                                                <p>Grup ataması</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <input type="hidden" name="responsible_type_field" :value="responsibleType">

                            <div x-show="responsibleType === 'user'" class="mb-4 fade-in">
                                <label for="responsible_user_id" class="form-label">👤 Sorumlu Kişi *</label>
                                <select :name="responsibleType === 'user' ? 'responsible_user_id' : ''"
                                    id="responsible_user_id"
                                    class="form-select <?php $__errorArgs = ['responsible_user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    :required="responsibleType === 'user'" <?php echo e($disableInput); ?>>
                                    <option value="">Kişi seçiniz...</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>"
                                            <?php echo e(old('responsible_id', $assignment->responsible_id) == $user->id && $assignment->responsible_type === App\Models\User::class ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['responsible_user_id'];
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

                            <div x-show="responsibleType === 'team'" class="mb-4 fade-in">
                                <label for="responsible_team_id" class="form-label">👥 Sorumlu Takım *</label>
                                <select :name="responsibleType === 'team' ? 'responsible_team_id' : ''"
                                    id="responsible_team_id"
                                    class="form-select <?php $__errorArgs = ['responsible_team_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    :required="responsibleType === 'team'" <?php echo e($disableInput); ?>>
                                    <option value="">Takım seçiniz...</option>
                                    <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($team->id); ?>"
                                            <?php echo e(old('responsible_id', $assignment->responsible_id) == $team->id && $assignment->responsible_type === App\Models\Team::class ? 'selected' : ''); ?>>
                                            <?php echo e($team->name); ?> (<?php echo e($team->users_count ?? 0); ?> kişi)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['responsible_team_id'];
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

                            
                            <div class="section-header">
                                <div class="icon">📝</div>
                                <h5>Görev Detayları</h5>
                            </div>

                            <div class="mb-4">
                                <label for="task_description" class="form-label">📋 Görev Açıklaması *</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['task_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="task_description" name="task_description"
                                    value="<?php echo e(old('task_description', $assignment->task_description)); ?>" required
                                    <?php echo e($disableInput === 'disabled' ? 'readonly' : ''); ?>>

                                <?php if($disableInput === 'disabled'): ?>
                                    <small class="form-text text-muted">Bu alanı yalnızca görevi atayan kişi
                                        düzenleyebilir.</small>
                                <?php endif; ?>

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
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">📍 Hedef Konum</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="destination" name="destination"
                                        value="<?php echo e(old('destination', $assignment->destination)); ?>"
                                        placeholder="Örn: Merkez Ofis, İstanbul Şubesi">
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

                                <div class="col-md-6 mb-3">
                                    <label for="requester_name" class="form-label">🙋 Talep Eden Kişi / Departman</label>
                                    <input type="text"
                                        class="form-control <?php $__errorArgs = ['requester_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="requester_name" name="requester_name"
                                        value="<?php echo e($assignment->createdBy->name ?? 'Bilinmiyor'); ?>" disabled readonly>
                                    <small class="form-text text-muted">Bu görev, görev atan kişi tarafından
                                        oluşturulmuştur.</small>
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
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">📌 Ek Notlar</label>
                                <textarea class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="notes" name="notes" rows="3"
                                    placeholder="Varsa ek bilgiler veya önemli notlar..."><?php echo e(old('notes', $assignment->notes)); ?></textarea>
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

                            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                <a href="<?php echo e(route('service.assignments.index')); ?>"
                                    class="btn btn-outline-secondary btn-lg">
                                    ← İptal
                                </a>
                                <button type="submit" class="btn btn-animated-gradient btn-lg">
                                    💾 Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/assignments/edit.blade.php ENDPATH**/ ?>