@extends('layouts.app')

@section('title', 'Form Alanını Düzenle')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                {{-- BAŞLIK VE GERİ BUTONU --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1">Alanı Düzenle</h2>
                        <p class="text-muted small mb-0">
                            <span class="fw-bold text-primary">"{{ $field->label }}"</span> alanının özelliklerini
                            güncelliyorsunuz.
                        </p>
                    </div>
                    <a href="{{ route('admin.custom-fields.index') }}"
                        class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center">
                        <svg style="width: 16px; height: 16px;" class="me-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Listeye Dön
                    </a>
                </div>

                {{-- FORM KARTI --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">

                        {{-- Hata Mesajları --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.custom-fields.update', $field->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- 1. MODÜL (Değiştirilemez) --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Modül</label>
                                    <input type="text" class="form-control bg-light"
                                        value="{{ class_basename($field->model_scope) }}" disabled>
                                    <div class="form-text text-muted small">
                                        Veri güvenliği için modül değiştirilemez.
                                    </div>
                                </div>

                                {{-- 2. VERİ TİPİ (Değiştirilemez) --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-bold text-secondary small text-uppercase">Veri Tipi</label>
                                    {{-- Tipi okunaklı hale getirelim --}}
                                    @php
                                        $typeLabel = match ($field->type) {
                                            'text' => 'Kısa Metin',
                                            'textarea' => 'Uzun Metin',
                                            'number' => 'Sayı',
                                            'date' => 'Tarih',
                                            'boolean' => 'Evet/Hayır',
                                            'select' => 'Seçim Kutusu',
                                            default => $field->type,
                                        };
                                    @endphp
                                    <input type="text" class="form-control bg-light" value="{{ $typeLabel }}"
                                        disabled>
                                    <div class="form-text text-muted small">
                                        Veri yapısı bozulmaması için tip değiştirilemez.
                                    </div>
                                </div>
                            </div>

                            {{-- 3. ETİKET (LABEL) --}}
                            <div class="mb-4">
                                <label for="label" class="form-label fw-bold text-secondary small text-uppercase">Alan
                                    Adı (Label)</label>
                                <input type="text" name="label" id="label"
                                    class="form-control @error('label') is-invalid @enderror"
                                    value="{{ old('label', $field->label) }}" required>
                                <div class="form-text text-muted">
                                    Formlarda görünen isimdir.
                                </div>
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 4. SEÇENEKLER (Sadece Select tipindeyse gösterilir) --}}
                            @if ($field->type === 'select')
                                <div class="mb-4 p-3 bg-light rounded border">
                                    <label for="options_text" class="form-label fw-bold text-dark">Seçenekler</label>
                                    <textarea name="options_text" id="options_text" rows="3"
                                        class="form-control @error('options_text') is-invalid @enderror" placeholder="Seçenekleri virgül ile ayırın.">{{ old('options_text', $optionsText ?? '') }}</textarea>
                                    <div class="form-text text-muted">
                                        Mevcut seçenekleri virgül ile ayırarak güncelleyebilirsiniz.
                                    </div>
                                    @error('options_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- 5. AYARLAR (Switch Yapısı) --}}
                            <div class="mb-4 bg-white border rounded p-3">
                                <h6 class="fw-bold text-secondary small text-uppercase mb-3">Ayarlar</h6>

                                {{-- Zorunlu Alan --}}
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required"
                                        name="is_required" value="1"
                                        {{ old('is_required', $field->is_required) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required">
                                        Zorunlu Alan (Kullanıcı bu alanı boş geçemez)
                                    </label>
                                </div>

                                {{-- Aktif/Pasif --}}
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                        name="is_active" value="1"
                                        {{ old('is_active', $field->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Aktif (Formlarda gösterilir)
                                    </label>
                                </div>

                                {{-- Sıralama --}}
                                <div class="row align-items-center g-2 mt-2">
                                    <div class="col-auto">
                                        <label for="order" class="col-form-label">Sıralama:</label>
                                    </div>
                                    <div class="col-auto">
                                        <input type="number" name="order" id="order"
                                            class="form-control form-control-sm" style="width: 80px;"
                                            value="{{ old('order', $field->order) }}">
                                    </div>
                                </div>
                            </div>

                            {{-- BUTONLAR --}}
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('admin.custom-fields.index') }}" class="btn btn-light border">İptal</a>
                                <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center">
                                    <svg style="width: 18px; height: 18px;" class="me-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Güncelle
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
