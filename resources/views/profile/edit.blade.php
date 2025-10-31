@extends('layouts.app')

@section('title', 'Profilimi Düzenle')

{{-- 
    YENİ EKLENEN STİL BÖLÜMÜ (Diğer form sayfalarıyla aynı)
--}}
<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app > main.py-4 {
        padding: 2.5rem 0 !important; 
        min-height: calc(100vh - 72px); 
        background: linear-gradient(-45deg, 
            #dbe4ff, /* #667EEA (Canlı mavi-mor) tonu */
            #fde2ff, /* #F093FB (Yumuşak pembe) tonu */
            #d9fcf7, /* #4FD1C5 (Teal/turkuaz) tonu */
            #fff0d9  /* #FBD38D (Sıcak sarı) tonu */
        );
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* Arka plan dalgalanma animasyonu */
    @keyframes gradientWave {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Ana Kart (Tam Şeffaf) */
    .profile-edit-card { /* Bu sayfa için özel class adı */
        border-radius: 1rem; 
        box-shadow: none !important; 
        border: 0;
        background-color: transparent; 
        backdrop-filter: none;
    }

    /* Form Etiketleri ve Başlık (Okunabilirlik İçin) */
    .profile-edit-card .card-header,
    .profile-edit-card .form-label,
    .profile-edit-card .text-muted { /* Şifre uyarısını da ekledik */
        color: #444; /* Koyu renk metin */
        font-weight: bold; /* Kalın Metin */
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7); 
    }
    
    .profile-edit-card .card-header {
         color: #000; /* Başlık siyah kalsın */
    }
     .profile-edit-card .text-muted {
         font-weight: normal; /* Uyarı metni normal kalınlıkta kalsın */
     }


    /* Form Elemanları (Yumuşak Köşe + Opak Arka Plan) */
    .profile-edit-card .form-control {
        border-radius: 0.5rem; 
        background-color: rgba(255, 255, 255, 0.8);
    }
    
    /* Animasyonlu Buton */
    .btn-animated-gradient {
        background: linear-gradient(-45deg, 
            #667EEA, #F093FB, #4FD1C5, #FBD38D
        );
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white; 
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05); 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- YENİ CLASS EKLENDİ: 'profile-edit-card' --}}
            <div class="card profile-edit-card">
                {{-- Başlık güncellendi --}}
                <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Profil Bilgilerini Düzenle') }}</div>

                {{-- Padding eklendi --}}
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT') 

                        {{-- Ad Soyad --}}
                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Ad Soyad') }}</label>
                            <div class="col-md-6">
                                {{-- Yumuşak köşe class'ı eklendi --}}
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- E-posta --}}
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('E-posta Adresi') }}</label>
                            <div class="col-md-6">
                                {{-- Yumuşak köşe class'ı eklendi --}}
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <hr style="border-top: 1px solid rgba(0,0,0,0.2);"> 
                        <p class="text-center text-muted">Şifrenizi değiştirmek istemiyorsanız bu alanı boş bırakın.</p>

                        {{-- Şifre --}}
                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Yeni Şifre') }}</label>
                            <div class="col-md-6">
                                {{-- Yumuşak köşe class'ı eklendi --}}
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Şifre Tekrar --}}
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Yeni Şifreyi Onayla') }}</label>
                            <div class="col-md-6">
                                {{-- Yumuşak köşe class'ı eklendi --}}
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>

                        {{-- Butonlar güncellendi --}}
                        <div class="row mb-0 mt-4">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                    Değişiklikleri Kaydet
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-3 ms-2">İptal</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Bu sayfada özel JavaScript olmadığı için @section('page_scripts') bloğu boş kalabilir veya silinebilir. --}}