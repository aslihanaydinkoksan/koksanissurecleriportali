@extends('layouts.app')
@section('title', 'Etkinlik Listesi')

@push('styles')
    <style>
        .page-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.25);
        }

        .page-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .page-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .page-hero-content {
            position: relative;
            z-index: 1;
        }

        /* Filtre Alanı */
        .filter-card {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.2rem;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .section-title h5 {
            margin: 0;
            font-weight: 600;
            color: #4a5568;
            font-size: 1.1rem;
        }

        /* Kart ve Tablo Yapısı */
        .content-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        /* FUAR KARTLARI İÇİN STİL */
        .travel-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .travel-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.12);
            border-color: #667EEA;
        }

        /* TABLO STİLLERİ */
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle !important;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #fcfcfc;
        }

        /* Badge ve Butonlar */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .status-badge.planned {
            background: #e0f2fe;
            color: #0284c7;
        }

        .status-badge.completed {
            background: #dcfce7;
            color: #16a34a;
        }

        .status-badge.cancelled {
            background: #fee2e2;
            color: #dc2626;
        }

        .status-badge.postponed {
            background: #ffedd5;
            color: #ea580c;
        }

        .type-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin: 0 auto;
        }

        .type-icon.fuar {
            background: #f3e8ff;
            color: #9333ea;
        }

        /* Mor */
        .type-icon.toplanti {
            background: #e0e7ff;
            color: #4f46e5;
        }

        /* İndigo */
        .type-icon.ziyaret {
            background: #dbeafe;
            color: #2563eb;
        }

        /* Mavi */
        .type-icon.egitim {
            background: #ffedd5;
            color: #c2410c;
        }

        /* Turuncu */
        .type-icon.diger {
            background: #f3f4f6;
            color: #4b5563;
        }

        /* Gri */

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 0.5rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: 1px solid transparent;
        }

        .btn-action:hover {
            background-color: #f3f4f6;
            border-color: #e5e7eb;
        }

        .btn-action.edit {
            color: #2563eb;
        }

        .btn-action.delete {
            color: #dc2626;
        }

        .btn-action.view {
            color: #4b5563;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #9ca3af;
        }

        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .btn-action-with-text {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
        }

        .btn-action-with-text i {
            font-size: 14px;
        }

        .btn-action-with-text.view {
            color: #6366f1;
        }

        .btn-action-with-text.view:hover {
            background: #eef2ff;
        }

        .btn-action-with-text.edit {
            color: #3b82f6;
        }

        .btn-action-with-text.edit:hover {
            background: #dbeafe;
        }

        .btn-action-with-text.delete {
            color: #ef4444;
        }

        .btn-action-with-text.delete:hover {
            background: #fee2e2;
        }

        /* Excel Export Buton Stili */
        .btn-export-global {
            background: linear-gradient(135deg, #28c76f 0%, #1e7e34 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
            margin-right: 10px;
            /* Butonlar arası boşluk */
        }

        .btn-export-global:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 199, 111, 0.3);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                {{-- Hero Section --}}
                <div class="page-hero">
                    <div class="page-hero-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @if (request('event_type') == 'fuar')
                                    <h2 class="mb-1">🎟️ Fuar Yönetimi</h2>
                                    <p class="mb-0 opacity-90">Planlanmış fuar organizasyonlarını ve rezervasyonları buradan
                                        yönetebilirsiniz.</p>
                                @else
                                    <h2 class="mb-1">📅 Etkinlik Listesi</h2>
                                    <p class="mb-0 opacity-90">Tüm toplantı, ziyaret ve organizasyon listesi.</p>
                                @endif
                            </div>
                            <div class="text-end d-none d-md-block">
                                <h1 class="mb-0 display-6 fw-bold">{{ $events->total() }}</h1>
                                <span class="small opacity-75">Toplam Kayıt</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alert Mesajları --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
                        style="background: #dcfce7; color: #166534;">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- 
                    ==================================================
                    MOD 1: FUAR YÖNETİMİ (Travels Tarzı Kartlar)
                    Adres: /service/events?event_type=fuar
                    ==================================================
                --}}
                @if (request('event_type') == 'fuar')

                    <div class="d-flex justify-content-end mb-4">
                        <a href="{{ route('service.events.export', request()->all()) }}" class="btn-export-global">
                            <i class="fas fa-file-excel me-2"></i> Fuar Listesini Excel'e Aktar
                        </a>
                        <a href="{{ route('service.events.create') }}" class="btn-gradient">
                            <i class="fa-solid fa-plus me-2"></i> Yeni Fuar Ekle
                        </a>
                    </div>

                    @if ($events->isEmpty())
                        <div class="content-card empty-state">
                            <i class="fa-solid fa-ticket"></i>
                            <h4>Henüz Fuar Kaydı Yok</h4>
                            <p>Planlanmış bir fuar bulunmuyor. Yeni ekleyerek başlayabilirsiniz.</p>
                        </div>
                    @else
                        @foreach ($events as $event)
                            <div class="travel-card">
                                <div class="row align-items-center">
                                    {{-- Önem Durumu --}}
                                    <div class="col-md-1 text-center">
                                        @if ($event->is_important)
                                            <i class="fa-solid fa-star text-warning fs-5" title="Önemli"></i>
                                        @else
                                            <i class="fa-regular fa-star text-muted fs-5 opacity-50"></i>
                                        @endif
                                    </div>

                                    {{-- Başlık ve Oluşturan --}}
                                    <div class="col-md-3">
                                        <div class="fw-bold text-dark fs-5">{{ $event->title }}</div>
                                        <div class="text-muted small mt-1">
                                            <i class="fa-solid fa-user-circle me-1"></i>
                                            {{ $event->user->name ?? 'Bilinmiyor' }}
                                        </div>
                                    </div>

                                    {{-- Başlangıç --}}
                                    <div class="col-md-2">
                                        <div class="text-muted small"
                                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Başlangıç</div>
                                        <div class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($event->start_datetime)->format('d.m.Y') }}</div>
                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($event->start_datetime)->format('H:i') }}</div>
                                    </div>

                                    {{-- Bitiş --}}
                                    <div class="col-md-2">
                                        <div class="text-muted small"
                                            style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Bitiş</div>
                                        <div class="fw-bold text-dark">
                                            {{ \Carbon\Carbon::parse($event->end_datetime)->format('d.m.Y') }}</div>
                                        <div class="small text-muted">
                                            {{ \Carbon\Carbon::parse($event->end_datetime)->format('H:i') }}</div>
                                    </div>

                                    {{-- Durum Badge --}}
                                    <div class="col-md-2">
                                        @php
                                            $statusClass = match ($event->visit_status) {
                                                'planlandi' => 'planned',
                                                'gerceklesti' => 'completed',
                                                'iptal' => 'cancelled',
                                                'ertelendi' => 'postponed',
                                                default => 'secondary',
                                            };
                                            $statusIcon = match ($event->visit_status) {
                                                'planlandi' => 'fa-clock',
                                                'gerceklesti' => 'fa-check-circle',
                                                'iptal' => 'fa-ban',
                                                'ertelendi' => 'fa-hourglass-half',
                                                default => 'fa-circle',
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="fa-solid {{ $statusIcon }}"></i>
                                            {{ ucfirst($event->visit_status) }}
                                        </span>
                                    </div>

                                    {{-- Butonlar --}}
                                    <div class="col-md-2 text-end">
                                        <a href="{{ route('service.events.show', $event) }}"
                                            class="btn btn-outline-primary w-100 rounded-pill fw-bold"
                                            style="border: 2px solid; padding: 0.5rem;">
                                            <i class="fa-solid fa-eye me-1"></i> Detaylar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="d-flex justify-content-center mt-4">
                            {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif

                    {{-- 
                    ==================================================
                    MOD 2: STANDART ETKİNLİK LİSTESİ (Bookings Tarzı Tablo)
                    Adres: /service/events
                    ==================================================
                --}}
                @else
                    {{-- 1. Filtre Açma Butonu --}}
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-white border shadow-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#eventFilters" aria-expanded="false" aria-controls="eventFilters">
                            <i class="fa-solid fa-filter me-1 text-primary"></i> Filtreleme Seçenekleri

                            {{-- Eğer aktif bir filtre varsa Badge göster --}}
                            @if (request()->hasAny(['title', 'event_type', 'visit_status', 'date_from', 'date_to']))
                                <span class="badge bg-primary ms-1">Aktif</span>
                            @endif
                        </button>
                    </div>

                    {{-- 2. Filtre Alanı (Collapse içine alındı) --}}
                    {{-- Eğer filtre varsa 'show' class'ı eklenir ve açık gelir --}}
                    <div class="collapse @if (request()->hasAny(['title', 'event_type', 'visit_status', 'date_from', 'date_to'])) show @endif" id="eventFilters">
                        <div class="filter-card">
                            <div class="section-title">
                                <i class="fa-solid fa-filter"></i>
                                <h5>Filtreleme Seçenekleri</h5>
                            </div>

                            <form method="GET" action="{{ route('service.events.index') }}">
                                <div class="row g-3">
                                    <div class="col-lg-3 col-md-6">
                                        <label class="form-label small fw-bold text-muted">Etkinlik Başlığı</label>
                                        <input type="text" class="form-control" name="title"
                                            value="{{ $filters['title'] ?? '' }}" placeholder="Ara...">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <label class="form-label small fw-bold text-muted">Etkinlik Tipi</label>
                                        <select class="form-select" name="event_type">
                                            <option value="all">Tümü</option>
                                            @foreach ($eventTypes as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ ($filters['event_type'] ?? '') == $key ? 'selected' : '' }}>
                                                    {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <label class="form-label small fw-bold text-muted">Durum</label>
                                        <select class="form-select" name="visit_status">
                                            <option value="all">Tümü</option>
                                            <option value="planlandi"
                                                {{ ($filters['visit_status'] ?? '') == 'planlandi' ? 'selected' : '' }}>
                                                Planlandı</option>
                                            <option value="gerceklesti"
                                                {{ ($filters['visit_status'] ?? '') == 'gerceklesti' ? 'selected' : '' }}>
                                                Gerçekleşti</option>
                                            <option value="iptal"
                                                {{ ($filters['visit_status'] ?? '') == 'iptal' ? 'selected' : '' }}>İptal
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <label class="form-label small fw-bold text-muted">Başlangıç</label>
                                        <input type="date" class="form-control" name="date_from"
                                            value="{{ $filters['date_from'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-2 col-md-6">
                                        <label class="form-label small fw-bold text-muted">Bitiş</label>
                                        <input type="date" class="form-control" name="date_to"
                                            value="{{ $filters['date_to'] ?? '' }}">
                                    </div>

                                    {{-- Butonlar --}}
                                    <div class="col-lg-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100"
                                            style="background-color: #667EEA; border-color: #667EEA;">
                                            <i class="fa-solid fa-filter"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Temizle Butonu (Filtre varsa görünür) --}}
                                @if (request()->hasAny(['title', 'event_type', 'visit_status', 'date_from', 'date_to']))
                                    <div class="row mt-3">
                                        <div class="col-12 text-end">
                                            <a href="{{ route('service.events.index') }}" class="btn btn-sm text-muted">
                                                <i class="fa-solid fa-rotate-right me-1"></i> Filtreleri Temizle
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="mb-4 text-end">
                        <a href="{{ route('service.events.export', request()->all()) }}" class="btn-export-global">
                            <i class="fas fa-file-excel me-2"></i> Tüm Listeyi Excel'e Aktar
                        </a>
                    </div>
                    {{-- Tablo Görünümü --}}
                    <div class="content-card">
                        @if ($events->isEmpty())
                            <div class="empty-state">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <h5>Kayıt Bulunamadı</h5>
                                <p class="mb-0">Arama kriterlerinize uygun etkinlik yok.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Tip</th>
                                            <th>Başlık</th>
                                            <th>Durum</th>
                                            <th>Konum</th>
                                            <th>Zaman</th>
                                            <th class="text-end pe-4">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                            {{-- HATA ÇÖZÜMÜ: Satır etiketi (tr) eklendi ve hiza için class verildi --}}
                                            <tr class="align-middle">

                                                {{-- 1. TİP SÜTUNU --}}
                                                <td class="text-center">
                                                    @php
                                                        $typeClass = match ($event->event_type) {
                                                            'fuar' => 'fuar',
                                                            'toplanti' => 'toplanti',
                                                            'musteri_ziyareti' => 'ziyaret',
                                                            'egitim' => 'egitim',
                                                            default => 'diger',
                                                        };
                                                        $typeIcon = match ($event->event_type) {
                                                            'fuar' => 'fa-ticket',
                                                            'toplanti' => 'fa-briefcase',
                                                            'musteri_ziyareti' => 'fa-handshake',
                                                            'egitim' => 'fa-graduation-cap',
                                                            default => 'fa-calendar',
                                                        };
                                                        $typeName =
                                                            $eventTypes[$event->event_type] ??
                                                            ucfirst($event->event_type);
                                                    @endphp

                                                    <div class="d-inline-flex align-items-center">
                                                        <div class="type-icon {{ $typeClass }} me-2"
                                                            title="{{ $typeName }}">
                                                            <i class="fa-solid {{ $typeIcon }}"></i>
                                                        </div>
                                                        <span class="fw-bold text-dark small">
                                                            {{ $typeName }}
                                                        </span>
                                                    </div>
                                                </td>

                                                {{-- 2. BAŞLIK SÜTUNU --}}
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $event->title }}</div>
                                                    <div class="small text-muted">{{ $event->user->name ?? 'Bilinmiyor' }}
                                                    </div>
                                                </td>

                                                {{-- 3. DURUM SÜTUNU --}}
                                                <td>
                                                    @php
                                                        $statusClass = match ($event->visit_status) {
                                                            'planlandi' => 'planned',
                                                            'gerceklesti' => 'completed',
                                                            'iptal' => 'cancelled',
                                                            'ertelendi' => 'postponed',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="status-badge {{ $statusClass }}">
                                                        {{ ucfirst($event->visit_status) }}
                                                    </span>
                                                </td>

                                                {{-- 4. KONUM SÜTUNU --}}
                                                <td>
                                                    @if ($event->location)
                                                        <span class="text-dark">
                                                            <i class="fa-solid fa-location-dot me-1 text-muted"></i>
                                                            {{ Str::limit($event->location, 20) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">-</span>
                                                    @endif
                                                </td>

                                                {{-- 5. ZAMAN SÜTUNU --}}
                                                <td>
                                                    <div class="fw-bold text-dark">
                                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('d.m.Y') }}
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ \Carbon\Carbon::parse($event->start_datetime)->format('H:i') }}
                                                        -
                                                        {{ \Carbon\Carbon::parse($event->end_datetime)->format('H:i') }}
                                                    </div>
                                                </td>

                                                {{-- 6. İŞLEMLER SÜTUNU --}}
                                                <td class="text-end pe-4">
                                                    <div class="d-flex justify-content-end gap-2">
                                                        <a href="{{ route('service.events.show', $event) }}"
                                                            class="btn-action-with-text view">
                                                            <i class="fa-solid fa-eye"></i>
                                                            <span>Görüntüle</span>
                                                        </a>

                                                        @if (!in_array(Auth::user()->role, ['izleyici']))
                                                            <a href="{{ route('service.events.edit', $event) }}"
                                                                class="btn-action-with-text edit">
                                                                <i class="fa-solid fa-pen"></i>
                                                                <span>Düzenle</span>
                                                            </a>


                                                            <form action="{{ route('service.events.destroy', $event) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn-action-with-text delete">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                    <span>Sil</span>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr> {{-- HATA ÇÖZÜMÜ: Satır kapatıldı --}}
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 border-top">
                                {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
