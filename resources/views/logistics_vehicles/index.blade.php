@extends('layouts.app')

@section('title', 'Lojistik Araçları')

@push('styles')
    <style>
        .page-header-gradient {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
        }

        .filter-card {
            background: white;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .filter-header {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .filter-body {
            padding: 1.5rem;
        }

        .btn-filter-toggle {
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-filter-toggle:hover {
            background-color: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        .vehicle-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .vehicle-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-maintenance {
            background-color: #ffedd5;
            color: #9a3412;
        }

        .status-inactive {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .table thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.2s;
        }

        .table tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.05);
        }

        .btn-create {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-create:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        /* İstatistik Kartları */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid #f0f0f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Hero Section --}}
        <div class="page-header-gradient">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-truck me-2"></i>Nakliye Araçları</h2>
                    <p class="mb-0 opacity-75">Nakliye araçlarının yönetimi ve takibi</p>
                </div>
                <a href="{{ route('service.logistics-vehicles.create') }}" class="btn btn-create">
                    <i class="fas fa-plus me-1"></i> Yeni Araç Ekle
                </a>
            </div>
        </div>

        {{-- İstatistik Kartları --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-label">Toplam Araç</div>
                        <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-light text-primary">
                        <i class="fas fa-truck"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-label">Aktif (Sahada)</div>
                        <div class="stat-number text-success">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-light text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div>
                        <div class="stat-label">Bakımda / Pasif</div>
                        <div class="stat-number text-warning">{{ $stats['maintenance'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon bg-light text-warning">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filter Card --}}
        <div class="filter-card">
            <div class="filter-header">
                <button class="btn btn-link text-decoration-none p-0 w-100 text-start" type="button"
                    data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false"
                    aria-controls="filterCollapse">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-filter me-2" style="color: #667EEA;"></i>
                            Filtreleme Seçenekleri
                        </h5>
                        <i class="fas fa-chevron-down text-muted"></i>
                    </div>
                </button>
            </div>

            <div class="collapse" id="filterCollapse">
                <div class="filter-body">
                    <form method="GET" action="{{ route('service.logistics-vehicles.index') }}" autocomplete="off">
                        <div class="row g-3">

                            {{-- 1. Genel Arama (Controller: search) --}}
                            <div class="col-md-4">
                                <label for="search" class="form-label text-muted fw-bold small">GENEL ARAMA</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white text-muted border-end-0"><i
                                            class="fas fa-search"></i></span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="search"
                                        name="search" value="{{ request('search') }}"
                                        placeholder="Plaka, Marka veya Model ara...">
                                </div>
                            </div>

                            {{-- 2. Durum Filtresi (Controller: status) --}}
                            <div class="col-md-3">
                                <label for="status" class="form-label text-muted fw-bold small">DURUM</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tümü</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>
                                        Bakımda</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Pasif
                                    </option>
                                </select>
                            </div>

                            {{-- 3. Yakıt Tipi Filtresi (Controller: fuel_type) --}}
                            <div class="col-md-3">
                                <label for="fuel_type" class="form-label text-muted fw-bold small">YAKIT TİPİ</label>
                                <select class="form-select" id="fuel_type" name="fuel_type">
                                    <option value="">Tümü</option>
                                    <option value="Diesel" {{ request('fuel_type') == 'Diesel' ? 'selected' : '' }}>Dizel
                                    </option>
                                    <option value="Gasoline" {{ request('fuel_type') == 'Gasoline' ? 'selected' : '' }}>
                                        Benzin</option>
                                    <option value="Electric" {{ request('fuel_type') == 'Electric' ? 'selected' : '' }}>
                                        Elektrik</option>
                                    <option value="Hybrid" {{ request('fuel_type') == 'Hybrid' ? 'selected' : '' }}>Hibrit
                                    </option>
                                </select>
                            </div>

                            {{-- Butonlar --}}
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="d-grid gap-2 w-100">
                                    <button type="submit" class="btn btn-primary fw-bold text-white"
                                        style="background: #667EEA; border: none;">
                                        <i class="fas fa-check me-1"></i> Uygula
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if (request()->anyFilled(['search', 'status', 'fuel_type']))
                            <div class="row mt-2">
                                <div class="col-12 text-end">
                                    <a href="{{ route('service.logistics-vehicles.index') }}"
                                        class="text-decoration-none text-muted small">
                                        <i class="fas fa-times me-1"></i> Filtreleri Temizle
                                    </a>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- Vehicle List Card --}}
        <div class="vehicle-card">
            <div class="card-body p-0">
                @if ($vehicles->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-truck text-secondary opacity-50"></i>
                        <h5 class="text-muted mt-3">Henüz Araç Bulunamadı</h5>
                        <p class="text-muted mb-0">Filtrelere uygun lojistik aracı yok veya henüz kayıt eklenmemiş.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Araç Bilgisi</th>
                                    <th>Kapasite</th>
                                    <th>Güncel KM</th>
                                    <th>Yakıt</th>
                                    <th>Durum</th>
                                    <th class="text-end pe-4">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicles as $vehicle)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light p-2 me-3 text-center"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-truck text-primary pt-1"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $vehicle->plate_number }}</div>
                                                    <small class="text-muted">{{ $vehicle->brand }}
                                                        {{ $vehicle->model }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-semibold text-secondary">{{ number_format($vehicle->capacity, 0) }}</span>
                                            <span class="text-muted small">kg</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-road text-muted me-2 small"></i>
                                                <span class="fw-bold">{{ number_format($vehicle->current_km, 0) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $fuelTypes = [
                                                    'Diesel' => 'Dizel',
                                                    'Gasoline' => 'Benzin',
                                                    'Electric' => 'Elektrik',
                                                    'Hybrid' => 'Hibrit',
                                                    'LPG' => 'LPG',
                                                ];
                                            @endphp

                                            {{ $fuelTypes[$vehicle->fuel_type] ?? $vehicle->fuel_type }}
                                        </td>
                                        <td>
                                            @if ($vehicle->status == 'active')
                                                <span class="status-badge status-active"><i
                                                        class="fas fa-circle fa-xs"></i> Aktif</span>
                                            @elseif($vehicle->status == 'maintenance')
                                                <span class="status-badge status-maintenance"><i
                                                        class="fas fa-wrench fa-xs"></i> Bakımda</span>
                                            @else
                                                <span class="status-badge status-inactive"><i
                                                        class="fas fa-ban fa-xs"></i> Pasif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="{{ route('service.logistics-vehicles.edit', $vehicle->id) }}"
                                                    class="btn btn-sm btn-outline-primary border-0" title="Düzenle">
                                                    <i class="fas fa-edit fa-lg"></i>
                                                </a>
                                                <form
                                                    action="{{ route('service.logistics-vehicles.destroy', $vehicle->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bu aracı silmek istediğinize emin misiniz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0"
                                                        title="Sil">
                                                        <i class="fas fa-trash fa-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if ($vehicles->hasPages())
                        <div class="card-footer bg-white border-0 py-3">
                            {{ $vehicles->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

{{-- JavaScript --}}
@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtreleme Collapse İkon Değişimi
            var filterCollapse = document.getElementById('filterCollapse');
            var filterButton = document.querySelector('[data-bs-target="#filterCollapse"]');
            var filterIcon = filterButton ? filterButton.querySelector('.fa-chevron-down') : null;

            if (filterCollapse && filterIcon) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    filterIcon.classList.remove('fa-chevron-down');
                    filterIcon.classList.add('fa-chevron-up');
                });
                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    filterIcon.classList.remove('fa-chevron-up');
                    filterIcon.classList.add('fa-chevron-down');
                });
            }
        });
    </script>
@endsection
