@extends('layouts.app')

@section('title', 'Yeni Etkinlik Oluştur')

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

    /* === GÜNCELLENDİ (create-event-card) === */
    .create-event-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-event-card .card-header,
    .create-event-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-event-card .card-header {
        color: #000;
    }

    .create-event-card .form-control,
    .create-event-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* === Dinamik satır CSS'leri (plan-detail-row) kaldırıldı === */

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
                <div class="card create-event-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header h4 bg-transparent border-0 pt-4">{{ __('Yeni Etkinlik Oluştur') }}</div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif

                        {{-- Form action güncellendi --}}
                        <form method="POST" action="{{ route('service.events.store') }}">
                            @csrf
                            <div class="row">
                                {{-- Sol Sütun (Ana Etkinlik Bilgileri) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Etkinlik Başlığı (*)</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            id="title" name="title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="event_type" class="form-label">Etkinlik Tipi (*)</label>
                                        {{-- Controller'dan gelen $eventTypes değişkenini kullanıyoruz --}}
                                        <select name="event_type" id="event_type"
                                            class="form-select @error('event_type') is-invalid @enderror" required>
                                            <option value="">Seçiniz...</option>
                                            @foreach ($eventTypes as $key => $value)
                                                <option value="{{ $key }}"
                                                    @if (old('event_type') == $key) selected @endif>{{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div id="crm-details-wrapper" style="display: none;">
                                            <hr class="my-4">

                                            <div class="row">
                                                {{-- SEYAHAT SEÇİMİ (OPSİYONEL) --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="travel_id" class="form-label">Bağlı Olduğu Seyahat
                                                            Programı
                                                            (Opsiyonel)</label>
                                                        <select name="travel_id" id="travel_id" class="form-select">
                                                            <option value="">Bağımsız Ziyaret</option>
                                                            @foreach ($availableTravels as $travel)
                                                                <option value="{{ $travel->id }}"
                                                                    @if (old('travel_id') == $travel->id) selected @endif>
                                                                    {{ $travel->name }}
                                                                    ({{ \Carbon\Carbon::parse($travel->start_date)->format('d/m/Y') }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                {{-- MÜŞTERİ SEÇİMİ (ZORUNLU) --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="customer_id" class="form-label">Hangi Müşteri Ziyaret
                                                            Edildi?
                                                            (*)</label>
                                                        <select name="customer_id" id="customer_id"
                                                            class="form-select @error('customer_id') is-invalid @enderror">
                                                            <option value="">Müşteri Seçiniz...</option>
                                                            @foreach ($customers as $customer)
                                                                <option value="{{ $customer->id }}"
                                                                    @if (old('customer_id') == $customer->id) selected @endif>
                                                                    {{ $customer->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('customer_id')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                {{-- ZİYARET AMACI (ZORUNLU) --}}
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="visit_purpose" class="form-label">Ziyaret Amacı
                                                            (*)</label>
                                                        <select name="visit_purpose" id="visit_purpose"
                                                            class="form-select @error('visit_purpose') is-invalid @enderror">
                                                            <option value="">Seçiniz...</option>
                                                            <option value="satis_sonrasi_hizmet"
                                                                @if (old('visit_purpose') == 'satis_sonrasi_hizmet') selected @endif>Satış
                                                                Sonrası Hizmet
                                                            </option>
                                                            <option value="egitim"
                                                                @if (old('visit_purpose') == 'egitim') selected @endif>
                                                                Eğitim</option>
                                                            <option value="rutin_ziyaret"
                                                                @if (old('visit_purpose') == 'rutin_ziyaret') selected @endif>Rutin
                                                                Ziyaret
                                                            </option>
                                                            <option value="pazarlama"
                                                                @if (old('visit_purpose') == 'pazarlama') selected @endif>Pazarlama
                                                                Amaçlı
                                                                Ziyaret</option>
                                                            <option value="diger"
                                                                @if (old('visit_purpose') == 'diger') selected @endif>
                                                                Diğer</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- 'Satış Sonrası Hizmet' seçilince görünecek alan --}}
                                            <div id="after-sales-details" style="display: none;">
                                                <div class="row">
                                                    {{-- İLGİLİ MAKİNE (AJAX ile dolacak) --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="customer_machine_id" class="form-label">İlgili
                                                                Makine (Opsiyonel)</label>
                                                            <select name="customer_machine_id" id="customer_machine_id"
                                                                class="form-select" disabled> {{-- Başlangıçta devre dışı --}}
                                                                <option value="">Önce bir müşteri seçiniz...</option>
                                                            </select>
                                                            @error('customer_machine_id')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    {{-- SATIŞ SONRASI NOTLARI (hep görünür, 'has_machine' kalktı) --}}
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="after_sales_notes" class="form-label">Satış Sonrası
                                                                Notları</label>
                                                            <textarea class="form-control" id="after_sales_notes" name="after_sales_notes" rows="3">{{ old('after_sales_notes') }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @error('event_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Konum / Yer</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                                            id="location" name="location" value="{{ old('location') }}">
                                        @error('location')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Sağ Sütun (Tarih ve Açıklama) --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç Tarihi ve Saati
                                            (*)</label>
                                        <input type="datetime-local"
                                            class="form-control @error('start_datetime') is-invalid @enderror"
                                            id="start_datetime" name="start_datetime"
                                            value="{{ old('start_datetime') }}" required>
                                        @error('start_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label">Bitiş Tarihi ve Saati (*)</label>
                                        <input type="datetime-local"
                                            class="form-control @error('end_datetime') is-invalid @enderror"
                                            id="end_datetime" name="end_datetime" value="{{ old('end_datetime') }}"
                                            required>
                                        @error('end_datetime')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Açıklama</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="text-end mt-4">
                                {{-- Buton metni güncellendi --}}
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Etkinliği
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ana elemanlar
            const eventTypeDropdown = document.getElementById('event_type');
            const crmWrapper = document.getElementById('crm-details-wrapper');

            // CRM Elemanları
            const customerIdDropdown = document.getElementById('customer_id');
            const visitPurposeDropdown = document.getElementById('visit_purpose');

            // Koşullu CRM Elemanları
            const afterSalesSection = document.getElementById('after-sales-details');
            const machineDropdown = document.getElementById('customer_machine_id'); // Radio yerine bu geldi

            // Seçilen müşteriye ait makineleri AJAX ile çeken fonksiyon
            async function fetchCustomerMachines() {
                const customerId = customerIdDropdown.value;

                // Müşteri seçilmemişse makine listesini sıfırla
                if (!customerId) {
                    machineDropdown.innerHTML = '<option value="">Önce bir müşteri seçiniz...</option>';
                    machineDropdown.disabled = true;
                    return;
                }

                // Müşteri seçilmişse API'ye istek at
                try {
                    // API rotamızı 'customer' ID'si ile çağırıyoruz
                    const response = await fetch(`/api/customers/${customerId}/machines`);
                    if (!response.ok) throw new Error('Network response was not ok');

                    const machines = await response.json();

                    // Makine dropdown'ını temizle ve doldur
                    machineDropdown.innerHTML = '<option value="">Makine seç (Opsiyonel)...</option>';

                    if (machines.length > 0) {
                        machines.forEach(machine => {
                            const option = document.createElement('option');
                            option.value = machine.id;
                            option.textContent =
                                `${machine.model} (Seri No: ${machine.serial_number || 'N/A'})`;
                            machineDropdown.appendChild(option);
                        });
                        machineDropdown.disabled = false; // Dropdown'ı aktif et
                    } else {
                        machineDropdown.innerHTML =
                            '<option value="">Bu müşteriye ait kayıtlı makine bulunamadı.</option>';
                        machineDropdown.disabled = true;
                    }

                } catch (error) {
                    console.error('Makineler çekilirken hata oluştu:', error);
                    machineDropdown.innerHTML = '<option value="">Makineler yüklenemedi.</option>';
                    machineDropdown.disabled = true;
                }
            }

            // Etkinlik Tipi 'Müşteri Ziyareti' ise ana CRM bloğunu yönetir
            function toggleCrmWrapper() {
                const selectedEventType = eventTypeDropdown.value;

                if (selectedEventType === 'musteri_ziyareti') {
                    crmWrapper.style.display = 'block';
                    customerIdDropdown.required = true;
                    visitPurposeDropdown.required = true;

                    // İç mantığı tetikle
                    togglePurposeDetails();
                    // Müşteri seçiliyse makineleri de yükle (sayfa validation'dan dönerse)
                    fetchCustomerMachines();
                } else {
                    crmWrapper.style.display = 'none';
                    customerIdDropdown.required = false;
                    visitPurposeDropdown.required = false;

                    // İç alanları gizle
                    afterSalesSection.style.display = 'none';
                }
            }

            // 'Ziyaret Amacı'na göre 'Satış Sonrası' bloğunu yönetir
            function togglePurposeDetails() {
                const selectedVisitPurpose = visitPurposeDropdown.value;

                if (selectedVisitPurpose === 'satis_sonrasi_hizmet') {
                    afterSalesSection.style.display = 'block';
                    // Artık radio zorunluluğu yok, makine seçimi opsiyonel
                } else {
                    afterSalesSection.style.display = 'none';
                }
            }

            // Olay dinleyicilerini ekle
            eventTypeDropdown.addEventListener('change', toggleCrmWrapper);
            visitPurposeDropdown.addEventListener('change', togglePurposeDetails);

            // YENİ DİNLEYİCİ: Müşteri dropdown'ı değiştiğinde makineleri çek
            customerIdDropdown.addEventListener('change', fetchCustomerMachines);

            // Sayfa yüklendiğinde (validation hatasıyla geri dönerse diye)
            toggleCrmWrapper();
        });
    </script>
@endsection
