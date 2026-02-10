@extends('layouts.app')

@section('title', 'Araç Listesi')

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
        }

        .status-active {
            background-color: #d1fae5;
            color: #065f46;
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

        /* Modern Action Buttons */
        .action-btn-group {
            display: inline-flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-action {
            width: 38px;
            height: 38px;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid;
            background: white;
            transition: all 0.3s ease;
            padding: 0;
        }

        .btn-action i {
            font-size: 0.95rem;
        }

        .btn-action-edit {
            border-color: #667EEA;
            color: #667EEA;
        }

        .btn-action-edit:hover {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border-color: #667EEA;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-action-delete {
            border-color: #F093FB;
            color: #F093FB;
        }

        .btn-action-delete:hover {
            background: linear-gradient(135deg, #F093FB, #F5576C);
            border-color: #F093FB;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">

        {{-- Hero Section --}}
        <div class="page-header-gradient">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1"><i class="fas fa-car me-2"></i>Araç Listesi</h2>
                    <p class="mb-0 opacity-75">Tüm araçların yönetimi ve takibi</p>
                </div>
                <a href="{{ route('service.vehicles.create') }}" class="btn btn-create">
                    <i class="fas fa-plus me-1"></i> Yeni Araç Ekle
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                    <form method="GET" action="{{ route('service.vehicles.index') }}" autocomplete="off">
                        <div class="row g-3">
                            {{-- Plaka Filtresi --}}
                            <div class="col-md-4">
                                <label for="plate_number" class="form-label">
                                    <i class="fas fa-hashtag me-1"></i> Plaka
                                </label>
                                <input type="text" class="form-control" id="plate_number" name="plate_number"
                                    value="{{ $filters['plate_number'] ?? '' }}" placeholder="Plaka girin...">
                            </div>

                            {{-- Araç Tipi Filtresi --}}
                            <div class="col-md-4">
                                <label for="type" class="form-label">
                                    <i class="fas fa-car-side me-1"></i> Araç Tipi
                                </label>
                                <input type="text" class="form-control" id="type" name="type"
                                    value="{{ $filters['type'] ?? '' }}" placeholder="Örn: Kamyonet, Otomobil...">
                            </div>

                            {{-- Durum Filtresi --}}
                            <div class="col-md-4">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on me-1"></i> Durum
                                </label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all" {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>
                                        Tümü</option>
                                    <option value="active"
                                        {{ ($filters['status'] ?? 'all') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="inactive"
                                        {{ ($filters['status'] ?? 'all') == 'inactive' ? 'selected' : '' }}>Pasif</option>
                                </select>
                            </div>
                        </div>

                        {{-- Butonlar --}}
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <a href="{{ route('service.vehicles.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Temizle
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check me-1"></i> Filtrele
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Vehicle List Card --}}
        <div class="vehicle-card">
            <div class="card-body p-0">
                @if ($vehicles->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-car fa-3x text-secondary"></i>
                        <h5 class="text-muted mt-3">Henüz Araç Yok</h5>
                        <p class="text-muted mb-0">Filtrelere uygun araç bulunamadı.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4 py-3">Plaka</th>
                                    <th>Tip</th>
                                    <th>Marka/Model</th>
                                    <th>Durum</th>
                                    <th class="text-end pe-4">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicles as $vehicle)
                                    <tr>
                                        <td class="ps-4 fw-bold text-primary">{{ $vehicle->plate_number }}</td>
                                        <td>{{ $vehicle->type }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold">{{ $vehicle->brand_model ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($vehicle->is_active)
                                                <span class="status-badge status-active">Aktif</span>
                                            @else
                                                <span class="status-badge status-inactive">Pasif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="action-btn-group">
                                                @if (!in_array(Auth::user()->role, ['izleyici']))
                                                    <a href="{{ route('service.vehicles.edit', $vehicle) }}"
                                                        class="btn btn-action btn-action-edit" title="Düzenle">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif

                                                @can('access-department', 'hizmet')
                                                    <form action="{{ route('service.vehicles.destroy', $vehicle) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Bu aracı silmek istediğinizden emin misiniz? Araca ait tüm geçmiş atamalar da silinebilir!');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-action btn-action-delete"
                                                            title="Sil">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
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
                            {{ $vehicles->appends($filters ?? [])->links('pagination::bootstrap-5') }}
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
