<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('koksan.ico?v=13') }}" type="image/ico">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- SweetAlert2 CSS --}}
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

        /* --- Navbar Temel Yapısı --- */
        nav.navbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
            margin: 0 !important;
            padding: 0.5rem 0 !important;
            /* Dikey padding azaltıldı */
            background: var(--navbar-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: var(--navbar-shadow);
            transition: var(--transition);
        }

        nav.navbar.scrolled {
            padding: 0.4rem 0 !important;
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

        .navbar-brand img {
            height: 30px;
            transition: var(--transition);
        }

        /* --- Esnek Nav Linkleri --- */
        .nav-link {
            font-weight: 600;
            font-size: 0.85rem;
            /* Font hafif küçültüldü */
            color: #4a5568 !important;
            padding: 0.4rem 0.6rem !important;
            /* Yatay boşluk daraltıldı */
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
            white-space: normal;
            /* Yakınlaştırınca metnin aşağı kaymasına izin verir */
            display: flex;
            align-items: center;
            gap: 0.4rem;
            text-align: center;
        }

        .nav-link:hover {
            background: var(--hover-bg);
            color: #667eea !important;
            transform: translateY(-2px);
        }

        /* --- Dropdown Menü --- */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            animation: slideDown 0.3s ease-out;
        }

        /* --- Kullanıcı Badge ve Dropdown --- */
        #navbarDropdown {
            background: var(--primary-gradient);
            color: white !important;
            padding: 0.45rem 1rem !important;
            border-radius: 25px;
            font-size: 0.8rem;
            max-width: 150px;
            /* Zoom'da taşmayı önlemek için sınırlandırıldı */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        /* --- Responsive Düzenlemeler (Zoom ve Mobil Dostu) --- */
        @media (max-width: 991px) {

            /* Mobil menü açıldığında ekranı kaplamaması için scroll eklendi */
            .navbar-collapse {
                max-height: 80vh;
                overflow-y: auto;
                background: rgba(255, 255, 255, 0.98);
                padding: 1rem;
                border-radius: 15px;
                margin-top: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .nav-item {
                width: 100%;
                margin-bottom: 5px;
            }

            .nav-link {
                justify-content: flex-start;
                padding: 0.75rem 1rem !important;
            }

            #navbarDropdown {
                max-width: 100% !important;
                /* Mobilde tam genişlik */
                width: 100%;
            }

            main {
                margin-top: 80px !important;
                /* Mobil navbar yüksekliğine göre ayar */
            }
        }

        /* --- Main Content --- */
        #app {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            padding: 0 !important;
            margin: 6rem 0 0 0 !important;
        }

        /* --- Loader ve Diğer Animasyonlar --- */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 99999;
            background: #f0f5ff;
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
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
    </style>
    @stack('styles')
</head>

