@extends('layouts.app')

@section('title', 'Birim Yönetimi')

@push('styles')
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    /* #667EEA (Canlı mavi-mor) tonu */
                    #fde2ff,
                    /* #F093FB (Yumuşak pembe) tonu */
                    #d9fcf7,
                    /* #4FD1C5 (Teal/turkuaz) tonu */
                    #fff0d9
                    /* #FBD38D (Sıcak sarı) tonu */
                );
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        /* Arka plan dalgalanma animasyonu */
        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Kartlar (Tam Şeffaf) */
        .birim-card {
            /* Bu sayfa için özel class adı */
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
            margin-bottom: 1.5rem;
            /* Kartlar arasına boşluk */
        }

        /* Başlıklar ve Etiketler (Okunabilirlik İçin) */
        .birim-card .card-header,
        .birim-card .form-label

        /* (Bu sayfada label yok ama tutarlılık için) */
            {
            color: #000;
            /* Başlıklar siyah kalsın */
            font-weight: bold;
            /* Kalın Metin */
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        /* Form Elemanları (Yumuşak Köşe + Opak Arka Plan) */
        .birim-card .form-control {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* Input Group için özel yuvarlatma */
        .birim-card .input-group .form-control {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .birim-card .input-group .btn {
            border-radius: 0.5rem;
            /* Genel */
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        /* Mevcut Birimler Listesi Öğeleri (Okunabilirlik) */
        .birim-card .list-group-item {
            background-color: rgba(255, 255, 255, 0.8);
            /* Hafif opak arka plan */
            border-radius: 0.5rem;
            /* Yumuşak köşeler */
            margin-bottom: 0.5rem;
            /* Öğeler arası boşluk */
            border: none;
            /* Kenarlığı kaldır */
            color: #333;
            /* Metin rengi */
            font-weight: 500;
            /* Biraz daha kalın metin */
        }

        .birim-card .list-group-item:last-child {
            margin-bottom: 0;
            /* Son öğenin alt boşluğunu kaldır */
        }

        .birim-card .list-group-item .btn-danger {
            font-weight: bold;
            /* Sil butonu yazısı kalın olsun */
        }


        /* Animasyonlu Buton */
        .btn-animated-gradient {
            background: linear-gradient(-45deg,
                    #667EEA, #F093FB, #4FD1C5, #FBD38D);
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Yeni Birim Ekleme Formu --}}
                {{-- YENİ CLASS EKLENDİ: 'birim-card' --}}
                <div class="card birim-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Yeni Birim Ekle') }}</div>
                    {{-- Padding eklendi --}}
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('birimler.store') }}">
                            @csrf
                            <div class="input-group">
                                {{-- Yumuşak köşe için stil --}}
                                <input type="text" class="form-control @error('ad') is-invalid @enderror" name="ad"
                                    placeholder="Yeni birim adı (örn: Metreküp)" required value="{{ old('ad') }}">
                                {{-- Animasyonlu buton class'ı eklendi --}}
                                <button class="btn btn-animated-gradient" type="submit">Ekle</button>
                            </div>
                            @error('ad')
                                {{-- Hata mesajı stili güncellendi --}}
                                <div class="text-danger mt-2 fw-bold"><small>{{ $message }}</small></div>
                            @enderror
                        </form>
                    </div>
                </div>

                {{-- Mevcut Birimler Listesi --}}
                {{-- YENİ CLASS EKLENDİ: 'birim-card' --}}
                <div class="card birim-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Mevcut Birimler') }}</div>
                    {{-- Padding eklendi --}}
                    <div class="card-body p-4">
                        {{-- 'list-group-flush' kaldırıldı, normal list group kullanılıyor --}}
                        <ul class="list-group">
                            @forelse ($birimler as $birim)
                                {{-- Stil güncellendi --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center shadow-sm">
                                    {{ $birim->ad }}
                                    <form action="{{ route('birimler.destroy', $birim->id) }}" method="POST"
                                        onsubmit="return confirm('Bu birimi silmek istediğinizden emin misiniz? Silinen birim geri alınamaz.');">
                                        @csrf
                                        @method('DELETE')
                                        {{-- Buton stili güncellendi --}}
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                                    </form>
                                </li>
                            @empty
                                <li class="list-group-item text-muted">Sistemde kayıtlı birim bulunamadı.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
