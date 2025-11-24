resources\views\service\assignments\index.blade.php:

@extends('layouts.app')
@section('title', 'Araç Görev Listesi')

@push('styles')
    <style>
        /* Vehicle Tasks Header - Mavi/Turkuaz Tema */
        .vehicle-tasks-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
            position: relative;
            overflow: hidden;
        }

        .vehicle-tasks-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: pulse 15s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .vehicle-tasks-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .vehicle-tasks-header .icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .vehicle-tasks-header .icon-wrapper i {
            font-size: 1.8rem;
            color: white;
        }

        .vehicle-tasks-header .header-content {
            position: relative;
            z-index: 1;
        }

        .vehicle-tasks-header .stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .vehicle-tasks-header .stat-item {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
        }

        .vehicle-tasks-header .stat-item strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .task-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .task-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .task-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
            border-color: rgba(79, 172, 254, 0.3);
        }

        .task-card:hover::before {
            transform: scaleY(1);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .task-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .task-title i {
            color: #4facfe;
            font-size: 1rem;
        }

        .task-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .meta-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4facfe;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .meta-content {
            flex: 1;
            min-width: 0;
        }

        .meta-label {
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            font-size: 0.95rem;
            color: #2d3748;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .modern-btn {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
        }

        .modern-btn-edit {
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            color: white;
        }

        .modern-btn-edit:hover {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
            color: white;
        }

        .modern-btn-delete {
            background: linear-gradient(135deg, #fc8181 0%, #f56565 100%);
            color: white;
        }

        .modern-btn-delete:hover {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
            color: white;
        }

        .modern-btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }

        .modern-btn-primary:hover {
            background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
            color: white;
        }

        .modern-btn-filter {
            background: white;
            color: #2d3748;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            border: 2px solid #e2e8f0;
        }

        .modern-btn-filter:hover {
            background: #f7fafc;
            border-color: #4facfe;
            color: #4facfe;
        }

        .modern-btn-filter[aria-expanded="true"] {
            background: #4facfe;
            color: white;
            border-color: #4facfe;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #4facfe15 0%, #00f2fe15 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: #4facfe;
        }

        .empty-state h5 {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #718096;
            margin-bottom: 1.5rem;
        }

        .filters-bar {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .filter-collapse {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .filter-collapse .form-label {
            font-weight: 600;
            color: #2d3748;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .filter-collapse .form-control,
        .filter-collapse .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .filter-collapse .form-control:focus,
        .filter-collapse .form-select:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 3px rgba(79, 172, 254, 0.1);
        }

        .modern-btn-apply {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .modern-btn-apply:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
            color: white;
        }

        .modern-btn-clear {
            background: #f7fafc;
            color: #2d3748;
            border: 2px solid #e2e8f0;
        }

        .modern-btn-clear:hover {
            background: white;
            border-color: #cbd5e0;
            color: #2d3748;
        }

        .pagination-wrapper {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
        }

        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: linear-gradient(135deg, #48bb7815 0%, #38a16915 100%);
            color: #22543d;
            border-left: 4px solid #48bb78;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fc818115 0%, #f5656515 100%);
            color: #742a2a;
            border-left: 4px solid #fc8181;
        }

        .alert-warning {
            background: linear-gradient(135deg, #f6ad5515 0%, #ed893615 100%);
            color: #7c2d12;
            border-left: 4px solid #f6ad55;
        }

        @media (max-width: 768px) {
            .vehicle-tasks-header {
                padding: 1.5rem;
            }

            .vehicle-tasks-header h4 {
                font-size: 1.3rem;
            }

            .vehicle-tasks-header .stats {
                flex-direction: column;
                gap: 1rem;
            }

            .task-card {
                padding: 1rem;
            }

            .task-meta {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                width: 100%;
            }

            .modern-btn {
                flex: 1;
                justify-content: center;
            }

            .filters-bar {
                flex-direction: column;
            }

            .modern-btn-primary,
            .modern-btn-filter {
                width: 100%;
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="vehicle-tasks-header fade-in">
            <div class="header-content">
                <h4>
                    <div class="icon-wrapper">
                        <i class="fas fa-car"></i>
                    </div>
                    <div>
                        Araç Görevleri
                        <small
                            style="display: block; font-size: 0.9rem; font-weight: 400; opacity: 0.9; margin-top: 0.25rem;">
                            Araç bazlı görev yönetimi
                        </small>
                    </div>
                </h4>
                <div class="stats">
                    <div class="stat-item">
                        <strong>{{ $assignments->total() }}</strong>
                        Toplam Görev
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show fade-in" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="filters-bar fade-in" style="animation-delay: 0.1s;">
            <a href="{{ route('service.assignments.create') }}" class="modern-btn modern-btn-primary">
                <i class="fas fa-plus"></i>
                Yeni Görev Oluştur
            </a>
            <button class="modern-btn modern-btn-filter" type="button" data-bs-toggle="collapse"
                data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                <i class="fas fa-filter"></i>
                Filtrele
                <i class="fas fa-chevron-down ms-1"></i>
            </button>
        </div>

        <div class="collapse" id="filterCollapse">
            <div class="filter-collapse fade-in">
                <form method="GET" action="{{ route('service.assignments.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="vehicle_id" class="form-label">
                                <i class="fas fa-car me-1"></i> Araç
                            </label>
                            <select class="form-select" id="vehicle_id" name="vehicle_id">
                                <option value="">Tümü</option>
                                {{-- Şirket Araçları Grubu (Opsiyonel: optgroup ile ayırabilirsin) --}}
                                <optgroup label="Şirket Araçları">
                                    @foreach ($vehicles->where('type', '!=', 'logistics')->whereInstanceOf(\App\Models\Vehicle::class) as $vehicle)
                                        <option value="{{ $vehicle->filter_key }}"
                                            {{ request('vehicle_id') == $vehicle->filter_key ? 'selected' : '' }}>
                                            {{ $vehicle->display_name }}
                                        </option>
                                    @endforeach
                                </optgroup>

                                {{-- Nakliye Araçları Grubu --}}
                                <optgroup label="Nakliye Araçları">
                                    @foreach ($vehicles->whereInstanceOf(\App\Models\LogisticsVehicle::class) as $vehicle)
                                        <option value="{{ $vehicle->filter_key }}"
                                            {{ request('vehicle_id') == $vehicle->filter_key ? 'selected' : '' }}>
                                            {{ $vehicle->display_name }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="task_description" class="form-label">
                                <i class="fas fa-search me-1"></i> Görev Açıklaması
                            </label>
                            <input type="text" class="form-control" id="task_description" name="task_description"
                                value="{{ $filters['task_description'] ?? '' }}" placeholder="Görev açıklaması girin...">
                        </div>

                        <div class="col-md-2">
                            <label for="date_from" class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i> Başlangıç
                            </label>
                            <input type="date" class="form-control" id="date_from" name="date_from"
                                value="{{ $filters['date_from'] ?? '' }}">
                        </div>

                        <div class="col-md-2">
                            <label for="date_to" class="form-label">
                                <i class="fas fa-calendar-check me-1"></i> Bitiş
                            </label>
                            <input type="date" class="form-control" id="date_to" name="date_to"
                                value="{{ $filters['date_to'] ?? '' }}">
                        </div>

                        <div class="col-md-1 d-flex align-items-end justify-content-end gap-2">
                            <a href="{{ route('service.assignments.index') }}" class="modern-btn modern-btn-clear"
                                title="Temizle">
                                <i class="fas fa-times"></i>
                            </a>
                            <button type="submit" class="modern-btn modern-btn-apply" title="Filtrele">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modern-card fade-in" style="animation-delay: 0.2s;">
            @forelse($assignments as $index => $assignment)
                <div class="task-card" style="animation-delay: {{ 0.3 + $index * 0.05 }}s;">
                    <div class="task-header">
                        <h5 class="task-title">
                            <i class="fas fa-clipboard-list"></i>
                            {{ $assignment->task_description }}
                        </h5>
                        <div class="action-buttons">
                            @if (!in_array(Auth::user()->role, ['izleyici']))
                                <a href="{{ route('service.assignments.edit', $assignment) }}"
                                    class="modern-btn modern-btn-edit">
                                    <i class="fas fa-edit"></i>
                                    Düzenle
                                </a>
                                <form action="{{ route('service.assignments.destroy', $assignment) }}" method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Bu araç görevini silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="modern-btn modern-btn-delete">
                                        <i class="fas fa-trash"></i>
                                        Sil
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="task-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-cog"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Sorumlu Kişi</div>
                                <div class="meta-value">
                                    @if ($assignment->responsible)
                                        <span style="color: #2d3748;">{{ $assignment->responsible->name }}</span>
                                    @else
                                        <span style="color: #a0aec0; font-style: italic;">Atanmamış</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-edit"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Atayan Kişi</div>
                                <div class="meta-value">{{ $assignment->createdBy->name ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-car"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Araç</div>
                                <div class="meta-value">
                                    @if ($assignment->vehicle)
                                        {{-- Araç Tipine Göre Gösterim --}}
                                        @if ($assignment->vehicle instanceof \App\Models\LogisticsVehicle)
                                            🚚 {{ $assignment->vehicle->plate_number }} <small
                                                class="text-muted">({{ $assignment->vehicle->brand }})</small>
                                        @else
                                            🚙 {{ $assignment->vehicle->plate_number }}
                                        @endif
                                    @else
                                        <span class="text-danger">Silinmiş Araç</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Hedef</div>
                                <div class="meta-value">{{ $assignment->destination ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Sefer Zamanı</div>
                                <div class="meta-value">{{ $assignment->start_time->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h5>Henüz Araç Görevi Bulunmuyor</h5>
                    <p>Yeni bir araç görevi oluşturarak başlayabilirsiniz.</p>
                    <a href="{{ route('service.assignments.create') }}" class="modern-btn modern-btn-primary">
                        <i class="fas fa-plus"></i>
                        İlk Görevi Oluştur
                    </a>
                </div>
            @endforelse

            @if ($assignments->isNotEmpty() && $assignments->hasPages())
                <div class="pagination-wrapper">
                    {{ $assignments->appends($filters ?? [])->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var filterCollapse = document.getElementById('filterCollapse');
                var filterButton = document.querySelector('.modern-btn-filter');
                var filterIcon = filterButton?.querySelector('.fa-chevron-down');

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
    @endpush
@endsection
