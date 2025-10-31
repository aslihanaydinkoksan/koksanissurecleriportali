

<?php $__env->startSection('title', 'Yeni Kullanıcı Kaydı'); ?>

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
    .user-create-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    /* Form Etiketleri (Okunabilirlik İçin) */
    .user-create-card .card-header,
    .user-create-card .form-label {
        color: #444;
        /* Koyu renk metin */
        font-weight: bold;
        /* Kalın Metin */
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .user-create-card .card-header {
        color: #000;
        /* Başlık siyah kalsın */
    }

    /* Form Elemanları (Yumuşak Köşe + Opak Arka Plan) */
    .user-create-card .form-control,
    .user-create-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* Şifre Alanı (Input Group) için Özel Yuvarlatma */
    .user-create-card .input-group .form-control {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .user-create-card .input-group .btn {
        border-radius: 0.5rem;
        /* Genel */
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        /* Hafif opak arka planı uygula */
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
                
                <div class="card user-create-card">
                    
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Kullanıcı Oluştur')); ?></div>

                    
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('users.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="row mb-3">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Ad Soyad')); ?></label>
                                <div class="col-md-6"><input id="name" type="text"
                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                                        value="<?php echo e(old('name')); ?>" required></div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('E-posta Adresi')); ?></label>
                                <div class="col-md-6"><input id="email" type="email"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                        value="<?php echo e(old('email')); ?>" required></div>
                            </div>

                            
                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Şifre')); ?></label>
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
                                            required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="password">
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
                                        <span class="invalid-feedback d-block"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            
                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Şifreyi Onayla')); ?></label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button"
                                            data-target="password-confirm">
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
                                        <option value="">Rol Seçiniz...</option>
                                        <?php if(Auth::user()->role === 'admin'): ?>
                                            <option value="admin" <?php if(old('role') == 'admin'): ?> selected <?php endif; ?>>Admin
                                            </option>
                                        <?php endif; ?>
                                        <option value="yönetici" <?php if(old('role') == 'yönetici'): ?> selected <?php endif; ?>>Yönetici
                                        </option>
                                        <option value="kullanıcı" <?php if(old('role') == 'kullanıcı'): ?> selected <?php endif; ?>>
                                            Kullanıcı</option>
                                    </select>
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
unset($__errorArgs, $__bag); ?>">
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
                            

                            
                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                        <?php echo e(__('Kullanıcıyı Oluştur')); ?>

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
                // Seçilen rol admin VEYA yönetici ise
                if (roleDropdown.value === 'admin' || roleDropdown.value === 'yönetici') {
                    departmentRow.style.display = 'none'; // Birim satırını gizle
                    departmentDropdown.required = false; // Zorunlu olmaktan çıkar
                    departmentDropdown.value = ''; // Değerini sıfırla
                } else {
                    departmentRow.style.display = ''; // Birim satırını göster
                    departmentDropdown.required = true; // Zorunlu yap
                }
            }

            // Rol değiştiğinde fonksiyonu tetikle
            roleDropdown.addEventListener('change', toggleDepartmentField);

            // Sayfa yüklendiğinde de bir kez kontrol et (eski veri varsa diye)
            toggleDepartmentField();
        });
    </script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\aslihan.aydin\Desktop\tedarik-yonetimi\tedarik-yonetimi\resources\views/users/create.blade.php ENDPATH**/ ?>