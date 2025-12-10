@extends('layouts.master')

@section('title', 'Yeni Mekan Ekle')

@section('content')

    {{-- 1. ÜST NAVİGASYON (BREADCRUMB) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.index') }}" class="text-decoration-none text-muted">Mekanlar</a>
                </li>
                @if ($parentLocation)
                    <li class="breadcrumb-item">
                        <a href="{{ route('locations.show', $parentLocation->id) }}" class="text-decoration-none text-muted">
                            {{ $parentLocation->name }}
                        </a>
                    </li>
                    <li class="breadcrumb-item active text-primary">Alt Birim Ekle</li>
                @else
                    <li class="breadcrumb-item active text-primary">Yeni Ana Mekan</li>
                @endif
            </ol>
        </nav>
    </div>

    {{-- 2. EKLEME KARTI --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">

                {{-- KART BAŞLIĞI: DİNAMİK MESAJ --}}
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary"
                            style="width: 48px; height: 48px;">
                            <i class="fa fa-plus fa-lg"></i>
                        </div>
                        <div>
                            @if ($parentLocation)
                                <h5 class="fw-bold text-dark mb-0">
                                    {{ $parentLocation->name }} içine ekle
                                </h5>
                                <p class="text-secondary small mb-0">
                                    Şu an
                                    <span class="badge bg-secondary">{{ $parentLocation->type }}</span>
                                    içine yeni bir alt birim tanımlıyorsunuz.
                                </p>
                            @else
                                <h5 class="fw-bold text-dark mb-0">Yeni Site / Kampüs Oluştur</h5>
                                <p class="text-secondary small mb-0">En üst seviye bir yerleşke tanımlıyorsunuz.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $parentLocation?->id }}">

                        {{-- BÖLÜM 1: TÜR SEÇİMİ (GÜNCELLENEN KISIM) --}}
                        <div class="alert alert-light border mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-dark mb-0">Ne Ekliyorsunuz?</label>
                                </div>
                                <div class="col-md-9">
                                    {{-- BURASI ARTIK IF-ELSE DEĞİL, CONTROLLER'DAN GELEN VERİYİ KULLANIYOR --}}
                                    <select name="type" class="form-select form-select-lg bg-white text-primary fw-bold"
                                        id="typeSelect" required>

                                        @foreach ($allowedTypes as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach

                                    </select>

                                    @if (empty($allowedTypes))
                                        <small class="text-danger d-block mt-2">
                                            <i class="fa fa-ban"></i> Bu birimin altına eklenebilecek uygun bir alt tür
                                            bulunamadı.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 2: İSİMLENDİRME --}}
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-medium text-secondary small">Mekan Adı / Numarası</label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="@if ($parentLocation && $parentLocation->type == 'block') Örn: Daire 5 @elseif($parentLocation && $parentLocation->type == 'site') Örn: A Blok @else Örn: Beytepe Lojmanları @endif"
                                    required>
                                <div class="form-text text-xs text-muted">
                                    @if ($parentLocation)
                                        Sistemde görünecek tam ad: <strong>{{ $parentLocation->name }} / [Gireceğiniz
                                            İsim]</strong> olacaktır.
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 3: MÜLKİYET DURUMU --}}
                        <div class="mb-4" id="ownershipSection">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary small">Mülkiyet</label>
                                    <select name="ownership" class="form-select" id="ownershipSelect">
                                        <option value="owned">KÖKSAN (Mülk)</option>
                                        <option value="rented">KİRALIK (Şahıs)</option>
                                    </select>
                                </div>

                                {{-- Dinamik Alan (Kiralıksa görünür) --}}
                                <div class="col-md-8" id="landlordFields" style="display: none;">
                                    <div class="row g-3 bg-light p-2 rounded-3 border">
                                        <div class="col-12">
                                            <small class="text-danger fw-bold"><i class="fa fa-info-circle me-1"></i>
                                                Kiralık Mülk Bilgileri</small>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="landlord_name" class="form-control bg-white"
                                                placeholder="Tapu Sahibi Adı">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="landlord_phone" class="form-control bg-white"
                                                placeholder="İletişim Tel (05...)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 4: ABONELİK BİLGİLERİ --}}
                        <div id="subscriptionFields" class="accordion mb-4" style="display: none;">
                            <div class="accordion-item border-0 shadow-sm mb-2">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-light" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapseSubs">
                                        <i class="fa fa-file-invoice me-2 text-muted"></i> Abonelik Numaraları (Opsiyonel)
                                    </button>
                                </h2>
                                <div id="collapseSubs" class="accordion-collapse collapse" data-bs-parent="#subsAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="small text-muted">Elektrik No</label>
                                                <input type="text" name="subs_electric"
                                                    class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted">Su No</label>
                                                <input type="text" name="subs_water"
                                                    class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted">Doğalgaz No</label>
                                                <input type="text" name="subs_gas"
                                                    class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="small text-muted">İnternet No</label>
                                                <input type="text" name="subs_internet"
                                                    class="form-control form-control-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 5: NOTLAR --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary small">Notlar</label>
                            <textarea name="notes" class="form-control" rows="2"
                                placeholder="Örn: 02.11.2025 tarihinde teslim edildi..."></textarea>
                        </div>

                        {{-- FOOTER: BUTONLAR --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ $parentLocation ? route('locations.show', $parentLocation->id) : route('locations.index') }}"
                                class="btn btn-light px-4">İptal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fa fa-save me-2"></i>Kaydet
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // 1. Kiralık Seçilirse Ev Sahibi Bilgilerini Göster
        function toggleLandlordFields() {
            var selectBox = document.getElementById("ownershipSelect");
            var landlordDiv = document.getElementById("landlordFields");

            if (selectBox && selectBox.value === "rented") {
                landlordDiv.style.display = "block";
            } else {
                if (landlordDiv) {
                    landlordDiv.style.display = "none";
                    // Gizlenince içini temizle
                    var inputs = landlordDiv.querySelectorAll('input');
                    inputs.forEach(input => input.value = '');
                }
            }
        }

        // 2. Türe Göre (Site/Daire) Alanları Gizle/Göster
        function toggleDynamicFields() {
            var typeSelect = document.getElementById("typeSelect");
            var selectedType = typeSelect.value;

            // Gizlenecek ve Gösterilecek Bölümler
            var subsDiv = document.getElementById("subscriptionFields");
            var ownerDiv = document.getElementById("ownershipSection");
            var ownerSelect = document.getElementById("ownershipSelect");

            // --- KRİTİK NOKTA ---
            // Hangi türlerde DETAY (Abonelik, Mülkiyet) GİZLENSİN?
            // Senaryo gereği: Site, Kampüs, Ortak Alan ve BLOK seçilirse abonelik sorulmaz.
            var simpleTypes = ['site', 'campus', 'common_area', 'block'];

            if (simpleTypes.includes(selectedType)) {
                // SADELEŞTİRİLMİŞ MOD
                if (subsDiv) subsDiv.style.display = "none"; // Aboneliği gizle

                // Blok için mülkiyet sorulup sorulmayacağına karar ver. 
                // Genelde Blok mülkiyeti Site ile aynıdır, o yüzden gizliyoruz.
                if (ownerDiv) ownerDiv.style.display = "none";

                // Arka planda "KÖKSAN Mülkü" olarak ayarla
                if (ownerSelect) ownerSelect.value = "owned";

                toggleLandlordFields(); // Kiralık detaylarını da kapat
            } else {
                // DETAYLI MOD (Daire, Bağımsız Bölüm vb.)
                if (subsDiv) subsDiv.style.display = "block";
                if (ownerDiv) ownerDiv.style.display = "block";
            }
        }

        // Sayfa yüklendiğinde ve Tür değiştiğinde çalıştır
        document.addEventListener("DOMContentLoaded", function() {
            var typeSelect = document.getElementById("typeSelect");
            var ownershipSelect = document.getElementById("ownershipSelect");

            if (typeSelect) {
                typeSelect.addEventListener("change", toggleDynamicFields);
            }

            if (ownershipSelect) {
                ownershipSelect.addEventListener("change", toggleLandlordFields);
            }

            // Başlangıç ayarlarını yap
            toggleDynamicFields();
            // toggleLandlordFields, toggleDynamicFields içinde zaten çağrılıyor ama garanti olsun
        });
    </script>
@endpush
