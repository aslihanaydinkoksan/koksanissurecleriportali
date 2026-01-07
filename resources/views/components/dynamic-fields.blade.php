@props(['model', 'entity' => null])

@php
    // Sizin orijinal mantığınız: Model sınıf adından alanları çekiyoruz.
    // Örn: \App\Models\MaintenancePlan::getCustomFields() çalışır.
    $fields = collect();
    if (class_exists($model) && method_exists($model, 'getCustomFields')) {
        $fields = $model::getCustomFields();
    }
@endphp

@if ($fields->count() > 0)
    {{-- BAŞLIK BÖLÜMÜ (Bootstrap Stilinde) --}}
    <div class="mt-4 mb-3 pb-2 border-bottom">
        <h5 class="text-primary fw-bold d-flex align-items-center">
            <svg style="width: 20px; height: 20px;" class="me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                </path>
            </svg>
            Ek Bilgiler
        </h5>
        <p class="text-muted small mb-0">Bu kayıt için tanımlanmış özel alanlar.</p>
    </div>

    {{-- GRID SİSTEMİ (Bootstrap row/col) --}}
    <div class="row g-3">
        @foreach ($fields as $field)
            @php
                // Form name: extras[plaka_no]
                $inputName = "extras[{$field->key}]";
                // Hata yakalama key'i: extras.plaka_no
$errorKey = "extras.{$field->key}";

// Değer önceliği: 1. Old input -> 2. DB'deki veri (Edit) -> 3. Boş
                // Sizin mantığınız korunuyor:
                $value = old($errorKey, $entity ? $entity->extras[$field->key] ?? '' : '');
            @endphp

            <div class="col-md-6">
                <label for="{{ $field->key }}" class="form-label fw-bold text-secondary small text-uppercase">
                    {{ $field->label }}
                    @if ($field->is_required)
                        <span class="text-danger">*</span>
                    @endif
                </label>

                {{-- TİPE GÖRE INPUT SEÇİMİ (Bootstrap Sınıfları ile) --}}
                @switch($field->type)
                    {{-- SELECT --}}
                    @case('select')
                        <select name="{{ $inputName }}" id="{{ $field->key }}"
                            class="form-select @error($errorKey) is-invalid @enderror"
                            {{ $field->is_required ? 'required' : '' }}>
                            <option value="">Seçiniz...</option>
                            @if (!empty($field->options))
                                @foreach ($field->options as $option)
                                    <option value="{{ $option }}" {{ $value == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    @break

                    {{-- TEXTAREA --}}
                    @case('textarea')
                        <textarea name="{{ $inputName }}" id="{{ $field->key }}" rows="3"
                            class="form-control @error($errorKey) is-invalid @enderror" placeholder="{{ $field->label }} giriniz..."
                            {{ $field->is_required ? 'required' : '' }}>{{ $value }}</textarea>
                    @break

                    {{-- BOOLEAN / CHECKBOX --}}
                    @case('boolean')
                    @case('checkbox')
                        <div class="form-check form-switch mt-2">
                            <input type="hidden" name="{{ $inputName }}" value="0">
                            <input type="checkbox" name="{{ $inputName }}" id="{{ $field->key }}" value="1"
                                class="form-check-input @error($errorKey) is-invalid @enderror"
                                {{ $value == '1' ? 'checked' : '' }}>
                            <label class="form-check-label text-dark" for="{{ $field->key }}">
                                {{ $field->label }} (Evet/Hayır)
                            </label>
                        </div>
                    @break

                    {{-- DEFAULT (text, number, date vb.) --}}

                    @default
                        <input type="{{ $field->type }}" name="{{ $inputName }}" id="{{ $field->key }}"
                            value="{{ $value }}" class="form-control @error($errorKey) is-invalid @enderror"
                            placeholder="{{ $field->label }}" {{ $field->is_required ? 'required' : '' }}>
                @endswitch

                {{-- HATA MESAJI --}}
                @error($errorKey)
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        @endforeach
    </div>
@endif
