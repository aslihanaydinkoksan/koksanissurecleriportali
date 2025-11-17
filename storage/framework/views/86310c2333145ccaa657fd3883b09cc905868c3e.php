

<?php $__env->startSection('title', 'Takımı Düzenle'); ?>

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

        .create-team-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .create-team-card .card-header,
        .create-team-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .create-team-card .card-header {
            color: #000;
        }

        .create-team-card .form-control,
        .create-team-card .form-select {
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
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card create-team-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4">
                        Takımı Düzenle: <?php echo e($team->name); ?>

                    </div>
                    <div class="card-body p-4">

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('teams.update', $team->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?> 

                            <div class="mb-3">
                                <label for="name" class="form-label">Takım Adı (*)</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    id="name" name="name" value="<?php echo e(old('name', $team->name)); ?>"
                                     required>
                            </div>

                            <div class="mb-3">
                                <label for="user_ids" class="form-label">Takım Üyeleri (*)</label>
                                <select name="user_ids[]" id="user_ids" class="form-select" multiple required
                                    size="10">

                                    
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" 
                                            <?php echo e(in_array($user->id, $selectedUserIds) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="form-text">
                                    Birden fazla kişi seçmek için (CTRL + Tık) veya (CMD + Tık) kullanın.
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                    Takımı Güncelle
                                </button>
                                <a href="<?php echo e(route('teams.index')); ?>" class="btn btn-outline-secondary rounded-3">
                                    İptal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/teams/edit.blade.php ENDPATH**/ ?>