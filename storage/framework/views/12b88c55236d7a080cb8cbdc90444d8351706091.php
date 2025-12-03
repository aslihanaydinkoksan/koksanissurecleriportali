<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(config('app.name')); ?></title>
    <link rel="icon" href="<?php echo e(asset('koksan.ico?v=13')); ?>" type="image/ico">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --navbar-bg: rgba(248, 252, 255, 0.1);
            --navbar-shadow: 0 2px 10px rgba(102, 126, 234, 0.15);
            --hover-bg: rgba(102, 126, 234, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html,
        body {
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            font-family: 'Nunito', sans-serif;
        }

        body {
            background: linear-gradient(180deg, #f0f5ff 0%, #e6f7f9 50%, #e6e9fa 100%);
            background-attachment: fixed;
            background-size: cover;
            overflow-x: hidden;
        }

        /* Navbar */
        nav.navbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
            margin: 0 !important;
            padding: 0.75rem 0 2rem 0 !important;
            background: var(--navbar-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--navbar-shadow);
            transition: var(--transition);
        }

        nav.navbar.scrolled {
            padding: 0.75rem 0 2rem 0 !important;
            box-shadow: 0 4px 25px rgba(102, 126, 234, 0.2);
        }

        .navbar-brand {
            font-size: 1rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: var(--transition);
            white-space: nowrap;
        }

        .navbar-brand:hover {
            transform: translateY(-2px);
        }

        .navbar-brand img {
            height: 30px;
            transition: var(--transition);
        }

        .navbar-brand:hover img {
            transform: rotate(5deg) scale(1.05);
        }

        .nav-link {
            font-weight: 600;
            font-size: 0.875rem;
            color: #4a5568 !important;
            padding: 0.4rem 0.85rem !important;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: #667eea !important;
            transform: translateY(-2px);
        }

        .nav-link:hover i {
            transform: scale(1.05);
        }

        /* Dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.55rem 1rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: var(--transition);
            color: #4a5568;
        }

        .dropdown-item:hover {
            background: var(--hover-bg);
            color: #667eea;
            transform: translateX(-2px);
        }

        /* User Badge */
        .badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.45rem;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1.05);
            }

            50% {
                transform: scale(1.05);
            }
        }

        #navbarDropdown {
            background: var(--primary-gradient);
            color: white !important;
            padding: 0.45rem 1.1rem !important;
            border-radius: 25px;
            font-size: 0.85rem;
            max-width: 180px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        #navbarDropdown:hover {
            transform: translateY(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        #navbarDropdown i {
            color: white;
        }

        /* Main Content */
        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding-top: 0;
        }

        main {
            flex: 1;
            padding: 0 !important;
            margin: 0 !important;
            margin-top: 6rem !important;
        }

        @media (max-width: 991px) {
            main {
                margin-top: 58px !important;
            }
        }

        /* SweetAlert Custom */
        .swal2-popup {
            font-family: 'Nunito', sans-serif !important;
            border-radius: 1rem !important;
        }

        .swal2-title {
            font-size: 1.25rem !important;
        }

        .swal2-content {
            font-size: 0.9rem !important;
        }

        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            /* Her şeyin üstünde olsun */
            background: #f0f5ff;
            /* Senin body renginle uyumlu */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            transition: opacity 0.6s ease-out, visibility 0.6s ease-out;
        }

        #global-loader.loaded {
            opacity: 0;
            visibility: hidden;
        }

        .loader-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-left-color: #667eea;
            /* Senin primary rengin */
            border-radius: 50%;
            animation: spin 1s linear infinite;
            box-shadow: 0 0 15px rgba(102, 126, 234, 0.2);
        }

        .loader-text {
            margin-top: 15px;
            font-weight: 700;
            font-size: 0.9rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: pulse 1.5s infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div id="global-loader">
        <div class="loader-spinner"></div>
        <div class="loader-text">Yükleniyor...</div>
    </div>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('welcome')); ?>">
                    <img src="<?php echo e(asset('koksan-logo.png')); ?>" alt="Köksan Logo" class="me-2">
                    <strong>Köksan İş Süreçleri Portalı</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        <?php if(auth()->guard()->guest()): ?>
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>"><i
                                            class="fa-solid fa-right-to-bracket"></i><span>Giriş Yap</span></a></li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('general.calendar')); ?>"><i
                                        class="fa-solid fa-calendar-days" style="color: #667EEA;"></i><span>Genel
                                        Takvim</span></a></li>
                            <?php if(auth()->guard()->check()): ?> <li class="nav-item"><a class="nav-link" href="<?php echo e(route('home')); ?>"><i
                                            class="fa-solid fa-calendar-check"
                                        style="color: #4FD1C5;"></i><span>Takvimim</span></a></li> <?php endif; ?>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown"><i class="fa-solid fa-car-side"
                                        style="color: #FBD38D;"></i><span>Görevler & Atamalar</span></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo e(route('service.assignments.create')); ?>"><i
                                                class="fa-solid fa-plus" style="color: #A78BFA;"></i> Yeni Görev Ekle</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('service.assignments.index')); ?>"><i
                                                class="fa-solid fa-list" style="color: #667EEA;"></i> Araçlı Görev
                                            Listesi</a>
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('service.general-tasks.index')); ?>"><i
                                                class="fa-solid fa-bars-progress" style="color: #435fdb;"></i> Araçsız Görev
                                            Listesi</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('service.assignments.created_by_me')); ?>"><i
                                                class="fa-solid fa-cloud-arrow-up" style="color: #659ef4;"></i> Atadığım
                                            Görevler</a></li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('my-assignments.index')); ?>"><i
                                                class="fa-solid fa-cloud-arrow-down" style="color: #4258bb;"></i> Bana
                                            Atanan
                                            Görevler</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?php echo e(route('teams.index')); ?>"><i
                                                class="fa-solid fa-people-group" style="color: #7a5ed1;"></i> Takım
                                            Yönetimi</a></li>
                                </ul>
                            </li>

                            

                            <?php if(Auth::user()->hasRole('admin') || Auth::user()->hasDepartment('Lojistik')): ?>
                                <li class="nav-item">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-route"
                                            style="color: #A78BFA;"></i><span>Lojistik</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?php echo e(route('shipments.create')); ?>"><i
                                                    class="fa-solid fa-truck-fast" style="color: #FBD38D;"></i> Yeni
                                                Sevkiyat</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('products.list')); ?>"><i
                                                    class="fa-solid fa-truck-ramp-box" style="color: #4FD1C5;"></i>
                                                Sevkiyat
                                                Listesi</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->hasRole('admin') || Auth::user()->hasDepartment('Üretim')): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-industry"
                                            style="color: #4FD1C5;"></i><span>Üretim</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?php echo e(route('production.plans.create')); ?>"><i
                                                    class="fa-solid fa-plus-circle" style="color: #F093FB;"></i> Yeni
                                                Plan</a>
                                        </li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('production.plans.index')); ?>"><i
                                                    class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Plan
                                                Listesi</a></li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            
                            <?php if(Auth::user()->hasRole('admin') || Auth::user()->hasDepartment('Bakım')): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown">
                                        
                                        <i class="fa-solid fa-screwdriver-wrench" style="color: #ED8936;"></i>
                                        <span>Bakım</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('maintenance.create')); ?>">
                                                <i class="fa-solid fa-plus-circle" style="color: #48BB78;"></i>
                                                Yeni Bakım Planı
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('maintenance.index')); ?>">
                                                <i class="fa-solid fa-clipboard-list" style="color: #4299E1;"></i>
                                                Planlanan Bakımlar Listesi
                                            </a>
                                        </li>
                                        
                                        <?php if(Auth::user()->role === 'admin' || Auth::user()->isManagerOrDirector()): ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="<?php echo e(route('approvals.maintenance')); ?>">
                                                    <i class="fas fa-check-double" style="color: #F6AD55;"></i> Onayımı
                                                    Bekleyenler
                                                    
                                                    <?php if(isset($globalPendingCount) && $globalPendingCount > 0): ?>
                                                        <span
                                                            class="badge bg-danger ms-auto rounded-pill"><?php echo e($globalPendingCount); ?></span>
                                                    <?php endif; ?>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo e(route('maintenance.assets.index')); ?>">
                                                <i class="fa-solid fa-industry" style="color: #805AD5;"></i>
                                                Makineler & Varlıklar
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            
                            <?php if(Auth::user()->hasDepartment('İdari İşler') || Auth::user()->hasDepartment('Ulaştırma')): ?>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown">
                                        
                                        <i class="fa-solid fa-concierge-bell" style="color: #d63384;"></i>
                                        <span>İdari İşler</span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        
                                        <?php if(Auth::user()->hasDepartment('İdari İşler')): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('service.events.create')); ?>">
                                                    
                                                    <i class="fa-solid fa-calendar-plus" style="color: #3B82F6;"></i>
                                                    Yeni Etkinlik
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('service.events.index')); ?>">
                                                    
                                                    <i class="fa-solid fa-calendar-days" style="color: #0EA5E9;"></i>
                                                    Etkinlik Listesi
                                                </a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                        <?php endif; ?>


                                        
                                        <?php if(Auth::user()->role === 'admin' ||
                                                (Auth::user()->department && in_array(Auth::user()->department->slug, ['hizmet', 'ulastirma']))): ?>
                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('service.vehicles.index')); ?>">
                                                    
                                                    <i class="fa-solid fa-car" style="color: #F59E0B;"></i>
                                                    Şirket Araçları
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item"
                                                    href="<?php echo e(route('service.logistics-vehicles.index')); ?>">
                                                    
                                                    <i class="fa-solid fa-truck" style="color: #EA580C;"></i>
                                                    Nakliye Araçları
                                                </a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php if(Auth::user()->role === 'admin' ||
                                                (Auth::user()->department && in_array(Auth::user()->department->slug, ['hizmet', 'ulastirma']))): ?>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            
                                            <?php if(Auth::user()->role === 'admin' || (Auth::user()->department && Auth::user()->department->slug === 'hizmet')): ?>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('travels.create')); ?>">
                                                        
                                                        <i class="fa-solid fa-route" style="color: #8B5CF6;"></i> Yeni
                                                        Seyahat
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <li>
                                                <a class="dropdown-item" href="<?php echo e(route('travels.index')); ?>">
                                                    
                                                    <i class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Seyahat
                                                    Listesi
                                                </a>
                                            </li>

                                            
                                            <?php if(Auth::user()->role === 'admin' ||
                                                    (Auth::user()->department && in_array(Auth::user()->department->slug, ['hizmet']))): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="<?php echo e(route('service.events.index', ['event_type' => 'fuar'])); ?>">
                                                        
                                                        <i class="fa-solid fa-tents" style="color: #10B981;"></i> Fuar
                                                        Yönetimi
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            
                                            <?php if(Auth::user()->role === 'admin' || (Auth::user()->department && Auth::user()->department->slug === 'hizmet')): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('bookings.index')); ?>">
                                                        
                                                        <i class="fa-solid fa-book-bookmark" style="color: #EC4899;"></i>
                                                        Tüm Rezervasyonlar
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            
                                            <?php if(Auth::user()->role === 'admin' ||
                                                    (Auth::user()->department && in_array(Auth::user()->department->slug, ['hizmet']))): ?>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="<?php echo e(route('customers.index')); ?>">
                                                        
                                                        <i class="fa-solid fa-users" style="color: #06B6D4;"></i> Müşteri
                                                        Yönetimi
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>

                            
                            <li class="nav-item dropdown me-3">
                                <?php
                                    $unreadCount = auth()->user()->unreadNotifications->count();
                                    $iconColor = $unreadCount > 0 ? '#d11f1f' : '#0d6efd';
                                ?>

                                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#"
                                    role="button">
                                    
                                    <i class="fa-solid fa-bell fa-lg" id="notification-icon"
                                        style="color: <?php echo e($iconColor); ?>; transition: color 0.3s ease;"></i>

                                    
                                    <span id="notification-badge"
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="display: <?php echo e($unreadCount > 0 ? 'inline-block' : 'none'); ?>;">
                                        <?php echo e($unreadCount); ?>

                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                    style="width: 320px; border-radius: 1rem;">
                                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light"
                                        style="border-radius: 1rem 1rem 0 0;">
                                        <h6 class="mb-0 fw-bold text-dark">Bildirimler</h6>

                                        
                                        <a href="<?php echo e(route('notifications.readAll')); ?>" id="mark-all-read"
                                            class="text-decoration-none small fw-bold text-primary"
                                            style="display: <?php echo e($unreadCount > 0 ? 'inline-block' : 'none'); ?>;">
                                            Tümünü Oku
                                        </a>
                                    </div>

                                    
                                    <div id="notification-list" class="list-group list-group-flush"
                                        style="max-height: 300px; overflow-y: auto;">
                                        <?php $__empty_1 = true; $__currentLoopData = auth()->user()->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <a href="<?php echo e(route('notifications.read', $notification->id)); ?>"
                                                class="list-group-item list-group-item-action p-3 border-bottom-0 d-flex align-items-start">
                                                <div
                                                    class="me-3 mt-1 text-<?php echo e($notification->data['color'] ?? 'primary'); ?>">
                                                    <i
                                                        class="fa-solid <?php echo e($notification->data['icon'] ?? 'fa-info-circle'); ?> fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="small fw-bold text-dark mb-1">
                                                        <?php echo e($notification->data['title'] ?? 'Bildirim'); ?></div>
                                                    <p class="mb-1 small text-muted lh-sm">
                                                        <?php echo e($notification->data['message'] ?? ''); ?></p>
                                                    <small class="text-secondary fw-bold" style="font-size: 0.7rem;">
                                                        <?php echo e($notification->created_at->diffForHumans()); ?>

                                                    </small>
                                                </div>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <div class="p-4 text-center text-muted">
                                                <i
                                                    class="fa-regular fa-bell-slash fa-2x mb-3 text-secondary opacity-50"></i>
                                                <p class="mb-0 small fw-medium">Şu an yeni bildiriminiz yok.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-gear me-1"></i><span class="d-inline-block text-truncate"
                                        style="max-width: 100px;"
                                        title="<?php echo e(Auth::user()->name); ?>"><?php echo e(Auth::user()->name); ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>"><i
                                                class="fa-solid fa-user-pen" style="color: #4FD1C5;"></i> Profilimi
                                            Düzenle</a></li>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('is-global-manager')): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('users.create')); ?>"><i
                                                    class="fa-solid fa-user-plus" style="color: #667EEA;"></i> Kullanıcı
                                                Ekle</a></li>
                                    <?php endif; ?>
                                    <?php if(Auth::user()->role === 'admin'): ?>
                                        <li><a class="dropdown-item" href="<?php echo e(route('users.index')); ?>"><i
                                                    class="fa-solid fa-list" style="color: #31317e;"></i>
                                                Kullanıcıları
                                                Görüntüle</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('birimler.index')); ?>"><i
                                                    class="fa-solid fa-tags" style="color: #FBD38D;"></i> Birimleri
                                                Yönet</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('departments.index')); ?>"><i
                                                    class="fa-solid fa-building" style="color: #667EEA;"></i>
                                                Departmanlar</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('roles.index')); ?>"><i
                                                    class="fa-solid fa-building-user" style="color: #8b0672;"></i>
                                                Roller</a></li>
                                        <li><a class="dropdown-item" href="<?php echo e(route('logs.index')); ?>"><i
                                                    class="fa-solid fa-file-lines" style="color: #f78dfb;"></i> Loglar</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="dropdown-item w-100 text-start"
                                                style="cursor: pointer; background: transparent; border: none;">
                                                <i class="fa-solid fa-right-from-bracket" style="color: #FC8181;"></i>
                                                Çıkış Yap
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <?php echo $__env->yieldContent('page_scripts'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. LOADER (YÜKLENİYOR EKRANI) ---
            // Sayfa tamamen hazır olduğunda loader'ı kaldır
            const loader = document.getElementById('global-loader');
            if (loader) {
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        loader.classList.add('loaded');
                    }, 150);
                });
                // Güvenlik önlemi: 3 saniye geçse bile loader gitmediyse zorla kaldır (Donmayı önler)
                setTimeout(function() {
                    if (!loader.classList.contains('loaded')) {
                        loader.classList.add('loaded');
                    }
                }, 3000);
            }

            // --- 2. SCROLL EFEKTİ & MOBİL MENÜ ---
            window.addEventListener('scroll', function() {
                const navbar = document.querySelector('.navbar');
                if (navbar) {
                    if (window.scrollY > 50) navbar.classList.add('scrolled');
                    else navbar.classList.remove('scrolled');
                }
            });

            document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        const navbarCollapse = document.querySelector('.navbar-collapse');
                        if (navbarCollapse) {
                            const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                                toggle: false
                            });
                            bsCollapse.hide();
                        }
                    }
                });
            });

            // --- 3. GLOBAL TOAST BİLDİRİMLERİ ---
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // PHP Session Mesajlarını JS ile Tetikle
            <?php if(session('success')): ?>
                Toast.fire({
                    icon: 'success',
                    title: '<?php echo e(session('success')); ?>'
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Toast.fire({
                    icon: 'error',
                    title: '<?php echo e(session('error')); ?>'
                });
            <?php endif; ?>
            <?php if(session('warning')): ?>
                Toast.fire({
                    icon: 'warning',
                    title: '<?php echo e(session('warning')); ?>'
                });
            <?php endif; ?>

            // Global fonksiyon olarak dışarı açıyoruz
            window.showToast = function(message, type = 'success') {
                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // --- 4. AKILLI SİLME (DELETE CONFIRMATION) ---
            // Tarayıcının varsayılan confirm kutusunu iptal et
            document.querySelectorAll('form').forEach(form => {
                if (form.getAttribute('onsubmit') && form.getAttribute('onsubmit').includes('confirm')) {
                    form.removeAttribute('onsubmit');
                }
            });

            document.addEventListener('submit', function(e) {
                const form = e.target;
                const methodInput = form.querySelector('input[name="_method"]');

                // Sadece DELETE metodlu formlarda çalış
                if (form.tagName === 'FORM' && methodInput && methodInput.value.toUpperCase() ===
                    'DELETE') {
                    e.preventDefault();

                    Swal.fire({
                        title: 'Emin misiniz?',
                        text: "Bu kaydı silmek istediğinize emin misiniz?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Evet, Sil!',
                        cancelButtonText: 'İptal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const btn = form.querySelector('button[type="submit"]');
                            if (btn) btn.disabled = true;

                            fetch(form.action, {
                                    method: 'POST',
                                    body: new FormData(form),
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(async response => {
                                    if (response.status === 403) {
                                        showToast(
                                            '⛔ Bu işlemi yapmaya yetkiniz bulunmamaktadır!',
                                            'error');
                                        return;
                                    }
                                    if (response.ok) {
                                        const contentType = response.headers.get(
                                            "content-type");
                                        if (contentType && contentType.indexOf(
                                                "application/json") !== -1) {
                                            await Swal.fire('Silindi!',
                                                'Kayıt başarıyla silindi.', 'success');
                                            window.location.reload();
                                        } else {
                                            // HTML döndüyse (redirect olduysa) hata kontrolü yap
                                            const htmlText = await response.text();
                                            const parser = new DOMParser();
                                            const doc = parser.parseFromString(htmlText,
                                                'text/html');
                                            const errorAlert = doc.querySelector(
                                                '.alert-danger');

                                            if (errorAlert) {
                                                let errorMsg = errorAlert.innerText.trim()
                                                    .replace('×', '').trim();
                                                showToast(errorMsg, 'error');
                                            } else {
                                                await Swal.fire('Silindi!',
                                                    'Kayıt başarıyla silindi.',
                                                    'success');
                                                window.location.reload();
                                            }
                                        }
                                    } else {
                                        showToast('Bir hata oluştu.', 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error(error);
                                    showToast('Sunucu hatası.', 'error');
                                })
                                .finally(() => {
                                    if (btn) btn.disabled = false;
                                });
                        }
                    });
                }
            });

            // --- 5. BİLDİRİM VE SİSTEM GÜNCELLEME KONTROLÜ ---
            // İki ayrı setInterval yerine tek bir zamanlayıcı kullanmak daha performanslıdır, 
            // ama yapıları farklı olduğu için bağımsız bırakıyoruz.

            // A) Bildirim Kontrolü
            setInterval(function() {
                fetch("<?php echo e(route('notifications.check')); ?>")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        const badge = document.getElementById('notification-badge');
                        const icon = document.getElementById('notification-icon');
                        const readAllLink = document.getElementById('mark-all-read');
                        const list = document.getElementById('notification-list');

                        if (badge) {
                            badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                            badge.innerText = data.count;
                        }
                        if (icon) icon.style.color = data.count > 0 ? '#d11f1f' : '#0d6efd';
                        if (readAllLink) readAllLink.style.display = data.count > 0 ? 'inline-block' :
                            'none';
                        if (list && list.innerHTML !== data.html) list.innerHTML = data.html;
                    })
                    .catch(err => console.error('Bildirim hatası:', err));
            }, 10000);

            // B) Global Akıllı Yenileme (Global Smart Refresh)
            const initialHash = "<?php echo e($globalDataHash ?? ''); ?>";
            if (initialHash) {
                setInterval(function() {
                    // Aktif olarak yazı yazılıyorsa yenileme yapma
                    if (document.activeElement.tagName === 'INPUT' ||
                        document.activeElement.tagName === 'TEXTAREA' ||
                        document.activeElement.isContentEditable) {
                        return;
                    }

                    fetch("<?php echo e(route('system.check_updates')); ?>")
                        .then(res => res.json())
                        .then(data => {
                            if (data.hash && data.hash !== initialHash) {
                                console.log('Sistem güncellendi, sayfa yenileniyor...');
                                window.location.reload();
                            }
                        })
                        .catch(err => console.error('Güncelleme kontrolü başarısız:', err));
                }, 10000); // 10 saniyede bir kontrol
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/layouts/app.blade.php ENDPATH**/ ?>