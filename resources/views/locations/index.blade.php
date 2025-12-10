@extends('layouts.master')

@section('title', 'Mekan Yönetimi')

@section('content')

    {{-- 1. BREADCRUMB --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 m-0 small">
            <li class="breadcrumb-item">
                <a href="{{ route('locations.index') }}" class="text-decoration-none text-muted">
                    <i class="fa fa-home"></i> Ana Dizin
                </a>
            </li>
            @foreach ($breadcrumbs as $crumb)
                <li class="breadcrumb-item active text-primary" aria-current="page">{{ $crumb->name }}</li>
            @endforeach
        </ol>
    </nav>

    {{-- 2. SAYFA LAYOUT COMPONENTİ --}}
    @php
        $pageTitle = $parentLocation ? $parentLocation->name . ' İçeriği' : 'Siteler / Kampüsler';
        $createRoute = route('locations.create', $parentLocation?->id);
    @endphp

    <x-page-layout :title="$pageTitle" :count="$locations->total()" :create-route="$createRoute" create-label="Yeni Mekan Ekle">

        {{-- Filtre Slotu --}}
        <x-slot:filters>
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text bg-white border-end-0"><i class="fa fa-search text-muted"></i></span>
                <input type="text" class="form-control border-start-0 ps-0" placeholder="Mekan adı ara...">
            </div>
        </x-slot:filters>

        {{-- 3. KART DÖNGÜSÜ --}}
        @forelse($locations as $loc)
            @php
                // Badge Rengi
                $badgeColor = match ($loc->type) {
                    'site', 'campus' => 'primary',
                    'block' => 'warning',
                    'apartment', 'room' => 'info',
                    default => 'secondary',
                };

                // Kapsayıcı mı? (İçine girilebilir mi?)
                $isContainer = in_array($loc->type, ['site', 'campus', 'block', 'common_area']);
            @endphp

            <div class="col">
                <x-info-card title="{{ $loc->name }}" badge="{{ ucfirst($loc->type) }}" badgeColor="{{ $badgeColor }}">

                    {{-- Kart İçeriği --}}
                    <div class="d-flex flex-column gap-2 mt-2">

                        {{-- MÜLKİYET BİLGİSİ: Sadece son nokta birimleri (Daire/Oda vb.) için göster --}}
                        @if (!$isContainer)
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fa-solid fa-file-contract me-2 opacity-50" style="width: 16px;"></i>
                                @if ($loc->ownership == 'owned')
                                    <span class="text-success fw-medium">Şirket Mülkü</span>
                                @else
                                    <span>Kiralık {{ $loc->landlord_name ? '(' . $loc->landlord_name . ')' : '' }}</span>
                                @endif
                            </div>
                        @else
                            {{-- Site/Blok/Kampüs için mülkiyet yerine hiyerarşi düzeyi bilgisi --}}
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fa-solid fa-layer-group me-2 opacity-50" style="width: 16px;"></i>
                                <span class="fst-italic text-secondary">{{ ucfirst($loc->type) }} Düzeyi</span>
                            </div>
                        @endif


                        {{-- DOLULUK BİLGİSİ: Sadece son nokta birimleri (Daire/Oda vb.) için göster --}}
                        @if (!$isContainer)
                            <div class="d-flex align-items-center text-muted small">
                                <i class="fa-solid fa-users me-2 opacity-50" style="width: 16px;"></i>
                                @if ($loc->current_stays_count > 0)
                                    <span class="text-danger fw-bold">{{ $loc->current_stays_count }} / {{ $loc->capacity }}
                                        DOLU</span>
                                @else
                                    <span class="badge bg-success-subtle text-success border border-success">
                                        <i class="fa fa-door-open me-1"></i> BOŞ
                                    </span>
                                @endif
                            </div>
                        @else
                            {{-- Kapsayıcı birimler için boş bir placeholder --}}
                            <span class="text-muted small">-</span>
                        @endif
                    </div>

                    {{-- --- AKSİYON BUTONLARI --- --}}
                    <x-slot:actions>

                        {{-- SENARYO 1: Kapsayıcı (Site/Blok) ise -> HEM 'GİR' HEM 'KÜNYE' Butonu --}}
                        @if ($isContainer)
                            @php
                                // Dinamik Buton Metni Belirleme
                                $drillDownText = match ($loc->type) {
                                    'site', 'campus' => 'Bloklar & Daireler', // Site ise "Bloklar"
                                    'block' => 'Daireler', // Blok ise "Daireler"
                                    default => 'Alt Birimler',
                                };
                            @endphp

                            {{-- A. İçeri Gir (Klasör Mantığı) --}}
                            <a href="{{ route('locations.index', $loc->id) }}"
                                class="btn btn-sm btn-light flex-fill text-primary fw-bold" data-bs-toggle="tooltip"
                                title="{{ $loc->name }} içeriğini görüntüle">
                                <i class="fa fa-folder-open me-1"></i> {{ $drillDownText }}
                            </a>

                            {{-- B. Bilgi/Künye (Teknik Detay) --}}
                            <a href="{{ route('locations.show', $loc->id) }}"
                                class="btn btn-sm btn-light flex-fill text-secondary fw-medium" data-bs-toggle="tooltip"
                                title="Teknik bilgiler, demirbaşlar ve notlar">
                                <i class="fa fa-id-card me-1"></i> Künye Bilgileri
                            </a>

                            {{-- SENARYO 2: Son Nokta (Daire) ise -> SADECE 'DETAY' Butonu --}}
                        @else
                            <x-action-button type="view" href="{{ route('locations.show', $loc->id) }}"
                                class="flex-fill justify-content-center" text="Detay & İşlem" />
                        @endif

                        {{-- Düzenle --}}
                        <x-action-button type="edit" href="{{ route('locations.edit', $loc->id) }}" />

                        {{-- Sil --}}
                        <x-delete-button action="{{ route('locations.destroy', $loc->id) }}" />
                    </x-slot:actions>

                </x-info-card>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm text-center py-5">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                    <h5 class="text-muted">Burada henüz hiç kayıt yok.</h5>
                    <p class="text-secondary small">Yeni bir mekan ekleyerek başlayabilirsiniz.</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        <x-slot:pagination>
            {{ $locations->links() }}
        </x-slot:pagination>

    </x-page-layout>

@endsection
