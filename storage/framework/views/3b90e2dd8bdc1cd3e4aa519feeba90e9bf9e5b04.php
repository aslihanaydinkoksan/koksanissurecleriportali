

<?php $__env->startSection('title', 'Kullanıcı Listesi'); ?>

<?php $__env->startPush('styles'); ?>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            /* Indigo */
            --primary-hover: #4338ca;
            --secondary-color: #64748b;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
        }

        /* --- ANA KAPLAYICI --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: 100vh;
        }

        /* --- KART TASARIMI --- */
        .modern-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* --- HEADER ALANI --- */
        .card-header-custom {
            padding: 1.5rem 2rem;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .header-title small {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* --- BUTONLAR --- */
        .btn-modern {
            background: var(--primary-color);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
            text-decoration: none;
        }

        .btn-modern:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
            color: white;
        }

        /* --- TABLO TASARIMI --- */
        .table-responsive {
            overflow-x: auto;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: #f9fafb;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        .table-custom td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-main);
            transition: background 0.2s;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* --- AVATAR VE İSİM --- */
        .user-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar-initials {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
        }

        .user-info span {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* --- ROZETLER (BADGES) --- */
        .badge-pill {
            padding: 0.35em 0.8em;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-right: 4px;
            margin-bottom: 2px;
        }

        .badge-role-admin {
            background-color: #ede9fe;
            color: #6d28d9;
        }

        .badge-role-user {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-dept {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid transparent;
        }

        /* --- AKSİYON BUTONLARI --- */
        .action-group {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: var(--secondary-color);
            transition: all 0.2s;
            background: transparent;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-icon:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }

        .btn-icon.delete:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }

        /* --- BOŞ DURUM --- */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* --- PAGINATION --- */
        .pagination-container {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background-color: #f9fafb;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert"
                        style="background: #d1fae5; color: #065f46; border-radius: 8px;">
                        <span class="me-2 fs-5">✅</span>
                        <div><?php echo e(session('success')); ?></div>
                    </div>
                <?php endif; ?>

                <div class="modern-card">
                    
                    <div class="card-header-custom">
                        <div class="header-title">
                            <h4>Kullanıcı Yönetimi</h4>
                            <small>Tüm kayıtlı kullanıcıları görüntüleyin ve yönetin.</small>
                        </div>
                        <a href="<?php echo e(route('users.create')); ?>" class="btn-modern">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Yeni Kullanıcı Ekle
                        </a>
                    </div>

                    
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>Roller</th>
                                    <th>Birimler</th>
                                    <th>Kayıt Tarihi</th>
                                    <th class="text-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="user-meta">
                                                <div class="avatar-initials">
                                                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                                                </div>
                                                <div class="user-info">
                                                    <h6><?php echo e($user->name); ?></h6>
                                                    <span><?php echo e($user->email); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $badgeClass =
                                                        $role->slug == 'admin' ? 'badge-role-admin' : 'badge-role-user';
                                                ?>
                                                <span class="badge-pill <?php echo e($badgeClass); ?>">
                                                    <?php echo e($role->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <?php if($user->departments->count() > 0): ?>
                                                <?php $__currentLoopData = $user->departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge-pill badge-dept"><?php echo e($dept->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">Birim Yok</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="text-secondary small fw-medium">
                                                <?php echo e($user->created_at->format('d M, Y')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn-icon" title="Düzenle">
                                                    ✏️
                                                </a>

                                                <?php if(Auth::id() !== $user->id): ?>
                                                    <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn-icon delete" title="Sil">
                                                            🗑️
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-icon">📂</div>
                                                <h6 class="text-muted">Kayıt bulunamadı.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <?php if($users->hasPages()): ?>
                        <div class="pagination-container">
                            <?php echo e($users->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/users/index.blade.php ENDPATH**/ ?>