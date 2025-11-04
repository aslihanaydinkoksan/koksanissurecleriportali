

<?php $__env->startSection('title', 'Giriş Yap'); ?>

<?php $__env->startSection('content'); ?>

<style>
    /* layouts.app'teki ana içerik alanını (main) yeniden düzenliyoruz.
      Padding'i sıfırlayıp, dikeyde ortalamak için flex kullanıyoruz.
      Navbar'ınızın yüksekliğini (~72px) 100vh'den (tüm ekran) çıkarıyoruz.
    */
    #app > main.py-4 {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        display: flex;
        align-items: center;
        min-height: calc(100vh - 72px); 
        
        /* İstediğiniz "dalgalanan ve silikleşen" animasyonlu arka plan.
          Renk paletinizdeki renklerin açık tonlarını kullandım.
        */
        background: linear-gradient(-45deg, 
            #dbe4ff, /* #667EEA (Canlı mavi-mor) tonu */
            #fde2ff, /* #F093FB (Yumuşak pembe) tonu */
            #d9fcf7, /* #4FD1C5 (Teal/turkuaz) tonu */
            #fff0d9  /* #FBD38D (Sıcak sarı) tonu */
        );
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* "Dalgalanma" animasyonunun @keyframes kuralı */
    @keyframes gradientWave {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Giriş Kartını daha modern hale getirelim */
    .login-card {
        border-radius: 1rem; /* Köşeleri daha yumuşak */
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.1) !important;
        border: 0;
        background-color: rgba(255, 255, 255, 0.85); /* Hafif şeffaflık */
        backdrop-filter: blur(5px); /* Arka planı bulanıklaştır (iOS/Safari) */
    }

    /* Input kutularının köşelerini yumuşatma (rounded-3) */
    .form-control.rounded-soft {
        border-radius: 0.5rem; 
    }
    
    /* Şifre alanı (input-group) için özel yuvarlatma */
    .input-group.rounded-soft .form-control,
    .input-group.rounded-soft .btn {
        /* Köşelerin birbirini tamamlaması için */
        border-radius: 0.5rem; 
    }
    .input-group.rounded-soft .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    .input-group.rounded-soft .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* === YENİ EKLENDİ (BUTON) === */
    /* Giriş Yap Butonu için Animasyonlu Arka Plan */
    .btn-animated-gradient {
        /* Bu sefer paletinizin canlı (koyu) renklerini kullanıyoruz */
        background: linear-gradient(-45deg, 
            #667EEA, /* Canlı mavi-mor */
            #F093FB, /* Yumuşak pembe */
            #4FD1C5, /* Teal/turkuaz */
            #FBD38D  /* Sıcak sarı */
        );
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        
        border: none;
        color: white; /* Beyaz yazı rengi */
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out; /* Efektler */
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05); /* Hover'da hafifçe büyüsün */
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    /* === YENİ EKLENDİ (LİNK) === */
    /* Şifremi Unuttum Linki */
    .link-palette {
        color: #667EEA; /* Paletten canlı mavi-mor */
        text-decoration: none;
        font-weight: 500; /* Normalden biraz kalın */
    }
    .link-palette:hover {
        color: #435EBE; /* Mavi-morun koyu tonu */
        text-decoration: underline;
    }

</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card login-card shadow-lg">
                <div class="card-header text-center h4 bg-transparent border-0 pt-4 pb-0">
                    Giriş Yap
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">E-posta Adresi</label>
                            <div class="col-md-8">
                                <input id="email" type="email" class="form-control rounded-soft <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">Şifre</label>
                            <div class="col-md-8">
                                <div class="input-group rounded-soft">
                                    <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" required autocomplete="current-password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                        </svg>
                                    </button>
                                </div>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-8 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="remember">
                                        Beni Hatırla
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4 d-flex align-items-center">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4">
                                    Giriş Yap
                                </button>

                                <?php if(Route::has('password.request')): ?>
                                    <a class="link-palette ms-3" href="<?php echo e(route('password.request')); ?>">
                                        Şifreni mi unuttun?
                                    </a>
                                <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        const eyeSvg = `<path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/><path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>`;
        const eyeSlashSvg = `<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.94 5.94 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.707z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.288.78.781c.294.289.682.483 1.11.531L9.25 12.5a3.5 3.5 0 0 1-4.474-4.474l.78.78a2.5 2.5 0 0 0 2.829 2.829zm-3.174.734a2 2 0 0 1 2.227-2.227l.649.649a3 3 0 0 0 4.357 4.357l.649.649a2 2 0 0 1-2.227 2.227L4.182 4.182a2 2 0 0 1-.582 1.487l.649.649a3 3 0 0 0 4.357 4.357l.649.649a2 2 0 0 1-1.487.582zM2.088 5.524l.523.523a13.134 13.134 0 0 0-1.465 1.755C1.121 8.243 1.516 9 2 9.5c.483.5 1.047.95 1.737 1.342l.524.524c-1.437.693-3.2 1.22-5.261 1.22C1.334 13.5 0 12.5 0 12.5s3-5.5 8-5.5c.34 0 .673.02 1 .06l.523.523c-.428-.15-.86-.26-1.312-.341zM8 4.5a3.5 3.5 0 0 0-3.5 3.5c0 1.933 1.567 3.5 3.5 3.5s3.5-1.567 3.5-3.5C11.5 6.067 9.933 4.5 8 4.5z"/>`;

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.innerHTML = type === 'password' ? eyeSvg : eyeSlashSvg;
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/auth/login.blade.php ENDPATH**/ ?>