
<?php $__env->startSection('title', 'Genel Görev Listesi (Araçsız)'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        .general-tasks-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .general-tasks-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .general-tasks-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .general-tasks-header .icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .general-tasks-header .icon-wrapper i {
            font-size: 1.8rem;
            color: white;
        }

        .general-tasks-header .header-content {
            position: relative;
            z-index: 1;
        }

        .general-tasks-header .stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .general-tasks-header .stat-item {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
        }

        .general-tasks-header .stat-item strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .task-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .task-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
            border-color: rgba(102, 126, 234, 0.3);
        }

        .task-card:hover::before {
            transform: scaleY(1);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .task-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-title i {
            color: #667eea;
            font-size: 1rem;
        }

        .task-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .meta-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .meta-content {
            flex: 1;
            min-width: 0;
        }

        .meta-label {
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 0.95rem;
            color: #2d3748;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            animation: fadeInScale 0.3s ease;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .status-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .modern-btn {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .modern-btn-view {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }

        .modern-btn-view:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5aa0 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
            color: white;
        }

        .modern-btn-edit {
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            color: white;
        }

        .modern-btn-edit:hover {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
            color: white;
        }

        .modern-btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .modern-btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: #667eea;
        }

        .empty-state h5 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .responsible-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #667eea;
        }

        .responsible-badge i {
            font-size: 0.8rem;
        }

        .type-badge {
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .type-person {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .type-team {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            color: white;
        }

        .pagination-wrapper {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .filters-bar {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 3rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        @media (max-width: 768px) {
            .general-tasks-header {
                padding: 1.5rem;
            }

            .general-tasks-header h4 {
                font-size: 1.3rem;
            }

            .general-tasks-header .stats {
                flex-direction: column;
                gap: 1rem;
            }

            .task-card {
                padding: 1rem;
            }

            .task-meta {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                width: 100%;
            }

            .modern-btn {
                flex: 1;
                justify-content: center;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid px-4 py-4">
        <!-- Modern Header -->
        <div class="general-tasks-header fade-in">
            <div class="header-content">
                <h4>
                    <div class="icon-wrapper">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <div>
                        Genel Görevler
                        <small
                            style="display: block; font-size: 0.9rem; font-weight: 400; opacity: 0.9; margin-top: 0.25rem;">
                            Araçsız görev yönetimi
                        </small>
                    </div>
                </h4>
                <div class="stats">
                    <div class="stat-item">
                        <strong><?php echo e($assignments->total()); ?></strong>
                        Toplam Görev
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="filters-bar fade-in" style="animation-delay: 0.1s;">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Görev ara..." id="searchInput">
            </div>
            <a href="<?php echo e(route('service.assignments.create')); ?>" class="modern-btn modern-btn-primary">
                <i class="fas fa-plus"></i>
                Yeni Görev Oluştur
            </a>
        </div>

        <!-- Task Cards -->
        <div class="modern-card fade-in" style="animation-delay: 0.2s;">
            <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="task-card" style="animation-delay: <?php echo e(0.3 + $index * 0.05); ?>s;">
                    <div class="task-header">
                        <h5 class="task-title">
                            <i class="fas fa-clipboard-check"></i>
                            <?php echo e($assignment->title); ?>

                        </h5>
                        <div class="action-buttons">
                            <a href="<?php echo e(route('service.assignments.show', $assignment)); ?>"
                                class="modern-btn modern-btn-view">
                                <i class="fas fa-eye"></i>
                                Görüntüle
                            </a>
                            <?php if(Auth::user()->role === 'admin' || Auth::id() === $assignment->user_id): ?>
                                <a href="<?php echo e(route('service.assignments.edit', $assignment)); ?>"
                                    class="modern-btn modern-btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Düzenle
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="task-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Görevden Sorumlu Kişi/Takım</div>
                                <div class="meta-value">
                                    <?php if($assignment->responsible): ?>
                                        <div class="responsible-badge">
                                            <i
                                                class="fas fa-<?php echo e($assignment->responsible_type === 'App\Models\User' ? 'user' : 'users'); ?>"></i>
                                            <?php echo e($assignment->responsible->name); ?>

                                            <span
                                                class="type-badge <?php echo e($assignment->responsible_type === 'App\Models\User' ? 'type-person' : 'type-team'); ?>">
                                                <?php echo e($assignment->responsible_type === 'App\Models\User' ? 'Kişi' : 'Takım'); ?>

                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #e53e3e; font-weight: 600;">
                                            <i class="fas fa-exclamation-triangle"></i> Silinmiş Sorumlu
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Görevi Atayan</div>
                                <div class="meta-value">
                                    <?php if($assignment->createdBy): ?>
                                        <div class="responsible-badge">
                                            <i class="fas fa-user"></i>
                                            <?php echo e($assignment->createdBy->name); ?>

                                            <span class="type-badge type-person">
                                                Kişi
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Konum</div>
                                <div class="meta-value"><?php echo e($assignment->destination ?? 'Belirtilmemiş'); ?></div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Başlangıç Zamanı</div>
                                <div class="meta-value"><?php echo e($assignment->start_time->format('d.m.Y H:i')); ?></div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Durum</div>
                                <div class="meta-value">
                                    <span class="status-badge bg-<?php echo e($assignment->status_badge); ?>">
                                        <?php echo e($assignment->status_name); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-tasks"></i>
                    </div>
                    <h5>Henüz Görev Bulunmuyor</h5>
                    <p>Yeni bir genel görev oluşturarak başlayabilirsiniz.</p>
                    <a href="<?php echo e(route('service.assignments.create')); ?>" class="modern-btn modern-btn-primary">
                        <i class="fas fa-plus"></i>
                        İlk Görevi Oluştur
                    </a>
                </div>
            <?php endif; ?>

            <?php if($assignments->isNotEmpty()): ?>
                <div class="pagination-wrapper">
                    <?php echo e($assignments->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Simple search functionality
            document.getElementById('searchInput')?.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const taskCards = document.querySelectorAll('.task-card');

                taskCards.forEach(card => {
                    const title = card.querySelector('.task-title').textContent.toLowerCase();
                    const responsible = card.querySelector('.meta-value').textContent.toLowerCase();

                    if (title.includes(searchTerm) || responsible.includes(searchTerm)) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/assignments/general_index.blade.php ENDPATH**/ ?>