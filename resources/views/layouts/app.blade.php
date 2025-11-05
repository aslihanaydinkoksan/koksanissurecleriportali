<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name') }}</title>

    <link rel="icon" href="{{ asset('koksan.png') }}" type="image/png">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm py-4"
            style="background: linear-gradient(to right, #DDE3FB, #FFFFFF);">
            <div class="container-fluid">

                <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                    <img src="{{ asset('koksan-logo.png') }}" alt="Köksan Logo" style="height: 30px;" class="me-2">
                    <strong>Köksan İş Süreçleri Portalı</strong>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link fw-bold" href="{{ route('login') }}">
                                        <i class="fa-solid fa-right-to-bracket me-1" style="color: #667EEA;"></i>
                                        Giriş Yap
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item me-2">
                                <a class="nav-link fw-bold" href="{{ route('general.calendar') }}">
                                    <i class="fa-solid fa-calendar-days" style="color: #667EEA;"></i>
                                    Genel KÖKSAN Takvimi
                                </a>
                            </li>
                            @auth
                                <li class="nav-item me-2">
                                    <a class="nav-link fw-bold" href="{{ route('home') }}">
                                        <i class="fa-solid fa-calendar-days me-1" style="color: #4FD1C5;"></i>
                                        Benim Takvimim
                                    </a>
                                </li>
                            @endauth


                            <li class="nav-item dropdown me-2">
                                <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdownProducts"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-car-side me-1" style="color: #FBD38D;"></i>
                                    Araç Görevleri
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownProducts">
                                    <li><a class="dropdown-item fw-bold" href="{{ route('service.assignments.create') }}">
                                            <i class="fa-solid fa-plus me-1" style="color: #A78BFA;"></i>
                                            Araç Görevi Ekle</a></li>
                                    <li><a class="dropdown-item fw-bold" href="{{ route('service.assignments.index') }}">
                                            <i class="fa-solid fa-list me-1" style="color: #667EEA"></i>
                                            Araç Görev Listesi</a></li>
                                </ul>
                            </li>

                            @can('access-department', 'lojistik')
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdownProducts"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-route" style="color: #A78BFA;"></i>
                                        Sevkiyatlar
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProducts">
                                        <li><a class="dropdown-item fw-bold" href="{{ route('shipments.create') }}">
                                                <i class="fa-solid fa-truck-fast me-1" style="color: #FBD38D;"></i>
                                                Yeni Sevkiyat Ekle</a></li>
                                        <li><a class="dropdown-item fw-bold" href="{{ route('products.list') }}">
                                                <i class="fa-solid fa-truck-ramp-box me-1" style="color: #4FD1C5"></i>
                                                Sevkiyat Listesi</a></li>
                                    </ul>
                                </li>
                            @endcan

                            @can('access-department', 'uretim')
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdownProduction"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-industry" style="color: #4FD1C5;"></i>
                                        Üretim
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownProduction">
                                        <li>
                                            <a class="dropdown-item fw-bold" href="{{ route('production.plans.create') }}">
                                                <i class="fa-solid fa-plus-circle me-1" style="color: #F093FB"></i>
                                                Yeni Üretim Planı
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-bold" href="{{ route('production.plans.index') }}">
                                                <i class="fa-solid fa-list-check me-1" style="color: #A78BFA"></i>
                                                Üretim Planı Listesi
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endcan
                            @can('access-department', 'hizmet')
                                <li class="nav-item dropdown me-2">
                                    <a class="nav-link dropdown-toggle fw-bold" href="#" id="navbarDropdownService"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-concierge-bell" style="color: #F093FB;"></i>
                                        İdari İşler
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownService">
                                        <li>
                                            <a class="dropdown-item fw-bold" href="{{ route('service.events.create') }}">
                                                <i class="fa-solid fa-calendar-plus me-1" style="color: #667EEA"></i>
                                                Yeni Etkinlik Ekle
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item fw-bold" href="{{ route('service.events.index') }}">
                                                <i class="fa-solid fa-calendar-days me-1" style="color: #4FD1C5"></i>
                                                Etkinlik Listesi
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <a class="dropdown-item fw-bold" href="{{ route('service.vehicles.index') }}">
                                            <i class="fa-solid fa-car me-1" style="color: #FBD38D"></i>
                                            Araç Tanımları
                                        </a>
                                </li>
                                </li>
                            </ul>
                            </li>
                        @endcan

                        @if (in_array(Auth::user()->role, ['admin', 'yönetici']))
                            <li class="nav-item me-2">
                                <a class="nav-link fw-bold" href="{{ route('users.create') }}">
                                    <i class="fa-solid fa-user-plus me-1" style="color: #667EEA;"></i>
                                    Kullanıcı Ekle
                                </a>
                            </li>
                        @endif
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" style="font-weight: 700;">
                                <i class="fa-solid fa-user-gear me-1" style="color: #F093FB;"></i>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item fw-bold" href="{{ route('profile.edit') }}">
                                    <i class="fa-solid fa-user-pen me-1" style="color: #4FD1C5;"></i>
                                    Profilimi Düzenle
                                </a>
                                @if (Auth::user()->role === 'admin')
                                    <a class="dropdown-item fw-bold" href="{{ route('birimler.index') }}">
                                        <i class="fa-solid fa-tags me-1" style="color: #FBD38D;"></i>
                                        Birimleri Yönet
                                    </a>
                                @endif
                                <a class="dropdown-item fw-bold" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa-solid fa-right-from-bracket me-1" style="color: #FC8181;"></i>
                                    Çıkış Yap
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @yield('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
