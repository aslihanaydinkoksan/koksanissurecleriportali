@extends('layouts.master')

@section('title', 'Giriş Yap - KÖKSAN')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-4">

            {{-- GİRİŞ KARTI --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Dekoratif Üst Çizgi --}}
                <div class="h-1 bg-primary"></div>

                <div class="card-body p-5">

                    {{-- Logo ve Başlık Alanı --}}
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3"
                            style="width: 70px; height: 70px;">
                            <i class="fa fa-building fa-2x"></i>
                        </div>
                        <h3 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">KÖKSAN</h3>
                        <p class="text-secondary small text-uppercase fw-bold" style="letter-spacing: 1px;">Misafirhane
                            Yönetimi</p>
                    </div>

                    {{-- Hata Mesajları --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 d-flex align-items-center shadow-sm mb-4 rounded-3">
                            <i class="fa fa-exclamation-circle me-2 fa-lg"></i>
                            <ul class="mb-0 small list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-secondary">E-Posta Adresi</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted ps-3">
                                    <i class="fa fa-envelope"></i>
                                </span>
                                <input type="email" name="email" class="form-control bg-light border-0 py-2"
                                    id="email" placeholder="ornek@koksan.com" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-secondary">Şifre</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted ps-3">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" name="password" class="form-control bg-light border-0 py-2"
                                    id="password" placeholder="••••••••" required>

                                {{-- ŞİFRE GÖSTER/GİZLE BUTONU --}}
                                <button class="btn bg-light border-0 text-muted pe-3" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid pt-2">
                            <button type="submit"
                                class="btn btn-primary rounded-pill py-2 shadow-sm fw-bold d-flex justify-content-center align-items-center">
                                Giriş Yap <i class="fa fa-arrow-right ms-2"></i>
                            </button>
                        </div>

                    </form>
                </div>

                {{-- Kart Altı Bilgi --}}
                <div class="card-footer bg-light border-0 text-center py-3">
                    <small class="text-muted" style="font-size: 0.75rem;">
                        &copy; {{ date('Y') }} İdari İşler Departmanı
                    </small>
                </div>
            </div>

            {{-- Ekstra Linkler (Opsiyonel) --}}
            {{-- <div class="text-center mt-4">
                <a href="#" class="text-decoration-none text-muted small hover-underline">
                    <i class="fa fa-question-circle me-1"></i> Şifremi Unuttum?
                </a>
            </div> --}}

        </div>
    </div>

    <style>
        /* Sadece bu sayfaya özel ufak bir hover efekti */
        .hover-underline:hover {
            text-decoration: underline !important;
            color: var(--primary-color) !important;
        }
    </style>
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function(e) {
            // Şifre alanının tipini değiştir (password <-> text)
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // İkonu değiştir (göz <-> üstü çizili göz)
            const icon = this.querySelector('i');

            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    </script>
@endsection
