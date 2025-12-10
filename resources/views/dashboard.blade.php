@extends('layouts.master')

@section('title', 'Yönetim Paneli')

@section('content')

    {{-- 1. HOŞGELDİN KARTI --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center text-warning"
                    style="width: 56px; height: 56px;">
                    <i class="fa fa-smile fa-xl"></i>
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Hoşgeldin, {{ Auth::user()->name }}!</h4>
                    <p class="text-secondary small mb-0">Misafirhane yönetim paneline genel bakış.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. İSTATİSTİK KARTLARI (Component Kullanımı) --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <x-stats-card title="Doluluk Oranı" value="{{ $stats['occupancy_rate'] }}%" icon="fa-chart-pie"
                color="primary" />
        </div>

        <div class="col-md-6 col-xl-3">
            <x-stats-card title="Kayıtlı Personel" value="{{ $stats['total_residents'] }}" icon="fa-users" color="success"
                link="{{ route('residents.index') }}" />
        </div>

        <div class="col-md-6 col-xl-3">
            <x-stats-card title="Müsait Yerler" value="{{ $stats['empty_locations'] }}" icon="fa-door-open" color="info"
                link="{{ route('locations.index') }}" />
        </div>

        {{-- Hızlı Erişim Kartı --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">
                        Hızlı İşlemler
                    </h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('residents.create') }}"
                            class="btn btn-white shadow-sm text-start text-primary fw-medium border">
                            <i class="fa fa-user-plus me-2"></i> Personel Ekle
                        </a>
                        <a href="{{ route('locations.index') }}"
                            class="btn btn-white shadow-sm text-start text-dark fw-medium border">
                            <i class="fa fa-bed me-2"></i> Oda Durumları
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. SON HAREKETLER TABLOSU --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
            <h5 class="fw-bold text-dark mb-0"><i class="fa fa-history text-secondary me-2"></i>Son Hareketler</h5>
        </div>
        <div class="card-body p-0 mt-3">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 text-secondary small fw-bold border-0">PERSONEL</th>
                            <th class="text-secondary small fw-bold border-0">MEKAN</th>
                            <th class="text-secondary small fw-bold border-0">İŞLEM</th>
                            <th class="text-secondary small fw-bold border-0">ZAMAN</th>
                            <th class="pe-4 text-end text-secondary small fw-bold border-0">DURUM</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestStays as $stay)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border"
                                            style="width: 36px; height: 36px;">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark small">
                                                {{ $stay->resident?->first_name ?? 'Silinmiş' }}
                                                {{ $stay->resident?->last_name ?? '' }}
                                            </div>
                                            <div class="text-muted x-small">{{ $stay->resident?->department ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($stay->location)
                                        <a href="{{ route('locations.show', $stay->location_id) }}"
                                            class="text-decoration-none text-dark small fw-medium">
                                            <i class="fa fa-map-pin text-danger opacity-75 me-1"></i>
                                            {{ $stay->location->name }}
                                        </a>
                                    @else
                                        <span class="text-muted small fst-italic">Silinmiş Mekan</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($stay->check_out_date)
                                        <span
                                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">Çıkış</span>
                                    @else
                                        <span
                                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">Giriş</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="text-dark small fw-medium">{{ $stay->updated_at->diffForHumans() }}</div>
                                    <div class="text-muted x-small">{{ $stay->updated_at->format('d.m H:i') }}</div>
                                </td>
                                <td class="pe-4 text-end">
                                    @if ($stay->check_out_date)
                                        <i class="fa fa-check-circle text-muted"></i>
                                    @else
                                        <i class="fa fa-clock text-success animate-pulse"></i>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted small">Henüz hareket kaydı yok.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
