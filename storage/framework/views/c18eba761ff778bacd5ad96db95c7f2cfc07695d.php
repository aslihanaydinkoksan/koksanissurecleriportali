

<?php $__env->startSection('title', 'Kullanıcıyı Düzenle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #667EEA,
                    #764BA2,
                    #F093FB,
                    #4FD1C5,
                    #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        /* Animated floating particles */
        #app>main.py-4::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {

            0%,
            100% {
                transform: translate(0, 0);
            }

            33% {
                transform: translate(30px, -30px);
            }

            66% {
                transform: translate(-20px, 20px);
            }
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

        /* Modern Glassmorphism Card */
        .user-edit-card {
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow:
                0 8px 32px 0 rgba(31, 38, 135, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.18) inset;
            border: 1px solid rgba(255, 255, 255, 0.3);
            overflow: hidden;
            position: relative;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-edit-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 12px 40px 0 rgba(31, 38, 135, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.18) inset;
        }

        /* Card Header with User Info */
        .user-edit-card .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            color: #2d3748;
            font-weight: 700;
            font-size: 1.75rem;
            padding: 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .user-edit-card .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .user-edit-card:hover .card-header::before {
            left: 100%;
        }

        .user-edit-card .card-header .header-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-edit-card .card-header .user-icon {
            font-size: 2rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }
        }

        .user-edit-card .card-header .user-name {
            display: block;
            font-size: 1rem;
            font-weight: 400;
            opacity: 0.8;
            margin-top: 0.25rem;
        }

        /* Form Labels */
        .user-edit-card .form-label,
        .user-edit-card .col-form-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-edit-card .col-form-label::before {
            content: '';
            width: 4px;
            height: 16px;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border-radius: 2px;
        }

        /* Modern Form Controls */
        .user-edit-card .form-control,
        .user-edit-card .form-select {
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .user-edit-card .form-control:focus,
        .user-edit-card .form-select:focus {
            background-color: #ffffff;
            border-color: #667EEA;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .user-edit-card .form-control:hover,
        .user-edit-card .form-select:hover {
            border-color: rgba(102, 126, 234, 0.4);
        }

        /* Input Icons */
        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper .input-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.2rem;
            transition: color 0.3s ease;
            pointer-events: none;
        }

        .input-icon-wrapper .form-control:focus~.input-icon,
        .input-icon-wrapper .form-select:focus~.input-icon {
            color: #667EEA;
        }

        /* Password Toggle Icon */
        .password-toggle-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .password-toggle-icon:hover {
            color: #667EEA;
            transform: translateY(-50%) scale(1.1);
        }

        /* Password Section Divider */
        .password-divider {
            margin: 2rem 0;
            padding: 1.5rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(240, 147, 251, 0.05));
            border-radius: 1rem;
            border-left: 4px solid #667EEA;
        }

        .password-divider-text {
            color: #4a5568;
            font-size: 0.9rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .password-divider-text::before {
            content: '🔒';
            font-size: 1.2rem;
        }

        /* Role & Department Select Wrappers */
        .role-select-wrapper,
        .department-select-wrapper {
            position: relative;
        }

        .role-select-wrapper::before {
            content: '🎭';
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            z-index: 1;
            pointer-events: none;
        }

        .department-select-wrapper::before {
            content: '🏢';
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            z-index: 1;
            pointer-events: none;
        }

        .role-select-wrapper .form-select,
        .department-select-wrapper .form-select {
            padding-left: 3rem;
        }

        /* Animated Gradient Button */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #764BA2, #F093FB, #4FD1C5);
            background-size: 300% 300%;
            animation: gradientWave 6s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-animated-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-animated-gradient:hover::before {
            left: 100%;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-animated-gradient:active {
            transform: translateY(0);
        }

        /* Cancel Button */
        .btn-outline-secondary {
            border: 2px solid #cbd5e0;
            color: #4a5568;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: #f7fafc;
            border-color: #a0aec0;
            color: #2d3748;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* Card Body */
        .user-edit-card .card-body {
            padding: 2.5rem;
        }

        /* Invalid Feedback */
        .invalid-feedback {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            animation: shake 0.4s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .user-edit-card .card-body {
                padding: 1.5rem;
            }

            .user-edit-card .card-header {
                font-size: 1.5rem;
                padding: 1.25rem 1.5rem;
            }

            .btn-animated-gradient,
            .btn-outline-secondary {
                width: 100%;
                margin-top: 0.5rem;
            }

            .user-edit-card .col-form-label::before {
                display: none;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card user-edit-card">
                    <div class="card-header border-0">
                        <div class="header-content">
                            <span class="user-icon">✏️</span>
                            <div>
                                <div><?php echo e(__('Kullanıcı Bilgilerini Düzenle')); ?></div>
                                <span class="user-name"><?php echo e($user->name); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('users.update', $user->id)); ?>" id="editUserForm">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            
                            <div class="row mb-4">
                                <label for="name" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Ad Soyad')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="input-icon-wrapper">
                                        <input id="name" type="text"
                                            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                                            value="<?php echo e(old('name', $user->name)); ?>" required autocomplete="name">
                                        <span class="input-icon">👤</span>
                                    </div>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-4">
                                <label for="email" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('E-posta Adresi')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="input-icon-wrapper">
                                        <input id="email" type="email"
                                            class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                            value="<?php echo e(old('email', $user->email)); ?>" required autocomplete="email">
                                        <span class="input-icon">✉️</span>
                                    </div>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-4">
                                <label for="role" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Kullanıcı Rolü')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="role-select-wrapper">
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
                                                <option value="admin" <?php if(old('role', $user->role) == 'admin'): ?> selected <?php endif; ?>>
                                                    Admin
                                                </option>
                                            <?php endif; ?>
                                            <option value="yönetici" <?php if(old('role', $user->role) == 'yönetici'): ?> selected <?php endif; ?>>
                                                Yönetici
                                            </option>
                                            <option value="kullanıcı" <?php if(old('role', $user->role) == 'kullanıcı'): ?> selected <?php endif; ?>>
                                                Kullanıcı
                                            </option>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-4">
                                <label for="department_id" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Birim')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="department-select-wrapper">
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
                                                    <?php if(old('department_id', $user->department_id) == $department->id): ?> selected <?php endif; ?>>
                                                    <?php echo e($department->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="password-divider">
                                <p class="password-divider-text">
                                    Şifrenizi değiştirmek istemiyorsanız bu alanları boş bırakın
                                </p>
                            </div>

                            
                            <div class="row mb-4">
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Yeni Şifre')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div style="position: relative;">
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
                                        <span class="password-toggle-icon" onclick="togglePassword('password')">
                                            👁️
                                        </span>
                                    </div>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-4">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Yeni Şifreyi Onayla')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div style="position: relative;">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" autocomplete="new-password">
                                        <span class="password-toggle-icon" onclick="togglePassword('password-confirm')">
                                            👁️
                                        </span>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row mb-0 mt-5">
                                <div class="col-md-6 offset-md-4 d-flex gap-2">
                                    <button type="submit" class="btn btn-animated-gradient">
                                        💾 Değişiklikleri Kaydet
                                    </button>
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
                                        ← İptal
                                    </a>
                                </div>
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
        // Şifre göster/gizle
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }

        // Form submit animasyonu
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('.btn-animated-gradient');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '⏳ Kaydediliyor...';
        });

        // Input animasyonları
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.transform = 'translateY(-2px)';
            });

            input.addEventListener('blur', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/users/edit.blade.php ENDPATH**/ ?>