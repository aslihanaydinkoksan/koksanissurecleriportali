@extends('layouts.app')

@section('title', 'Yeni Araç Ekle')

<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg,
                #dbe4ff,
                #fde2ff,
                #d9fcf7,
                #fff0d9);
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

    /* === GÜNCELLENDİ (create-vehicle-card) === */
    .create-vehicle-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-vehicle-card .card-header,
    .create-vehicle-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-vehicle-card .card-header {
        color: #000;
    }

    .create-vehicle-card .form-control,
    .create-vehicle-card .form-select,
    .create-vehicle-card .form-check-input {
        /* Checkbox eklendi */
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .create-vehicle-card .form-check-input {
        border: 1px solid rgba(0, 0, 0, .25);
        /* Checkbox kenarlığı */
    }

    /* Animasyonlu buton (Değişiklik yok) */
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

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            {{-- Sütun genişliği ayarlandı (col-md-8) --}}
            <div class="col-md-8">
                {{-- CSS Sınıfı güncellendi --}}
                <div class="card create-vehicle-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Yeni Araç Ekle') }}</div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action güncellendi --}}
                        <form method="POST" action="{{ route('service.vehicles.store') }}">
                            @csrf
                            {{-- Row kaldırıldı, tek sütunlu form --}}
                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plaka (*)</label>
                                <input type="text" class="form-control @error('plate_number') is-invalid @enderror"
                                    id="plate_number" name="plate_number" value="{{ old('plate_number') }}" required
                                    placeholder="Örn: 34 ABC 123">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Araç Tipi (*)</label>
                                <input type="text" class="form-control @error('type') is-invalid @enderror"
                                    id="type" name="type" value="{{ old('type') }}" required
                                    placeholder="Örn: Kamyonet, Otomobil, Minibüs">
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="brand_model" class="form-label">Marka / Model</label>
                                <input type="text" class="form-control @error('brand_model') is-invalid @enderror"
                                    id="brand_model" name="brand_model" value="{{ old('brand_model') }}"
                                    placeholder="Örn: Ford Transit, Fiat Doblo">
                                @error('brand_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama / Notlar</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Aktif mi Checkbox'ı --}}
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Araç Aktif (Kullanımda)</label>
                            </div>


                            <div class="text-end mt-4">
                                {{-- Buton metni güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Aracı
                                    Kaydet</button>
                                <a href="{{ route('service.vehicles.index') }}"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- @section('page_scripts') ... JavaScript gerekmiyor @endsection --}}
