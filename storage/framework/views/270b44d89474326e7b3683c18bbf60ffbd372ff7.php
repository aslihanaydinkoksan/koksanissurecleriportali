<?php $__env->startSection('title', 'Yeni Kullanıcı Kaydı'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* --- 1. ARKA PLAN VE ANİMASYONLAR --- */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667EEA, #764BA2, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 15s ease infinite;
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

        .user-create-card {
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #4A5568;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* --- 2. INPUT VE İKONLAR --- */
        .custom-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-input-group .form-control {
            border-radius: 1rem;
            padding: 0.8rem 1rem;
            padding-right: 2.5rem;
            border: 2px solid rgba(102, 126, 234, 0.15);
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .custom-input-group .form-control:focus {
            background: #fff;
            border-color: #667EEA;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .custom-input-group .input-icon {
            position: absolute;
            right: 15px;
            color: #A0AEC0;
            font-size: 1.1rem;
            pointer-events: none;
        }

        /* --- 3. ROL SEÇİMİ (RADIO) --- */
        .role-radio {
            display: none;
        }

        .role-label {
            display: inline-block;
            cursor: pointer;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 50px;
            background: #fff;
            border: 2px solid #E2E8F0;
            color: #718096;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .role-label:hover {
            transform: translateY(-2px);
            border-color: #CBD5E0;
        }

        .role-radio:checked+.role-label {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
            transform: scale(1.05);
        }

        /* --- 4. DEPARTMAN & FABRİKA TASARIMI --- */
        .section-label {
            display: flex;
            align-items: center;
            color: #2D3748;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.875rem;
        }

        .section-icon {
            margin-right: 0.625rem;
            font-size: 1.15rem;
            color: #667EEA;
        }

        .list-wrapper {
            border-radius: 1.125rem;
            padding: 1.125rem;
            max-height: 280px;
            overflow-y: auto;
            border: 2px solid rgba(102, 126, 234, 0.15);
            background: rgba(247, 250, 252, 0.8);
        }

        .modern-checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border-radius: 0.875rem;
            cursor: pointer;
            background: white;
            border: 2px solid #E2E8F0;
            transition: all 0.25s ease;
            margin-bottom: 8px;
        }

        .modern-checkbox-item:hover {
            border-color: #667EEA;
            transform: translateX(4px);
            background: #F7FAFC;
        }

        .modern-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 0.875rem;
            accent-color: #667EEA;
        }

        .btn-magic {
            background: linear-gradient(-45deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-magic:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card user-create-card">
                    <div class="card-header-custom">
                        <i class="fas fa-user-plus me-2"></i> Yeni Kullanıcı Tanımlama
                    </div>

                    <div class="card-body p-5">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger rounded-4">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('users.store')); ?>" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Ad Soyad <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="text" name="name" class="form-control"
                                            value="<?php echo e(old('name')); ?>" required placeholder="Ad Soyad">
                                        <span class="input-icon">👤</span>
                                    </div>
                                </div>

                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Kurumsal E-posta <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="email" name="email" class="form-control"
                                            value="<?php echo e(old('email')); ?>" required placeholder="ornek@koksan.com">
                                        <span class="input-icon">✉️</span>
                                    </div>
                                </div>

                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Şifre <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password')">👁️</span>
                                    </div>
                                </div>

                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Şifre Tekrar <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                            class="form-control" required>
                                        <span class="input-icon">🔑</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.1">

                            
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-building section-icon"></i> Çalıştığı Departmanlar ve Özel Görevler
                                </label>
                                <div class="list-wrapper p-4">

                                    <h6 class="text-muted small fw-bold mb-2 border-bottom pb-1">Departmanlar</h6>
                                    <div class="row g-3 mb-4">
                                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-4">
                                                <label class="modern-checkbox-item" for="dept_<?php echo e($dept->id); ?>">
                                                    <input type="checkbox" name="departments[]"
                                                        id="dept_<?php echo e($dept->id); ?>" value="<?php echo e($dept->id); ?>"
                                                        class="modern-checkbox"
                                                        <?php echo e(is_array(old('departments')) && in_array($dept->id, old('departments')) ? 'checked' : ''); ?>>
                                                    <span class="checkbox-label small fw-bold"><?php echo e($dept->name); ?></span>
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>

                                    <?php
                                        // Ana roller ve sistem rolleri hariç kalan özel rolleri bul ("Satış Yöneticisi" buraya düşer)
                                        $excludedRoles = [
                                            'admin',
                                            'yonetici',
                                            'user',
                                            'lojistik_personeli',
                                            'uretim_personeli',
                                            'idari_isler_personeli',
                                            'bakim_personeli',
                                        ];
                                        $extraRoles = $roles->whereNotIn('name', $excludedRoles);
                                    ?>

                                    <?php if($extraRoles->count() > 0): ?>
                                        <h6 class="text-primary small fw-bold mb-2 border-bottom pb-1">Departman İçi Ekstra
                                            Görevler / Rol Atamaları</h6>
                                        <div class="row g-3">
                                            <?php $__currentLoopData = $extraRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $extraRole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-md-4">
                                                    <label class="modern-checkbox-item border-primary"
                                                        style="background-color: rgba(102, 126, 234, 0.05);"
                                                        for="extra_role_<?php echo e($extraRole->id); ?>">
                                                        <input type="checkbox" name="extra_roles[]"
                                                            id="extra_role_<?php echo e($extraRole->id); ?>"
                                                            value="<?php echo e($extraRole->name); ?>" class="modern-checkbox"
                                                            <?php echo e(is_array(old('extra_roles')) && in_array($extraRole->name, old('extra_roles')) ? 'checked' : ''); ?>>
                                                        <span
                                                            class="checkbox-label small fw-bold text-primary"><?php echo e($extraRole->name); ?></span>
                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-industry section-icon" style="color: #764BA2"></i> Yetkili Olduğu
                                    Fabrikalar
                                </label>
                                <div class="list-wrapper" style="border-color: rgba(118, 75, 162, 0.15)">
                                    <div class="row">
                                        <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-md-4">
                                                <label class="modern-checkbox-item" for="unit_<?php echo e($unit->id); ?>">
                                                    <input type="checkbox" name="units[]" id="unit_<?php echo e($unit->id); ?>"
                                                        value="<?php echo e($unit->id); ?>" class="modern-checkbox"
                                                        <?php echo e(is_array(old('units')) && in_array($unit->id, old('units')) ? 'checked' : ''); ?>>
                                                    <span class="checkbox-label small fw-bold"><?php echo e($unit->name); ?></span>
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-5">
                                <label class="form-label text-muted fw-bold ms-1 d-block">
                                    <i class="fas fa-shield-alt text-primary me-1"></i> Yetki Seviyesi (Ana Hiyerarşi)
                                </label>
                                <div class="d-flex flex-wrap">
                                    <?php
                                        // Sadece ana rollerimizi listeliyoruz
                                        $mainRoles = ['admin', 'yonetici', 'user'];
                                    ?>

                                    <?php $__currentLoopData = $roles->whereIn('name', $mainRoles); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="me-2 mb-2">
                                            <input type="radio" name="role" id="role_<?php echo e($role->id); ?>"
                                                value="<?php echo e($role->name); ?>" class="role-radio"
                                                <?php echo e(old('role', 'user') == $role->name ? 'checked' : ''); ?>>
                                            <label for="role_<?php echo e($role->id); ?>" class="role-label">
                                                <?php echo e(\Illuminate\Support\Facades\Lang::has('roles.' . $role->name) ? __('roles.' . $role->name) : ucfirst($role->name)); ?>

                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn-magic py-3 shadow">
                                    ✨ Kullanıcı Kaydını Tamamla
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
    <script>
        function togglePwd(id) {
            var input = document.getElementById(id);
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/users/create.blade.php ENDPATH**/ ?>