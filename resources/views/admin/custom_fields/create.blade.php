@extends('layouts.app')

@section('title', 'Yeni Form Alanı Oluştur')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                {{-- BAŞLIK VE GERİ BUTONU --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="h3 fw-bold text-dark mb-1">Yeni Dinamik Alan</h2>
                        <p class="text-muted small mb-0">Modüllere eklenecek yeni veri alanını tanımlayın.</p>
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

                        {{-- Hata Mesajları Genel Gösterim (Opsiyonel) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.custom-fields.store') }}" method="POST">
                            @csrf

                            {{-- 1. MODÜL SEÇİMİ --}}
                            <div class="mb-4">
                                <label for="model_scope"
                                    class="form-label fw-bold text-secondary small text-uppercase">Hangi Modül İçin?</label>
                                <select name="model_scope" id="model_scope"
                                    class="form-select @error('model_scope') is-invalid @enderror">
                                    <option value="" disabled selected>Lütfen bir modül seçin</option>
                                    @foreach ($models as $class => $name)
                                        <option value="{{ $class }}"
                                            {{ old('model_scope') == $class ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('model_scope')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- İŞ BİRİMİ SEÇİMİ --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small text-uppercase">Geçerli İş Birimi
                                    (Fabrika)</label>
                                <select name="business_unit_id"
                                    class="form-select @error('business_unit_id') is-invalid @enderror">
                                    <option value="">Tüm Birimler (Genel)</option>
                                    @foreach ($businessUnits as $unit)
                                        <option value="{{ $unit->id }}"
                                            {{ old('business_unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">
                                    Eğer "Tüm Birimler" seçerseniz, bu alan her fabrikada görünür.
                                </div>
                            </div>

                            {{-- 2. ETİKET (LABEL) --}}
                            <div class="mb-4">
                                <label for="label" class="form-label fw-bold text-secondary small text-uppercase">Alan
                                    Adı (Label)</label>
                                <input type="text" name="label" id="label"
                                    class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}"
                                    placeholder="Örn: Konteyner No, Raf Ömrü vb." required>
                                <div class="form-text text-muted">
                                    Bu isim formlarda görünecek başlıktır. Veritabanı anahtarı (key) bundan otomatik
                                    üretilir.
                                </div>
                                @error('label')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- 3. VERİ TİPİ --}}
                                <div class="col-md-6 mb-4">
                                    <label for="typeSelect"
                                        class="form-label fw-bold text-secondary small text-uppercase">Veri Tipi</label>
                                    <select name="type" id="typeSelect"
                                        class="form-select @error('type') is-invalid @enderror" onchange="toggleOptions()">
                                        <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Kısa Metin
                                            (Text)</option>
                                        <option value="textarea" {{ old('type') == 'textarea' ? 'selected' : '' }}>Uzun
                                            Metin (Textarea)</option>
                                        <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Sayı
                                            (Number)</option>
                                        <option value="date" {{ old('type') == 'date' ? 'selected' : '' }}>Tarih (Date)
                                        </option>
                                        <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Evet/Hayır
                                            (Checkbox)</option>
                                        <option value="select" {{ old('type') == 'select' ? 'selected' : '' }}>Seçim Kutusu
                                            (Select)</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- 4. SIRALAMA --}}
                                <div class="col-md-6 mb-4">
                                    <label for="order"
                                        class="form-label fw-bold text-secondary small text-uppercase">Sıralama</label>
                                    <input type="number" name="order" id="order"
                                        class="form-control @error('order') is-invalid @enderror"
                                        value="{{ old('order', 0) }}">
                                </div>
                            </div>

                            {{-- 5. SEÇENEKLER (Dinamik - Sadece Select seçilirse görünür) --}}
                            {{-- Bootstrap'te gizlemek için 'd-none' sınıfı kullanılır --}}
                            <div class="mb-4 d-none p-3 bg-light rounded border" id="optionsDiv">
                                <label for="options_text" class="form-label fw-bold text-dark">Seçenekler</label>
                                <textarea name="options_text" id="options_text" rows="3"
                                    class="form-control @error('options_text') is-invalid @enderror"
                                    placeholder="Seçenekleri virgül ile ayırarak yazın.&#10;Örn: Kırmızı, Mavi, Yeşil, Sarı">{{ old('options_text') }}</textarea>
                                <div class="form-text text-muted">
                                    Kullanıcının seçebileceği değerleri virgül (,) ile ayırın.
                                </div>
                                @error('options_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 6. ZORUNLU ALAN CHECKBOX --}}
                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_required"
                                        name="is_required" value="1" {{ old('is_required') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-medium text-dark" for="is_required">
                                        Bu alanın doldurulması zorunlu olsun
                                    </label>
                                </div>
                            </div>

                            {{-- AKSİYON BUTONLARI --}}
                            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                                <a href="{{ route('admin.custom-fields.index') }}" class="btn btn-light border">İptal</a>
                                <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center">
                                    <svg style="width: 18px; height: 18px;" class="me-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Kaydet
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script: Select seçilirse seçenekler kutusunu göster --}}
    <script>
        function toggleOptions() {
            const typeSelect = document.getElementById('typeSelect');
            const optionsDiv = document.getElementById('optionsDiv');

            if (typeSelect.value === 'select') {
                optionsDiv.classList.remove('d-none'); // Bootstrap class'ını kaldır
                // Kullanıcı deneyimi: Focus yap
                document.getElementById('options_text').focus();
            } else {
                optionsDiv.classList.add('d-none'); // Bootstrap class'ını ekle
            }
        }

        // Sayfa yüklendiğinde (Validation hatasıyla geri dönerse) eski durumu korumak için çalıştır
        document.addEventListener('DOMContentLoaded', function() {
            toggleOptions();
        });
    </script>
@endsection
