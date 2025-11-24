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
    </style>
    @stack('styles')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid px-lg-4">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
                    <img src="{{ asset('koksan-logo.png') }}" alt="Köksan Logo" class="me-2">
                    <strong>Köksan İş Süreçleri Portalı</strong>
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
                            <li class="nav-item"><a class="nav-link" href="{{ route('general.calendar') }}"><i
                                        class="fa-solid fa-calendar-days" style="color: #667EEA;"></i><span>Genel
                                        Takvim</span></a></li>
                            @auth <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i
                                            class="fa-solid fa-calendar-check"
                                        style="color: #4FD1C5;"></i><span>Takvimim</span></a></li> @endauth

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
                                                class="fa-solid fa-share-from-square" style="color: #4258bb;"></i> Atadığım
                                            Görevler</a></li>
                                    <li><a class="dropdown-item" href="{{ route('teams.index') }}"><i
                                                class="fa-solid fa-people-group" style="color: #7a5ed1;"></i> Takım
                                            Yönetimi</a></li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('my-assignments.index') }}">
                                    <i class="fas fa-tasks" style="color: #df6060;"></i><span>Görevlerim</span>
                                    @if (Auth::user()->pending_assignments_count > 0)
                                        <span
                                            class="badge bg-danger rounded-pill">{{ Auth::user()->pending_assignments_count }}</span>
                                    @endif
                                </a>
                            </li>

                            @can('access-department', 'lojistik')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-route"
                                            style="color: #A78BFA;"></i><span>Lojistik</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('shipments.create') }}"><i
                                                    class="fa-solid fa-truck-fast" style="color: #FBD38D;"></i> Yeni
                                                Sevkiyat</a></li>
                                        <li><a class="dropdown-item" href="{{ route('products.list') }}"><i
                                                    class="fa-solid fa-truck-ramp-box" style="color: #4FD1C5;"></i> Sevkiyat
                                                Listesi</a></li>
                                    </ul>
                                </li>
                            @endcan

                            @can('access-department', 'uretim')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-industry"
                                            style="color: #4FD1C5;"></i><span>Üretim</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('production.plans.create') }}"><i
                                                    class="fa-solid fa-plus-circle" style="color: #F093FB;"></i> Yeni Plan</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('production.plans.index') }}"><i
                                                    class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Plan
                                                Listesi</a></li>
                                    </ul>
                                </li>
                            @endcan

                            @can('access-department', 'hizmet')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown"><i class="fa-solid fa-concierge-bell"
                                            style="color: #F093FB;"></i><span>İdari İşler</span></a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('service.events.create') }}"><i
                                                    class="fa-solid fa-calendar-plus" style="color: #667EEA;"></i> Yeni
                                                Etkinlik</a></li>
                                        <li><a class="dropdown-item" href="{{ route('service.events.index') }}"><i
                                                    class="fa-solid fa-calendar-days" style="color: #4FD1C5;"></i> Etkinlik
                                                Listesi</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('service.vehicles.index') }}"><i
                                                    class="fa-solid fa-car" style="color: #FBD38D;"></i> Şirket Araçları </a>
                                        </li>
                                        <li><a class="dropdown-item"
                                                href="{{ route('service.logistics-vehicles.index') }}"><i
                                                    class="fa-solid fa-truck" style="color: #f1b09e;"></i> Nakliye Araçları
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('travels.create') }}"><i
                                                    class="fa-solid fa-route" style="color: #A78BFA;"></i> Yeni Seyahat</a>
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('travels.index') }}"><i
                                                    class="fa-solid fa-list-check" style="color: #A78BFA;"></i> Seyahat
                                                Listesi</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('customers.index') }}"><i
                                                    class="fa-solid fa-users" style="color: #A78BFA;"></i> Müşteri
                                                Yönetimi</a></li>
                                    </ul>
                                </li>
                            @endcan

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
                                    @can('is-global-manager')
                                        <li><a class="dropdown-item" href="{{ route('users.create') }}"><i
                                                    class="fa-solid fa-user-plus" style="color: #667EEA;"></i> Kullanıcı
                                                Ekle</a></li>
                                    @endcan
                                    @if (Auth::user()->role === 'admin')
                                        <li><a class="dropdown-item" href="{{ route('birimler.index') }}"><i
                                                    class="fa-solid fa-tags" style="color: #FBD38D;"></i> Birimleri
                                                Yönet</a></li>
                                        <li><a class="dropdown-item" href="{{ route('departments.index') }}"><i
                                                    class="fa-solid fa-building-user" style="color: #667EEA;"></i>
                                                Departmanlar</a></li>
                                        <li><a class="dropdown-item" href="{{ route('logs.index') }}"><i
                                                    class="fa-solid fa-file-lines" style="color: #f78dfb;"></i> Loglar</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="fa-solid fa-right-from-bracket" style="color: #FC8181;"></i> Çıkış
                                            Yap</a></li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">@csrf</form>
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

    @yield('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Scroll Efekti
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // 2. Mobil Menü
        document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                        toggle: false
                    });
                    bsCollapse.hide();
                }
            });
        });

        // 3. GLOBAL TOAST
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

        // PHP Session Mesajları
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
        @if (session('warning'))
            Toast.fire({
                icon: 'warning',
                title: '{{ session('warning') }}'
            });
        @endif

        // JS İçinden Çağırmak İçin
        window.showToast = function(message, type = 'success') {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // 4. AKILLI SİLME (MESAJ AYIKLAYICI)
        document.addEventListener('DOMContentLoaded', function() {

            // Tarayıcının kendi confirm kutusunu iptal et
            document.querySelectorAll('form').forEach(form => {
                if (form.getAttribute('onsubmit') && form.getAttribute('onsubmit').includes('confirm')) {
                    form.removeAttribute('onsubmit');
                }
            });

            document.addEventListener('submit', function(e) {
                const form = e.target;
                const methodInput = form.querySelector('input[name="_method"]');

                // Sadece DELETE işlemleri
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
                                    // 1. Eğer sunucu direkt 403 dönerse (Handler.php JSON dönerse)
                                    if (response.status === 403) {
                                        showToast(
                                            '⛔ Bu işlemi yapmaya yetkiniz bulunmamaktadır!',
                                            'error');
                                        return;
                                    }

                                    // 2. Eğer sunucu 200 OK dönerse (Redirect back yaptıysa)
                                    if (response.ok) {
                                        const contentType = response.headers.get(
                                            "content-type");

                                        if (contentType && contentType.indexOf(
                                                "application/json") !== -1) {
                                            // JSON geldiyse (Başarılı API cevabı gibi)
                                            await Swal.fire('Silindi!',
                                                'Kayıt başarıyla silindi.', 'success');
                                            window.location.reload();
                                        } else {
                                            // HTML geldiyse (Sayfa redirect oldu demektir)
                                            // Gelen HTML'in içindeki HATA mesajını okuyalım
                                            const htmlText = await response.text();
                                            const parser = new DOMParser();
                                            const doc = parser.parseFromString(htmlText,
                                                'text/html');

                                            // Gelen sayfada kırmızı alert var mı?
                                            const errorAlert = doc.querySelector(
                                                '.alert-danger');

                                            if (errorAlert) {
                                                // HATA VAR!
                                                // Mesajı al, temizle ve Toast olarak bas
                                                let errorMsg = errorAlert.innerText.trim();
                                                // Simge veya kapatma butonu yazılarını temizle (isteğe bağlı)
                                                errorMsg = errorMsg.replace('×', '').trim();

                                                showToast(errorMsg, 'error');

                                                // Sayfayı yenilemiyoruz! Kullanıcı listede kalsın.
                                            } else {
                                                // Hata yok, demek ki başarılı silinmiş
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
                                    showToast('Sunucu hatası.', 'error');
                                })
                                .finally(() => {
                                    if (btn) btn.disabled = false;
                                });
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
