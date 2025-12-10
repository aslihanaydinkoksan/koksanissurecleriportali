@auth
    <nav class="navbar navbar-expand-lg sticky-top"
        style="background-color: var(--bg-primary); border-bottom: 1px solid var(--border-color); box-shadow: var(--shadow-sm); height: 85px;">

        <div class="container">
            <a class="navbar-brand p-0" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/favicon.png') }}" alt="KÖKSAN Misafirhane" style="height: 75px; width: auto;">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon" style="filter: invert(0.6);"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">

                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">

                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 {{ request()->routeIs('dashboard') ? 'fw-bold text-primary-custom bg-light-custom' : 'text-secondary' }}"
                            href="{{ route('dashboard') }}">
                            <i class="fa fa-home {{ request()->routeIs('dashboard') ? '' : 'text-muted' }} me-1"></i>
                            Anasayfa
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded-2 {{ request()->is('locations*') ? 'fw-bold text-primary-custom bg-light-custom' : 'text-secondary' }}"
                            href="#" data-bs-toggle="dropdown">
                            <i class="fa fa-map-marker-alt {{ request()->is('locations*') ? '' : 'text-muted' }} me-1"></i>
                            Mekanlar
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: var(--radius-lg);">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('locations.index') }}">
                                    <i class="fa fa-list text-secondary me-2"></i> Tüm Mekanlar
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider opacity-50">
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('locations.create') }}">
                                    <i class="fa fa-plus text-success me-2"></i> Yeni Site/Kampüs
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle px-3 rounded-2 {{ request()->is('residents*') || request()->is('contacts*') ? 'fw-bold text-primary-custom bg-light-custom' : 'text-secondary' }}"
                            href="#" data-bs-toggle="dropdown">
                            <i
                                class="fa fa-users {{ request()->is('residents*') || request()->is('contacts*') ? '' : 'text-muted' }} me-1"></i>
                            Personel & Misafir
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: var(--radius-lg);">
                            <li>
                                <a class="dropdown-item py-2 {{ request()->is('contacts*') ? 'active bg-light text-primary fw-bold' : '' }}"
                                    href="{{ route('contacts.index') }}">
                                    <i class="fa fa-address-book text-secondary me-2"></i> Rehber
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('residents.index') }}">
                                    <i class="fa fa-search text-primary me-2"></i> Kişi Ara / Listele
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('residents.create') }}">
                                    <i class="fa fa-user-plus text-success me-2"></i> Yeni Personel Ekle
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3 rounded-2 {{ request()->is('reports*') ? 'fw-bold text-primary-custom bg-light-custom' : 'text-secondary' }}"
                            href="{{ route('reports.index') }}">
                            <i class="fa fa-chart-line {{ request()->is('reports*') ? '' : 'text-muted' }} me-1"></i>
                            Raporlar
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto align-items-center">
                    @guest
                        @if (!request()->routeIs('login'))
                            <li class="nav-item">
                                <a class="btn btn-primary px-4 rounded-pill" href="{{ route('login') }}">
                                    <i class="fa fa-sign-in-alt me-1"></i> Giriş Yap
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                                data-bs-toggle="dropdown">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center border"
                                    style="width: 32px; height: 32px;">
                                    <i class="fa fa-user text-primary"></i>
                                </div>
                                <span class="fw-medium text-dark">{{ Auth::user()->name }}</span>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg"
                                style="border-radius: var(--radius-lg);">
                                <li>
                                    <a class="dropdown-item py-2" href="#">
                                        <i class="fa fa-cog text-muted me-2"></i> Ayarlar
                                    </a>
                                </li>

                                @can('admin')
                                    <li>
                                        <hr class="dropdown-divider opacity-50">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-uppercase x-small text-muted fw-bold">Yönetici Paneli</h6>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('users.index') }}">
                                            <i class="fa fa-users-cog text-primary me-2"></i> Kullanıcılar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('logs.index') }}">
                                            <i class="fa fa-clipboard-list text-warning me-2"></i> Log Kayıtları
                                        </a>
                                    </li>
                                @endcan

                                <li>
                                    <hr class="dropdown-divider opacity-50">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger fw-medium">
                                            <i class="fa fa-sign-out-alt me-2"></i> Çıkış Yap
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
@endauth
