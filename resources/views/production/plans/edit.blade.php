@extends('layouts.app')

@section('title', 'Üretim Planını Düzenle')

@push('styles')
    <style>
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

        .plan-edit-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
        }

        .plan-edit-card .card-header,
        .plan-edit-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .plan-edit-card .form-control,
        .plan-edit-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .plan-detail-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            align-items: center;
        }

        .plan-detail-row .form-control,
        .plan-detail-row .form-select {
            flex: 1;
        }

        /* Önemli Switch Özel Tasarımı */
        .custom-switch-lg .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }

        .custom-switch-lg .form-check-input:checked {
            background-color: #ffc107;
            border-color: #ffb300;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
        }
    </style>
@endpush

@section('content')
    @php
        // Hem validasyon hatası hem de mevcut veriyi kapsayan veri seti
        $details_data = old('plan_details', $plan->plan_details ?? []);
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-11">
                <div class="card plan-edit-card">
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span><i class="fas fa-edit me-2"></i>{{ __('Üretim Planını Düzenle') }}</span>

                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'yönetici')
                            <form method="POST" action="{{ route('production.plans.destroy', $plan->id) }}"
                                onsubmit="return confirm('Bu üretim planını silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="fas fa-trash me-1"></i> Planı Sil
                                </button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('production.plans.update', $plan->id) }}" autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Sol Sütun --}}
                                <div class="col-md-5">
                                    <div class="mb-3">
                                        <label for="plan_title" class="form-label">Plan Başlığı (*)</label>
                                        <input type="text" class="form-control @error('plan_title') is-invalid @enderror"
                                            id="plan_title" name="plan_title"
                                            value="{{ old('plan_title', $plan->plan_title) }}" required>
                                    </div>

                                    <div class="mb-4 p-3 border rounded-3 bg-white bg-opacity-50">
                                        <div class="form-check form-switch custom-switch-lg">
                                            <input class="form-check-input" type="checkbox" role="switch" id="is_important"
                                                name="is_important" value="1"
                                                {{ old('is_important', $plan->is_important) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold text-dark" for="is_important">
                                                <i class="fas fa-star text-warning me-1"></i> Bu Plan Önemli
                                            </label>
                                        </div>
                                        <small class="text-muted d-block mt-1">İşaretlenirse takvimde vurgulanmış olarak
                                            görünür.</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="week_start_date" class="form-label">Hafta Başlangıç Tarihi (*)</label>
                                        <input type="date"
                                            class="form-control @error('week_start_date') is-invalid @enderror"
                                            id="week_start_date" name="week_start_date"
                                            value="{{ old('week_start_date', optional($plan->week_start_date)->format('Y-m-d')) }}"
                                            required>
                                    </div>
                                </div>

                                {{-- Sağ Sütun --}}
                                <div class="col-md-7">
                                    <fieldset class="p-3 border rounded-3 bg-white bg-opacity-25">
                                        <legend class="h6 fw-bold mb-3">Plan Detayları (Makine, Ürün, Miktar, Birim)
                                        </legend>

                                        <div id="plan-details-wrapper">
                                            @foreach ($details_data as $index => $details)
                                                <div class="plan-detail-row">
                                                    <input type="text" name="plan_details[{{ $index }}][machine]"
                                                        class="form-control" placeholder="Makine"
                                                        value="{{ $details['machine'] ?? '' }}" required>

                                                    <input type="text" name="plan_details[{{ $index }}][product]"
                                                        class="form-control" placeholder="Ürün"
                                                        value="{{ $details['product'] ?? '' }}" required>

                                                    <input type="number"
                                                        name="plan_details[{{ $index }}][quantity]"
                                                        class="form-control" placeholder="Miktar"
                                                        value="{{ $details['quantity'] ?? '' }}" required min="1">

                                                    <select name="plan_details[{{ $index }}][birim_id]"
                                                        class="form-select" required>
                                                        <option value="" disabled>Birim</option>
                                                        @foreach ($birimler as $birim)
                                                            <option value="{{ $birim->id }}"
                                                                {{ isset($details['birim_id']) && $details['birim_id'] == $birim->id ? 'selected' : '' }}>
                                                                {{ $birim->ad }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-plan-row">&times;</button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" id="add-plan-row" class="btn btn-success btn-sm mt-2">
                                            <i class="fas fa-plus me-1"></i> Yeni Satır Ekle
                                        </button>
                                    </fieldset>
                                </div>
                            </div>

                            {{-- Dinamik Alanlar --}}
                            <div class="mt-4">
                                <x-dynamic-fields :model="\App\Models\ProductionPlan::class" :entity="$plan" />
                            </div>

                            <div class="text-end mt-4">
                                <a href="{{ route('production.plans.index') }}"
                                    class="btn btn-outline-secondary rounded-3 px-4 me-2">İptal</a>
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-5 py-2">Değişiklikleri
                                    Kaydet</button>
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
        const allBirimler = @json($birimler);

        document.addEventListener('DOMContentLoaded', function() {
            let rowIndex = {{ count($details_data) }};
            const wrapper = document.getElementById('plan-details-wrapper');
            const addButton = document.getElementById('add-plan-row');

            function getBirimOptions() {
                let optionsHtml = '<option value="" selected disabled>Birim</option>';
                allBirimler.forEach(birim => {
                    optionsHtml += `<option value="${birim.id}">${birim.ad}</option>`;
                });
                return optionsHtml;
            }

            addButton.addEventListener('click', function() {
                const birimOptions = getBirimOptions();
                const newRow = `
                    <div class="plan-detail-row">
                        <input type="text" name="plan_details[${rowIndex}][machine]" class="form-control" placeholder="Makine" required>
                        <input type="text" name="plan_details[${rowIndex}][product]" class="form-control" placeholder="Ürün" required>
                        <input type="number" name="plan_details[${rowIndex}][quantity]" class="form-control" placeholder="Miktar" required min="1">
                        <select name="plan_details[${rowIndex}][birim_id]" class="form-select" required>
                            ${birimOptions}
                        </select>
                        <button type="button" class="btn btn-danger btn-sm remove-plan-row">&times;</button>
                    </div>`;
                wrapper.insertAdjacentHTML('beforeend', newRow);
                rowIndex++;
            });

            wrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-plan-row')) {
                    e.target.closest('.plan-detail-row').remove();
                }
            });
        });
    </script>
@endsection
