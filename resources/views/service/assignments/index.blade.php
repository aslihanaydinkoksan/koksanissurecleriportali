@extends('layouts.app')

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        /* Arka plan animasyonu */
        @keyframes gradientShift {
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

        /* Yüzen parçacık efekti */
        #app>main.py-4::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.06) 0%, transparent 50%);
            animation: floatingParticles 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floatingParticles {

            0%,
            100% {
                transform: translate(0, 0);
            }

            33% {
                transform: translate(30px, -30px);
            }

            66% {
                transform: translate(-20px, 20px);
            }
        }

        /* Modern Glassmorphism Kart */
        .list-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            overflow: hidden;
            animation: cardSlideIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Kart Başlığı */
        .list-card .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            color: #1a202c;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
            text-shadow: none;
        }

        /* Modern Butonlar */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        /* Filtre Toggle Butonu */
        .btn-filter-toggle {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 0, 0, 0.15);
            color: #1a202c;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-filter-toggle:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #1a202c;
        }

        .btn-filter-toggle[aria-expanded="true"] {
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Filtre Kartı */
        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            animation: filterSlideDown 0.4s ease-out;
        }

        @keyframes filterSlideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-card .form-label {
            font-weight: 600;
            color: #1a202c;
            text-shadow: none;
            margin-bottom: 0.5rem;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            background: #fff;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
            transform: translateY(-1px);
        }

        /* Filtre Butonları */
        .btn-apply-filter {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(67, 233, 123, 0.4);
        }

        .btn-apply-filter:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 20px rgba(67, 233, 123, 0.5);
        }

        .btn-clear-filter {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-clear-filter:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: translateY(-2px);
            color: white;
        }

        /* Tablo Stilleri */
        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #1a202c;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
            padding: 1.25rem 1rem;
            text-shadow: none;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:nth-of-type(even) {
            background: rgba(255, 255, 255, 0.95);
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table td {
            color: #1a202c;
            font-weight: 500;
            padding: 1rem;
            vertical-align: middle;
            text-shadow: none;
        }

        .table td.fw-bold {
            font-weight: 700;
            color: #1a202c;
        }

        /* İşlem Butonları */
        .btn-sm {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .table .btn-sm.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .table .btn-sm.btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
        }

        .table .btn-sm.btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            box-shadow: 0 4px 10px rgba(245, 87, 108, 0.3);
        }

        .table .btn-sm.btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 87, 108, 0.4);
        }

        /* Alert Stilleri */
        .alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            color: #1a202c;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: alertSlideIn 0.4s ease-out;
        }

        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.2), rgba(56, 249, 215, 0.2));
            border-color: rgba(67, 233, 123, 0.5);
            color: #0d5c2e;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.2), rgba(245, 87, 108, 0.2));
            border-color: rgba(245, 87, 108, 0.5);
            color: #7a1f2e;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(251, 200, 212, 0.3), rgba(255, 195, 113, 0.3));
            border-color: rgba(255, 195, 113, 0.5);
            color: #7c4a03;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        /* Sayfalama */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            margin: 0 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Responsive iyileştirmeler */
        @media (max-width: 768px) {
            .list-card .card-header {
                font-size: 1.25rem;
                padding: 1.25rem 1.5rem;
            }

            .filter-card {
                padding: 1.5rem;
            }

            .table-responsive {
                border-radius: 0 0 24px 24px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                {{-- Başarı/Hata Mesajları --}}
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

                {{-- Filtreleme Bölümü --}}
                <div class="mb-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                        <a href="{{ route('service.assignments.create') }}" class="btn btn-primary btn-sm mb-2 mb-md-0">
                            <i class="fas fa-plus me-2"></i> Yeni Araç Görevi Ekle
                        </a>
                        <button class="btn btn-filter-toggle btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-filter me-2"></i> Filtrele
                            <i class="fas fa-chevron-down ms-2 small"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card">
                            <form method="GET" action="{{ route('service.assignments.index') }}">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="vehicle_id" class="form-label">
                                            <i class="fas fa-car me-1"></i> Araç
                                        </label>
                                        <select class="form-select form-select-sm" id="vehicle_id" name="vehicle_id">
                                            <option value="">Tümü</option>
                                            @foreach ($vehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}"
                                                    {{ ($filters['vehicle_id'] ?? '') == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->plate_number }} ({{ $vehicle->type }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="task_description" class="form-label">
                                            <i class="fas fa-search me-1"></i> Görev Açıklaması
                                        </label>
                                        <input type="text" class="form-control form-control-sm" id="task_description"
                                            name="task_description" value="{{ $filters['task_description'] ?? '' }}"
                                            placeholder="Görev açıklaması girin...">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="date_from" class="form-label">
                                            <i class="fas fa-calendar-alt me-1"></i> Başlangıç
                                        </label>
                                        <input type="date" class="form-control form-control-sm" id="date_from"
                                            name="date_from" value="{{ $filters['date_from'] ?? '' }}">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="date_to" class="form-label">
                                            <i class="fas fa-calendar-check me-1"></i> Bitiş
                                        </label>
                                        <input type="date" class="form-control form-control-sm" id="date_to"
                                            name="date_to" value="{{ $filters['date_to'] ?? '' }}">
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end justify-content-end gap-2">
                                        <a href="{{ route('service.assignments.index') }}"
                                            class="btn btn-secondary btn-clear-filter btn-sm" title="Temizle">
                                            <i class="fas fa-times"></i>
                                        </a>
                                        <button type="submit" class="btn btn-apply-filter btn-sm" title="Filtrele">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Araç Görev Listesi --}}
                <div class="card list-card">
                    <div class="card-header">
                        <i class="fas fa-clipboard-list me-2"></i> Araç Görev Listesi
                    </div>

                    <div class="card-body p-0">
                        @if ($assignments->isEmpty())
                            <div class="alert alert-warning m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> Filtrelere uygun araç görevi bulunamadı.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-3">
                                                <i class="fas fa-car me-2"></i>Araç
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-tasks me-2"></i>Görev
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-map-marker-alt me-2"></i>Yer
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-user me-2"></i>Talep Eden
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-clock me-2"></i>Sefer Zamanı
                                            </th>
                                            <th scope="col" class="text-end pe-3">
                                                <i class="fas fa-cog me-2"></i>İşlemler
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                <td class="ps-3 fw-bold">
                                                    {{ $assignment->vehicle->plate_number ?? 'Silinmiş Araç' }}
                                                </td>
                                                <td>{{ $assignment->task_description }}</td>
                                                <td>{{ $assignment->destination ?? '-' }}</td>
                                                <td>{{ $assignment->createdBy->name ?? '-' }}</td>
                                                <td>{{ $assignment->start_time->format('d.m.Y H:i') }}</td>
                                                <td class="text-end pe-3">
                                                    @if (!in_array(Auth::user()->role, ['izleyici']))
                                                        <a href="{{ route('service.assignments.edit', $assignment) }}"
                                                            class="btn btn-sm btn-primary me-1" title="Düzenle">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if (!in_array(Auth::user()->role, ['izleyici']))
                                                        <form
                                                            action="{{ route('service.assignments.destroy', $assignment) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bu araç görevini silmek istediğinizden emin misiniz?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                title="Sil">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                @if ($assignments->hasPages())
                                    <div class="card-footer bg-transparent border-top-0 pt-3 pb-2 px-3">
                                        {{ $assignments->appends($filters ?? [])->links('pagination::bootstrap-5') }}
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
