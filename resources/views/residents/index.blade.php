@extends('layouts.master')

@section('title', 'Personel & Misafir Listesi')

@section('content')

    <x-page-layout title="Personel & Misafir Listesi" :count="$residents->total()" :create-route="route('residents.create')" create-label="Yeni Kişi Ekle">

        {{-- FİLTRE / ARAMA SLOTU --}}
        <x-slot:filters>
            <form action="{{ route('residents.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group input-group-sm" style="min-width: 250px;">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fa fa-search"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="İsim, TC veya Sicil No..." value="{{ request('search') }}">

                    @if (request('search'))
                        <a href="{{ route('residents.index') }}" class="btn btn-light border" title="Filtreyi Temizle">
                            <i class="fa fa-times text-secondary"></i>
                        </a>
                    @endif
                </div>
                <button type="submit" class="btn btn-sm btn-dark shadow-sm px-3">Ara</button>
            </form>
        </x-slot:filters>

        {{-- LİSTE (GRID) --}}
        @forelse($residents as $person)
            @php
                // Konaklama Durumu Kontrolü
                $isStaying = $person->stays()->whereNull('check_out_date')->exists();
                $badgeText = $isStaying ? 'Konaklıyor' : 'Boşta';
                $badgeColor = $isStaying ? 'success' : 'secondary';
            @endphp

            <div class="col">
                <x-info-card title="{{ $person->first_name }} {{ $person->last_name }}"
                    subtitle="{{ $person->department ?? 'Departman Yok' }}" badge="{{ $badgeText }}"
                    badgeColor="{{ $badgeColor }}">
                    {{-- Kart İçeriği --}}
                    <div class="d-flex flex-column gap-2 mt-2 text-secondary small">

                        {{-- Sicil No --}}
                        <div class="d-flex justify-content-between align-items-center border-bottom border-light pb-1">
                            <span><i class="fa fa-id-badge me-2 opacity-50"></i>Sicil No:</span>
                            <span class="fw-medium text-dark font-monospace">{{ $person->employee_id ?? '-' }}</span>
                        </div>

                        {{-- Telefon --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fa fa-phone me-2 opacity-50"></i>Telefon:</span>
                            <span class="fw-medium text-dark">{{ $person->phone ?? '-' }}</span>
                        </div>
                    </div>

                    {{-- Aksiyon Butonları --}}
                    <x-slot:actions>
                        {{-- Geçmiş (View butonu olarak kullanıyoruz ama ikonu değiştiriyoruz) --}}
                        <a href="{{ route('reports.index', ['search' => $person->first_name . ' ' . $person->last_name]) }}"
                            class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-2 flex-fill justify-content-center">
                            <i class="fa fa-history"></i> Geçmiş
                        </a>

                        {{-- Düzenle --}}
                        <x-action-button type="edit" href="{{ route('residents.edit', $person->id) }}" />

                        {{-- Sil --}}
                        <x-delete-button action="{{ route('residents.destroy', $person->id) }}"
                            message="{{ $person->first_name }} kişisini silmek istediğinize emin misiniz?" />
                    </x-slot:actions>

                </x-info-card>
            </div>

        @empty
            {{-- Boş Durum (Empty State) --}}
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="fa fa-user-slash fa-2x text-secondary opacity-50"></i>
                    </div>
                    <h5 class="text-muted">Kayıt Bulunamadı</h5>
                    <p class="text-secondary small">
                        Aradığınız kriterlere uygun personel yok veya henüz kimse eklenmemiş.
                    </p>
                    <a href="{{ route('residents.create') }}" class="btn btn-primary px-4 rounded-pill">
                        <i class="fa fa-user-plus me-2"></i> İlk Kişiyi Ekle
                    </a>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        <x-slot:pagination>
            {{ $residents->links() }}
        </x-slot:pagination>

    </x-page-layout>

@endsection
