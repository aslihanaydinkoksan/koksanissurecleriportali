resources\views\service\assignments\assigned_by_me.blade.php:

@extends('layouts.app')
@section('title', 'Atadığım Görevler')

@push('styles')
    <style>
        /* Mavi/Mor Tema Başlangıcı */
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

        .modern-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
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

        /* Kartın solundaki şerit rengi */
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

        /* Başlık ikon rengi */
        .task-title i {
            color: #667eea;
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

        /* Meta ikon kutuları */
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

        .meta-value.allow-overflow {
            overflow: visible !important;
            white-space: normal !important;
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

        /* Buton renkleri - Mavi tema */
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
            background: linear-gradient(135deg, #f6ad55 0%, #ed8936 100%);
            color: white;
        }

        .modern-btn-edit:hover {
            background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
            color: white;
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

        .responsible-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #667eea;
        }

        .responsible-badge i {
            font-size: 0.8rem;
        }

        /* Araç Badge'i - Mavi temaya uygun revize edildi */
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
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="assigned-tasks-header fade-in">
            <div class="header-content">
                <h4>
                    <div class="icon-wrapper">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <div>
                        Atadığım Görevler
                        <small
                            style="display: block; font-size: 0.9rem; font-weight: 400; opacity: 0.9; margin-top: 0.25rem;">
                            Başkalarına verdiğim görevleri takip edin
                        </small>
                    </div>
                </h4>
                <div class="stats">
                    <div class="stat-item">
                        <strong>{{ $assignments->total() }}</strong>
                        Toplam Atama
                    </div>
                </div>
            </div>
        </div>

        <div class="modern-card fade-in" style="animation-delay: 0.2s;">
            @forelse($assignments as $index => $assignment)
                <div class="task-card" style="animation-delay: {{ 0.3 + $index * 0.05 }}s;">
                    <div class="task-header">
                        <h5 class="task-title">
                            <i class="fas fa-clipboard-check"></i>
                            {{ $assignment->title }}
                        </h5>
                        <div class="action-buttons">
                            <a href="{{ route('service.assignments.show', $assignment) }}"
                                class="modern-btn modern-btn-view">
                                <i class="fas fa-eye"></i>
                                Görüntüle
                            </a>
                            <a href="{{ route('service.assignments.edit', $assignment) }}"
                                class="modern-btn modern-btn-edit">
                                <i class="fas fa-edit"></i>
                                Düzenle
                            </a>
                        </div>
                    </div>

                    <div class="task-meta">
                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Atanan Kişi/Takım</div>
                                <div class="meta-value">
                                    @if ($assignment->responsible)
                                        <div class="responsible-badge">
                                            <i
                                                class="fas fa-{{ $assignment->responsible_type === 'App\Models\User' ? 'user' : 'users' }}"></i>
                                            {{ $assignment->responsible->name }}
                                        </div>
                                    @else
                                        <span style="color: #e53e3e; font-weight: 600;">
                                            <i class="fas fa-exclamation-triangle"></i> Sorumlu Yok
                                        </span>
                                    @endif
                                </div>
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
                                        <div class="vehicle-badge">
                                            <i class="fas fa-car"></i>
                                            {{ $assignment->vehicle->plate_number }}
                                        </div>
                                    @else
                                        <span style="color: #a0aec0;">Araç Yok</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Başlangıç Zamanı</div>
                                <div class="meta-value">{{ $assignment->start_time->format('d.m.Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="meta-item">
                            <div class="meta-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Durum</div>
                                <div class="meta-value">
                                    <span class="status-badge bg-{{ $assignment->status_badge }}">
                                        {{ $assignment->status_name }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"
                                style="{{ $assignment->files->count() > 0 ? 'background: rgba(79, 172, 254, 0.1); color: #4facfe;' : 'background: #f7fafc; color: #cbd5e0;' }}">
                                <i class="fas fa-paperclip"></i>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Ekli Dosyalar</div>
                                <div class="meta-value allow-overflow">
                                    @if ($assignment->files->count() > 0)
                                        <div class="dropdown">
                                            <a href="#" class="text-decoration-none dropdown-toggle fw-bold"
                                                style="color: #4facfe;" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ $assignment->files->count() }} Dosya Görüntüle
                                            </a>
                                            <ul class="dropdown-menu shadow-lg border-0 p-2"
                                                style="border-radius: 12px; min-width: 250px; z-index: 1050;">
                                                <li class="dropdown-header fw-bold small py-1" style="color: #4facfe;">
                                                    DOSYALAR</li>
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                @foreach ($assignment->files as $file)
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
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h5>Henüz Görev Atamadınız</h5>
                    <p>Takım üyelerine veya araçlara görev atayarak başlayabilirsiniz.</p>
                </div>
            @endforelse

            @if ($assignments->isNotEmpty() && $assignments->hasPages())
                <div class="pagination-wrapper">
                    {{ $assignments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
