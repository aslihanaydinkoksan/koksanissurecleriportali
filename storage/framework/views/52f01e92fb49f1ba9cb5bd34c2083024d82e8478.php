

<?php $__env->startSection('title', 'Giriş Yap'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Ana içerik alanı - dikeyde ortalanmış */
        #app>main.py-4 {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: flex;
            align-items: center;
            min-height: calc(100vh - 72px);

            /* Animasyonlu arka plan */
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    #fde2ff,
                    #d9fcf7,
                    #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        /* Dalgalanma animasyonu */
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

        /* Giriş Kartı - Modern glassmorphism */
        .login-card {
            border-radius: 1.5rem;
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.15) !important;
            border: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            overflow: hidden;
        }

        /* Kart başlığı */
        .login-card .card-header {
            background: rgba(255, 255, 255, 0.5);
            border: none;
            padding: 2rem 2rem 1rem;
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
        }

        .login-card .card-body {
            padding: 1.5rem 2rem 2.5rem;
        }

        /* Form etiketleri */
        .form-label-custom {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        /* Input kutuları */
        .form-control-custom {
            border-radius: 0.75rem;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control-custom:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background: #ffffff;
        }

        /* Şifre input group */
        .password-group {
            position: relative;
        }

        .password-group .form-control-custom {
            padding-right: 3rem;
        }

        .password-toggle-btn {
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 1rem;
            color: #6c757d;
            cursor: pointer;
            border-top-right-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
            transition: color 0.2s;
        }

        .password-toggle-btn:hover {
            color: #667EEA;
        }

        /* Checkbox özelleştirme */
        .form-check-custom {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-check-custom .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            cursor: pointer;
        }

        .form-check-custom .form-check-label {
            cursor: pointer;
            font-size: 0.9rem;
            color: #495057;
        }

        /* reCAPTCHA container */
        .recaptcha-wrapper {
            display: flex;
            justify-content: center;
            margin: 1.5rem 0;
        }

        /* Animasyonlu gradient buton */
        .btn-animated-gradient {
            background: linear-gradient(-45deg,
                    #667EEA,
                    #F093FB,
                    #4FD1C5,
                    #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.75rem 2.5rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            width: 100%;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
        }

        /* Şifremi unuttum linki */
        .link-palette {
            color: #667EEA;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.2s;
        }

        .link-palette:hover {
            color: #435EBE;
            text-decoration: underline;
            transform: translateX(3px);
        }

        /* Form grubu spacing */
        .form-group-custom {
            margin-bottom: 1.5rem;
        }

        /* Error mesajları */
        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* Responsive ayarlar */
        @media (max-width: 768px) {
            .login-card .card-header {
                padding: 1.5rem 1.5rem 0.75rem;
                font-size: 1.5rem;
            }

            .login-card .card-body {
                padding: 1rem 1.5rem 2rem;
            }
        }
    </style>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card login-card">
                    <div class="card-header text-center">
                        <i class="fa-solid fa-lock me-2" style="color: #667EEA;"></i>
                        Giriş Yap
                    </div>

                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>

                            <!-- E-posta Adresi -->
                            <div class="form-group-custom">
                                <label for="email" class="form-label-custom">
                                    <i class="fa-solid fa-envelope me-1" style="color: #E8D5F2;"></i>
                                    E-posta Adresi
                                </label>
                                <input id="email" type="email"
                                    class="form-control form-control-custom <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                                    placeholder="ornek@koksan.com">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Şifre -->
                            <div class="form-group-custom">
                                <label for="password" class="form-label-custom">
                                    <i class="fa-solid fa-key me-1" style="color: #FFE5EC;"></i>
                                    Şifre
                                </label>
                                <div class="password-group">
                                    <input id="password" type="password"
                                        class="form-control form-control-custom <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="password" required autocomplete="current-password" placeholder="••••••••">
                                    <button class="password-toggle-btn" type="button" id="togglePassword">
                                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
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

                            <!-- Beni Hatırla -->
                            <div class="form-group-custom">
                                <div class="form-check-custom">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                        <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="remember">
                                        Beni Hatırla
                                    </label>
                                </div>
                            </div>
                            <?php if($showCaptcha ?? false): ?>
                                <div class="recaptcha-wrapper">
                                    <div class="g-recaptcha" data-sitekey="<?php echo e(config('services.recaptcha.key')); ?>"></div>
                                </div>
                                <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="text-center mb-3">
                                        <span class="text-danger">
                                            <strong>Lütfen robot olmadığınızı doğrulayın.</strong>
                                        </span>
                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php endif; ?>

                            <!-- Giriş Butonu -->
                            <div class="form-group-custom">
                                <button type="submit" class="btn btn-animated-gradient">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i>
                                    Giriş Yap
                                </button>
                            </div>

                            <!-- Şifremi Unuttum Linki -->
                            <?php if(Route::has('password.request')): ?>
                                <div class="text-center">
                                    <a class="link-palette" href="<?php echo e(route('password.request')); ?>">
                                        <i class="fa-solid fa-question-circle me-1"></i>
                                        Şifreni mi unuttun?
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // İkon değiştirme
                if (type === 'password') {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/auth/login.blade.php ENDPATH**/ ?>