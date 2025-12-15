@extends('layouts.master')

{{-- Türü kontrol etmek için basit bir PHP bloğu --}}
@php
    // Abonelik ve Doluluk gerektiren "Son Nokta" birimler
    $isUnit = in_array($location->type, ['apartment', 'room', 'independent_section']);

    // Altına birim eklenebilen "Kapsayıcı" türler (Site, Blok, Kampüs)
    $isContainer = in_array($location->type, ['site', 'campus', 'block', 'common_area']);
@endphp

@section('title', $location->name . ' - Detaylar')

@section('content')

    {{-- 1. ÜST NAVİGASYON (BREADCRUMB) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.index') }}" class="text-decoration-none text-muted">Mekanlar</a>
                </li>
                @foreach ($breadcrumbs as $crumb)
                    <li class="breadcrumb-item">
                        <a href="{{ route('locations.show', $crumb->id) }}"
                            class="text-decoration-none text-muted">{{ $crumb->name }}</a>
                    </li>
                @endforeach
                <li class="breadcrumb-item active text-primary" aria-current="page">{{ $location->name }}</li>
            </ol>
        </nav>

        {{-- Üst Aksiyon Butonları --}}
        <div class="d-flex gap-2">
            <a href="{{ route('locations.print', $location->id) }}" target="_blank"
                class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-print me-1"></i> Yazdır
            </a>
            <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-edit me-1"></i> Düzenle
            </a>
            <x-delete-button action="{{ route('locations.destroy', $location->id) }}"
                message="Bu mekanı ve altındaki tüm birimleri silmek istediğinize emin misiniz?" />
        </div>
    </div>

    <div class="row g-4">
        {{-- SOL KOLON: KİMLİK & TEKNİK --}}
        <div class="col-lg-5">
            {{-- 1. KİMLİK KARTI --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @php
                                $badgeColor = match ($location->type) {
                                    'site', 'campus' => 'primary', // Mavi
                                    'block' => 'info', // Açık Mavi
                                    'apartment' => 'success', // Yeşil
                                    default => 'secondary',
                                };
                                $typeLabel = match ($location->type) {
                                    'site' => 'SİTE / LOJMAN',
                                    'campus' => 'KAMPÜS',
                                    'block' => 'BLOK',
                                    'apartment' => 'DAİRE',
                                    'common_area' => 'ORTAK ALAN',
                                    default => strtoupper($location->type),
                                };
                            @endphp
                            <span
                                class="badge bg-{{ $badgeColor }}-subtle text-{{ $badgeColor }} border border-{{ $badgeColor }}-subtle rounded-pill mb-2 px-3">
                                {{ $typeLabel }}
                            </span>
                            <h3 class="fw-bold text-dark mb-1">{{ $location->name }}</h3>
                            <div class="text-muted small">
                                <i class="fa fa-map-marker-alt me-1"></i>
                                {{ collect($breadcrumbs)->last()->name ?? 'Ana Konum' }}
                            </div>
                        </div>
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary"
                            style="width: 50px; height: 50px;">
                            <i class="fa {{ $isUnit ? 'fa-home' : 'fa-building' }} fa-xl"></i>
                        </div>
                    </div>

                    <hr class="opacity-10 my-3">

                    {{-- Mülkiyet Bilgisi (SADECE DAİRE TİPLERİNDE GÖRÜNÜR) --}}
                    @if ($isUnit)
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="text-secondary small fw-medium">Mülkiyet Durumu</span>
                                @if ($location->ownership == 'owned')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="fa fa-check-circle me-1"></i> KÖKSAN MÜLKÜ
                                    </span>
                                @else
                                    <span
                                        class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                        <i class="fa fa-file-contract me-1"></i> KİRALIK
                                    </span>
                                @endif
                            </div>

                            @if ($location->landlord_name)
                                <div class="bg-light p-3 rounded-3 border border-light-subtle">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-white rounded-circle p-2 shadow-sm text-secondary">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">
                                                Mülk Sahibi</div>
                                            <div class="fw-bold text-dark">{{ $location->landlord_name }}</div>
                                            <div class="small text-secondary">{{ $location->landlord_phone }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                    {{-- Mülkiyet Bitiş --}}

                </div>
            </div>

            {{-- 2. ABONELİK BİLGİLERİ (SADECE DAİRELER İÇİN GÖRÜNSÜN) --}}
            @if ($isUnit)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0"><i class="fa fa-file-invoice text-primary me-2"></i>Abonelikler
                        </h6>
                        <button class="btn btn-sm btn-light text-primary border rounded-pill px-3" data-bs-toggle="modal"
                            data-bs-target="#addSubscriptionModal">
                            <i class="fa fa-plus me-1"></i> Ekle
                        </button>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-3">
                            @forelse($location->subscriptions as $sub)
                                @php
                                    $subIcon = match ($sub->type) {
                                        'electric' => 'fa-bolt text-warning',
                                        'water' => 'fa-tint text-info',
                                        'gas' => 'fa-fire text-danger',
                                        'internet' => 'fa-wifi text-secondary',
                                        default => 'fa-hashtag',
                                    };
                                    $subBg = match ($sub->type) {
                                        'electric' => 'bg-warning',
                                        'water' => 'bg-info',
                                        'gas' => 'bg-danger',
                                        'internet' => 'bg-secondary',
                                        default => 'bg-light',
                                    };
                                @endphp
                                <div
                                    class="d-flex justify-content-between align-items-center p-2 rounded-3 hover-bg-light transition-base position-relative group">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle {{ $subBg }} bg-opacity-10 d-flex align-items-center justify-content-center"
                                            style="width: 36px; height: 36px;">
                                            <i class="fa {{ $subIcon }}"></i>
                                        </div>
                                        <span class="text-secondary fw-medium text-capitalize">
                                            {{ $sub->type == 'gas' ? 'Doğalgaz' : ($sub->type == 'water' ? 'Su' : ($sub->type == 'electric' ? 'Elektrik' : 'İnternet')) }}
                                        </span>
                                    </div>
                                    <span
                                        class="fw-bold font-monospace text-dark bg-light px-2 py-1 rounded border">{{ $sub->subscriber_no }}</span>
                                </div>
                            @empty
                                <div class="text-center text-muted small py-3 opacity-75">
                                    <i class="fa fa-info-circle me-1"></i> Kayıtlı abonelik yok.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif

            {{-- 3. SORUMLU ATAMA --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div
                    class="card-header bg-dark text-white pt-3 px-4 pb-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold small text-uppercase"><i class="fa fa-tools me-2"></i>Teknik Sorumlular</span>
                    <button class="btn btn-sm btn-outline-light rounded-pill px-3" data-bs-toggle="modal"
                        data-bs-target="#assignModal">
                        <i class="fa fa-plus me-1"></i> Ata
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @php
                            $services = [
                                'electric' => ['icon' => 'fa-bolt text-warning', 'label' => 'Elektrik'],
                                'water' => ['icon' => 'fa-tint text-info', 'label' => 'Su/Tesisat'],
                                'gas' => ['icon' => 'fa-fire text-danger', 'label' => 'Doğalgaz'],
                                'internet' => ['icon' => 'fa-wifi text-secondary', 'label' => 'İnternet'],
                                'locksmith' => ['icon' => 'fa-key text-dark', 'label' => 'Çilingir'],
                            ];
                        @endphp

                        @foreach ($services as $key => $val)
                            @php
                                $contact = $location->getResponsibleContact($key);
                                // Bu kayıt, atamanın tam bu lokasyona (daireye) yapılıp yapılmadığını kontrol eder.
                                $isDirect = $location->serviceAssignments->where('service_type', $key)->first();
                            @endphp

                            <div class="list-group-item px-4 py-3 d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="fa {{ $val['icon'] }}" style="width: 20px;"></i>
                                    <span class="text-secondary small fw-bold text-uppercase">{{ $val['label'] }}</span>
                                </div>

                                @if ($contact)
                                    <div class="text-end lh-sm">
                                        <div class="fw-bold text-dark small">{{ $contact->name }}</div>
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <a href="tel:{{ $contact->phone }}"
                                                class="text-decoration-none text-muted x-small">
                                                {{ $contact->phone }}
                                            </a>

                                            {{-- Kontrol ve Aksiyon Bölgesi --}}
                                            @if ($isDirect)
                                                <button type="button" class="btn btn-sm btn-link p-0 ms-2 text-primary"
                                                    style="font-size: 0.8rem;" data-bs-toggle="modal"
                                                    data-bs-target="#assignModal"
                                                    onclick="document.querySelector('#assignModal select[name=service_type]').value = '{{ $key }}';">
                                                    <i class="fa fa-edit"></i> Değiştir
                                                </button>
                                                {{-- 1. DOĞRUDAN ATAMA VARSA: Sadece Silme Butonu (Mevcut) --}}
                                                <form action="{{ route('assignments.destroy', $isDirect->id) }}"
                                                    method="POST" class="d-inline delete-form"
                                                    data-message="Atamayı kaldırmak istiyor musunuz?">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0 ms-1"
                                                        style="font-size: 0.8rem;">
                                                        <i class="fa fa-times-circle"></i>
                                                    </button>
                                                </form>
                                            @else
                                                {{-- 2. MİRAS KALAN ATAMA VARSA: Genel Etiketi ve Yeni Atama Butonu (Override için) --}}
                                                <span class="badge bg-light text-secondary border px-1"
                                                    style="font-size: 0.6rem;">GENEL</span>

                                                {{-- YENİ EKLEME: Mirası geçersiz kılmak için Atama Modalını aç --}}
                                                {{-- Bu, kullanıcıya daireye spesifik atama yapma şansı verir --}}
                                                <button type="button" class="btn btn-sm btn-link p-0 ms-1 text-primary"
                                                    style="font-size: 0.8rem;" data-bs-toggle="modal"
                                                    data-bs-target="#assignModal"
                                                    onclick="document.querySelector('#assignModal select[name=service_type]').value = '{{ $key }}';">
                                                    <i class="fa fa-edit"></i> Değiştir
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted x-small fst-italic">-</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 4. NOTLAR --}}
            @if ($location->notes)
                <div class="card border-0 shadow-sm rounded-4 bg-warning bg-opacity-10">
                    <div class="card-body p-4">
                        <h6 class="text-warning-emphasis fw-bold small mb-2"><i class="fa fa-sticky-note me-2"></i>Notlar
                        </h6>
                        <p class="mb-0 text-secondary small fst-italic">{{ $location->notes }}</p>
                    </div>
                </div>
            @endif

        </div>

        {{-- SAĞ KOLON --}}
        <div class="col-lg-7">

            {{-- SENARYO 1: EĞER BU BİR DAİRE İSE -> DOLULUK DURUMU (HERO ACTION) --}}
            @if ($isUnit)
                @php
                    $activeStay = $location->currentStays->first();
                @endphp
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div
                        class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                        <div class="d-flex align-items-center gap-4">
                            <div class="rounded-circle {{ $activeStay ? 'bg-danger' : 'bg-success' }} bg-opacity-10 d-flex align-items-center justify-content-center {{ $activeStay ? 'text-danger' : 'text-success' }}"
                                style="width: 64px; height: 64px;">
                                <i class="fa {{ $activeStay ? 'fa-user-lock' : 'fa-door-open' }} fa-2x"></i>
                            </div>
                            <div>
                                @if ($activeStay)
                                    <h5 class="fw-bold text-danger mb-1">DOLU</h5>
                                    <div class="text-dark fw-medium">
                                        {{ $activeStay->resident->first_name }} {{ $activeStay->resident->last_name }}
                                    </div>
                                    <small class="text-muted">Giriş:
                                        {{ \Carbon\Carbon::parse($activeStay->start_date)->format('d.m.Y') }}</small>
                                @else
                                    <h5 class="fw-bold text-success mb-1">BOŞ (MÜSAİT)</h5>
                                    <div class="text-secondary small">Bu mekan şu anda konaklamaya uygundur.</div>
                                @endif
                            </div>
                        </div>

                        <div>
                            @if ($activeStay)
                                <a href="{{ route('stays.checkout', $activeStay->id) }}"
                                    class="btn btn-danger btn-lg px-4 shadow-sm rounded-pill">
                                    <i class="fa fa-sign-out-alt me-2"></i> Çıkış Yap
                                </a>
                            @else
                                <a href="{{ route('stays.create', $location->id) }}"
                                    class="btn btn-success btn-lg px-4 shadow-sm rounded-pill">
                                    <i class="fa fa-sign-in-alt me-2"></i> Giriş Yap
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- SENARYO 2: EĞER BU BİR SİTE/BLOK İSE -> ALT BİRİMLER LİSTESİ --}}
            @if ($isContainer)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div
                        class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0">
                            <i class="fa fa-layer-group text-primary me-2"></i>
                            {{ $location->type == 'site' ? 'Bloklar & Alanlar' : 'Daireler' }}
                        </h6>
                        {{-- PARENT ID GÖNDERMEK ÇOK ÖNEMLİ --}}
                        <a href="{{ route('locations.create', ['parent_id' => $location->id]) }}"
                            class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                            <i class="fa fa-plus me-1"></i> Yeni Ekle
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($location->children->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4 text-secondary small fw-bold border-0">İsim</th>
                                            <th class="text-secondary small fw-bold border-0">Tür</th>
                                            <th class="text-secondary small fw-bold border-0">Durum</th>
                                            <th class="text-end pe-4 text-secondary small fw-bold border-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($location->children as $child)
                                            <tr style="cursor: pointer;"
                                                onclick="window.location='{{ route('locations.show', $child->id) }}'">
                                                <td class="ps-4 fw-bold text-dark">{{ $child->name }}</td>
                                                <td>
                                                    <span class="badge bg-light text-secondary border">
                                                        {{ match ($child->type) {'block' => 'Blok','apartment' => 'Daire','common_area' => 'Ortak Alan',default => $child->type} }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- Eğer child bir daire ise doluluk durumunu özet göster --}}
                                                    @if ($child->currentStays->count() > 0)
                                                        <span class="badge bg-danger-subtle text-danger"><i
                                                                class="fa fa-user me-1"></i> Dolu</span>
                                                    @elseif(in_array($child->type, ['apartment', 'room']))
                                                        <span class="badge bg-success-subtle text-success">Boş</span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4">
                                                    <i class="fa fa-chevron-right text-muted small"></i>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 60px; height: 60px;">
                                    <i class="fa fa-folder-open text-secondary opacity-50 fa-lg"></i>
                                </div>
                                <p class="text-muted small mb-0">Henüz altına eklenmiş bir birim (Blok/Daire) yok.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- DEMİRBAŞ LİSTESİ (HER İKİ SENARYODA DA GÖRÜNÜR) --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div
                    class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0"><i class="fa fa-couch text-primary me-2"></i>Demirbaş Listesi</h6>
                    <a href="{{ route('assets.create', $location->id) }}"
                        class="btn btn-sm btn-light text-primary fw-medium rounded-pill border">
                        <i class="fa fa-plus me-1"></i> Yeni Ekle
                    </a>
                </div>
                <div class="card-body p-0">
                    @if ($location->assets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 text-secondary small fw-bold border-0">Ürün</th>
                                        <th class="text-secondary small fw-bold border-0">Marka/Model</th>
                                        <th class="text-secondary small fw-bold border-0">Durum</th>
                                        <th class="text-end pe-4 text-secondary small fw-bold border-0">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($location->assets as $asset)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">{{ $asset->name }}</td>
                                            <td class="text-muted">
                                                {{ $asset->brand ?? '-' }}
                                                @if ($asset->serial_number)
                                                    <div class="x-small font-monospace text-secondary">
                                                        {{ $asset->serial_number }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColor = match ($asset->status) {
                                                        'active' => 'success',
                                                        'broken' => 'danger',
                                                        'repair' => 'warning',
                                                        default => 'secondary',
                                                    };
                                                    $statusLabel = match ($asset->status) {
                                                        'active' => 'Sağlam',
                                                        'broken' => 'Arızalı',
                                                        'repair' => 'Tamirde',
                                                        default => 'Bilinmiyor',
                                                    };
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} border border-{{ $statusColor }}-subtle rounded-pill">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="{{ route('assets.destroy', $asset->id) }}" method="POST"
                                                    class="d-inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-icon btn-light text-danger rounded-circle shadow-sm"
                                                        style="width: 32px; height: 32px;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 60px; height: 60px;">
                                <i class="fa fa-box-open text-secondary opacity-50 fa-lg"></i>
                            </div>
                            <p class="text-muted small mb-0">Henüz demirbaş kaydı yok.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- YENİ MODAL: ABONELİK EKLEME --}}
    <div class="modal fade" id="addSubscriptionModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <h5 class="modal-title fw-bold"><i class="fa fa-plus-circle text-primary me-2"></i>Yeni Abonelik Ekle
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('locations.addSubscription', $location->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Abonelik Türü</label>
                            <select name="new_sub_type" class="form-select bg-light border-0" required>
                                <option value="electric">Elektrik</option>
                                <option value="water">Su</option>
                                <option value="gas">Doğalgaz</option>
                                <option value="internet">İnternet</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <label class="form-label small fw-bold text-secondary">Abone Numarası</label>
                            <input type="text" name="new_sub_no" class="form-control bg-light border-0"
                                placeholder="Örn: 12345678" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ATAMA MODALI --}}
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0 rounded-top-4">
                    <h5 class="modal-title fs-6"><i class="fa fa-user-plus me-2"></i>Teknik Sorumlu Ata</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('locations.assign', $location->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="alert alert-light border border-light-subtle d-flex gap-3 mb-4">
                            <i class="fa fa-info-circle text-primary mt-1"></i>
                            <div class="small text-muted">
                                Bu atama, <strong>{{ $location->name }}</strong> ve altındaki tüm birimler için geçerli
                                olacaktır.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Hizmet Türü</label>
                            <select name="service_type" class="form-select bg-light border-0" required>
                                <option value="electric">Elektrik</option>
                                <option value="water">Su / Tesisat</option>
                                <option value="gas">Doğalgaz</option>
                                <option value="internet">İnternet</option>
                                <option value="locksmith">Çilingir</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Sorumlu Kişi/Firma</label>
                            <select name="contact_id" class="form-select bg-light border-0" required>
                                <option value="">-- Rehberden Seçin --</option>
                                @foreach (\App\Models\Contact::orderBy('name')->get() as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">Atamayı Yap</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
