@extends('layouts.app')
@section('title', 'Bana Atanan Görevler')

@push('styles')
    <style>
        /* Mavi/Mor Tema - assigned_by_me ile aynı */
        .assigned-tasks-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: relative;
            overflow: hidden;
        }

        .assigned-tasks-header::before {
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

        .assigned-tasks-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .assigned-tasks-header .icon-wrapper {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .assigned-tasks-header .icon-wrapper i {
            font-size: 1.8rem;
            color: white;
        }

        .assigned-tasks-header .header-content {
            position: relative;
            z-index: 1;
        }

        .assigned-tasks-header .stats {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
        }

        .assigned-tasks-header .stat-item {
            color: rgba(255, 255, 255, 0.95);
            font-size: 0.9rem;
        }

        .assigned-tasks-header .stat-item strong {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
        }

        /* Filtre Bölümü */
        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
        }

        .filter-section .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-section .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .filter-section .btn-outline-secondary {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.6rem 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-section .btn-outline-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
        }

        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: visible !important;
            transition: all 0.3s ease;
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
            z-index: 1;
            overflow: visible !important;
        }

        /* Kartın solundaki şerit rengi - Duruma göre dinamik */
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
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            z-index: 2;
        }

        .task-card.status-pending::before {
            background: linear-gradient(135deg, #ecc94b 0%, #f6ad55 100%);
        }

        .task-card.status-in_progress::before {
            background: linear-gradient(135deg, #4299e1 0%, #667eea 100%);
        }

        .task-card.status-completed::before {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        }

        .task-card.status-cancelled::before {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
        }

        .task-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-4px);
            border-color: rgba(102, 126, 234, 0.3);
            z-index: 100 !important;
        }

        .task-card:hover::before {
            transform: scaleY(1);
        }

        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
            gap: 1rem;
        }

        .task-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
        }

        .task-title i {
            color: #667eea;
            font-size: 1rem;
        }

        /* Görev Türü Badge */
        .task-type-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .task-type-badge.vehicle {
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            color: #667eea;
        }

        .task-type-badge.general {
            background: linear-gradient(135deg, #e2e8f015 0%, #cbd5e015 100%);
            color: #718096;
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
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
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

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .status-badge::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .status-badge.bg-warning {
            background: linear-gradient(135deg, #ecc94b 0%, #f6ad55 100%);
            color: #7c4a03;
        }

        .status-badge.bg-primary {
            background: linear-gradient(135deg, #4299e1 0%, #667eea 100%);
            color: white;
        }

        .status-badge.bg-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            color: white;
        }

        .status-badge.bg-danger {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            color: white;
        }

        .status-badge.bg-info {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
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

        .modern-btn-view {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .modern-btn-view:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .modern-btn-edit {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }

        .modern-btn-edit:hover {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(66, 153, 225, 0.4);
            color: white;
        }

        .creator-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4338ca;
        }

        .creator-badge i {
            font-size: 0.8rem;
        }

        .vehicle-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4338ca;
        }

        .vehicle-badge i {
            font-size: 0.8rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .empty-state-icon i {
            font-size: 3rem;
            color: #667eea;
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

        .empty-state .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .empty-state .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .pagination-wrapper {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
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

        @media (max-width: 768px) {
            .assigned-tasks-header {
                padding: 1.5rem;
            }

            .assigned-tasks-header h4 {
                font-size: 1.3rem;
            }

            .assigned-tasks-header .stats {
                flex-direction: column;
                gap: 1rem;
            }

            .task-card {
                padding: 1rem;
            }

            .task-header {
                flex-direction: column;
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

            .filter-section {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="assigned-tasks-header fade-in">
            <div class="header-content">
                <h4>
                    <div class="icon-wrapper">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div>
                        Bana Atanan Görevler
                        <small
                            style="display: block; font-size: 0.9rem; font-weight: 400; opacity: 0.9; margin-top: 0.25rem;">
                            Sorumluluğunuzdaki tüm araçlı ve genel görevler
                        </small>
                    </div>
                </h4>
                <div class="stats">
                    <div class="stat-item">
                        <strong>{{ $tasks->total() }}</strong>
                        Toplam Görev
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtre Bölümü --}}
        <div class="filter-section fade-in" style="animation-delay: 0.1s;">
            <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
                <div class="d-flex align-items-center gap-2">
                    <label for="status-filter" class="text-muted fw-semibold mb-0" style="white-space: nowrap;">
                        <i class="fas fa-filter me-1"></i> Durum Filtresi:
                    </label>
                    <select name="status" id="status-filter" class="form-select form-select-sm"
                        onchange="this.form.submit()" style="min-width: 180px;">
                        <option value="">Tüm Durumlar</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Bekleyenler</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>Devam Edenler
                        </option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Tamamlananlar
                        </option>
                    </select>
                </div>

                @if (request()->has('status') || request()->has('type'))
                    <a href="{{ route('my-assignments.index') }}" class="btn btn-sm btn-outline-secondary"
                        title="Filtreleri Temizle">
                        <i class="fa-solid fa-times me-1"></i> Temizle
                    </a>
                @endif
            </form>
        </div>

        <div class="modern-card fade-in" style="animation-delay: 0.2s;">
            @forelse($tasks as $index => $task)
                @php
                    $statusClass = match ($task->status) {
                        'completed' => 'status-completed',
                        'in_progress' => 'status-in_progress',
                        'cancelled' => 'status-cancelled',
                        default => 'status-pending',
                    };
                @endphp

                <div class="task-card {{ $statusClass }}" style="animation-delay: {{ 0.3 + $index * 0.05 }}s;">
                    <div class="task-header">
                        <div style="flex: 1;">
                            {{-- Görev Türü Badge --}}
                            @if ($task->assignment_type === 'vehicle')
                                <span class="task-type-badge vehicle">
                                    <i class="fa-solid fa-car"></i> Araç Görevi
                                </span>
                            @else
                                <span class="task-type-badge general">
                                    <i class="fa-solid fa-clipboard-check"></i> Genel Görev
                                </span>
                            @endif

                            {{-- Başlık --}}
                            <h5 class="task-title">
                                <i class="fas fa-clipboard-check"></i>
                                {{ $task->title }}
                            </h5>

                            {{-- Açıklama --}}
                            @if ($task->task_description)
                                <p class="text-muted mb-0 mt-2" style="font-size: 0.9rem;">
                                    {{ Str::limit($task->task_description, 100) }}
                                </p>
                            @endif
                        </div>

                        <div class="action-buttons">
                            <a href="{{ route('service.assignments.show', $task->id) }}" class="modern-btn modern-btn-view">
                                <i class="fas fa-eye"></i>
                                Detay
                            </a>
                            @if (!in_array($task->status, ['completed', 'cancelled']))
                                <a href="{{ route('service.assignments.edit', $task->id) }}"
                                    class="modern-btn modern-btn-edit">
                                    <i class="fas fa-pen-to-square"></i>
                                    Güncelle
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="task-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Başlangıç Zamanı</div>
                                <div class="meta-value">{{ $task->start_time->format('d.m.Y H:i') }}</div>
                                <small class="text-muted" style="font-size: 0.8rem;">
                                    <i class="far fa-clock me-1"></i>{{ $task->start_time->diffForHumans() }}
                                </small>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Durum</div>
                                <div class="meta-value">
                                    @if ($task->status == 'pending')
                                        <span class="status-badge bg-warning">
                                            <i class="fas fa-clock"></i> Bekliyor
                                        </span>
                                    @elseif($task->status == 'in_progress')
                                        <span class="status-badge bg-primary">
                                            <i class="fas fa-spinner fa-spin"></i> Devam Ediyor
                                        </span>
                                    @elseif($task->status == 'completed')
                                        <span class="status-badge bg-success">
                                            <i class="fas fa-check-circle"></i> Tamamlandı
                                        </span>
                                    @elseif($task->status == 'waiting_assignment')
                                        <span class="status-badge bg-info">
                                            <i class="fas fa-hourglass-half"></i> Atama Bekliyor
                                        </span>
                                    @else
                                        <span class="status-badge bg-danger">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($task->vehicle)
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="meta-content">
                                    <div class="meta-label">Araç</div>
                                    <div class="meta-value">
                                        <div class="vehicle-badge">
                                            <i class="fas fa-car"></i>
                                            {{ $task->vehicle->plate_number }}
                                        </div>
                                        @if ($task->vehicle->brand_model)
                                            <small class="text-muted d-block mt-1" style="font-size: 0.8rem;">
                                                {{ $task->vehicle->brand_model }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Atayan Kişi</div>
                                <div class="meta-value">
                                    <div class="creator-badge">
                                        <i class="fas fa-user"></i>
                                        {{ $task->createdBy->name ?? 'Sistem' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"
                                style="{{ $task->files->count() > 0 ? 'background: rgba(79, 172, 254, 0.1); color: #4facfe;' : 'background: #f7fafc; color: #cbd5e0;' }}">
                                <i class="fas fa-paperclip"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Ekli Dosyalar</div>
                                <div class="meta-value allow-overflow">
                                    @if ($task->files->count() > 0)
                                        <div class="dropdown">
                                            <a href="#" class="text-decoration-none dropdown-toggle fw-bold"
                                                style="color: #4facfe;" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $task->files->count() }} Dosya Görüntüle
                                            </a>
                                            <ul class="dropdown-menu shadow-lg border-0 p-2"
                                                style="border-radius: 12px; min-width: 250px; z-index: 1050;">
                                                <li class="dropdown-header fw-bold small py-1" style="color: #4facfe;">
                                                    DOSYALAR</li>
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                @foreach ($task->files as $file)
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center justify-content-between rounded-2 py-2"
                                                            href="{{ route('files.download', $file->id) }}"
                                                            target="_blank">
                                                            <div class="d-flex align-items-center text-truncate me-2"
                                                                style="max-width: 150px;">
                                                                @if (Str::contains($file->mime_type, 'image'))
                                                                    <i
                                                                        class="fa-regular fa-file-image me-2 text-success"></i>
                                                                @elseif(Str::contains($file->mime_type, 'pdf'))
                                                                    <i class="fa-regular fa-file-pdf me-2 text-danger"></i>
                                                                @else
                                                                    <i class="fa-regular fa-file me-2 text-muted"></i>
                                                                @endif
                                                                <span
                                                                    class="text-truncate">{{ $file->original_name }}</span>
                                                            </div>
                                                            <i
                                                                class="fa-solid fa-download text-secondary small opacity-50"></i>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-weight: 400; font-size: 0.9rem;">
                                            Dosya Yok
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-mug-hot"></i>
                    </div>
                    <h5>Harika! İşler Yolunda.</h5>
                    <p>Şu anda size atanmış bekleyen veya aktif bir görev bulunmuyor.</p>
                    <a href="{{ route('welcome') }}" class="btn">
                        <i class="fas fa-home me-2"></i>Ana Sayfaya Dön
                    </a>
                </div>
            @endforelse

            @if ($tasks->isNotEmpty() && $tasks->hasPages())
                <div class="pagination-wrapper">
                    {{ $tasks->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
