@extends('layouts.app')

@section('title', 'Üretim Planını Düzenle')

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

        /* === GÜNCELLENDİ (plan-edit-card) === */
        .plan-edit-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .plan-edit-card .card-header,
        .plan-edit-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .plan-edit-card .card-header {
            color: #000;
        }

        .plan-edit-card .form-control,
        .plan-edit-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* Plan detayları satırı için stiller (create.blade.php ile aynı) */
        .plan-detail-row {
            display: flex;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
            align-items: center;
        }

        .plan-detail-row .form-control {
            flex: 1;
        }

        .plan-detail-row .btn-danger {
            flex-shrink: 0;
            padding: 0.375rem 0.75rem;
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

    {{-- 
    Validasyon hatası durumunda 'old()' fonksiyonundan gelen verileri, 
    normal durumda ise modelden ($productionPlan) gelen verileri kullanmak için
    bir değişken tanımlıyoruz.
--}}
    @php
        $details_data = old('plan_details', $productionPlan->plan_details ?? []);
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                {{-- CSS Sınıfı güncellendi --}}
                <div class="card plan-edit-card">

                    {{-- Başlık ve Sil Butonu (shipments/edit.blade.php referans alındı) --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Üretim Planını Düzenle') }}</span>

                        {{-- Controller ve list.blade.php'deki 'admin' kuralı baz alındı --}}
                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('production.plans.destroy', $productionPlan->id) }}"
                                onsubmit="return confirm('Bu üretim planını silmek istediğinizden emin misiniz?');"
                                autocomplete="off">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Planı Sil</button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action ve method güncellendi --}}
                        <form method="POST" action="{{ route('production.plans.update', $productionPlan->id) }}"
                            autocomplete="off">
                            @csrf
                            @method('PUT') {{-- GÜNCELLENDİ --}}

                            <div class="row">
                                {{-- Sol Sütun (Ana Bilgiler) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="plan_title" class="form-label">Plan Başlığı (*)</label>
                                        {{-- Veri doldurma güncellendi --}}
                                        <input type="text" class="form-control @error('plan_title') is-invalid @enderror"
                                            id="plan_title" name="plan_title"
                                            value="{{ old('plan_title', $productionPlan->plan_title) }}" required
                                            autocomplete="off">
                                        @error('plan_title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="week_start_date" class="form-label">Hafta Başlangıç Tarihi (*)</label>
                                        {{-- Veri doldurma ve formatlama güncellendi --}}
                                        <input type="date"
                                            class="form-control @error('week_start_date') is-invalid @enderror"
                                            id="week_start_date" name="week_start_date"
                                            value="{{ old('week_start_date', $productionPlan->week_start_date->format('Y-m-d')) }}"
                                            required autocomplete="off">
                                        @error('week_start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun (Dinamik Plan Detayları) --}}
                                <fieldset class="col-md-6">
                                    <legend class="form-label">Plan Detayları (Makine, Ürün, Adet)</legend>

                                    @if ($errors->has('plan_details.*'))
                                        <div class="alert alert-danger p-2 small">
                                            Plan detaylarında hatalar var. Lütfen kırmızı ile işaretli alanları kontrol
                                            edin.
                                        </div>
                                    @endif

                                    <div id="plan-details-wrapper">

                                        {{-- Döngü, hem 'old' hem de '$productionPlan' verisini işlemek için $details_data'yı kullanır --}}
                                        @foreach ($details_data as $index => $details)
                                            <div class="plan-detail-row">
                                                <input type="text" name="plan_details[{{ $index }}][machine]"
                                                    class="form-control @error('plan_details.' . $index . '.machine') is-invalid @enderror"
                                                    placeholder="Makine Adı" value="{{ $details['machine'] ?? '' }}"
                                                    required autocomplete="off">

                                                <input type="text" name="plan_details[{{ $index }}][product]"
                                                    class="form-control @error('plan_details.' . $index . '.product') is-invalid @enderror"
                                                    placeholder="Ürün Kodu/Adı" value="{{ $details['product'] ?? '' }}"
                                                    required autocomplete="off">

                                                <input type="number" name="plan_details[{{ $index }}][quantity]"
                                                    class="form-control @error('plan_details.' . $index . '.quantity') is-invalid @enderror"
                                                    placeholder="Adet" value="{{ $details['quantity'] ?? '' }}" required
                                                    min="1" autocomplete="off">

                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-plan-row">&times;</button>
                                            </div>
                                            {{-- Hata mesajları (Validasyon için) --}}
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
                                    </div>

                                    <button type="button" id="add-plan-row" class="btn btn-success btn-sm mt-2">+ Satır
                                        Ekle</button>
                                </fieldset>
                            </div>


                            <div class="text-end mt-4">
                                {{-- Butonlar güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
                                    Kaydet</button>
                                <a href="{{ route('production.plans.index') }}"
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
    {{-- JavaScript (create.blade.php'den kopyalandı, rowIndex güncellendi) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Satır index'ini, sayfada halihazırda bulunan satır sayısından başlatıyoruz.
            let rowIndex = {{ count($details_data) }};

            const wrapper = document.getElementById('plan-details-wrapper');
            const addButton = document.getElementById('add-plan-row');

            addButton.addEventListener('click', function() {
                const newRow = `
            <div class="plan-detail-row">
                <input type="text" name="plan_details[${rowIndex}][machine]" class="form-control" placeholder="Makine Adı" required autocomplete="off">
                <input type="text" name="plan_details[${rowIndex}][product]" class="form-control" placeholder="Ürün Kodu/Adı" required autocomplete="off">
                <input type="number" name="plan_details[${rowIndex}][quantity]" class="form-control" placeholder="Adet" required min="1" autocomplete="off">
                <button type="button" class="btn btn-danger btn-sm remove-plan-row">&times;</button>
            </div>
        `;
                wrapper.insertAdjacentHTML('beforeend', newRow);
                rowIndex++;
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove-plan-row')) {
                    e.target.closest('.plan-detail-row').remove();
                }
            });

        });
    </script>
@endsection
