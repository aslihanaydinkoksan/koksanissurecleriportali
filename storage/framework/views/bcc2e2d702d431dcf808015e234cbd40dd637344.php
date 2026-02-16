<?php $__env->startSection('title', 'Takım Yönetimi'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Arka plan animasyonu */
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

        /* Başlık Bölümü */
        .page-header-modern {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.06);
        }

        .page-header-modern h2 {
            color: #2d3748;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-header-modern .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        /* Yeni Takım Butonu */
        .btn-add-team {
            background: linear-gradient(-45deg, #667EEA, #F093FB);
            background-size: 200% 200%;
            animation: gradientWave 10s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 1.75rem;
            border-radius: 0.875rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add-team:hover {
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 25px rgba(102, 126, 234, 0.4);
        }

        /* Success Alert */
        .alert-success-modern {
            border-radius: 1rem;
            border: none;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.1), rgba(56, 161, 105, 0.1));
            color: #2f855a;
            border-left: 4px solid #48bb78;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.1);
        }

        .alert-success-modern i {
            font-size: 1.25rem;
        }

        /* Kart Stili */
        .team-list-card {
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            overflow: hidden;
        }

        /* Tablo Stilleri */
        .table-modern {
            margin: 0;
        }

        .table-modern thead th {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08), rgba(240, 147, 251, 0.08));
            color: #2d3748;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border: none;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.03), rgba(240, 147, 251, 0.03));
            transform: scale(1.01);
        }

        .table-modern tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            color: #4a5568;
        }

        /* Takım Adı */
        .team-name-cell {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .team-name-cell .team-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(240, 147, 251, 0.15));
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667EEA;
            font-size: 1.1rem;
        }

        /* Üyeler Badge */
        .members-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.875rem;
            background: linear-gradient(135deg, rgba(79, 209, 197, 0.1), rgba(102, 126, 234, 0.1));
            border-radius: 0.75rem;
            color: #2d3748;
            font-size: 0.875rem;
            border: 1px solid rgba(79, 209, 197, 0.2);
            gap: 0.5rem;
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .members-badge i {
            color: #4FD1C5;
        }

        /* Aksiyon Butonları */
        .btn-action-edit {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
        }

        .btn-action-edit:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-action-delete {
            background: linear-gradient(135deg, #f56565, #e53e3e);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.625rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(245, 101, 101, 0.2);
        }

        .btn-action-delete:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.3);
        }

        /* Boş Durum */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #667EEA;
        }

        .empty-state h4 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header-modern {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .page-header-modern h2 {
                font-size: 1.5rem;
            }

            .btn-add-team {
                width: 100%;
                justify-content: center;
            }

            .table-modern {
                font-size: 0.875rem;
            }

            .team-name-cell {
                flex-direction: column;
                text-align: center;
            }

            .members-badge {
                max-width: 100%;
            }

            .table-modern tbody td:last-child {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }

            .btn-action-edit,
            .btn-action-delete {
                width: 100%;
            }
        }

        /* Animasyon */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .team-list-card {
            animation: fadeInUp 0.6s ease;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11 col-lg-10">
                <!-- Başlık Bölümü -->
                <div class="page-header-modern">
                    <h2>
                        <div class="icon-wrapper">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        Takım Yönetimi
                    </h2>
                    <a href="<?php echo e(route('teams.create')); ?>" class="btn btn-add-team">
                        <i class="fas fa-plus-circle"></i>
                        Yeni Takım Ekle
                    </a>
                </div>

                <!-- Success Mesajı -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success-modern" role="alert">
                        <i class="fas fa-check-circle"></i>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Takımlar Tablosu -->
                <div class="card team-list-card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-layer-group me-2"></i>Takım Adı</th>
                                        <th><i class="fas fa-user-friends me-2"></i>Üyeler</th>
                                        <th><i class="fas fa-user-tie me-2"></i>Oluşturan</th>
                                        <th><i class="fas fa-calendar me-2"></i>Tarih</th>
                                        <th><i class="fas fa-cog me-2"></i>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td>
                                                <div class="team-name-cell">
                                                    <div class="team-icon">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <?php echo e($team->name); ?>

                                                </div>
                                            </td>
                                            <td>
                                                <div class="members-badge"
                                                    title="<?php echo e($team->users->pluck('name')->implode(', ')); ?>">
                                                    <i class="fas fa-users"></i>
                                                    <?php echo e($team->users->pluck('name')->implode(', ')); ?>

                                                </div>
                                            </td>
                                            <td>
                                                <strong><?php echo e($team->creator->name); ?></strong>
                                            </td>
                                            <td>
                                                <i class="far fa-calendar-alt me-2" style="color: #667EEA;"></i>
                                                <?php echo e($team->created_at->format('d.m.Y')); ?>

                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <!-- Düzenle Butonu -->
                                                    <a href="<?php echo e(route('teams.edit', $team->id)); ?>"
                                                        class="btn btn-action-edit">
                                                        <i class="fas fa-edit me-1"></i>Düzenle
                                                    </a>

                                                    <!-- Sil Butonu -->
                                                    <form action="<?php echo e(route('teams.destroy', $team->id)); ?>" method="POST"
                                                        onsubmit="return confirm('<?php echo e($team->name); ?> takımını silmek istediğinizden emin misiniz?');"
                                                        style="display:inline-block;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-action-delete">
                                                            <i class="fas fa-trash-alt me-1"></i>Sil
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5">
                                                <div class="empty-state">
                                                    <div class="empty-state-icon">
                                                        <i class="fas fa-users-slash"></i>
                                                    </div>
                                                    <h4>Henüz Takım Yok</h4>
                                                    <p>Yeni bir takım oluşturarak başlayın!</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/teams/index.blade.php ENDPATH**/ ?>