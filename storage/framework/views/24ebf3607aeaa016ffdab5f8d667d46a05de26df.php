

<?php $__env->startSection('title', 'Araç Atamasını Düzenle'); ?>

<style>
    /* create.blade.php ile aynı stiller */
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

    [x-cloak] {
        display: none !important;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card create-assignment-card" x-data="{
                    // Görev tipi: vehicle veya general
                    assignmentType: '<?php echo e(old('assignment_type', $assignment->assignment_type)); ?>',
                
                    // Araç tipi: company veya logistics (Controller'dan gelmeli veya modelden çekilmeli)
                    vehicleType: '<?php echo e(old('vehicle_type', $assignment->vehicle->type ?? '')); ?>',
                
                    // Sorumlu tipi: user veya team (Modelden çekilen Class ismine göre ayarlanır)
                    responsibleType: '<?php echo e(old('responsible_type', $assignment->responsible_type === App\Models\User::class ? 'user' : ($assignment->responsible_type === App\Models\Team::class ? 'team' : 'user'))); ?>',
                
                    // Sadece sorumlu seçimi için required mantığı
                    isRequired(type) {
                        return this.responsibleType === type;
                    }
                }" x-cloak>
                    
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span><?php echo e(__('Araç Atamasını Düzenle')); ?></span>
                        <?php if(Auth::user()->role === 'admin'): ?>
                            <form method="POST" action="<?php echo e(route('service.assignments.destroy', $assignment->id)); ?>"
                                onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Görevi
                                    Sil</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('service.assignments.update', $assignment->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Görev Başlığı (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="title" name="title" value="<?php echo e(old('title', $assignment->title)); ?>" required>
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

                            
                            <div class="mb-3">
                                <label for="status" class="form-label">Görev Durumu (*)</label>
                                <select name="status" id="status"
                                    class="form-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <?php $__currentLoopData = ['pending' => 'Bekliyor', 'in_progress' => 'Devam Ediyor', 'completed' => 'Tamamlandı', 'cancelled' => 'İptal Edildi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e(old('status', $assignment->status) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($value); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                            
                            <div class="mb-3">
                                <label class="form-label">Atanan Sefer Zamanı
                                    (Değiştirilemez)</label>
                                <input type="text" class="form-control"
                                    value="<?php echo e($assignment->start_time->format('d.m.Y H:i')); ?> Seferi" disabled readonly>
                                <small class="form-text text-muted">Bir görevin zamanını
                                    değiştirmek için silip yeniden
                                    eklemeniz gerekmektedir.</small>
                            </div>

                            <hr>

                            
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
                                            <?php echo e(old('vehicle_id', $assignment->vehicle_id) == $vehicle->id ? 'selected' : ''); ?>>
                                            <?php echo e($vehicle->plate_number); ?>

                                            (<?php echo e($vehicle->type); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['vehicle_id'];
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

                            <hr class="mt-4 mb-4">
                            <h5 class="mb-3 fw-bold">Sorumlu Güncelleme</h5>

                            <div class="row mb-3">
                                
                                <div class="col-md-6 mb-3">
                                    <label for="responsibleTypeSelect" class="form-label">Sorumlu Tipi</label>
                                    <select id="responsibleTypeSelect" x-model="responsibleType" class="form-select">
                                        <option value="user">Tek Kişi</option>
                                        <option value="team">Takım</option>
                                    </select>
                                </div>

                                
                                <div class="col-md-6 mb-3">
                                    <input type="hidden" name="responsible_type_field" :value="responsibleType">

                                    
                                    <div x-show="responsibleType === 'user'">
                                        <label for="responsible_user_id" class="form-label">👤 Sorumlu Kişi (*)</label>
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
                                            :required="isRequired('user')">
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

                                    
                                    <div x-show="responsibleType === 'team'">
                                        <label for="responsible_team_id" class="form-label">👥 Sorumlu Takım (*)</label>
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
                                            :required="isRequired('team')">
                                            <option value="">Takım seçiniz...</option>
                                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($team->id); ?>"
                                                    <?php echo e(old('responsible_id', $assignment->responsible_id) == $team->id && $assignment->responsible_type === App\Models\Team::class ? 'selected' : ''); ?>>
                                                    <?php echo e($team->name); ?> (<?php echo e($team->users_count); ?> kişi)
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
                                </div>
                            </div>
                            
                            <?php if($assignment->isLogistics()): ?>
                                <hr class="mt-4 mb-4">
                                <h5 class="mb-3 fw-bold">Nakliye Detayları (KM & Yakıt)</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="initial_km" class="form-label">Başlangıç KM</label>
                                        <input type="number" step="0.01" name="initial_km" id="initial_km"
                                            class="form-control" value="<?php echo e(old('initial_km', $assignment->start_km)); ?>"
                                            disabled readonly>
                                        <small class="form-text text-muted">Başlangıç KM değiştirilemez.</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="initial_fuel" class="form-label">Başlangıç Yakıt Durumu</label>
                                        <input type="text" name="initial_fuel" id="initial_fuel" class="form-control"
                                            value="<?php echo e(old('initial_fuel', $assignment->start_fuel_level)); ?>" disabled
                                            readonly>
                                        <small class="form-text text-muted">Başlangıç Yakıt Durumu değiştirilemez.</small>
                                    </div>
                                </div>

                                <hr>
                                <h6 class="fw-bold">Görev Tamamlama Detayları</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="final_km" class="form-label">Bitiş KM</label>
                                        <input type="number" step="0.01" name="final_km" id="final_km"
                                            class="form-control" value="<?php echo e(old('final_km', $assignment->end_km)); ?>">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="final_fuel" class="form-label">Bitiş Yakıt Durumu</label>
                                        <select name="final_fuel" id="final_fuel" class="form-select">
                                            <option value="">Seçiniz...</option>
                                            <?php $__currentLoopData = ['full', '3/4', '1/2', '1/4', 'empty']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($level); ?>"
                                                    <?php echo e(old('final_fuel', $assignment->end_fuel_level) == $level ? 'selected' : ''); ?>>
                                                    <?php echo e($level); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="fuel_cost" class="form-label">Yakıt Maliyeti (TL)</label>
                                        <input type="number" step="0.01" name="fuel_cost" id="fuel_cost"
                                            class="form-control" value="<?php echo e(old('fuel_cost', $assignment->fuel_cost)); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <hr class="mt-4 mb-4">

                            <div class="mb-3">
                                <label for="task_description" class="form-label">Görev
                                    Açıklaması (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['task_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="task_description" name="task_description"
                                    value="<?php echo e(old('task_description', $assignment->task_description)); ?>" required>
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
                                <label for="destination" class="form-label">Yer /
                                    Gidilecek Nokta</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['destination'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="destination" name="destination"
                                    value="<?php echo e(old('destination', $assignment->destination)); ?>">
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
                                <label for="requester_name" class="form-label">Talep Eden
                                    Kişi / Departman</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['requester_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="requester_name" name="requester_name"
                                    value="<?php echo e(old('requester_name', $assignment->requester_name)); ?>">
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
unset($__errorArgs, $__bag); ?>" id="notes" name="notes" rows="3"><?php echo e(old('notes', $assignment->notes)); ?></textarea>
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

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
                                    Kaydet</button>
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

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/service/assignments/edit.blade.php ENDPATH**/ ?>