<body>
    <div id="global-loader">
        <div class="loader-spinner"></div>
        <div class="loader-text">Yükleniyor...</div>
    </div>
    <div id="app">
        <nav class="navbar navbar-expand-xl navbar-light">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                    <img src="{{ asset('koksan-logo.png') }}" alt="Köksan Logo" class="me-2">
                    <strong>KÖKSAN Takvim</strong>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-lg-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i
                                            class="fa-solid fa-right-to-bracket"></i><span>Giriş Yap</span></a></li>
                            @endif
                        @else
                            {{-- GLOBAL USER DEĞİŞKENİ --}}
                            @php $user = Auth::user(); @endphp

                            <li class="nav-item"><a class="nav-link" href="{{ route('general.calendar') }}"><i
                                        class="fa-solid fa-calendar-days" style="color: #667EEA;"></i><span>Genel
                                        Takvim</span></a></li>
                            @auth <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i
                                            class="fa-solid fa-calendar-check"
                                        style="color: #4FD1C5;"></i><span>Takvimim</span></a></li> @endauth

                            {{-- GÖREVLER & ATAMALAR (Herkes görebilir - Kısıtlama Yok) --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown"><i class="fa-solid fa-car-side"
                                        style="color: #FBD38D;"></i><span>Görevler & Atamalar</span></a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('service.assignments.create') }}"><i
                                                class="fa-solid fa-plus" style="color: #A78BFA;"></i> Yeni Görev Ekle</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('service.assignments.index') }}"><i
                                                class="fa-solid fa-list" style="color: #667EEA;"></i> Araçlı Görev
                                            Listesi</a>
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('service.general-tasks.index') }}"><i
                                                class="fa-solid fa-bars-progress" style="color: #435fdb;"></i> Araçsız Görev
                                            Listesi</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('service.assignments.created_by_me') }}"><i
                                                class="fa-solid fa-cloud-arrow-up" style="color: #659ef4;"></i> Atadığım
                                            Görevler</a></li>
                                    <li><a class="dropdown-item" href="{{ route('my-assignments.index') }}"><i
                                                class="fa-solid fa-cloud-arrow-down" style="color: #4258bb;"></i> Bana
                                            Atanan Görevler</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('teams.index') }}"><i
                                                class="fa-solid fa-people-group" style="color: #7a5ed1;"></i> Takım
                                            Yönetimi</a></li>
                                </ul>
                            </li>

                            {{-- LOJİSTİK MENÜSÜ --}}
                            @if ($user->hasDepartmentPermission('view_logistics'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-route"
                                            style="color: #A78BFA;"></i><span>Lojistik</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('shipments.create') }}"><i
                                                    class="fa-solid fa-truck-fast" style="color: #FBD38D;"></i> Yeni
                                                Sevkiyat</a></li>
                                        <li><a class="dropdown-item" href="{{ route('products.list') }}"><i
                                                    class="fa-solid fa-truck-ramp-box" style="color: #4FD1C5;"></i>
                                                Sevkiyat Listesi</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- ÜRETİM MENÜSÜ --}}
                            @if ($user->hasDepartmentPermission('view_production'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-industry"
                                            style="color: #4FD1C5;"></i><span>Üretim</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('production.plans.create') }}"><i
                                                    class="fa-solid fa-plus-circle" style="color: #F093FB;"></i> Yeni
                                                Plan</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('production.plans.index') }}"><i
                                                    class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Plan
                                                Listesi</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- BAKIM DEPARTMANI MENÜSÜ --}}
                            @if ($user->hasDepartmentPermission('view_maintenance'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-screwdriver-wrench" style="color: #ED8936;"></i>
                                        <span>Bakım</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('maintenance.create') }}">
                                                <i class="fa-solid fa-plus-circle" style="color: #48BB78;"></i>
                                                Yeni Bakım Planı
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('maintenance.index') }}">
                                                <i class="fa-solid fa-clipboard-list" style="color: #4299E1;"></i>
                                                Planlanan Bakımlar Listesi
                                            </a>
                                        </li>

                                        {{-- Onay Menüsü: Burası özel, sadece yetkisi olan görmeli --}}
                                        @can('approve_maintenance')
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('approvals.maintenance') }}">
                                                    <i class="fas fa-check-double" style="color: #F6AD55;"></i> Onayımı
                                                    Bekleyenler
                                                    @if (isset($globalPendingCount) && $globalPendingCount > 0)
                                                        <span
                                                            class="badge bg-danger ms-auto rounded-pill">{{ $globalPendingCount }}</span>
                                                    @endif
                                                </a>
                                            </li>
                                        @endcan
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('maintenance.assets.index') }}">
                                                <i class="fa-solid fa-industry" style="color: #805AD5;"></i>
                                                Makineler & Varlıklar
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif

                            {{-- İDARİ İŞLER MENÜSÜ --}}
                            @if ($user->hasDepartmentPermission('view_administrative'))
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-concierge-bell" style="color: #d63384;"></i>
                                        <span>İdari İşler</span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-menu-end">
                                        {{-- ETKİNLİK YÖNETİMİ --}}
                                        <li><a class="dropdown-item" href="{{ route('service.events.create') }}">
                                                <i class="fa-solid fa-calendar-plus" style="color: #3B82F6;"></i> Yeni
                                                Etkinlik</a></li>
                                        <li><a class="dropdown-item" href="{{ route('service.events.index') }}">
                                                <i class="fa-solid fa-calendar-days" style="color: #0EA5E9;"></i> Etkinlik
                                                Listesi</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        {{-- ARAÇ YÖNETİMİ --}}
                                        <li><a class="dropdown-item" href="{{ route('service.vehicles.index') }}">
                                                <i class="fa-solid fa-car" style="color: #F59E0B;"></i> Şirket
                                                Araçları</a></li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('service.logistics-vehicles.index') }}">
                                                <i class="fa-solid fa-truck" style="color: #EA580C;"></i> Nakliye
                                                Araçları</a></li>

                                        {{-- SEYAHAT YÖNETİMİ --}}
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('travels.create') }}">
                                                <i class="fa-solid fa-route" style="color: #8B5CF6;"></i> Yeni Seyahat</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('travels.index') }}">
                                                <i class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Seyahat
                                                Listesi</a></li>

                                        {{-- FUAR YÖNETİMİ --}}
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('service.events.index', ['event_type' => 'fuar']) }}">
                                                <i class="fa-solid fa-tents" style="color: #10B981;"></i> Fuar
                                                Yönetimi</a></li>

                                        {{-- REZERVASYON & MÜŞTERİ --}}
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('bookings.index') }}">
                                                <i class="fa-solid fa-book-bookmark" style="color: #EC4899;"></i> Tüm
                                                Rezervasyonlar</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('customers.index') }}">
                                                <i class="fa-solid fa-users" style="color: #06B6D4;"></i> Müşteri
                                                Yönetimi</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- BUSINESS UNIT SWITCHER (BİRİM DEĞİŞTİRİCİ) --}}
                            @auth
                                @php
                                    // Admin ise tüm aktif birimleri, değilse sadece bağlı olduklarını getirir
                                    $authorizedUnits = auth()->user()->getAuthorizedBusinessUnits();
                                @endphp

                                @if ($authorizedUnits->count() > 1)
                                    <li class="nav-item dropdown me-3 d-flex align-items-center">
                                        <a class="nav-link dropdown-toggle btn btn-sm shadow-sm border" href="#"
                                            role="button" data-bs-toggle="dropdown"
                                            style="border-radius: 20px; padding: 5px 15px; background: rgba(255,255,255,0.8); border-color: #e2e8f0 !important;">
                                            <i class="fa-solid fa-industry text-primary me-2"></i>
                                            <span class="fw-bold text-dark" style="font-size: 0.85rem;">
                                                {{ session('active_unit_name', 'Birim Seçiniz') }}
                                            </span>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0"
                                            style="border-radius: 12px; min-width: 200px;">
                                            <li class="dropdown-header text-uppercase small fw-bold text-muted px-3 py-2">
                                                Çalışma Alanı Seç</li>
                                            <li>
                                                <hr class="dropdown-divider my-0">
                                            </li>
                                            @foreach ($authorizedUnits as $unit)
                                                <li>
                                                    <form action="{{ route('switch.unit') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="unit_id" value="{{ $unit->id }}">
                                                        <button type="submit"
                                                            class="dropdown-item d-flex justify-content-between align-items-center px-3 py-2"
                                                            style="cursor: pointer;">
                                                            <span class="fw-semibold">{{ $unit->name }}</span>
                                                            @if (session('active_unit_id') == $unit->id)
                                                                <i class="fa-solid fa-circle-check text-success"></i>
                                                            @endif
                                                        </button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @elseif($authorizedUnits->count() == 1)
                                    <li class="nav-item me-3 d-flex align-items-center">
                                        <span class="badge bg-white text-primary border px-3 py-2 rounded-pill shadow-sm"
                                            style="font-size: 0.8rem;">
                                            <i class="fa-solid fa-industry me-1"></i>
                                            {{ $authorizedUnits->first()->name }}
                                        </span>
                                    </li>
                                @else
                                    {{-- Sadece Admin OLMAYAN ve hiçbir birime atanmamış personelde burası gözükür --}}
                                    <li class="nav-item me-3 d-flex align-items-center">
                                        <span class="badge bg-danger text-white border px-3 py-2 rounded-pill shadow-sm">
                                            <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                            Yetkili Birim Yok!
                                        </span>
                                    </li>
                                @endif
                            @endauth

                            {{-- BİLDİRİM MENÜSÜ --}}
                            <li class="nav-item dropdown me-3">
                                @php
                                    $unreadCount = auth()->user()->unreadNotifications->count();
                                    $iconColor = $unreadCount > 0 ? '#d11f1f' : '#0d6efd';
                                @endphp

                                <a class="nav-link position-relative" data-bs-toggle="dropdown" href="#"
                                    role="button">
                                    <i class="fa-solid fa-bell fa-lg" id="notification-icon"
                                        style="color: {{ $iconColor }}; transition: color 0.3s ease;"></i>

                                    <span id="notification-badge"
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        style="display: {{ $unreadCount > 0 ? 'inline-block' : 'none' }};">
                                        {{ $unreadCount }}
                                    </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow-lg border-0"
                                    style="width: 320px; border-radius: 1rem;">
                                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light"
                                        style="border-radius: 1rem 1rem 0 0;">
                                        <h6 class="mb-0 fw-bold text-dark">Bildirimler</h6>

                                        <a href="{{ route('notifications.readAll') }}" id="mark-all-read"
                                            class="text-decoration-none small fw-bold text-primary"
                                            style="display: {{ $unreadCount > 0 ? 'inline-block' : 'none' }};">
                                            Tümünü Oku
                                        </a>
                                    </div>

                                    <div id="notification-list" class="list-group list-group-flush"
                                        style="max-height: 300px; overflow-y: auto;">
                                        @forelse (auth()->user()->unreadNotifications as $notification)
                                            <a href="{{ route('notifications.read', $notification->id) }}"
                                                class="list-group-item list-group-item-action p-3 border-bottom-0 d-flex align-items-start">
                                                <div
                                                    class="me-3 mt-1 text-{{ $notification->data['color'] ?? 'primary' }}">
                                                    <i
                                                        class="fa-solid {{ $notification->data['icon'] ?? 'fa-info-circle' }} fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="small fw-bold text-dark mb-1">
                                                        {{ $notification->data['title'] ?? 'Bildirim' }}</div>
                                                    <p class="mb-1 small text-muted lh-sm">
                                                        {{ $notification->data['message'] ?? '' }}</p>
                                                    <small class="text-secondary fw-bold" style="font-size: 0.7rem;">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </a>
                                        @empty
                                            <div class="p-4 text-center text-muted">
                                                <i
                                                    class="fa-regular fa-bell-slash fa-2x mb-3 text-secondary opacity-50"></i>
                                                <p class="mb-0 small fw-medium">Şu an yeni bildiriminiz yok.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </li>

                            {{-- KULLANICI MENÜSÜ --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fa-solid fa-user-gear me-1"></i><span class="d-inline-block text-truncate"
                                        style="max-width: 100px;"
                                        title="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i
                                                class="fa-solid fa-user-pen" style="color: #4FD1C5;"></i> Profilimi
                                            Düzenle</a></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('kanban-boards.index') }}">
                                            <i class="fa-solid fa-chalkboard-user" style="color: #e65100;"></i>
                                            İş Panoları (Kanban)
                                        </a>
                                    </li>

                                    {{-- YÖNETİCİ MENÜLERİ (Spatie Güncellemesi) --}}
                                    @role('admin')
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}"><i
                                                    class="fa-solid fa-user-plus" style="color: #667EEA;"></i> Kullanıcı
                                                Ekle</a></li>

                                        <li><a class="dropdown-item" href="{{ route('users.index') }}"><i
                                                    class="fa-solid fa-list" style="color: #31317e;"></i>
                                                Kullanıcıları
                                                Görüntüle</a></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('business-units.index') }}">
                                                <i class="fa-solid fa-industry" style="color: #FBD38D;"></i>
                                                Fabrika Yönetimi
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('birimler.index') }}">
                                                <i class="fa-solid fa-scale-balanced" style="color: #667EEA;"></i>
                                                Ölçü Birimleri
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.custom-fields.index') }}">
                                                <i class="fa-solid fa-wpforms" style="color: #00177c;"></i>
                                                Form Alanları
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item" href="{{ route('report-settings.index') }}">
                                                <i class="fa-solid  fa-cogs" style="color: #8b8fa3;"></i>
                                                Rapor Yönetimi
                                            </a>
                                        </li>



                                        <li><a class="dropdown-item" href="{{ route('departments.index') }}"><i
                                                    class="fa-solid fa-building" style="color: #667EEA;"></i>
                                                Departmanlar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('roles.index') }}"><i
                                                    class="fa-solid fa-building-user" style="color: #8b0672;"></i>
                                                Roller</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logs.index') }}"><i
                                                    class="fa-solid fa-file-lines" style="color: #f78dfb;"></i> Loglar</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endrole

                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item w-100 text-start"
                                                style="cursor: pointer; background: transparent; border: none;">
                                                <i class="fa-solid fa-right-from-bracket" style="color: #FC8181;"></i>
                                                Çıkış Yap
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    <div id="ai-chat-wrapper" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <button id="chat-toggle" class="btn btn-primary rounded-circle shadow-lg"
            style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <i class="fa-solid fa-robot fa-lg text-white"></i>
        </button>

        <div id="chat-window" class="card shadow-lg d-none"
            style="position: absolute; bottom: 70px; right: 0; width: 350px; border-radius: 15px; border: none; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
                style="border-radius: 15px 15px 0 0;">
                <span class="fw-bold"><i class="fa-solid fa-headset me-2"></i>Portal Asistanı</span>
                <button type="button" class="btn-close btn-close-white" id="chat-close"></button>
            </div>
            <div id="chat-body" class="card-body"
                style="height: 300px; overflow-y: auto; font-size: 0.9rem; display: flex; flex-direction: column; gap: 10px;">
                <div class="ai-msg bg-light p-2 rounded">Merhaba! Size nasıl yardımcı olabilirim?</div>
            </div>
            <div class="card-footer bg-transparent border-top-0">
                <div class="input-group">
                    <input type="text" id="chat-input" class="form-control form-control-sm"
                        placeholder="Sorunuzu yazın...">
                    <button class="btn btn-primary btn-sm" id="chat-send"><i
                            class="fa-solid fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
    </div>
    @yield('page_scripts')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. LOADER (YÜKLENİYOR EKRANI) ---
            const loader = document.getElementById('global-loader');
            if (loader) {
                window.addEventListener('load', function() {
                    setTimeout(function() {
                        loader.classList.add('loaded');
                    }, 150);
                });
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

            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            window.showToast = function(message, type = 'success') {
                Toast.fire({
                    icon: type,
                    title: message
                });
            }

            // --- 4. AKILLI SİLME KONFİRMASYONU ---
            document.addEventListener('submit', function(e) {
                const form = e.target;
                const methodInput = form.querySelector('input[name="_method"]');
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
                        if (result.isConfirmed) form.submit();
                    });
                }
            });

            // --- 5. BİLDİRİM VE GÜNCELLEME KONTROLÜ ---
            setInterval(function() {
                fetch("{{ route('notifications.check') }}")
                    .then(res => res.ok ? res.json() : Promise.reject(res))
                    .then(data => {
                        const badge = document.getElementById('notification-badge');
                        const icon = document.getElementById('notification-icon');
                        const list = document.getElementById('notification-list');
                        if (badge) {
                            badge.style.display = data.count > 0 ? 'inline-block' : 'none';
                            badge.innerText = data.count;
                        }
                        if (icon) icon.style.color = data.count > 0 ? '#d11f1f' : '#0d6efd';
                        if (list && list.innerHTML !== data.html) list.innerHTML = data.html;
                    })
                    .catch(err => console.error('Bildirim hatası:', err));
            }, 30000);

            // --- 6. PORTAL ASİSTANI (AI CHAT) ---
            const toggle = document.getElementById('chat-toggle');
            const windowDiv = document.getElementById('chat-window');
            const close = document.getElementById('chat-close');
            const input = document.getElementById('chat-input');
            const send = document.getElementById('chat-send');
            const body = document.getElementById('chat-body');

            if (toggle) {
                toggle.onclick = () => windowDiv.classList.toggle('d-none');
                close.onclick = () => windowDiv.classList.add('d-none');

                function linkify(text) {
                    var urlPattern = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
                    return text.replace(urlPattern,
                        '<a href="$1" target="_blank" class="fw-bold" style="color: #667eea; text-decoration: underline;">$1</a>'
                    );
                }

                async function sendMessage() {
                    const text = input.value.trim();
                    if (!text) return;

                    body.innerHTML +=
                        `<div class="user-msg text-end mb-2"><span class="bg-primary text-white p-2 rounded d-inline-block shadow-sm" style="font-size:0.85rem;">${text}</span></div>`;
                    input.value = '';
                    body.scrollTop = body.scrollHeight;

                    const loaderId = 'loader_' + Date.now();
                    body.innerHTML +=
                        `<div class="ai-msg bg-light p-2 rounded shadow-sm mb-2" id="${loaderId}" style="font-size:0.85rem;"><i class="fa-solid fa-spinner fa-spin"></i> Düşünüyorum...</div>`;
                    body.scrollTop = body.scrollHeight;

                    try {
                        const res = await axios.post("{{ route('ai.ask') }}", {
                            message: text
                        });
                        // Düzeltme: innerText yerine innerHTML kullanıyoruz
                        document.getElementById(loaderId).innerHTML = linkify(res.data.answer);
                    } catch (e) {
                        document.getElementById(loaderId).innerText = "Hata oluştu. Lütfen tekrar deneyin.";
                    }
                    body.scrollTop = body.scrollHeight;
                }

                send.onclick = sendMessage;
                input.onkeypress = (e) => {
                    if (e.key === 'Enter') sendMessage();
                };
            }
        });
    </script>
</body>

</html>
