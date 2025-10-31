@extends('layouts.app')

@section('title', 'Etkinliği Düzenle')

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

    /* === GÜNCELLENDİ (event-edit-card) === */
    .event-edit-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .event-edit-card .card-header,
    .event-edit-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .event-edit-card .card-header {
        color: #000;
    }

    .event-edit-card .form-control,
    .event-edit-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* === Dinamik satır CSS'leri kaldırıldı === */

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
            <div class="col-md-10">
                {{-- CSS Sınıfı güncellendi --}}
                <div class="card event-edit-card">

                    {{-- Başlık ve Sil Butonu güncellendi --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Etkinliği Düzenle') }}</span>

                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('service.events.destroy', $event->id) }}"
                                onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Etkinliği Sil</button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action ve method güncellendi --}}
                        <form method="POST" action="{{ route('service.events.update', $event->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Sol Sütun (Ana Etkinlik Bilgileri) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Etkinlik Başlığı (*)</label>
                                        {{-- Veri doldurma güncellendi --}}
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $event->title) }}"
                                            required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="event_type" class="form-label">Etkinlik Tipi (*)</label>
                                        {{-- Veri doldurma güncellendi --}}
                                        <select name="event_type" id="event_type"
                                            class="form-select @error('event_type') is-invalid @enderror" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach ($eventTypes as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (old('event_type', $event->event_type) == $key) selected @endif>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('event_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Konum / Yer</label>
                                        {{-- Veri doldurma güncellendi --}}
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location', $event->location) }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun (Tarih ve Açıklama) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç Tarihi ve Saati (*)</label>
                                        {{-- Veri doldurma ve formatlama güncellendi (Carbon) --}}
                                        <input type="datetime-local"
                                            class="form-control @error('start_datetime') is-invalid @enderror"
                                            id="start_datetime" name="start_datetime"
                                            value="{{ old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i')) }}"
                                            required>
                                        @error('start_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label">Bitiş Tarihi ve Saati (*)</label>
                                        {{-- Veri doldurma ve formatlama güncellendi (Carbon) --}}
                                        <input type="datetime-local"
                                            class="form-control @error('end_datetime') is-invalid @enderror"
                                            id="end_datetime" name="end_datetime"
                                            value="{{ old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i')) }}"
                                            required>
                                        @error('end_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Açıklama</label>
                                        {{-- Veri doldurma güncellendi --}}
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description', $event->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                {{-- Butonlar güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
                                    Kaydet</button>
                                <a href="{{ route('service.events.index') }}"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- 
@section('page_scripts')
    ...
    // DİNAMİK SATIR EKLEME JAVASCRIPT'İ KALDIRILDI
@endsection 
--}}
