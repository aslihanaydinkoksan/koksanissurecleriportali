@extends('layouts.app')

{{-- Stil bölümü (Referans dosya ile aynı) --}}
<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
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

    /* Cam Kart Stili (Liste için) */
    .list-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
    }

    /* Kart Başlığı (Liste için) */
    .list-card .card-header {
        background: rgba(255, 255, 255, 0.5);
        color: #333;
        font-weight: bold;
        font-size: 1.25rem;
        border-bottom: none;
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        padding: 1rem 1.5rem;
    }

    /* Tablo Stilleri */
    .table {
        background-color: transparent;
        margin-bottom: 0;
    }

    .table thead th {
        color: #333;
        border-bottom-width: 2px;
        border-color: rgba(0, 0, 0, 0.15);
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.4);
        color: #212529;
    }

    .table-striped>tbody>tr:nth-of-type(even)>* {
        --bs-table-accent-bg: transparent;
        color: #212529;
    }

    .table-hover>tbody>tr:hover>* {
        --bs-table-accent-bg: rgba(255, 255, 255, 0.8);
        color: #000;
    }

    .table td,
    .table th {
        vertical-align: middle;
    }

    /* Filtre Stilleri */
    .btn-filter-toggle {
        background-color: rgba(255, 255, 255, 0.8);
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: #333;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-filter-toggle:hover {
        background-color: rgba(255, 255, 255, 0.95);
        border-color: rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn-filter-toggle[aria-expanded="true"] {
        background-color: rgba(230, 235, 255, 0.9);
    }

    .filter-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 0.75rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
    }

    .filter-card .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .filter-card .form-control,
    .filter-card .form-select {
        border-radius: 0.5rem;
        background-color: #fff;
    }

    .filter-card .row {
        margin-bottom: -1rem;
    }

    .filter-card .row>div {
        margin-bottom: 1rem;
    }

    .btn-apply-filter {
        background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        padding: 0.5rem 1.25rem;
    }

    .btn-apply-filter:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .btn-clear-filter {
        padding: 0.5rem 1.25rem;
    }
</style>

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                {{-- Başarı/Hata Mesajları --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- === ARAÇ FİLTRELEME BÖLÜMÜ === --}}
                <div class="mb-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        {{-- Yeni Araç Ekle Butonu --}}
                        <a href="{{ route('service.vehicles.create') }}" class="btn btn-primary btn-sm mb-2 mb-md-0">
                            <i class="fas fa-plus me-1"></i> Yeni Araç Ekle
                        </a>
                        <button class="btn btn-filter-toggle btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-filter me-2"></i> Filtrele
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card">
                            {{-- Form action güncellendi --}}
                            <form method="GET" action="{{ route('service.vehicles.index') }}">
                                <div class="row">
                                    {{-- Plaka Filtresi --}}
                                    <div class="col-md-4">
                                        <label for="plate_number" class="form-label">Plaka (Ara)</label>
                                        <input type="text" class="form-control form-control-sm" id="plate_number"
                                            name="plate_number" value="{{ $filters['plate_number'] ?? '' }}"
                                            placeholder="Plaka girin...">
                                    </div>

                                    {{-- Araç Tipi Filtresi --}}
                                    <div class="col-md-4">
                                        <label for="type" class="form-label">Araç Tipi (Ara)</label>
                                        <input type="text" class="form-control form-control-sm" id="type"
                                            name="type" value="{{ $filters['type'] ?? '' }}"
                                            placeholder="Örn: Kamyonet, Otomobil...">
                                    </div>

                                    {{-- Durum Filtresi --}}
                                    <div class="col-md-4">
                                        <label for="status" class="form-label">Durum</label>
                                        <select class="form-select form-select-sm" id="status" name="status">
                                            <option value="all"
                                                {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>Tümü
                                            </option>
                                            <option value="active"
                                                {{ ($filters['status'] ?? 'all') == 'active' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="inactive"
                                                {{ ($filters['status'] ?? 'all') == 'inactive' ? 'selected' : '' }}>Pasif
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Butonlar --}}
                                    <div class="col-md-12 d-flex align-items-end justify-content-end gap-2 mt-3">
                                        <a href="{{ route('service.vehicles.index') }}"
                                            class="btn btn-secondary btn-clear-filter btn-sm">
                                            <i class="fas fa-times me-1"></i> Temizle
                                        </a>
                                        <button type="submit" class="btn btn-apply-filter btn-sm">
                                            <i class="fas fa-check me-1"></i> Filtrele
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- === FİLTRELEME BÖLÜMÜ SONU === --}}


                {{-- ARAÇ LİSTESİ KARTI --}}
                <div class="card list-card">
                    {{-- Başlık güncellendi --}}
                    <div class="card-header">Araç Listesi</div>

                    <div class="card-body p-0">
                        {{-- Değişken ve mesaj güncellendi --}}
                        @if ($vehicles->isEmpty())
                            <div class="alert alert-warning m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> Filtrelere uygun araç bulunamadı.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead>
                                        {{-- Tablo başlıkları güncellendi --}}
                                        <tr>
                                            <th scope="col" class="ps-3">Plaka</th>
                                            <th scope="col">Tip</th>
                                            <th scope="col">Marka/Model</th>
                                            <th scope="col">Durum</th>
                                            <th scope="col" class="text-end pe-3">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Döngü güncellendi: $vehicles as $vehicle --}}
                                        @foreach ($vehicles as $vehicle)
                                            <tr>
                                                {{-- Tablo verileri güncellendi --}}
                                                <td class="ps-3 fw-bold">{{ $vehicle->plate_number }}</td>
                                                <td>{{ $vehicle->type }}</td>
                                                <td>{{ $vehicle->brand_model ?? '-' }}</td>
                                                <td>
                                                    @if ($vehicle->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Pasif</span>
                                                    @endif
                                                </td>

                                                {{-- İşlem butonları güncellendi --}}
                                                <td class="text-end pe-3">
                                                    @if (!in_array(Auth::user()->role, ['izleyici']))
                                                        <a href="{{ route('service.vehicles.edit', $vehicle) }}"
                                                            class="btn btn-sm btn-primary" title="Düzenle"><i
                                                                class="fas fa-edit"></i></a>
                                                    @endif

                                                    @can('access-department', 'hizmet')
                                                        <form action="{{ route('service.vehicles.destroy', $vehicle) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz? Araca ait tüm geçmiş atamalar da silinebilir!');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Sil"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Sayfalama linkleri --}}
                                @if ($vehicles->hasPages())
                                    <div class="card-footer bg-transparent border-top-0 pt-3 pb-2 px-3">
                                        {{ $vehicles->appends($filters ?? [])->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JavaScript (Referans dosya ile aynı) --}}
@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var filterCollapse = document.getElementById('filterCollapse');
            var filterButtonIcon = document.querySelector('.btn-filter-toggle .fa-chevron-down');

            if (filterCollapse && filterButtonIcon) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    filterButtonIcon.classList.remove('fa-chevron-up');
                    filterButtonIcon.classList.add('fa-chevron-down');
                });
                if (filterCollapse.classList.contains('show')) {
                    filterButtonIcon.classList.remove('fa-chevron-down');
                    filterButtonIcon.classList.add('fa-chevron-up');
                }
            }
        });
    </script>
@endsection
