@extends('layouts.app')

@section('title', 'Yeni Üretim Planı Oluştur')

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

    /* === GÜNCELLENDİ (create-plan-card) === */
    .create-plan-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-plan-card .card-header,
    .create-plan-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-plan-card .card-header {
        color: #000;
    }

    .create-plan-card .form-control,
    .create-plan-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* YENİ EKLENDİ: Plan detayları satırı için stiller */
    .plan-detail-row {
        display: flex;
        gap: 0.75rem;
        /* 12px */
        margin-bottom: 0.75rem;
        align-items: center;
    }

    .plan-detail-row .form-control {
        flex: 1;
        /* Alanların eşit büyümesini sağlar */
    }

    .plan-detail-row .btn-danger {
        flex-shrink: 0;
        /* Butonun küçülmesini engeller */
        padding: 0.375rem 0.75rem;
        /* Bootstrap btn-sm boyutu */
    }

    /* YENİ EKLENDİ BİTİŞ */

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
                <div class="card create-plan-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Yeni Üretim Planı Oluştur') }}</div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action ve enctype güncellendi --}}
                        <form method="POST" action="{{ route('production.plans.store') }}">
                            @csrf
                            <div class="row">
                                {{-- Sol Sütun (Ana Bilgiler) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="plan_title" class="form-label">Plan Başlığı (*)</label>
                                        <input type="text" class="form-control @error('plan_title') is-invalid @enderror"
                                            id="plan_title" name="plan_title" value="{{ old('plan_title') }}" required>
                                        @error('plan_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="week_start_date" class="form-label">Hafta Başlangıç Tarihi (*)</label>
                                        <input type="date"
                                            class="form-control @error('week_start_date') is-invalid @enderror"
                                            id="week_start_date" name="week_start_date" value="{{ old('week_start_date') }}"
                                            required>
                                        @error('week_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun (Dinamik Plan Detayları) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Plan Detayları (Makine, Ürün, Adet)</label>

                                    {{-- Hata durumunda eski verileri doldurmak için --}}
                                    @if ($errors->has('plan_details.*'))
                                        <div class="alert alert-danger p-2 small">
                                            Plan detaylarında hatalar var. Lütfen kırmızı ile işaretli alanları kontrol
                                            edin.
                                        </div>
                                    @endif

                                    {{-- Dinamik satırların ekleneceği ana kapsayıcı --}}
                                    <div id="plan-details-wrapper">

                                        {{-- Validasyon hatası olursa, eski girilen satırları tekrar yükle --}}
                                        @if (old('plan_details'))
                                            @foreach (old('plan_details') as $index => $details)
                                                <div class="plan-detail-row">
                                                    <input type="text" name="plan_details[{{ $index }}][machine]"
                                                        class="form-control @error('plan_details.' . $index . '.machine') is-invalid @enderror"
                                                        placeholder="Makine Adı" value="{{ $details['machine'] ?? '' }}"
                                                        required>

                                                    <input type="text" name="plan_details[{{ $index }}][product]"
                                                        class="form-control @error('plan_details.' . $index . '.product') is-invalid @enderror"
                                                        placeholder="Ürün Kodu/Adı" value="{{ $details['product'] ?? '' }}"
                                                        required>

                                                    <input type="number"
                                                        name="plan_details[{{ $index }}][quantity]"
                                                        class="form-control @error('plan_details.' . $index . '.quantity') is-invalid @enderror"
                                                        placeholder="Adet" value="{{ $details['quantity'] ?? '' }}"
                                                        required min="1">

                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-plan-row">&times;</button>
                                                </div>
                                                {{-- Hata mesajlarını satırın altında göstermek için (opsiyonel ama şık) --}}
                                                @error('plan_details.' . $index . '.machine')
                                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                                @enderror
                                                @error('plan_details.' . $index . '.product')
                                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                                @enderror
                                                @error('plan_details.' . $index . '.quantity')
                                                    <div class="invalid-feedback d-block mb-2">{{ $message }}</div>
                                                @enderror
                                            @endforeach
                                        @endif
                                    </div>

                                    <button type="button" id="add-plan-row" class="btn btn-success btn-sm mt-2">+ Satır
                                        Ekle</button>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                {{-- Buton metni güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Planı
                                    Oluştur</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    {{-- Sevkiyat formundaki eski JavaScript'in yerine bu eklendi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Satır ekleme ve silme işlemleri için benzersiz bir index tutucu.
            // Eğer form validasyondan dönerse, mevcut satır sayısından başlar.
            let rowIndex = {{ old('plan_details') ? count(old('plan_details')) : 0 }};

            const wrapper = document.getElementById('plan-details-wrapper');
            const addButton = document.getElementById('add-plan-row');

            // "Satır Ekle" butonuna tıklandığında
            addButton.addEventListener('click', function() {
                // Yeni satırın HTML şablonu
                const newRow = `
            <div class="plan-detail-row">
                <input type="text" name="plan_details[${rowIndex}][machine]" class="form-control" placeholder="Makine Adı" required>
                <input type="text" name="plan_details[${rowIndex}][product]" class="form-control" placeholder="Ürün Kodu/Adı" required>
                <input type="number" name="plan_details[${rowIndex}][quantity]" class="form-control" placeholder="Adet" required min="1">
                <button type="button" class="btn btn-danger btn-sm remove-plan-row">&times;</button>
            </div>
        `;

                // Yeni satırı kapsayıcıya ekle
                wrapper.insertAdjacentHTML('beforeend', newRow);

                // Bir sonraki satır için index'i artır
                rowIndex++;
            });

            // "Sil" (X) butonuna tıklandığında (Event Delegation)
            // Kapsayıcıya bir tıklama dinleyicisi ekliyoruz, bu sayede sonradan eklenen
            // butonlar da çalışır.
            wrapper.addEventListener('click', function(e) {
                // Tıklanan eleman 'remove-plan-row' class'ına sahip bir buton mu?
                if (e.target && e.target.classList.contains('remove-plan-row')) {
                    // Butonun en yakın 'plan-detail-row' class'lı ebeveynini bul ve kaldır
                    e.target.closest('.plan-detail-row').remove();
                }
            });

        });
    </script>
@endsection
