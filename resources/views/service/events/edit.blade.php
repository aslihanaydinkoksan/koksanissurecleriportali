@extends('layouts.app')

@section('title', 'Etkinliği Düzenle')

@push('styles')
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

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

        /* Kart Stili */
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

        /* Buton Stili */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
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

        /* CRM Bölümü Stili */
        .crm-update-section {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card event-edit-card">

                    {{-- Başlık ve Sil Butonu --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Etkinliği Düzenle') }}</span>

                        {{-- Durum Rozeti --}}
                        @php
                            $statusMap = [
                                'planlandi' => ['class' => 'bg-info text-dark', 'icon' => '⏳', 'label' => 'Planlandı'],
                                'gerceklesti' => ['class' => 'bg-success', 'icon' => '✅', 'label' => 'Gerçekleşti'],
                                'ertelendi' => [
                                    'class' => 'bg-warning text-dark',
                                    'icon' => '📅',
                                    'label' => 'Ertelendi',
                                ],
                                'iptal' => ['class' => 'bg-danger', 'icon' => '❌', 'label' => 'İptal'],
                            ];
                            $status = $statusMap[$event->visit_status] ?? [
                                'class' => 'bg-secondary',
                                'icon' => '?',
                                'label' => 'Bilinmiyor',
                            ];
                        @endphp
                        <span class="badge fs-6 rounded-pill {{ $status['class'] }} ms-2">
                            {{ $status['icon'] }} {{ $status['label'] }}
                        </span>

                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('service.events.destroy', $event->id) }}"
                                onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');"
                                class="ms-auto">
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
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="{{ route('service.events.update', $event->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Sol Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Etkinlik Başlığı (*)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title', $event->title) }}"
                                            required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="event_type" class="form-label">Etkinlik Tipi (*)</label>
                                        {{-- Müşteri ziyareti ise tipi değiştirmeyi engelle (mantık karışmasın diye) --}}
                                        <select name="event_type" id="event_type"
                                            class="form-select @error('event_type') is-invalid @enderror" required
                                            {{ $event->event_type == 'musteri_ziyareti' ? 'disabled' : '' }}>
                                            <option value="">Seçiniz...</option>
                                            @foreach ($eventTypes as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (old('event_type', $event->event_type) == $key) selected @endif>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($event->event_type == 'musteri_ziyareti')
                                            <input type="hidden" name="event_type" value="{{ $event->event_type }}">
                                        @endif
                                        @error('event_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Konum / Yer</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location', $event->location) }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç (*)</label>
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
                                        <label for="end_datetime" class="form-label">Bitiş (*)</label>
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
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description', $event->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ===== DURUM VE SONUÇ GÜNCELLEME BÖLÜMÜ (HERKES İÇİN GÖRÜNÜR) ===== --}}
                            <div class="crm-update-section mt-4 p-3 rounded-3">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                    {{ $event->event_type == 'musteri_ziyareti' ? 'Ziyaret Durumu ve Sonuçları' : 'Etkinlik Durumu' }}
                                </h5>

                                <div class="row">
                                    {{-- Müşteri Bilgisi (SADECE Müşteri Ziyaretinde Görünsün) --}}
                                    @if ($event->event_type == 'musteri_ziyareti')
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small">İlgili Müşteri</label>
                                            <input type="text" class="form-control bg-white"
                                                value="{{ $event->customer?->name ?? 'Müşteri Bilgisi Yok' }}" disabled>
                                            <input type="hidden" name="customer_id" value="{{ $event->customer_id }}">
                                        </div>
                                    @endif

                                    {{-- Durum Dropdown'ı (HERKES İÇİN GÖRÜNÜR) --}}
                                    <div
                                        class="{{ $event->event_type == 'musteri_ziyareti' ? 'col-md-6' : 'col-12' }} mb-3">
                                        <label for="visit_status" class="form-label fw-bold">Durum (*)</label>
                                        <select name="visit_status" id="visit_status"
                                            class="form-select @error('visit_status') is-invalid @enderror">
                                            <option value="planlandi"
                                                {{ old('visit_status', $event->visit_status) == 'planlandi' ? 'selected' : '' }}>
                                                ⏳ Planlandı</option>
                                            <option value="gerceklesti"
                                                {{ old('visit_status', $event->visit_status) == 'gerceklesti' ? 'selected' : '' }}>
                                                ✅ Gerçekleşti / Tamamlandı</option>
                                            <option value="ertelendi"
                                                {{ old('visit_status', $event->visit_status) == 'ertelendi' ? 'selected' : '' }}>
                                                📅 Ertelendi</option>
                                            <option value="iptal"
                                                {{ old('visit_status', $event->visit_status) == 'iptal' ? 'selected' : '' }}>
                                                ❌ İptal Edildi</option>
                                        </select>
                                        @error('visit_status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Dinamik Açıklama Alanı (JS ile yönetilir) --}}
                                <div class="row" id="reason-row" style="display: none;">
                                    <div class="col-12">
                                        <label for="cancellation_reason" class="form-label" id="reason-label">Açıklama /
                                            Not</label>
                                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3"
                                            placeholder="Durum ile ilgili notunuzu giriniz...">{{ old('cancellation_reason', $event->cancellation_reason) }}</textarea>
                                        @error('cancellation_reason')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- ===== BÖLÜM SONU ===== --}}

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri Kaydet</button>
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

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elemanları seç
            const visitStatusDropdown = document.getElementById('visit_status');
            const reasonRow = document.getElementById('reason-row');
            const reasonLabel = document.getElementById('reason-label');
            const reasonInput = document.getElementById('cancellation_reason');

            // Elemanlar yoksa çalışmayı durdur (Hata önleme)
            if (!visitStatusDropdown || !reasonRow) return;

            function toggleReasonField() {
                const status = visitStatusDropdown.value;

                // 1. Görünürlük Mantığı
                if (status === 'planlandi') {
                    reasonRow.style.display = 'none';
                    reasonInput.required = false;
                } else {
                    reasonRow.style.display = 'block';

                    // 2. Metin ve Zorunluluk Mantığı
                    if (status === 'iptal') {
                        reasonLabel.textContent = 'İptal Sebebi (Zorunlu)';
                        reasonInput.placeholder = 'Neden iptal edildiğini kısaca belirtiniz...';
                        reasonInput.required = true;

                    } else if (status === 'ertelendi') {
                        reasonLabel.textContent = 'Erteleme Sebebi (Zorunlu)';
                        reasonInput.placeholder = 'Neden ertelendiğini ve yeni planı belirtiniz...';
                        reasonInput.required = true;

                    } else if (status === 'gerceklesti') {
                        reasonLabel.textContent = 'Sonuç Notları / Rapor (Opsiyonel)';
                        reasonInput.placeholder = 'Toplantı notları, alınan kararlar, ziyaret sonucu vb...';
                        reasonInput.required = false; // Gerçekleştiyse not girmek opsiyonel
                    }
                }
            }

            // Sayfa ilk yüklendiğinde çalıştır
            toggleReasonField();

            // Değişiklik olduğunda çalıştır
            visitStatusDropdown.addEventListener('change', toggleReasonField);
        });
    </script>
@endsection
