@extends('layouts.app')

@section('title', 'Yeni Sevkiyat Kaydı')

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
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

        .create-shipment-card {
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(15px);
        }

        .section-header {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667EEA;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 0.6rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25 margin-bottom: rgba(102, 126, 234, 0.25);
            border-color: #667EEA;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 10s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-animated-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card create-shipment-card">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold text-dark mb-0"><i class="fas fa-shipping-fast me-2"></i>Yeni Sevkiyat Planla</h3>

                        {{-- Kritik Sevkiyat Switch --}}
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_important" id="is_important"
                                form="mainShipmentForm" value="1" {{ old('is_important') ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold text-danger ms-2" for="is_important">
                                <i class="fas fa-exclamation-triangle me-1"></i> KRİTİK SEVKİYAT
                            </label>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('shipments.store') }}" id="mainShipmentForm"
                            enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            <div class="row">
                                {{-- 1. BÖLÜM: GENEL VE ARAÇ --}}
                                <div class="col-md-6 border-end">
                                    <div class="section-header">1. Operasyon & Araç Bilgileri</div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Sevkiyat Türü (*)</label>
                                        <div class="d-flex gap-4 mt-1">
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_import" value="import"
                                                    {{ old('shipment_type', 'import') == 'import' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="type_import">İthalat</label>
                                            </div>
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_export" value="export"
                                                    {{ old('shipment_type') == 'export' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="type_export">İhracat</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="arac_tipi" class="form-label fw-bold">Araç Tipi (*)</label>
                                        <select name="arac_tipi" id="arac_tipi"
                                            class="form-select @error('arac_tipi') is-invalid @enderror" required>
                                            <option value="">Seçiniz...</option>
                                            <option value="tır" {{ old('arac_tipi') == 'tır' ? 'selected' : '' }}>Tır
                                            </option>
                                            <option value="kamyon" {{ old('arac_tipi') == 'kamyon' ? 'selected' : '' }}>
                                                Kamyon</option>
                                            <option value="gemi" {{ old('arac_tipi') == 'gemi' ? 'selected' : '' }}>Gemi
                                            </option>
                                        </select>
                                        @error('arac_tipi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Kara Aracı Dinamik Alanlar --}}
                                    <div id="karaAraciAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="plaka" class="form-label">Plaka (*)</label>
                                                <input type="text" class="form-control" id="plaka" name="plaka"
                                                    value="{{ old('plaka') }}" placeholder="34 ABC 123">
                                            </div>
                                            <div class="col-md-6" id="dorse_plakasi_div">
                                                <label for="dorse_plakasi" class="form-label">Dorse Plakası (*)</label>
                                                <input type="text" class="form-control" id="dorse_plakasi"
                                                    name="dorse_plakasi" value="{{ old('dorse_plakasi') }}">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sofor_adi" class="form-label">Şoför Adı & Soyadı</label>
                                            <input type="text" class="form-control" id="sofor_adi" name="sofor_adi"
                                                value="{{ old('sofor_adi') }}">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="kalkis_noktasi" class="form-label">Kalkış Noktası</label>
                                                <input type="text" class="form-control" name="kalkis_noktasi"
                                                    value="{{ old('kalkis_noktasi') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="varis_noktasi" class="form-label">Varış Noktası</label>
                                                <input type="text" class="form-control" name="varis_noktasi"
                                                    value="{{ old('varis_noktasi') }}">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Gemi Dinamik Alanlar --}}
                                    <div id="gemiAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="imo_numarasi" class="form-label">IMO Numarası (*)</label>
                                                <input type="text" class="form-control" id="imo_numarasi"
                                                    name="imo_numarasi" value="{{ old('imo_numarasi') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="gemi_adi" class="form-label">Gemi Adı (*)</label>
                                                <input type="text" class="form-control" id="gemi_adi"
                                                    name="gemi_adi" value="{{ old('gemi_adi') }}">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="kalkis_limani" class="form-label">Kalkış Limanı (*)</label>
                                                <input type="text" class="form-control" id="kalkis_limani"
                                                    name="kalkis_limani" value="{{ old('kalkis_limani') }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="varis_limani" class="form-label">Varış Limanı (*)</label>
                                                <input type="text" class="form-control" id="varis_limani"
                                                    name="varis_limani" value="{{ old('varis_limani') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. BÖLÜM: KARGO VE TAKVİM --}}
                                <div class="col-md-6">
                                    <div class="section-header ms-md-3">2. Kargo & Zamanlama</div>
                                    <div class="ms-md-3">
                                        <div class="mb-3">
                                            <label for="kargo_icerigi" class="form-label fw-bold">Kargo İçeriği
                                                (*)</label>
                                            <input type="text" name="kargo_icerigi"
                                                class="form-control border-2 @error('kargo_icerigi') is-invalid @enderror"
                                                value="{{ old('kargo_icerigi') }}" required>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-7">
                                                <label for="kargo_miktari" class="form-label fw-bold">Miktar (*)</label>
                                                <input type="text" name="kargo_miktari"
                                                    class="form-control @error('kargo_miktari') is-invalid @enderror"
                                                    value="{{ old('kargo_miktari') }}" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="kargo_tipi" class="form-label fw-bold">Birim (*)</label>
                                                <select name="kargo_tipi" class="form-select" required>
                                                    @foreach ($birimler as $birim)
                                                        <option value="{{ $birim->ad }}"
                                                            {{ old('kargo_tipi') == $birim->ad ? 'selected' : '' }}>
                                                            {{ $birim->ad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="cikis_tarihi" class="form-label fw-bold">Çıkış Tarihi
                                                    (*)</label>
                                                <input type="datetime-local"
                                                    class="form-control @error('cikis_tarihi') is-invalid @enderror"
                                                    name="cikis_tarihi" value="{{ old('cikis_tarihi') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tahmini_varis_tarihi" class="form-label fw-bold">Planlanan
                                                    Varış (*)</label>
                                                <input type="datetime-local"
                                                    class="form-control @error('tahmini_varis_tarihi') is-invalid @enderror"
                                                    name="tahmini_varis_tarihi" value="{{ old('tahmini_varis_tarihi') }}"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="ek_dosya" class="form-label fw-bold">Ek Dosya
                                                (İrsaliye/Belge)</label>
                                            <input class="form-control" type="file" name="ek_dosya">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- DİNAMİK ALANLAR (Polimorfik Custom Fields) --}}
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header">3. Ekstra Veriler & Açıklamalar</div>
                                    <x-dynamic-fields :model="\App\Models\Shipment::class" />
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="aciklamalar" class="form-label fw-bold">Detaylı Açıklama</label>
                                    <textarea class="form-control" id="aciklamalar" name="aciklamalar" rows="4">{{ old('aciklamalar') }}</textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient btn-lg px-5 py-3 rounded-pill shadow">
                                    <i class="fas fa-save me-2"></i>SEVKİYAT KAYDINI OLUŞTUR
                                </button>
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
            const aracTipiDropdown = document.getElementById('arac_tipi');
            const karaAraciAlanlari = document.getElementById('karaAraciAlanlari');
            const dorsePlakasiDiv = document.getElementById('dorse_plakasi_div');
            const gemiAlanlari = document.getElementById('gemiAlanlari');

            // Inputs
            const inputs = {
                plaka: document.getElementById('plaka'),
                dorse: document.getElementById('dorse_plakasi'),
                imo: document.getElementById('imo_numarasi'),
                gemi: document.getElementById('gemi_adi'),
                kalkisL: document.getElementById('kalkis_limani'),
                varisL: document.getElementById('varis_limani')
            };

            function updateVehicleFields() {
                const val = aracTipiDropdown.value;

                // Reset
                karaAraciAlanlari.style.display = 'none';
                gemiAlanlari.style.display = 'none';
                Object.values(inputs).forEach(i => i.required = false);

                if (val === 'tır' || val === 'kamyon') {
                    karaAraciAlanlari.style.display = 'block';
                    inputs.plaka.required = true;

                    if (val === 'tır') {
                        dorsePlakasiDiv.style.display = 'block';
                        inputs.dorse.required = true;
                    } else {
                        dorsePlakasiDiv.style.display = 'none';
                        inputs.dorse.required = false;
                    }
                } else if (val === 'gemi') {
                    gemiAlanlari.style.display = 'block';
                    inputs.imo.required = true;
                    inputs.gemi.required = true;
                    inputs.kalkisL.required = true;
                    inputs.varisL.required = true;
                }
            }

            aracTipiDropdown.addEventListener('change', updateVehicleFields);
            updateVehicleFields(); // Initial run
        });
    </script>
@endsection
