

<?php $__env->startSection('title', 'Yeni Kullanıcı Kaydı'); ?>

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
        .user-create-card {
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

        .user-create-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 12px 40px 0 rgba(31, 38, 135, 0.2),
                0 0 0 1px rgba(255, 255, 255, 0.18) inset;
        }

        /* Card Header with Gradient */
        .user-create-card .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            color: #2d3748;
            font-weight: 700;
            font-size: 1.75rem;
            padding: 1.5rem 2rem;
            position: relative;
            overflow: hidden;
        }

        .user-create-card .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .user-create-card:hover .card-header::before {
            left: 100%;
        }

        /* User Plus Icon */
        .user-create-card .card-header::after {
            content: '👥➕';
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            opacity: 0.3;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: translateY(-50%) scale(1);
            }

            50% {
                transform: translateY(-50%) scale(1.1);
            }
        }

        /* Form Labels */
        .user-create-card .form-label,
        .user-create-card .col-form-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .user-create-card .col-form-label::before {
            content: '';
            width: 4px;
            height: 16px;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border-radius: 2px;
        }

        /* Modern Form Controls */
        .user-create-card .form-control,
        .user-create-card .form-select {
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.9);
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .user-create-card .form-control:focus,
        .user-create-card .form-select:focus {
            background-color: #ffffff;
            border-color: #667EEA;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .user-create-card .form-control:hover,
        .user-create-card .form-select:hover {
            border-color: rgba(102, 126, 234, 0.4);
        }

        /* Password Toggle Button */
        .user-create-card .input-group {
            position: relative;
        }

        .user-create-card .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            padding-right: 1rem;
        }

        .user-create-card .input-group .toggle-password {
            border-radius: 0.75rem;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-left: none;
            color: #667EEA;
            transition: all 0.3s ease;
            padding: 0.75rem 1rem;
        }

        .user-create-card .input-group .toggle-password:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(240, 147, 251, 0.2));
            color: #764BA2;
            transform: scale(1.05);
        }

        .user-create-card .input-group .toggle-password svg {
            transition: transform 0.3s ease;
        }

        .user-create-card .input-group .toggle-password:hover svg {
            transform: rotate(15deg);
        }

        /* Success Alert */
        .alert-success {
            border-radius: 1rem;
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 178, 172, 0.1));
            border: 2px solid rgba(72, 187, 120, 0.3);
            color: #2f855a;
            padding: 1rem 1.5rem;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Role Select Special Styling */
        .role-select-wrapper {
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

        .role-select-wrapper .form-select {
            padding-left: 3rem;
        }

        /* Department Select Special Styling */
        .department-select-wrapper {
            position: relative;
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

        .department-select-wrapper .form-select {
            padding-left: 3rem;
        }

        /* Smooth Row Transition */
        #department-row {
            transition: all 0.3s ease;
        }

        /* Card Body */
        .user-create-card .card-body {
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
            .user-create-card .card-body {
                padding: 1.5rem;
            }

            .user-create-card .card-header {
                font-size: 1.5rem;
                padding: 1.25rem 1.5rem;
            }

            .btn-animated-gradient {
                width: 100%;
            }

            .user-create-card .col-form-label::before {
                display: none;
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card user-create-card">
                    <div class="card-header border-0">
                        <?php echo e(__('Yeni Kullanıcı Oluştur')); ?>

                    </div>

                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>✓</strong> <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('users.store')); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            
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
                                            value="<?php echo e(old('name')); ?>" required autocomplete="off">
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
                                            value="<?php echo e(old('email')); ?>" placeholder="ornek@koksan.com" required
                                            autocomplete="off">
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
                                <label for="password" class="col-md-4 col-form-label text-md-end">
                                    <?php echo e(__('Şifre')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="password" type="password"
                                            class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                            required autocomplete="off">
                                        <button class="btn toggle-password" type="button" data-target="password">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                            </svg>
                                        </button>
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
                                    <?php echo e(__('Şifreyi Onayla')); ?>

                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required autocomplete="off">
                                        <button class="btn toggle-password" type="button" data-target="password-confirm">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
                                            </svg>
                                        </button>
                                    </div>
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
                                            <option value="">Rol Seçiniz...</option>
                                            <?php if(Auth::user()->role === 'admin'): ?>
                                                <option value="admin" <?php if(old('role') == 'admin'): ?> selected <?php endif; ?>>
                                                    Admin
                                                </option>
                                            <?php endif; ?>
                                            <option value="yönetici" <?php if(old('role') == 'yönetici'): ?> selected <?php endif; ?>>
                                                Yönetici
                                            </option>
                                            <option value="kullanıcı" <?php if(old('role') == 'kullanıcı'): ?> selected <?php endif; ?>>
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

                            
                            <div class="row mb-4" id="department-row">
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
unset($__errorArgs, $__bag); ?>">
                                            <option value="">Birim Seçiniz...</option>
                                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($department->id); ?>"
                                                    <?php if(old('department_id') == $department->id): ?> selected <?php endif; ?>>
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

                            
                            <div class="row mb-0 mt-5">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-animated-gradient">
                                        ➕ <?php echo e(__('Kullanıcıyı Oluştur')); ?>

                                    </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');

            const eyeSvg =
                `<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>`;
            const eyeSlashSvg =
                `<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.94 5.94 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.707z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.288.78.781c.294.289.682.483 1.11.531L9.25 12.5a3.5 3.5 0 0 1-4.474-4.474l.78.78a2.5 2.5 0 0 0 2.829 2.829zm-3.174.734a2 2 0 0 1 2.227-2.227l.649.649a3 3 0 0 0 4.357 4.357l.649.649a2 2 0 0 1-2.227 2.227L4.182 4.182a2 2 0 0 1-.582 1.487l.649.649a3 3 0 0 0 4.357 4.357l.649.649a2 2 0 0 1-1.487.582zM2.088 5.524l.523.523a13.134 13.134 0 0 0-1.465 1.755C1.121 8.243 1.516 9 2 9.5c.483.5 1.047.95 1.737 1.342l.524.524c-1.437.693-3.2 1.22-5.261 1.22C1.334 13.5 0 12.5 0 12.5s3-5.5 8-5.5c.34 0 .673.02 1 .06l.523.523c-.428-.15-.86-.26-1.312-.341zM8 4.5a3.5 3.5 0 0 0-3.5 3.5c0 1.933 1.567 3.5 3.5 3.5s3.5-1.567 3.5-3.5C11.5 6.067 9.933 4.5 8 4.5z"/>`;

            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetInputId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetInputId);
                    const eyeIcon = this.querySelector('svg');

                    if (targetInput) {
                        const type = targetInput.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        targetInput.setAttribute('type', type);
                        eyeIcon.innerHTML = type === 'password' ? eyeSvg : eyeSlashSvg;
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleDropdown = document.getElementById('role');
            const departmentRow = document.getElementById('department-row');
            const departmentDropdown = document.getElementById('department_id');

            function toggleDepartmentField() {
                if (!roleDropdown || !departmentRow || !departmentDropdown) {
                    console.error("Form elemanları (role, department-row, department_id) bulunamadı.");
                    return;
                }
                const selectedRole = roleDropdown.value;
                if (selectedRole === 'admin') {
                    departmentRow.style.display = 'none';
                    departmentDropdown.required = false;
                    departmentDropdown.value = '';
                } else if (selectedRole === 'yönetici') {
                    departmentRow.style.display = '';
                    departmentDropdown.required = false;
                    departmentDropdown.value = '';
                } else {
                    departmentRow.style.display = '';
                    departmentDropdown.required = true;
                }
            }
            roleDropdown.addEventListener('change', toggleDepartmentField);
            toggleDepartmentField();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/users/create.blade.php ENDPATH**/ ?>