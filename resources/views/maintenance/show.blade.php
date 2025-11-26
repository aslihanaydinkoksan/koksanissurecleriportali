@extends('layouts.app')

@section('title', 'Bakım Detayı')

@push('styles')
    <style>
        /* --- ANA TASARIM (Diğer sayfalarla ortak) --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

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

        .modern-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Kart Stilleri */
        .detail-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .detail-card:hover {
            transform: translateY(-2px);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Sayaç Kutusu (Timer Box) */
        .timer-widget {
            background: linear-gradient(145deg, #ffffff, #f5f7fa);
            border-radius: 15px;
            padding: 1.5rem;
            text-align: center;
            border: 2px solid #e9ecef;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
        }

        .timer-widget.active {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .timer-widget.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            animation: pulse-border 2s infinite;
        }

        .timer-display {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #2d3748;
            letter-spacing: 2px;
        }

        /* Timeline (Loglar) */
        .timeline {
            border-left: 2px solid rgba(102, 126, 234, 0.3);
            padding-left: 2rem;
            position: relative;
            margin-top: 1rem;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.6rem;
            top: 0.2rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: white;
            border: 3px solid #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .timeline-item.system::before {
            border-color: #adb5bd;
            box-shadow: none;
        }

        /* Dosya Listesi */
        .file-item {
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
        }

        .file-item:hover {
            background-color: #f8f9fa;
            border-color: #667eea;
        }

        /* Modern Butonlar */
        .btn-modern-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .transition-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2) !important;
        }

        /* Badge Stilleri */
        .badge-xl {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 8px;
        }

        /* Animations */
        @keyframes pulse-border {
            0% {
                opacity: 0.6;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.6;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        .toast-notification {
            background: #ffffff;
            border-left: 4px solid #ef4444;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            padding: 16px 20px;
            margin-bottom: 10px;
            min-width: 320px;
            max-width: 400px;
            animation: slideIn 0.3s ease-out;
            position: relative;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .toast-notification.hiding {
            animation: slideOut 0.3s ease-in forwards;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }

            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .toast-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .toast-icon {
            width: 24px;
            height: 24px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ef4444;
            font-size: 14px;
        }

        .toast-title {
            font-weight: 600;
            color: #111827;
            font-size: 15px;
            flex: 1;
        }

        .toast-close {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 20px;
            line-height: 1;
            padding: 0;
            width: 20px;
            height: 20px;
        }

        .toast-close:hover {
            color: #6b7280;
        }

        .toast-message {
            color: #374151;
            font-size: 14px;
            line-height: 1.5;
            margin-left: 34px;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #ef4444;
            animation: progress 5s linear forwards;
        }

        @keyframes progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid modern-container">

        {{-- Üst Navigasyon --}}
        <div class="position-relative mb-4">
            <div class="d-flex align-items-center justify-content-between p-4 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.98) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">

                {{-- Sol Taraf: Geri Dön ve Başlık --}}
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('maintenance.index') }}"
                        class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 48px; height: 48px; transition: all 0.3s ease;"
                        onmouseover="this.style.transform='translateX(-4px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.1)';"
                        onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='';">
                        <i class="fas fa-arrow-left" style="color: #6366f1; font-size: 18px;"></i>
                    </a>

                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div
                                style="width: 4px; height: 24px; background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 100%); border-radius: 2px;">
                            </div>
                            <h4 class="fw-bold mb-0" style="color: #1e293b; font-size: 24px; letter-spacing: -0.5px;">
                                Bakım Detayı
                            </h4>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-1">
                            <span class="badge rounded-pill px-3 py-1"
                                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-size: 11px; font-weight: 600; letter-spacing: 0.3px;">
                                PLAN #{{ $plan->id }}
                            </span>
                            <span style="color: #64748b; font-size: 13px; font-weight: 500;">
                                Detayları görüntülüyorsunuz
                            </span>
                        </div>
                    </div>
                </div>

                <div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>
                {{-- Sağ Taraf: Düzenle Butonu --}}
                @can('update', $plan)
                    {{-- YETKİLİ İSE: Aktif Buton --}}
                    <a href="{{ route('maintenance.edit', $plan->id) }}"
                        class="btn btn-primary border-0 shadow-sm px-4 py-2 rounded-3 d-flex align-items-center gap-2"
                        style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-weight: 600; font-size: 14px; letter-spacing: 0.3px; transition: all 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 24px rgba(99, 102, 241, 0.3)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                        <i class="fas fa-edit" style="font-size: 14px;"></i>
                        <span>Düzenle</span>
                    </a>
                @else
                    {{-- YETKİSİZ İSE: Pasif (Disabled) Görünümlü ve Uyarılı Buton --}}
                    <button type="button"
                        class="btn btn-secondary border-0 shadow-sm px-4 py-2 rounded-3 d-flex align-items-center gap-2"
                        style="background: #94a3b8; cursor: not-allowed; opacity: 0.8;" onclick="showToast()">
                        <i class="fas fa-lock" style="font-size: 14px;"></i>
                        <span>Düzenle</span>
                    </button>
                @endcan
            </div>
        </div>

        <style>
            .btn-white:hover {
                background: #f8fafc !important;
            }
        </style>

        <div class="row">
            {{-- SOL SÜTUN: Bilgiler ve Sayaç --}}
            <div class="col-lg-4">

                {{-- 1. Kart: Özet Bilgi --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-info-circle me-2"></i>Genel Bilgiler</span>
                    </div>
                    <div class="p-4">
                        <div class="mb-3">
                            <h5 class="fw-bold text-dark mb-1">{{ $plan->title }}</h5>
                            <span class="badge bg-light text-secondary border mt-1">{{ $plan->type->name }}</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="badge badge-xl bg-{{ $plan->status_color }} shadow-sm">
                                {{ $plan->status_label }}
                            </span>
                            <span class="text-muted small">
                                <i class="fas fa-user-circle me-1"></i> {{ $plan->user->name }}
                            </span>
                        </div>

                        <ul class="list-group list-group-flush rounded-3 border-0">
                            <li class="list-group-item border-0 px-0 d-flex align-items-center bg-transparent">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 text-primary">
                                    <i class="fas fa-microchip"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Varlık / Makine</small>
                                    <strong>{{ $plan->asset->name }}</strong>
                                </div>
                            </li>
                            <li class="list-group-item border-0 px-0 d-flex align-items-center bg-transparent">
                                <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3 text-danger">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Konum</small>
                                    <strong>{{ $plan->asset->location ?? 'Belirtilmemiş' }}</strong>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-4 p-3 bg-light rounded-3 border">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Planlanan Başlangıç:</small>
                                <small class="fw-bold">{{ $plan->planned_start_date->format('d.m.Y H:i') }}</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Planlanan Bitiş:</small>
                                <small class="fw-bold">{{ $plan->planned_end_date->format('d.m.Y H:i') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Kart: SAYAÇ (Action Zone) --}}
                <div class="detail-card">
                    <div class="p-4">
                        <h6 class="fw-bold mb-3 text-uppercase text-muted small ls-1">
                            <i class="fas fa-stopwatch me-2 text-primary"></i>İşlem Sayacı
                        </h6>

                        @if ($plan->isTimerActive())
                            {{-- SAYAÇ AKTİF --}}
                            <div class="timer-widget active">
                                <div class="text-primary fw-bold mb-2 animate-pulse">
                                    <i class="fas fa-circle me-1 small"></i> ÇALIŞMA SÜRÜYOR
                                </div>
                                <h2 class="display-5 timer-display mb-2" id="liveTimer">00:00:00</h2>
                                <small class="text-muted d-block mb-3">
                                    Başlangıç:
                                    {{ $plan->timeEntries->whereNull('end_time')->first()->start_time->format('H:i:s') }}
                                </small>

                                <button type="button" class="btn btn-danger w-100 py-2 rounded-3 shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#stopTimerModal">
                                    <i class="fas fa-stop-circle me-2"></i>Çalışmayı Durdur
                                </button>
                            </div>
                        @else
                            {{-- SAYAÇ PASİF --}}
                            @if ($plan->status != 'completed')
                                <div class="timer-widget">
                                    <div class="text-muted mb-1 small">Toplam Çalışma Süresi</div>
                                    <h2 class="display-6 timer-display mb-3 text-secondary">
                                        {{ sprintf('%02d:%02d:00', floor($plan->previous_duration_minutes / 60), $plan->previous_duration_minutes % 60) }}
                                    </h2>

                                    <form action="{{ route('maintenance.start-timer', $plan->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success w-100 py-2 rounded-3 shadow-sm">
                                            <i class="fas fa-play me-2"></i>
                                            @if ($plan->previous_duration_minutes > 0)
                                                Çalışmaya Devam Et
                                            @else
                                                Çalışmayı Başlat
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            @else
                                {{-- TAMAMLANDI --}}
                                <div
                                    class="alert alert-success border-0 bg-success bg-opacity-10 text-success text-center py-4 rounded-3">
                                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                                    <h5 class="fw-bold">Bakım Tamamlandı</h5>
                                    <p class="mb-0">
                                        Toplam Süre: <br>
                                        <strong>{{ floor($plan->previous_duration_minutes / 60) }} saat
                                            {{ $plan->previous_duration_minutes % 60 }} dakika</strong>
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- SAĞ SÜTUN: İçerik --}}
            <div class="col-lg-8">

                {{-- Açıklama --}}
                <div class="detail-card">
                    <div class="card-header-custom">
                        <span><i class="fas fa-align-left me-2"></i>İş Emri Detayları</span>
                    </div>
                    <div class="p-4">
                        <p class="text-secondary mb-0" style="white-space: pre-line; line-height: 1.6;">
                            {{ $plan->description ?? 'Herhangi bir açıklama girilmemiş.' }}
                        </p>
                    </div>
                </div>

                {{-- Dosyalar --}}
                <div class="detail-card">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0 text-dark">
                                <i class="fas fa-paperclip me-2 text-primary"></i>Ekli Dosyalar
                            </h5>
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal"
                                data-bs-target="#uploadModal">
                                <i class="fas fa-plus me-1"></i> Dosya Ekle
                            </button>
                        </div>

                        @if ($plan->files->count() > 0)
                            <div class="row g-3">
                                @foreach ($plan->files as $file)
                                    <div class="col-md-6">
                                        <div
                                            class="file-item p-3 bg-white rounded-3 d-flex justify-content-between align-items-center h-100">
                                            <div class="d-flex align-items-center text-truncate">
                                                <div class="bg-light p-2 rounded me-3 text-secondary">
                                                    <i class="fas fa-file-alt fa-lg"></i>
                                                </div>
                                                <div class="text-truncate">
                                                    <a href="{{ url($file->file_path) }}" target="_blank"
                                                        class="text-decoration-none text-dark fw-bold text-truncate d-block">
                                                        {{ $file->file_name }}
                                                    </a>
                                                    <small
                                                        class="text-muted">{{ $file->created_at->format('d.m.Y') }}</small>
                                                </div>
                                            </div>
                                            <form action="{{ route('maintenance.delete-file', $file->id) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-link text-danger p-0 ms-2 opacity-50 hover-opacity-100"
                                                    title="Sil">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded-3 border border-dashed">
                                <i class="fas fa-folder-open text-muted fa-2x mb-2 opacity-50"></i>
                                <p class="text-muted small mb-0">Henüz dosya yüklenmemiş.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tarihçe / Loglar --}}
                <div class="detail-card">
                    <div class="p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-4 text-dark">
                            <i class="fas fa-history me-2 text-primary"></i>İşlem Tarihçesi
                        </h5>
                        <div class="timeline">
                            @foreach ($plan->logs->sortByDesc('created_at') as $log)
                                <div class="timeline-item">
                                    <div class="d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <strong class="text-dark"
                                                style="font-size: 1.05rem;">{{ $log->user->name }}</strong>
                                            <small
                                                class="text-muted bg-light px-2 py-1 rounded">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-secondary bg-light p-2 rounded border-start border-4 border-primary bg-opacity-10"
                                            style="font-size: 0.95rem;">
                                            {{ $log->description }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="timeline-item system">
                                <div class="d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <strong class="text-muted">Sistem</strong>
                                        <small class="text-muted">{{ $plan->created_at->format('d.m.Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted fst-italic">Plan oluşturuldu.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODALS (Tasarım güncellemeleriyle) --}}

    {{-- 1. Sayaç Durdurma Modalı --}}
    <div class="modal fade" id="stopTimerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="{{ route('maintenance.stop-timer', $plan->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Çalışmayı Durdur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            Çalışma süresi sisteme kaydedilecek.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Yapılan İşlem Notu (Opsiyonel)</label>
                            <textarea name="note" class="form-control bg-light" rows="3"
                                placeholder="Örn: Parça beklendiği için ara verildi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-danger px-4">Durdur ve Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 2. Dosya Yükleme Modalı  --}}
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('maintenance.upload-file', $plan->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Dosya Yükle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fileInput" class="form-label fw-bold">Dosya(lar) Seçin</label>
                            <input type="file" id="fileInput" name="files[]" class="form-control" multiple required>

                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Birden fazla dosya seçmek için <strong>CTRL</strong> tuşuna basılı tutarak tıklayın.
                                <br> (İzin verilenler: PDF, Resim, Excel, Word. Max: 50MB)
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-cloud-upload-alt me-2"></i> Yükle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($plan->isTimerActive())
                // Sayaç Mantığı (Değişmedi, sadece ID'ler kontrol edildi)
                const startTimeString =
                    "{{ $plan->timeEntries->whereNull('end_time')->first()->start_time->format('Y-m-d H:i:s') }}";
                const startTime = new Date(startTimeString).getTime();
                const previousDurationMs = {{ $plan->previous_duration_minutes * 60 * 1000 }};
                const timerElement = document.getElementById("liveTimer");

                if (timerElement) {
                    const x = setInterval(function() {
                        const now = new Date().getTime();
                        const currentSessionDuration = now - startTime;
                        const totalDuration = previousDurationMs + currentSessionDuration;

                        const hours = Math.floor((totalDuration / (1000 * 60 * 60)));
                        const minutes = Math.floor((totalDuration % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((totalDuration % (1000 * 60)) / 1000);

                        timerElement.innerHTML =
                            (hours < 10 ? "0" + hours : hours) + ":" +
                            (minutes < 10 ? "0" + minutes : minutes) + ":" +
                            (seconds < 10 ? "0" + seconds : seconds);
                    }, 1000);
                }
            @endif
        });

        function showToast() {
            const container = document.getElementById('toastContainer');

            const toast = document.createElement('div');
            toast.className = 'toast-notification';

            toast.innerHTML = `
        <div class="toast-header">
            <div class="toast-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="toast-title">Yetkisiz İşlem</div>
            <button class="toast-close" onclick="closeToast(this)">×</button>
        </div>
        <div class="toast-message">
            Bu planı düzenleme yetkiniz bulunmuyor.<br>
            Sadece Admin, Departman Yöneticisi veya Oluşturan kişi düzenleyebilir.
        </div>
        <div class="toast-progress"></div>
    `;

            container.appendChild(toast);

            // 5 saniye sonra otomatik kapan
            setTimeout(() => {
                closeToast(toast.querySelector('.toast-close'));
            }, 5000);
        }

        function closeToast(button) {
            const toast = button.closest('.toast-notification');
            toast.classList.add('hiding');

            setTimeout(() => {
                toast.remove();
            }, 300);
        }
    </script>
@endsection
