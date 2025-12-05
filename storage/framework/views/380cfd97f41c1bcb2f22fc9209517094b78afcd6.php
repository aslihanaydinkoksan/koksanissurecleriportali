

<?php $__env->startSection('title', 'Seyahat Planı Listesi'); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .page-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .page-hero-content {
            position: relative;
            z-index: 1;
        }

        .filter-card {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .travel-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .travel-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
            border-color: #667EEA;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .status-badge.planned {
            background: linear-gradient(135deg, #667EEA20, #667EEA10);
            color: #667EEA;
        }

        .status-badge.completed {
            background: linear-gradient(135deg, #4FD1C520, #4FD1C510);
            color: #2c9e91;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-create {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-create:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .btn-action-equal {
            min-width: 180px;
            text-align: center;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm-modern {
            padding: 0.4rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-modern {
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .btn-outline-modern:hover {
            background: #667EEA;
            border-color: #667EEA;
            color: white;
            transform: translateY(-1px);
        }

        .important-star {
            font-size: 1.2rem;
        }

        .important-star.active {
            color: #F093FB;
        }

        .important-star.inactive {
            color: #dee2e6;
        }

        .btn-export-global {
            background: linear-gradient(135deg, #28c76f 0%, #1e7e34 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-export-global:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
            color: white;
        }

        /* Kart İçindeki Küçük Excel Butonu */
        .btn-icon-excel {
            color: #198754;
            border-color: #198754;
        }

        .btn-icon-excel:hover {
            background-color: #198754;
            color: white;
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                
                <div class="page-hero">
                    <div class="page-hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-2">✈️ Seyahat Planı Listesi</h2>
                                <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                                    <i class="fa-solid fa-list me-2"></i>
                                    Tüm seyahat planlarınızı buradan görüntüleyebilir ve yönetebilirsiniz
                                </p>
                            </div>
                            <a href="<?php echo e(route('travels.create')); ?>" class="btn-create">
                                <i class="fa-solid fa-plus me-1"></i> Yeni Plan
                            </a>
                        </div>
                    </div>
                </div>

                
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-outline-modern" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="fa-solid fa-filter me-1"></i> Filtreleme Seçenekleri
                        
                    </button>
                </div>

                
                
                <div class="collapse <?php if(request()->hasAny(['name', 'status', 'is_important', 'date_from', 'date_to', 'user_id'])): ?> show <?php endif; ?>" id="filterCollapse">
                    <div class="filter-card">
                        <div class="section-title">
                            <i class="fa-solid fa-filter"></i>
                            <h5 class="mb-0">Filtreleme Seçenekleri</h5>
                        </div>

                        <form method="GET" action="<?php echo e(route('travels.index')); ?>" autocomplete="off">
                            
                            
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-6">
                                    <label for="name" class="form-label">Plan Adı</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Plan adı girin..." value="<?php echo e($filters['name'] ?? ''); ?>"
                                        autocomplete="off">
                                </div>

                                <div class="col-lg-2 col-md-6">
                                    <label for="status" class="form-label">Durum</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="all" <?php if(($filters['status'] ?? 'all') == 'all'): ?> selected <?php endif; ?>>Tümü
                                        </option>
                                        <option value="planned" <?php if(($filters['status'] ?? '') == 'planned'): ?> selected <?php endif; ?>>Planlanan
                                        </option>
                                        <option value="completed" <?php if(($filters['status'] ?? '') == 'completed'): ?> selected <?php endif; ?>>
                                            Tamamlanan
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-6">
                                    <label for="is_important" class="form-label">Önem</label>
                                    <select name="is_important" id="is_important" class="form-select">
                                        <option value="all" <?php if(($filters['is_important'] ?? 'all') == 'all'): ?> selected <?php endif; ?>>Tümü
                                        </option>
                                        <option value="yes" <?php if(($filters['is_important'] ?? '') == 'yes'): ?> selected <?php endif; ?>>Önemli
                                        </option>
                                        <option value="no" <?php if(($filters['is_important'] ?? '') == 'no'): ?> selected <?php endif; ?>>Normal
                                        </option>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-6">
                                    <label for="date_from" class="form-label">Başlangıç</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control"
                                        value="<?php echo e($filters['date_from'] ?? ''); ?>" autocomplete="off">
                                </div>

                                <div class="col-lg-2 col-md-6">
                                    <label for="date_to" class="form-label">Bitiş</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control"
                                        value="<?php echo e($filters['date_to'] ?? ''); ?>" autocomplete="off">
                                </div>
                            </div>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('is-global-manager')): ?>
                                <div class="row g-3 mt-2">
                                    <div class="col-lg-4 col-md-6">
                                        <label for="user_id" class="form-label">Kullanıcı</label>
                                        <select name="user_id" id="user_id" class="form-select">
                                            <option value="all">Tüm Kullanıcılar</option>
                                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($user->id); ?>"
                                                    <?php if(($filters['user_id'] ?? '') == $user->id): ?> selected <?php endif; ?>>
                                                    <?php echo e($user->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-end gap-2">
                                    <a href="<?php echo e(route('travels.index')); ?>" class="btn btn-sm-modern btn-outline-modern">
                                        <i class="fa-solid fa-rotate-right me-1"></i> Temizle
                                    </a>
                                    <button type="submit" class="btn-gradient">
                                        <i class="fa-solid fa-filter me-1"></i> Filtrele
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-4">
                    <a href="<?php echo e(route('travels.export', request()->all())); ?>" class="btn-export-global">
                        <i class="fas fa-file-excel me-2"></i> Seyahat Planı Listesini Excel'e Aktar
                    </a>
                </div>

                
                <div class="section-title">
                    <i class="fa-solid fa-plane-departure"></i>
                    <h5 class="mb-0">Seyahat Planları <span
                            class="badge bg-primary rounded-pill"><?php echo e($travels->total()); ?></span></h5>
                </div>

                <?php if($travels->isEmpty()): ?>
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <h5 class="text-muted">Henüz Seyahat Planı Yok</h5>
                        <p class="text-muted mb-0">Filtrelerinize uyan seyahat planı bulunamadı.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $travels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $travel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="travel-card">
                            <div class="row align-items-center">
                                <div class="col-md-1 text-center">
                                    <?php if($travel->is_important): ?>
                                        <i class="fa-solid fa-star important-star active" title="Önemli"></i>
                                    <?php else: ?>
                                        <i class="fa-regular fa-star important-star inactive"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <strong style="font-size: 1.1rem;"><?php echo e($travel->name); ?></strong>
                                    <div class="text-muted small"><?php echo e($travel->user->name ?? 'Bilinmiyor'); ?></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-muted small">Başlangıç</div>
                                    <strong><?php echo e(\Carbon\Carbon::parse($travel->start_date)->format('d/m/Y')); ?></strong>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-muted small">Bitiş</div>
                                    <strong><?php echo e(\Carbon\Carbon::parse($travel->end_date)->format('d/m/Y')); ?></strong>
                                </div>
                                <div class="col-md-2">
                                    <?php if($travel->status == 'planned'): ?>
                                        <span class="status-badge planned">
                                            <i class="fa-solid fa-clock"></i> Planlandı
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge completed">
                                            <i class="fa-solid fa-check-circle"></i> Tamamlandı
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2 text-end">
                                    <div class="action-buttons">
                                        <a href="<?php echo e(route('travels.export', ['id' => $travel->id])); ?>"
                                            class="btn btn-sm-modern btn-outline-modern btn-action-equal"
                                            title="Excel İndir">
                                            <i class="fa-solid fa-file-excel"></i>
                                            <span class="ms-1">Excel İndir</span>
                                        </a>
                                        <?php if(Auth::id() === $travel->user_id || Auth::user()->role === 'admin' || Auth::user()->can('is-global-manager')): ?>
                                            <a href="<?php echo e(route('travels.show', $travel)); ?>"
                                                class="btn btn-sm-modern btn-outline-modern btn-action-equal"
                                                title="Detaylar & İşlemler">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="ms-1">Detaylar & İşlemler</span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                            <a href="<?php echo e(route('travels.edit', $travel)); ?>"
                                                class="btn btn-sm-modern btn-outline-modern btn-action-equal"
                                                title="Düzenle">
                                                <i class="fa-solid fa-pen"></i>
                                                <span class="ms-1">Düzenle</span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    
                    <div class="d-flex justify-content-center mt-4">
                        <?php echo e($travels->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/travels/index.blade.php ENDPATH**/ ?>