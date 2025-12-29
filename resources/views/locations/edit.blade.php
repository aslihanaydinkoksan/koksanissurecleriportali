@extends('layouts.master')

@section('title', 'Mekanı Düzenle')

@section('content')

    {{-- 1. ÜST NAVİGASYON (BREADCRUMB) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.index') }}" class="text-decoration-none text-muted">Mekanlar</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.show', $location->id) }}"
                        class="text-decoration-none text-muted">{{ $location->name }}</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">Düzenle</li>
            </ol>
        </nav>
    </div>

    {{-- 2. DÜZENLEME KARTI --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">

                {{-- Kart Başlığı --}}
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary"
                            style="width: 48px; height: 48px;">
                            <i class="fa fa-pen-to-square fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Mekan Bilgilerini Güncelle</h5>
                            <p class="text-secondary small mb-0">{{ $location->name }} kaydını düzenliyorsunuz</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('locations.update', $location->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- BÖLÜM 1: GENEL BİLGİLER --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">Genel Bilgiler</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label class="form-label fw-medium text-secondary small">Mekan Adı / No</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $location->name) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-medium text-secondary small">Türü</label>
                                <select name="type" class="form-select">
                                    <option value="site" {{ $location->type == 'site' ? 'selected' : '' }}>Site / Lojman
                                    </option>
                                    <option value="campus" {{ $location->type == 'campus' ? 'selected' : '' }}>Kampüs
                                    </option>
                                    <option value="block" {{ $location->type == 'block' ? 'selected' : '' }}>Blok</option>
                                    <option value="apartment" {{ $location->type == 'apartment' ? 'selected' : '' }}>Daire
                                    </option>
                                    <option value="room" {{ $location->type == 'room' ? 'selected' : '' }}>Oda</option>
                                    <option value="common_area" {{ $location->type == 'common_area' ? 'selected' : '' }}>
                                        Ortak Alan</option>
                                </select>
                            </div>
                        </div>

                        {{-- YENİ EKLENDİ: ADRES VE KONUM --}}
                        {{-- Create sayfasındaki gibi buraya da ekledik ve mevcut verileri çektik --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary small">Adres ve Konum Bilgileri</label>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <textarea name="address" class="form-control" rows="2" placeholder="Açık adres giriniz">{{ old('address', $location->address) }}</textarea>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted"><i
                                                class="fa fa-map-marker-alt"></i></span>
                                        <input type="url" name="map_link" class="form-control"
                                            value="{{ old('map_link', $location->map_link) }}"
                                            placeholder="Google Haritalar Linki">
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- BÖLÜM 2: MÜLKİYET DURUMU --}}
                        <div class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium text-secondary small">Mülkiyet</label>
                                    <select name="ownership" class="form-select" id="ownershipSelect"
                                        onchange="toggleLandlordFields()">
                                        <option value="owned" {{ $location->ownership == 'owned' ? 'selected' : '' }}>
                                            KÖKSAN (Mülk)</option>
                                        <option value="rented" {{ $location->ownership == 'rented' ? 'selected' : '' }}>
                                            KİRALIK (Şahıs)</option>
                                    </select>
                                </div>

                                {{-- Dinamik Alan (Kiralıksa görünür) --}}
                                <div class="col-md-8" id="landlordFields" style="display: none;">
                                    <div class="row g-3 bg-light p-2 rounded-3 border">
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium text-secondary small">Tapu Sahibi Adı</label>
                                            <input type="text" name="landlord_name" class="form-control bg-white"
                                                value="{{ old('landlord_name', $location->landlord_name) }}"
                                                placeholder="Ad Soyad">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-medium text-secondary small">İletişim
                                                Telefonu</label>
                                            <input type="text" name="landlord_phone" class="form-control bg-white"
                                                value="{{ old('landlord_phone', $location->landlord_phone) }}"
                                                placeholder="0555...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 3: ABONELİK BİLGİLERİ (GÜNCELLENDİ) --}}
                        {{-- Yapıyı değiştirdik: Her satırda Abone No ve Sahip İsmi yan yana --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2 mt-4">Abonelik Bilgileri
                        </h6>

                        <div class="mb-4">
                            {{-- Elektrik --}}
                            <div class="row g-2 mb-3 align-items-end">
                                <div class="col-md-6">
                                    <label class="small text-muted fw-bold"><i class="fa fa-bolt text-warning me-1"></i>
                                        Elektrik No</label>
                                    <input type="text" name="subs_electric" class="form-control"
                                        value="{{ old('subs_electric', $location->subscriptions->where('type', 'electric')->first()?->subscriber_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Kimin Üzerine?</label>
                                    <input type="text" name="subs_electric_holder" class="form-control"
                                        placeholder="Ad Soyad"
                                        value="{{ old('subs_electric_holder', $location->subscriptions->where('type', 'electric')->first()?->holder_name) }}">
                                </div>
                            </div>

                            {{-- Su --}}
                            <div class="row g-2 mb-3 align-items-end">
                                <div class="col-md-6">
                                    <label class="small text-muted fw-bold"><i class="fa fa-tint text-primary me-1"></i>
                                        Su No</label>
                                    <input type="text" name="subs_water" class="form-control"
                                        value="{{ old('subs_water', $location->subscriptions->where('type', 'water')->first()?->subscriber_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Kimin Üzerine?</label>
                                    <input type="text" name="subs_water_holder" class="form-control"
                                        placeholder="Ad Soyad"
                                        value="{{ old('subs_water_holder', $location->subscriptions->where('type', 'water')->first()?->holder_name) }}">
                                </div>
                            </div>

                            {{-- Doğalgaz --}}
                            <div class="row g-2 mb-3 align-items-end">
                                <div class="col-md-6">
                                    <label class="small text-muted fw-bold"><i class="fa fa-fire text-danger me-1"></i>
                                        Doğalgaz No</label>
                                    <input type="text" name="subs_gas" class="form-control"
                                        value="{{ old('subs_gas', $location->subscriptions->where('type', 'gas')->first()?->subscriber_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Kimin Üzerine?</label>
                                    <input type="text" name="subs_gas_holder" class="form-control"
                                        placeholder="Ad Soyad"
                                        value="{{ old('subs_gas_holder', $location->subscriptions->where('type', 'gas')->first()?->holder_name) }}">
                                </div>
                            </div>

                            {{-- İnternet --}}
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label class="small text-muted fw-bold"><i class="fa fa-wifi text-info me-1"></i>
                                        İnternet No</label>
                                    <input type="text" name="subs_internet" class="form-control"
                                        value="{{ old('subs_internet', $location->subscriptions->where('type', 'internet')->first()?->subscriber_no) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="small text-muted">Kimin Üzerine?</label>
                                    <input type="text" name="subs_internet_holder" class="form-control"
                                        placeholder="Ad Soyad"
                                        value="{{ old('subs_internet_holder', $location->subscriptions->where('type', 'internet')->first()?->holder_name) }}">
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 4: NOTLAR --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary small">Notlar</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Mekanla ilgili özel notlar...">{{ old('notes', $location->notes) }}</textarea>
                        </div>

                        {{-- FOOTER: BUTONLAR --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('locations.show', $location->id) }}" class="btn btn-light px-4">İptal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fa fa-save me-2"></i>Değişiklikleri Kaydet
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
        function toggleLandlordFields() {
            var selectBox = document.getElementById("ownershipSelect");
            var landlordDiv = document.getElementById("landlordFields");

            if (selectBox.value === "rented") {
                landlordDiv.style.display = "block";
            } else {
                landlordDiv.style.display = "none";
            }
        }

        // Sayfa yüklendiğinde çalıştır
        document.addEventListener("DOMContentLoaded", function() {
            toggleLandlordFields();
        });
    </script>
@endpush
