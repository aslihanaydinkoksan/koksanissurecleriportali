@extends('layouts.app')

@section('title', $event->title . ' Detayları')

@push('styles')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            --glass-white: rgba(255, 255, 255, 0.95);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: #f3f4f6;
        }

        .event-hero {
            background: var(--primary-gradient);
            border-radius: 1.5rem;
            padding: 3rem 2.5rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(118, 75, 162, 0.25);
            margin-bottom: -3rem;
            z-index: 1;
        }

        .hero-title {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-glass:hover {
            background: white;
            color: #764BA2;
            transform: translateY(-2px);
        }

        .content-container {
            position: relative;
            z-index: 2;
        }

        .custom-card {
            background: var(--glass-white);
            border-radius: 1.25rem;
            border: none;
            box-shadow: var(--card-shadow);
            height: 100%;
        }

        .booking-section {
            background: white;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-top: 2rem;
        }

        .status-icon-wrapper {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            background: #f7fafc;
        }

        .status-icon-wrapper i {
            font-size: 2.5rem;
        }

        .detail-item {
            padding: 1rem;
            border-radius: 12px;
            background: #f8f9fc;
            height: 100%;
        }

        .detail-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }

        .detail-value {
            font-size: 1.05rem;
            color: #2d3748;
            font-weight: 600;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: 0.3s;
        }

        /* YENİ: Dosya Listesi CSS */
        .attachment-item {
            transition: all 0.2s ease;
            text-decoration: none !important;
        }

        .attachment-item:hover {
            background: #edf2f7 !important;
            transform: scale(1.02);
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                        <i class="fa-solid fa-circle-check fa-lg me-3"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- 1. HERO SECTION --}}
                <div class="event-hero">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <span class="hero-badge"><i class="fa-solid fa-tag me-1"></i>
                                    {{ $eventTypes[$event->event_type] ?? ucfirst($event->event_type) }}</span>

                                {{-- ÖNEMLİ İŞARETİ --}}
                                @if ($event->is_important)
                                    <span
                                        class="badge bg-danger rounded-pill px-3 shadow-sm border border-white border-opacity-25">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i> ÖNEMLİ
                                    </span>
                                @endif

                                @if ($event->visit_status == 'gerceklesti')
                                    <span
                                        class="badge bg-success bg-opacity-25 border border-success text-white rounded-pill px-3">Tamamlandı</span>
                                @endif
                            </div>
                            <h1 class="hero-title mb-2">
                                {{ $event->title }}
                            </h1>
                            <p class="mb-0 opacity-75 fs-5">
                                <i class="fa-regular fa-calendar me-2"></i>
                                {{ $event->start_datetime->format('d F Y, H:i') }}
                                <i class="fa-solid fa-arrow-right mx-2 text-white-50"></i>
                                {{ $event->end_datetime->format('d F Y, H:i') }}
                            </p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                            <a href="{{ route('service.events.index') }}" class="btn-glass"><i
                                    class="fa-solid fa-arrow-left"></i> Listeye Dön</a>
                        </div>
                    </div>
                </div>

                {{-- 2. CONTENT CARDS --}}
                <div class="content-container mt-5 pt-3">
                    <div class="row g-4 mb-5">
                        <div class="col-lg-8">
                            <div class="custom-card p-4">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Konum</div>
                                            <div class="detail-value">{{ $event->location ?? '-' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item">
                                            <div class="detail-label">Oluşturan</div>
                                            <div class="detail-value">{{ $event->user->name }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="detail-item">
                                            <div class="detail-label">Açıklama</div>
                                            <div class="detail-value text-muted" style="white-space: pre-wrap;">
                                                {{ $event->description ?? 'Girilmemiş.' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SAĞ SÜTUN: DURUM VE DOSYALAR --}}
                        <div class="col-lg-4">
                            <div class="row g-4">
                                {{-- DURUM KARTI --}}
                                <div class="col-12">
                                    <div
                                        class="custom-card p-4 text-center d-flex flex-column align-items-center justify-content-center">
                                        <div class="status-icon-wrapper text-info"><i class="fa-solid fa-circle-info"></i>
                                        </div>
                                        <h4 class="fw-bold mb-1">{{ ucfirst($event->visit_status) }}</h4>
                                        <p class="text-muted small mb-0">Etkinlik Durumu</p>
                                    </div>
                                </div>

                                {{-- DOSYALAR KARTI --}}
                                <div class="col-12">
                                    <div class="custom-card p-4">
                                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-paperclip text-primary me-2"></i>Ek
                                            Dosyalar</h6>
                                        <div class="attachment-list">
                                            @php
                                                $attachments = $event->getMedia('event_attachments');
                                            @endphp

                                            @forelse($attachments as $media)
                                                @php
                                                    $ext = strtolower($media->extension);
                                                    $icon = match ($ext) {
                                                        'pdf' => 'fa-file-pdf text-danger',
                                                        'doc', 'docx' => 'fa-file-word text-primary',
                                                        'xls', 'xlsx' => 'fa-file-excel text-success',
                                                        'jpg', 'jpeg', 'png' => 'fa-file-image text-info',
                                                        default => 'fa-file-lines text-secondary',
                                                    };
                                                @endphp
                                                <a href="{{ $media->getUrl() }}" target="_blank"
                                                    class="attachment-item d-flex align-items-center p-2 mb-2 bg-light rounded border border-light shadow-sm">
                                                    <div class="me-3 fs-3">
                                                        <i class="fa-solid {{ $icon }}"></i>
                                                    </div>
                                                    <div class="overflow-hidden">
                                                        <div class="text-dark fw-bold text-truncate small">
                                                            {{ $media->file_name }}</div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">
                                                            {{ $media->human_readable_size }}</div>
                                                    </div>
                                                    <i class="fa-solid fa-download ms-auto text-muted small"></i>
                                                </a>
                                            @empty
                                                <div class="text-center py-3">
                                                    <p class="text-muted small mb-0 italic">Dosya bulunmuyor.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. REZERVASYON YÖNETİMİ --}}
                    @if ($event->event_type === 'fuar')
                        <div class="booking-section mt-4">
                            {{-- Mevcut rezervasyon içeriğin --}}
                            <div class="section-title mb-4">
                                <h4 class="fw-bold"><i class="fa-solid fa-plane-departure text-primary me-2"></i>Seyahat &
                                    Konaklama</h4>
                            </div>
                            {{-- ... Geri kalan form ve liste kodların ... --}}
                            <div class="card border-0 shadow-sm mb-5 bg-light bg-opacity-50">
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-circle-plus me-2"></i>Yeni
                                        Rezervasyon Ekle</h6>
                                    <form action="{{ route('service.events.bookings.store', $event->id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @include('bookings._form', ['booking' => null])
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-gradient px-5 shadow">
                                                <i class="fa-solid fa-save me-2"></i>Rezervasyonu Kaydet
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- LİSTELEME --}}
                            <div class="row g-3">
                                @forelse ($event->bookings->sortBy('start_datetime') as $booking)
                                    @php
                                        $typeClass = match ($booking->type) {
                                            'flight' => 'border-primary',
                                            'hotel' => 'border-success',
                                            'bus' => 'border-warning',
                                            default => 'border-secondary',
                                        };
                                        $icon = match ($booking->type) {
                                            'flight' => 'fa-plane',
                                            'hotel' => 'fa-hotel',
                                            'bus' => 'fa-bus',
                                            default => 'fa-ticket',
                                        };
                                    @endphp
                                    <div class="col-md-6 col-xl-4">
                                        <div
                                            class="card h-100 border-0 shadow-sm {{ $typeClass }} border-start border-start-5">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <h6 class="fw-bold mb-0 text-dark">{{ $booking->provider_name }}</h6>
                                                    <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                                        onsubmit="return confirm('Emin misiniz?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0">
                                                            <i class="fa-solid fa-trash-can"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="small text-muted mb-2">
                                                    <i class="fa-solid {{ $icon }} me-1"></i>
                                                    {{ ucfirst($booking->type) }} |
                                                    {{ $booking->start_datetime->format('d.m.Y H:i') }}
                                                </div>
                                                @if ($booking->origin || $booking->destination)
                                                    <div class="small mb-2 text-dark">
                                                        {{ $booking->origin ?? '?' }} <i
                                                            class="fa-solid fa-arrow-right mx-1 small"></i>
                                                        {{ $booking->destination ?? '?' }}
                                                    </div>
                                                @elseif($booking->location)
                                                    <div class="small mb-2 text-dark italic"><i
                                                            class="fa-solid fa-location-dot me-1"></i>{{ $booking->location }}
                                                    </div>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center mt-2">
                                                    <span
                                                        class="badge bg-light text-dark border small">{{ $booking->confirmation_code ?? '-' }}</span>
                                                    <span
                                                        class="fw-bold text-success">{{ number_format($booking->cost, 2) }}
                                                        {{ $booking->currency ?? 'TRY' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-4 text-muted border rounded bg-white">Henüz
                                        rezervasyon eklenmedi.</div>
                                @endforelse
                            </div>
                        </div>

                        {{-- 4. MASRAFLAR --}}
                        @include('partials._expense_section', ['model' => $event])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
