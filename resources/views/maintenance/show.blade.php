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

        .btn-white:hover {
            background: #f8fafc !important;
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
            <div class="d-flex align-items-center justify-content-between p-3 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.98) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">

                {{-- SOL TARAF: Geri Dön, Başlık ve Rozet --}}
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
                                PLAN "{{ $plan->title }}"
                            </span>
                            <span style="color: #64748b; font-size: 13px; font-weight: 500;">
                                Detayları görüntülüyorsunuz
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SAĞ TARAF: Onay Durumu ve Aksiyon Butonları --}}
                <div class="d-flex align-items-center gap-3">

                    {{-- 1. ONAY/DURUM KUTUSU --}}
                    @if ($plan->status == 'pending_approval')
                        <div
                            class="d-flex align-items-center px-3 py-2 rounded-3 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                            @can('approve', $plan)
                                {{-- YÖNETİCİ: Hem uyarı metni hem butonlar yanyana --}}
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center text-warning-emphasis">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span class="small fw-bold lh-1">Onay Bekliyor</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        {{-- REDDET --}}
                                        <form action="{{ route('maintenance.update', $plan->id) }}" method="POST"
                                            onsubmit="return confirm('Bu planı reddedip personele geri göndermek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit"
                                                class="btn btn-danger btn-sm fw-bold text-white shadow-sm px-3" title="Reddet">
                                                <i class="fas fa-undo-alt"></i> Reddet
                                            </button>
                                        </form>

                                        {{-- ONAYLA --}}
                                        <form action="{{ route('maintenance.update', $plan->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="completed">
                                            <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm px-3">
                                                <i class="fas fa-check me-1"></i> Onayla
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                {{-- STANDART KULLANICI --}}
                                <div class="d-flex align-items-center text-warning-emphasis">
                                    <i class="fas fa-clock me-2"></i>
                                    <div>
                                        <strong class="d-block small">Yönetici Onayı Bekleniyor</strong>
                                        <span style="font-size: 11px; opacity: 0.8;">İşlem tamamlandı, onay bekleniyor.</span>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    @endif

                    {{-- 2. DÜZENLEME BUTONU --}}
                    @if ($plan->status === 'completed' && Auth::user()->cannot('approve', $plan))
                        <button type="button"
                            class="btn btn-secondary border-0 shadow-sm px-4 py-2 rounded-3 d-flex align-items-center gap-2"
                            style="background: #a4a6a8; cursor: not-allowed; opacity: 0.8;"
                            onclick="alert('İşlem Engellendi!\n\nBu plan tamamlanmıştır. Değişiklik yapmak için yöneticinizle görüşün.')">
                            <i class="fas fa-lock" style="font-size: 14px;"></i>
                            <span>Düzenle</span>
                        </button>
                    @else
                        @can('update', $plan)
                            <a href="{{ route('maintenance.edit', $plan->id) }}"
                                class="btn btn-primary border-0 shadow-sm px-4 py-2 rounded-3 d-flex align-items-center gap-2"
                                style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); font-weight: 600; font-size: 14px; letter-spacing: 0.3px; transition: all 0.3s ease;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 24px rgba(99, 102, 241, 0.3)';"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                                <i class="fas fa-edit" style="font-size: 14px;"></i>
                                <span>Düzenle</span>
                            </a>
                        @else
                            <button type="button"
                                class="btn btn-secondary border-0 shadow-sm px-4 py-2 rounded-3 d-flex align-items-center gap-2"
                                style="background: #94a3b8; cursor: not-allowed; opacity: 0.8;"
                                onclick="alert('Bu planı düzenleme yetkiniz bulunmuyor.\nSadece Admin, Departman Yöneticisi veya Oluşturan kişi düzenleyebilir.')">
                                <i class="fas fa-ban" style="font-size: 14px;"></i>
                                <span>Düzenle</span>
                            </button>
                        @endcan
                    @endif
                </div>
            </div>
        </div>

        {{-- REDDEDİLME BİLGİLENDİRMESİ (NAVBAR DIŞINDA KALMALI) --}}
        @php
            $lastLog = $plan->logs->sortByDesc('created_at')->first();
        @endphp

        @if (
            $plan->status == 'in_progress' &&
                $lastLog &&
                $lastLog->action == 'rejected' &&
                Auth::user()->cannot('approve', $plan))
            <div class="alert alert-danger d-flex align-items-center shadow-sm mb-4">
                <i class="fas fa-undo-alt fa-2x me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">İşlem Reddedildi / Geri Gönderildi</h6>
                    <p class="mb-0 small">
                        Yöneticiniz bu planı onaylamadı ve size geri gönderdi. Lütfen eksikleri tamamlayıp tekrar onaya
                        sununuz.
                        <br>
                        <span class="fst-italic text-opacity-75">({{ $lastLog->created_at->diffForHumans() }}
                            reddedildi)</span>
                    </p>
                </div>
            </div>
        @endif
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
                            {{-- PLANLANAN BAŞLANGIÇ --}}
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom border-light-subtle">
                                <div class="d-flex flex-column">
                                    <small class="text-muted fw-semibold"
                                        style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">Planlanan
                                        Başlangıç</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="far fa-calendar me-1 text-muted opacity-50"></i>
                                        {{ $plan->planned_start_date->format('d.m.Y') }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 12px;">
                                        <i class="far fa-clock me-1 text-muted opacity-50"></i>
                                        {{ $plan->planned_start_date->format('H:i') }}
                                    </div>
                                </div>
                            </div>

                            {{-- PLANLANAN BİTİŞ --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex flex-column">
                                    <small class="text-muted fw-semibold"
                                        style="font-size: 11px; letter-spacing: 0.5px; text-transform: uppercase;">Planlanan
                                        Bitiş</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-dark fs-6">
                                        <i class="far fa-calendar me-1 text-muted opacity-50"></i>
                                        {{ $plan->planned_end_date->format('d.m.Y') }}
                                    </div>
                                    <div class="text-muted small" style="font-size: 12px;">
                                        <i class="far fa-clock me-1 text-muted opacity-50"></i>
                                        {{ $plan->planned_end_date->format('H:i') }}
                                    </div>
                                </div>
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

                        @php
                            // Sadece AÇIK olan sayacı buluyoruz. Kimin olduğu önemsiz.
                            $activeEntry = $plan->timeEntries->whereNull('end_time')->first();
                        @endphp

                        @if ($activeEntry)
                            {{-- DURUM 1: SAYAÇ AKTİF (KİMİN OLDUĞU FARK ETMEZ, GÖRÜNSÜN) --}}
                            <div class="timer-widget active">
                                <div class="text-primary fw-bold mb-2 animate-pulse">
                                    <i class="fas fa-circle me-1 small"></i> ÇALIŞMA SÜRÜYOR
                                    {{-- Çalışan kişinin ismini de ufakça belirtelim ki karışıklık olmasın --}}
                                    <span class="text-muted small fw-normal">({{ $activeEntry->user->name }})</span>
                                </div>

                                {{-- CANLI SAYAÇ ALANI --}}
                                <h2 class="display-5 timer-display mb-2" id="liveTimer">00:00:00</h2>

                                <small class="text-muted d-block mb-3">
                                    Başlangıç: {{ $activeEntry->start_time->format('H:i:s') }}
                                </small>

                                {{-- DURDURMA BUTONU --}}
                                {{-- Sadece sayacı başlatan kişi veya Admin durdurabilsin (Controller yetkisine bağlı) --}}
                                @if (Auth::id() == $activeEntry->user_id || Auth::user()->role == 'admin')
                                    <button type="button" class="btn btn-danger w-100 py-2 rounded-3 shadow-sm"
                                        data-bs-toggle="modal" data-bs-target="#stopTimerModal">
                                        <i class="fas fa-stop-circle me-2"></i>Çalışmayı Durdur
                                    </button>
                                @else
                                    <div class="alert alert-light border small text-muted">
                                        <i class="fas fa-lock me-1"></i> Sadece başlatan kişi durdurabilir.
                                    </div>
                                @endif
                            </div>
                        @elseif ($plan->status == 'pending_approval')
                            {{-- DURUM 2: ONAY BEKLİYOR --}}
                            <div class="timer-widget bg-light border-warning">
                                <div class="text-warning fw-bold mb-3">
                                    <i class="fas fa-hourglass-half fa-3x mb-2"></i><br>
                                    ONAY SÜRECİNDE
                                </div>
                                <div class="mt-3 pt-3 border-top border-warning border-opacity-25">
                                    <strong class="text-dark d-block mb-1">Kaydedilen Toplam Süre</strong>
                                    <span class="fs-5 font-monospace text-secondary">
                                        {{ floor($plan->previous_duration_minutes / 60) }} sa
                                        {{ $plan->previous_duration_minutes % 60 }} dk
                                    </span>
                                </div>
                            </div>
                        @elseif ($plan->status == 'completed')
                            {{-- DURUM 3: TAMAMLANDI --}}
                            <div
                                class="alert alert-success border-0 bg-success bg-opacity-10 text-success text-center py-4 rounded-3 mb-0">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <h5 class="fw-bold">Bakım Tamamlandı</h5>
                                <p class="mb-0">
                                    Toplam Süre: <br>
                                    <strong>{{ floor($plan->previous_duration_minutes / 60) }} saat
                                        {{ $plan->previous_duration_minutes % 60 }} dakika</strong>
                                </p>
                            </div>
                        @else
                            {{-- DURUM 4: KİMSE ÇALIŞMIYOR -> BAŞLAT --}}
                            <div class="timer-widget">
                                <div class="text-muted mb-1 small">Toplam Çalışma Süresi</div>
                                <h2 class="display-6 timer-display mb-3 text-secondary">
                                    {{ sprintf('%02d:%02d:00', floor($plan->previous_duration_minutes / 60), $plan->previous_duration_minutes % 60) }}
                                </h2>

                                <div class="d-grid gap-2">
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

                                    @if ($plan->previous_duration_minutes > 0)
                                        <button type="button"
                                            class="btn btn-outline-primary w-100 py-2 rounded-3 border-2 fw-bold"
                                            data-bs-toggle="modal" data-bs-target="#quickFinishModal">
                                            <i class="fas fa-check-double me-2"></i>
                                            Sayaçsız Tamamla
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                {{-- 3. Kart: ZAMANLAMA VE PERFORMANS ANALİZİ (GENİŞLETİLMİŞ VERSİYON) --}}
                @if ($plan->actual_end_date && ($plan->status == 'completed' || $plan->status == 'pending_approval'))
                    <div class="detail-card">
                        <div class="card-header-custom bg-gradient-dark text-white">
                            <span><i class="fas fa-chart-line me-2"></i>Zamanlama ve Performans Analizi</span>
                        </div>
                        <div class="p-4">

                            {{-- 1. BÖLÜM: TARİHLER (ÜSTTE VE GENİŞ) --}}
                            <div class="d-flex justify-content-between align-items-center mb-4 px-2">

                                {{-- Planlanan --}}
                                <div class="text-center">
                                    <small class="text-uppercase text-secondary fw-bold"
                                        style="font-size: 0.7rem; letter-spacing: 1px;">
                                        PLANLANAN
                                    </small>
                                    <div class="mt-2">
                                        <div class="fw-bold text-dark fs-4" style="line-height: 1;">
                                            {{ $plan->planned_end_date->format('d.m.Y') }}
                                        </div>
                                        <div class="text-muted fw-bold mt-1">
                                            {{ $plan->planned_end_date->format('H:i') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Ok İşareti --}}
                                <div class="text-muted opacity-25">
                                    <i class="fas fa-chevron-right fa-2x"></i>
                                </div>

                                {{-- Gerçekleşen --}}
                                <div class="text-center">
                                    <small class="text-uppercase text-secondary fw-bold"
                                        style="font-size: 0.7rem; letter-spacing: 1px;">
                                        GERÇEKLEŞEN
                                    </small>
                                    <div class="mt-2">
                                        <div class="fw-bold text-dark fs-4" style="line-height: 1;">
                                            {{ $plan->actual_end_date->format('d.m.Y') }}
                                        </div>
                                        <div class="text-muted fw-bold mt-1">
                                            {{ $plan->actual_end_date->format('H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- 2. BÖLÜM: PROGRESS BAR --}}
                            @php
                                $diffMinutes = $plan->actual_end_date->diffInMinutes($plan->planned_end_date, false);
                                $isEarly = $diffMinutes > 0;
                                $diffAbs = abs($diffMinutes);

                                // Daha okunaklı süre formatı (Gün varsa ekle)
                                $days = floor($diffAbs / (24 * 60));
                                $hours = floor(($diffAbs % (24 * 60)) / 60);
                                $minutes = $diffAbs % 60;

                                $diffText = '';
                                if ($days > 0) {
                                    $diffText .= $days . ' gün ';
                                }
                                if ($hours > 0) {
                                    $diffText .= $hours . ' sa ';
                                }
                                $diffText .= $minutes . ' dk';
                            @endphp

                            <div class="progress rounded-pill mb-2" style="height: 35px; background-color: #f1f5f9;">
                                @if ($isEarly)
                                    <div class="progress-bar bg-success d-flex align-items-center justify-content-center"
                                        role="progressbar" style="width: 100%; font-size: 0.9rem;">
                                        <i class="fas fa-check-circle me-2"></i> {{ $diffText }} ERKEN
                                    </div>
                                @else
                                    <div class="progress-bar bg-danger d-flex align-items-center justify-content-center"
                                        role="progressbar" style="width: 100%; font-size: 0.9rem;">
                                        <i class="fas fa-exclamation-triangle me-2"></i> {{ $diffText }} GECİKMELİ
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mb-4">
                                @if ($isEarly)
                                    <small class="text-success fw-bold"><i class="fas fa-thumbs-up me-1"></i> Harika!
                                        Planlanandan daha kısa sürdü.</small>
                                @else
                                    <small class="text-danger fw-bold"><i class="fas fa-clock me-1"></i> Planlanan süreyi
                                        aştı.</small>
                                @endif
                            </div>

                            {{-- AYIRACI ÇİZGİ --}}
                            <hr class="border-secondary opacity-10 my-4">

                            {{-- 3. BÖLÜM: AÇIKLAMA / NOT (ALTTA VE GENİŞ) --}}
                            <div>
                                <h6 class="fw-bold text-secondary mb-2" style="font-size: 0.85rem;">
                                    <i class="fas fa-comment-alt me-2"></i>Bitiş Notu / Sapma Nedeni:
                                </h6>
                                <div
                                    class="bg-light p-3 rounded-3 border border-start-0 border-end-0 border-top-0 border-bottom-4 
                    {{ $isEarly ? 'border-success' : 'border-danger' }} bg-opacity-25">
                                    <p class="mb-0 text-dark fst-italic">
                                        "{{ $plan->completion_note ?? 'Herhangi bir not girilmemiş.' }}"
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif
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
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- 1. Sayaç Durdurma Modalı --}}
    <div class="modal fade" id="stopTimerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <form action="{{ route('maintenance.stop-timer', $plan->id) }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Çalışma İşlemi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-3">Ne yapmak istiyorsunuz?</label>

                            {{-- SEÇENEK 1: İşi Bitir (ARTIK VARSAYILAN SEÇİLİ - CHECKED BURADA) --}}
                            <div class="form-check custom-option basic-checkbox p-3 rounded border mb-3 bg-light"
                                style="cursor: pointer;" onclick="document.getElementById('radioFinish').checked = true;">
                                <input class="form-check-input" type="radio" name="completion_type" id="radioFinish"
                                    value="finish" checked>
                                <label class="form-check-label ms-2" for="radioFinish" style="cursor: pointer;">
                                    <span class="fw-bold d-block text-dark"><i
                                            class="fas fa-check-circle text-success me-2"></i>İşi Tamamla</span>
                                    @can('approve', $plan)
                                        <span class="small text-success">İşlem direkt <strong>Tamamlandı</strong>
                                            olacak.</span>
                                    @else
                                        <span class="small text-muted">İşlem <strong>Onay Bekliyor</strong> statüsüne
                                            geçecek.</span>
                                    @endcan
                                </label>
                            </div>

                            {{-- SEÇENEK 2: Sadece Duraklat (İkincil Seçenek) --}}
                            <div class="form-check custom-option basic-checkbox p-3 rounded border"
                                style="cursor: pointer;" onclick="document.getElementById('radioPause').checked = true;">
                                <input class="form-check-input" type="radio" name="completion_type" id="radioPause"
                                    value="pause">
                                <label class="form-check-label ms-2" for="radioPause" style="cursor: pointer;">
                                    <span class="fw-bold d-block text-dark"><i
                                            class="fas fa-pause-circle text-warning me-2"></i>Sadece Duraklat / Ara
                                        Ver</span>
                                    <span class="small text-muted">Sayaç duracak ama durum "İşlemde" kalacak.</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Açıklama / Not <span class="text-danger">*</span>
                            </label>

                            {{-- is-invalid sınıfı ile kutuyu kırmızı yapıyoruz --}}
                            <textarea name="note" class="form-control bg-light @error('note') is-invalid @enderror" rows="3"
                                placeholder="Yapılan işlemi kısaca özetleyin...">{{ old('note') }}</textarea>

                            {{-- Hata mesajını gösteren blok --}}
                            @error('note')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary px-4">Kaydet</button>
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
    {{-- 3. HIZLI BİTİRME MODALI (Sayaçsız İşlem) --}}
    <div class="modal fade" id="quickFinishModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                {{-- DİKKAT: Burası 'update' rotasına gidiyor, 'stop-timer' rotasına DEĞİL --}}
                <form action="{{ route('maintenance.update', $plan->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Kullanıcının yetkisine göre statü belirleme mantığı Controller'da var ama --}}
                    {{-- Formdan 'completed' gönderiyoruz, controller yetkisi yoksa 'pending_approval' yapıyor --}}
                    <input type="hidden" name="status" value="completed">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">İşlemi Tamamla</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Sayaç çalıştırılmadan işlem sonlandırılıyor. (Hızlı Onay)
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Açıklama / Yapılan Düzeltme <span class="text-danger">*</span>
                            </label>
                            <textarea name="completion_note" class="form-control bg-light" rows="3" required
                                placeholder="Örn: Yöneticinin belirttiği eksikler tamamlandı..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Vazgeç</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane me-2"></i>Gönder
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
            const hasNoteError = @json($errors->has('note'));
            const hasCompletionNoteError = @json($errors->has('completion_note'));

            if (hasNoteError) {
                const stopModalEl = document.getElementById('stopTimerModal');
                if (stopModalEl) {
                    const stopModal = new bootstrap.Modal(stopModalEl);
                    stopModal.show();
                }
            }

            if (hasCompletionNoteError) {
                const quickModalEl = document.getElementById('quickFinishModal');
                if (quickModalEl) {
                    const quickModal = new bootstrap.Modal(quickModalEl);
                    quickModal.show();
                }
            }

            // --- 2. SAYAÇ MANTIĞI ---
            @if ($plan->isTimerActive())
                // ... (Sayaç kodları aynen kalacak, buraya dokunma) ...
                const startTimeString =
                    "{{ $plan->timeEntries->whereNull('end_time')->where('user_id', Auth::id())->first()->start_time->format('Y-m-d H:i:s') }}";
                // ...
                // NOT: Yukarıdaki satırda ->where('user_id', Auth::id()) ekledim ki başkasının saatiyle karışmasın.
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

        // ... (Toast fonksiyonları aynen kalacak) ...
        function showToast() {
            /* ... */
        }

        function closeToast(button) {
            /* ... */
        }
    </script>
@endsection
