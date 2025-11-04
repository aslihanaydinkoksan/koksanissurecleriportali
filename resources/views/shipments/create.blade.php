@extends('layouts.app')

@section('title', 'Yeni Sevkiyat Kaydı')

<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg,
                #dbe4ff,
                /* #667EEA (Canlı mavi-mor) tonu */
                #fde2ff,
                /* #F093FB (Yumuşak pembe) tonu */
                #d9fcf7,
                /* #4FD1C5 (Teal/turkuaz) tonu */
                #fff0d9
                /* #FBD38D (Sıcak sarı) tonu */
            );
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

    /* === GÜNCELLENDİ (TAM ŞEFFAF KART) === */
    .create-shipment-card {
        border-radius: 1rem;
        /* Gölgeyi ve blur'u kaldırıyoruz */
        box-shadow: none !important;
        border: 0;
        /* Arka planı tamamen şeffaf yapıyoruz */
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-shipment-card .card-header,
    .create-shipment-card .form-label {
        color: #444;
        /* Koyu renk (veya #FFFFFF beyaz) metin */
        font-weight: bold;
        /* Kalın Metin */
        /* Hareketli arka plan üzerinde okunabilirlik için hafif gölge */
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-shipment-card .card-header {
        color: #000;
        /* Başlık siyah kalsın */
    }

    /* Yumuşak köşeli form elemanları */
    .create-shipment-card .form-control,
    .create-shipment-card .form-select {
        border-radius: 0.5rem;
        /* Formların içini daha okunaklı yapmak için hafif opaklık */
        background-color: rgba(255, 255, 255, 0.8);
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

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card create-shipment-card">
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Yeni Sevkiyat Kaydı Oluştur') }}</div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('shipments.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                {{-- Sol Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="arac_tipi" class="form-label">Araç Tipi (*)</label>
                                        <select name="arac_tipi" id="arac_tipi"
                                            class="form-select @error('arac_tipi') is-invalid @enderror" required>
                                            <option value="">Seçiniz...</option>
                                            <option value="tır" @if (old('arac_tipi') == 'tır') selected @endif>Tır
                                            </option>
                                            <option value="gemi" @if (old('arac_tipi') == 'gemi') selected @endif>Gemi
                                            </option>
                                            <option value="kamyon" @if (old('arac_tipi') == 'kamyon') selected @endif>Kamyon
                                            </option>
                                        </select>
                                        @error('arac_tipi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div id="karaAraciAlanlari" style="display: none;">
                                        <div class="mb-3">
                                            <label for="plaka" class="form-label">Plaka (*)</label>
                                            <input type="text" class="form-control @error('plaka') is-invalid @enderror"
                                                id="plaka" name="plaka" value="{{ old('plaka') }}">
                                            @error('plaka')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3" id="dorse_plakasi_div">
                                            <label for="dorse_plakasi" class="form-label">Dorse Plakası (*)</label>
                                            <input type="text"
                                                class="form-control @error('dorse_plakasi') is-invalid @enderror"
                                                id="dorse_plakasi" name="dorse_plakasi" value="{{ old('dorse_plakasi') }}">
                                            @error('dorse_plakasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="sofor_adi" class="form-label">Şoför Adı</label>
                                            <input type="text"
                                                class="form-control @error('sofor_adi') is-invalid @enderror" id="sofor_adi"
                                                name="sofor_adi" value="{{ old('sofor_adi') }}">
                                            @error('sofor_adi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="kalkis_noktasi" class="form-label">Kalkış Noktası
                                                (Gümrük/Tesis)</label>
                                            <input type="text" class="form-control" id="kalkis_noktasi"
                                                name="kalkis_noktasi"
                                                value="{{ old('kalkis_noktasi', $shipment->kalkis_noktasi ?? '') }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="varis_noktasi" class="form-label">Varış Noktası
                                                (Gümrük/Tesis)</label>
                                            <input type="text" class="form-control" id="varis_noktasi"
                                                name="varis_noktasi"
                                                value="{{ old('varis_noktasi', $shipment->varis_noktasi ?? '') }}">
                                        </div>
                                    </div>

                                    <div id="gemiAlanlari" style="display: none;">
                                        <div class="mb-3">
                                            <label for="imo_numarasi" class="form-label">IMO Numarası (*)</label>
                                            <input type="text"
                                                class="form-control @error('imo_numarasi') is-invalid @enderror"
                                                id="imo_numarasi" name="imo_numarasi" value="{{ old('imo_numarasi') }}">
                                            @error('imo_numarasi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="gemi_adi" class="form-label">Gemi Adı (*)</label>
                                            <input type="text"
                                                class="form-control @error('gemi_adi') is-invalid @enderror" id="gemi_adi"
                                                name="gemi_adi" value="{{ old('gemi_adi') }}">
                                            @error('gemi_adi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="kalkis_limani" class="form-label">Kalkış Limanı (*)</label>
                                            <input type="text"
                                                class="form-control @error('kalkis_limani') is-invalid @enderror"
                                                id="kalkis_limani" name="kalkis_limani" value="{{ old('kalkis_limani') }}">
                                            @error('kalkis_limani')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="varis_limani" class="form-label">Varış Limanı (*)</label>
                                            <input type="text"
                                                class="form-control @error('varis_limani') is-invalid @enderror"
                                                id="varis_limani" name="varis_limani" value="{{ old('varis_limani') }}">
                                            @error('varis_limani')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cikis_tarihi" class="form-label">Gümrükten / Limandan Çıkış Tarihi
                                            (*)</label>
                                        <input type="datetime-local"
                                            class="form-control @error('cikis_tarihi') is-invalid @enderror"
                                            id="cikis_tarihi" name="cikis_tarihi" value="{{ old('cikis_tarihi') }}"
                                            required>
                                        @error('cikis_tarihi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="tahmini_varis_tarihi" class="form-label">Tesise / Limana Planlanan
                                            Varış Tarihi (*)</label>
                                        <input type="datetime-local"
                                            class="form-control @error('tahmini_varis_tarihi') is-invalid @enderror"
                                            id="tahmini_varis_tarihi" name="tahmini_varis_tarihi"
                                            value="{{ old('tahmini_varis_tarihi') }}" required>
                                        @error('tahmini_varis_tarihi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kargo_icerigi" class="form-label">Kargo Yükü / İçeriği (*)</label>
                                        <input type="text"
                                            class="form-control @error('kargo_icerigi') is-invalid @enderror"
                                            id="kargo_icerigi" name="kargo_icerigi" value="{{ old('kargo_icerigi') }}"
                                            required>
                                        @error('kargo_icerigi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="kargo_tipi" class="form-label">Kargo Yük Tipi (*)</label>
                                        <select name="kargo_tipi" id="kargo_tipi"
                                            class="form-select @error('kargo_tipi') is-invalid @enderror" required>
                                            <option value="">Birim Seçiniz...</option>
                                            @if (isset($birimler))
                                                @foreach ($birimler as $birim)
                                                    <option value="{{ $birim->ad }}"
                                                        @if (old('kargo_tipi') == $birim->ad) selected @endif>
                                                        {{ $birim->ad }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('kargo_tipi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="kargo_miktari" class="form-label">Yük Miktarı (*)</label>
                                        <input type="text"
                                            class="form-control @error('kargo_miktari') is-invalid @enderror"
                                            id="kargo_miktari" name="kargo_miktari" value="{{ old('kargo_miktari') }}"
                                            required>
                                        @error('kargo_miktari')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Sevkiyat Türü</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shipment_type"
                                                id="type_import" value="import"
                                                {{ old('shipment_type', 'import') == 'import' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_import">
                                                İthalat
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="shipment_type"
                                                id="type_export" value="export"
                                                {{ old('shipment_type') == 'export' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="type_export">
                                                İhracat
                                            </label>
                                        </div>
                                    </div>
                                    {{-- Ek Dosya Alanı --}}
                                    <div class="mb-3">
                                        <label for="ek_dosya" class="form-label">Ek Dosya (Opsiyonel)</label>
                                        <input class="form-control @error('ek_dosya') is-invalid @enderror"
                                            type="file" id="ek_dosya" name="ek_dosya">
                                        @error('ek_dosya')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="aciklamalar" class="form-label">Açıklamalar</label>
                                        <textarea class="form-control @error('aciklamalar') is-invalid @enderror" id="aciklamalar" name="aciklamalar"
                                            rows="6">{{ old('aciklamalar') }}</textarea>
                                        @error('aciklamalar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Kaydı
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
    {{-- JavaScript kodunuzda değişiklik yapılmadı --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aracTipiDropdown = document.getElementById('arac_tipi');
            const karaAraciAlanlari = document.getElementById('karaAraciAlanlari');
            const dorsePlakasiDiv = document.getElementById('dorse_plakasi_div');
            const gemiAlanlari = document.getElementById('gemiAlanlari');
            const plakaInput = document.getElementById('plaka');
            const dorsePlakasiInput = document.getElementById('dorse_plakasi');
            const imoInput = document.getElementById('imo_numarasi');
            const gemiAdiInput = document.getElementById('gemi_adi');
            const kalkisLimaniInput = document.getElementById('kalkis_limani');
            const varisLimaniInput = document.getElementById('varis_limani');
            const kalkisNoktasiInput = document.getElementById('kalkis_noktasi');
            const varisNoktasiInput = document.getElementById('varis_noktasi');

            function updateVehicleFields() {
                const selectedValue = aracTipiDropdown.value;
                karaAraciAlanlari.style.display = 'none';
                dorsePlakasiDiv.style.display = 'none';
                gemiAlanlari.style.display = 'none';
                plakaInput.required = false;
                dorsePlakasiInput.required = false;
                imoInput.required = false;
                gemiAdiInput.required = false;
                kalkisLimaniInput.required = false;
                varisLimaniInput.required = false;
                kalkisNoktasiInput.required = false;
                varisNoktasiInput.required = false;

                if (selectedValue === 'tır') {
                    karaAraciAlanlari.style.display = 'block';
                    dorsePlakasiDiv.style.display = 'block';
                    plakaInput.required = true;
                    dorsePlakasiInput.required = true;
                    kalkisNoktasiInput.required = true;
                    varisNoktasiInput.required = true;
                } else if (selectedValue === 'kamyon') {
                    karaAraciAlanlari.style.display = 'block';
                    plakaInput.required = true;
                    kalkisNoktasiInput.required = true;
                    varisNoktasiInput.required = true;
                } else if (selectedValue === 'gemi') {
                    gemiAlanlari.style.display = 'block';
                    imoInput.required = true;
                    gemiAdiInput.required = true;
                    kalkisLimaniInput.required = true;
                    varisLimaniInput.required = true;
                }
            }
            aracTipiDropdown.addEventListener('change', updateVehicleFields);
            updateVehicleFields();
        });
    </script>
@endsection
