

<?php $__env->startSection('title', 'Kullanıcıyı Düzenle'); ?>


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

    /* Ana Kart (Tam Şeffaf) */
    .user-edit-card {
        /* Bu sayfa için özel class adı */
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    /* Form Etiketleri (Okunabilirlik İçin) */
    .user-edit-card .card-header,
    .user-edit-card .form-label {
        color: #444;
        /* Koyu renk metin */
        font-weight: bold;
        /* Kalın Metin */
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .user-edit-card .card-header {
        color: #000;
        /* Başlık siyah kalsın */
    }

    /* Form Elemanları (Yumuşak Köşe + Opak Arka Plan) */
    .user-edit-card .form-control,
    .user-edit-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
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

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card user-edit-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Kullanıcı Bilgilerini Düzenle')); ?>:
                        <?php echo e($user->name); ?></div>

                    
                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Ad Soyad')); ?></label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                                        value="<?php echo e(old('name', $user->name)); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('E-posta Adresi')); ?></label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                        value="<?php echo e(old('email', $user->email)); ?>" required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <label for="role"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Kullanıcı Rolü')); ?></label>
                                <div class="col-md-6">
                                    <select name="role" id="role"
                                        class="form-select <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <?php if(Auth::user()->role === 'admin'): ?>
                                            <option value="admin" <?php if(old('role', $user->role) == 'admin'): ?> selected <?php endif; ?>>Admin
                                            </option>
                                        <?php endif; ?>
                                        <option value="yönetici" <?php if(old('role', $user->role) == 'yönetici'): ?> selected <?php endif; ?>>Yönetici
                                        </option>
                                        <option value="kullanıcı" <?php if(old('role', $user->role) == 'kullanıcı'): ?> selected <?php endif; ?>>
                                            Kullanıcı</option>
                                    </select>
                                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <label for="department_id"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Birim')); ?></label>
                                <div class="col-md-6">
                                    <select name="department_id" id="department_id"
                                        class="form-select <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Birim Seçiniz...</option>
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($department->id); ?>"
                                                <?php if(old('department_id') == $department->id): ?> selected <?php endif; ?>>
                                                <?php echo e($department->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>

                                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            

                            <hr style="border-top: 1px solid rgba(0,0,0,0.2);"> 
                            <p class="text-center"
                                style="color: #444; font-weight: bold; text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);">
                                Şifreyi değiştirmek istemiyorsanız bu alanı boş bırakın.</p>

                            
                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Yeni Şifre')); ?></label>
                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                        autocomplete="new-password">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Yeni Şifreyi Onayla')); ?></label>
                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" autocomplete="new-password">
                                </div>
                            </div>

                            
                            <div class="row mb-0 mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                        Değişiklikleri Kaydet
                                    </button>
                                    <a href="<?php echo e(route('home')); ?>"
                                        class="btn btn-outline-secondary rounded-3 ms-2">İptal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/users/edit.blade.php ENDPATH**/ ?>