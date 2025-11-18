<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('koksan.ico?v=13') }}" type="image/ico">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html,
        body {
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        body {
            background: linear-gradient(180deg, #f0f5ff 0%, #e6f7f9 50%, #e6e9fa 100%);
            background-attachment: fixed;
            background-size: cover;
            overflow-x: hidden;
        }

        nav.navbar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 1000 !important;
            margin: 0 !important;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

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
            margin-top: 70px !important;
        }

        .card {
            background-color: transparent !important;
            border: none !important;
        }
    </style>
    @stack('styles')
</head>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm"
            style="background: linear-gradient(to right, #DDE3FB, #FFFFFF);">
            <div class="container-fluid"> <a class="navbar-brand d-flex align-items-center"
                    href="{{ route('welcome') }}"> <img src="{{ asset('koksan-logo.png') }}" alt="Köksan Logo"
                        style="height: 30px;" class="me-2"> <strong>Köksan İş Süreçleri Portalı</strong> </a> <button
                    class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"> <span class="navbar-toggler-icon"></span> </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto"> </ul>
                    <ul class="navbar-nav ms-auto"> @guest @if (Route::has('login'))
                            <li class="nav-item"> <a class="nav-link fw-bold" href="{{ route('login') }}"> <i
                                        class="fa-solid fa-right-to-bracket me-1" style="color: #667EEA;"></i> Giriş Yap
                                </a> </li>
                        @endif
                    @else
                        <li class="nav-item me-2"> <a class="nav-link fw-bold" href="{{ route('general.calendar') }}">
                                <i class="fa-solid fa-calendar-days" style="color: #667EEA;"></i> Genel KÖKSAN Takvimi
                            </a> </li> @auth <li class="nav-item me-2"> <a class="nav-link fw-bold"
                                    href="{{ route('home') }}"> <i class="fa-solid fa-calendar-days me-1"
                                    style="color: #4FD1C5;"></i> Benim Takvimim </a> </li> @endauth <li
                            class="nav-item dropdown me-2"> <a class="nav-link dropdown-toggle fw-bold" href="#"
                                id="navbarDropdownProducts" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false"> <i class="fa-solid fa-car-side me-1" style="color: #FBD38D;"></i>
                                Araç Görevleri </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownProducts">
                                <li><a class="dropdown-item fw-bold" href="{{ route('service.assignments.create') }}">
                                        <i class="fa-solid fa-plus me-1" style="color: #A78BFA;"></i> Araç Görevi
                                        Ekle</a></li>
                                <li><a class="dropdown-item fw-bold" href="{{ route('service.assignments.index') }}">
                                        <i class="fa-solid fa-list me-1" style="color: #667EEA"></i> Araç Görev
                                        Listesi</a></li>
                                <li><a class="dropdown-item fw-bold" href="{{ route('teams.index') }}">
                                        <i class="fa-solid fa-people-group me-1" style="color: #7a5ed1"></i> Takımlar ve
                                        Yönetimleri</a></li>
                            </ul>
                            @if (Auth::check() && Auth::user()->pending_assignments_count > 0)
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ route('my-assignments.index') }}">
                                <i class="fas fa-tasks me-1" style="color: #d15e5e"></i> Görevlerim
                                <span class="badge bg-danger rounded-pill ms-1">
                                    {{ Auth::user()->pending_assignments_count }}
                                </span>
                            </a>
                        </li>
                        @endif
                        </li> @can('access-department', 'lojistik')
                            <li class="nav-item dropdown me-2"> <a class="nav-link dropdown-toggle fw-bold" href="#"
                                    id="navbarDropdownProducts" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"> <i class="fa-solid fa-route" style="color: #A78BFA;"></i>
                                    Sevkiyatlar </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownProducts">
                                    <li><a class="dropdown-item fw-bold" href="{{ route('shipments.create') }}"> <i
                                                class="fa-solid fa-truck-fast me-1" style="color: #FBD38D;"></i> Yeni
                                            Sevkiyat Ekle</a></li>
                                    <li><a class="dropdown-item fw-bold" href="{{ route('products.list') }}"> <i
                                                class="fa-solid fa-truck-ramp-box me-1" style="color: #4FD1C5"></i>
                                            Sevkiyat
                                            Listesi</a></li>
                                </ul>
                            </li>
                            @endcan @can('access-department', 'uretim')
                            <li class="nav-item dropdown me-2"> <a class="nav-link dropdown-toggle fw-bold"
                                    href="#" id="navbarDropdownProduction" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false"> <i class="fa-solid fa-industry"
                                        style="color: #4FD1C5;"></i>
                                    Üretim </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownProduction">
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('production.plans.create') }}">
                                            <i class="fa-solid fa-plus-circle me-1" style="color: #F093FB"></i> Yeni
                                            Üretim Planı </a> </li>
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('production.plans.index') }}">
                                            <i class="fa-solid fa-list-check me-1" style="color: #A78BFA"></i> Üretim
                                            Planı Listesi </a> </li>
                                </ul>
                            </li>
                            @endcan @can('access-department', 'hizmet')
                            <li class="nav-item dropdown me-2"> <a class="nav-link dropdown-toggle fw-bold"
                                    href="#" id="navbarDropdownService" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"> <i class="fa-solid fa-concierge-bell"
                                        style="color: #F093FB;"></i> İdari İşler </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownService">
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('service.events.create') }}"> <i
                                                class="fa-solid fa-calendar-plus me-1" style="color: #667EEA"></i> Yeni
                                            Etkinlik Ekle </a> </li>
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('service.events.index') }}"> <i
                                                class="fa-solid fa-calendar-days me-1" style="color: #4FD1C5"></i>
                                            Etkinlik Listesi </a> </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li> <a class="dropdown-item fw-bold" href="{{ route('service.vehicles.index') }}">
                                        <i class="fa-solid fa-car me-1" style="color: #FBD38D"></i> Araç Tanımları </a>
                                    <li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('travels.create') }}"> <i
                                                class="fa-solid fa-route me-1" style="color: #A78BFA"></i> Yeni Seyahat
                                            Planı</a>
                                    </li>
                                    <li> <a class="dropdown-item fw-bold" href="{{ route('travels.index') }}"> <i
                                                class="fa-solid fa-list-check me-1" style="color: #A78BFA"></i> Seyahat
                                            Planı Listesi</a>
                                    </li>
                                    <hr class="dropdown-divider">
                            </li> <a class="dropdown-item fw-bold" href="{{ route('customers.index') }}">
                                <i class="fa-solid fa-users me-1" style="color: #A78BFA;"></i> Müşteri Yönetimi
                            </a>
                            </li>
                            </li>
                        </ul>
                        </li>
                    @endcan
                    <li class="nav-item dropdown"> <a id="navbarDropdown" class="nav-link dropdown-toggle"
                            href="#" role="button" data-bs-toggle="dropdown" style="font-weight: 700;">
                            <i class="fa-solid fa-user-gear me-1" style="color: #F093FB;"></i>
                            {{ Auth::user()->name }} </a>
                        <div class="dropdown-menu dropdown-menu-end"> <a class="dropdown-item fw-bold"
                                href="{{ route('profile.edit') }}"> <i class="fa-solid fa-user-pen me-1"
                                    style="color: #4FD1C5;"></i> Profilimi Düzenle </a>
                            @can('is-global-manager')
                                <a class="dropdown-item fw-bold" href="{{ route('users.create') }}">
                                    <i class="fa-solid fa-user-plus me-1" style="color: #667EEA;"></i> Kullanıcı Ekle
                                </a>
                            @endcan

                            @if (Auth::user()->role === 'admin')
                                <a class="dropdown-item fw-bold" href="{{ route('birimler.index') }}"> <i
                                        class="fa-solid fa-tags me-1" style="color: #FBD38D;"></i> Birimleri Yönet
                                </a>
                                <a class="dropdown-item fw-bold" href="{{ route('departments.index') }}">
                                    <i class="fa-solid fa-building-user me-1" style="color: #667EEA;"></i>
                                    Departmanları Yönet
                                </a>
                                <a class="dropdown-item fw-bold" href="{{ route('logs.index') }}"> <i
                                        class="fa-solid fa-plus me-1" style="color: #f78dfb;"></i> Loglar
                                </a>
                            @endif <a class="dropdown-item fw-bold" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-right-from-bracket me-1" style="color: #FC8181;"></i> Çıkış
                                Yap </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf </form>
                        </div>
                </li> @endguest </ul>
            </div>
        </div>
    </nav>
    <main> @yield('content') </main>
</div> @yield('page_scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
