@extends('layouts.app')

@section('title', 'Araç Bilgilerini Düzenle')

@push('styles')
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

        /* === GÜNCELLENDİ (edit-vehicle-card) === */
        .edit-vehicle-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .edit-vehicle-card .card-header,
        .edit-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .edit-vehicle-card .card-header {
            color: #000;
        }

        .edit-vehicle-card .form-control,
        .edit-vehicle-card .form-select,
        .edit-vehicle-card .form-check-input {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .edit-vehicle-card .form-check-input {
            border: 1px solid rgba(0, 0, 0, .25);
            cursor: pointer;
            position: relative;
            /* Tıklanabilirlik için */
            z-index: 10;
            /* Katman sorunu için */
        }

        .edit-vehicle-card .form-check-label {
            cursor: pointer;
            position: relative;
            z-index: 10;
            user-select: none;
        }

        /* CHECKBOX SEÇİLİ (CHECKED) DURUMU */
        .edit-vehicle-card .form-check-input:checked {
            background-color: #667EEA !important;
            border-color: #667EEA !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e") !important;
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
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
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- CSS Sınıfı güncellendi --}}
                <div class="card edit-vehicle-card">
                    {{-- Başlık ve Sil Butonu güncellendi --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Araç Bilgilerini Düzenle') }}</span>

                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('service.vehicles.destroy', $vehicle->id) }}"
                                onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz? Araca ait tüm geçmiş atamalar da silinebilir!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Aracı Sil</button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action ve method güncellendi --}}
                        <form method="POST" action="{{ route('service.vehicles.update', $vehicle->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="plate_number" class="form-label">Plaka (*)</label>
                                {{-- Veri doldurma güncellendi --}}
                                <input type="text" class="form-control @error('plate_number') is-invalid @enderror"
                                    id="plate_number" name="plate_number"
                                    value="{{ old('plate_number', $vehicle->plate_number) }}" required
                                    placeholder="Örn: 34 ABC 123">
                                @error('plate_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Araç Tipi (*)</label>
                                {{-- Veri doldurma güncellendi --}}
                                <input type="text" class="form-control @error('type') is-invalid @enderror"
                                    id="type" name="type" value="{{ old('type', $vehicle->type) }}" required
                                    placeholder="Örn: Kamyonet, Otomobil, Minibüs">
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="brand_model" class="form-label">Marka / Model</label>
                                {{-- Veri doldurma güncellendi --}}
                                <input type="text" class="form-control @error('brand_model') is-invalid @enderror"
                                    id="brand_model" name="brand_model"
                                    value="{{ old('brand_model', $vehicle->brand_model) }}"
                                    placeholder="Örn: Ford Transit, Fiat Doblo">
                                @error('brand_model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Açıklama / Notlar</label>
                                {{-- Veri doldurma güncellendi --}}
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description', $vehicle->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Aktif mi Checkbox'ı --}}
                            <div class="mb-3 form-check">
                                {{-- Veri doldurma güncellendi --}}
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $vehicle->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Araç Aktif (Kullanımda)</label>
                            </div>

                            <div class="text-end mt-4">
                                {{-- Butonlar güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
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
