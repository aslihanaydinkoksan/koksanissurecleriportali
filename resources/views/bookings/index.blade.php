@extends('layouts.app')

@section('title', 'Tüm Rezervasyonlar')

@section('content')
    <style>
        .page-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
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

        .booking-table-card {
            background: white;
            border-radius: 1rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
            color: #495057;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle !important;
            border-bottom: 1px solid #e9ecef;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 1rem;
        }

        .type-badge.flight {
            background: #E3F2FD;
            color: #1976D2;
        }

        .type-badge.hotel {
            background: #E0F2F1;
            color: #00897B;
        }

        .type-badge.car_rental {
            background: #F3E5F5;
            color: #8E24AA;
        }

        .type-badge.train {
            background: #FFF3E0;
            color: #F57C00;
        }

        .type-badge.bus {
            background: #FFEBEE;
            color: #D32F2F;
        }

        /* Yeni Otobüs Rengi */
        .type-badge.other {
            background: #ECEFF1;
            color: #455A64;
        }

        .btn-sm-modern {
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-sm-modern:hover {
            background-color: #e9ecef;
        }

        .empty-state {
            padding: 3rem;
            text-align: center;
            color: #6c757d;
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                {{-- Hero Section --}}
                <div class="page-hero">
                    <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                        <div>
                            <h2 class="mb-1">🎫 Tüm Rezervasyonlar</h2>
                            <p class="mb-0 opacity-75">Sistemdeki kayıtlı tüm seyahat rezervasyonları</p>
                        </div>
                        <div class="text-end">
                            <h1 class="mb-0 display-6 fw-bold">{{ $bookings->total() }}</h1>
                            <span class="small opacity-75">Toplam Kayıt</span>
                        </div>
                    </div>
                </div>

                {{-- Table Card --}}
                <div class="booking-table-card">
                    @if ($bookings->isEmpty())
                        <div class="empty-state">
                            <i class="fa-solid fa-ticket fa-3x mb-3 text-muted opacity-50"></i>
                            <h4>Henüz hiç rezervasyon yok</h4>
                            <p>Seyahat veya Etkinlik planlarına giderek yeni rezervasyonlar ekleyebilirsiniz.</p>
                            <div class="mt-3">
                                <a href="{{ route('travels.index') }}" class="btn btn-primary me-2">Seyahat Planları</a>
                                <a href="{{ route('service.events.index', ['event_type' => 'fuar']) }}"
                                    class="btn btn-outline-primary">Fuar Yönetimi</a>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;" class="text-center"</th>
                                        <th>Sağlayıcı / Kod</th>
                                        <th>Bağlı Kayıt (Seyahat/Etkinlik) & Kişi</th> {{-- Başlık Düzeldi --}}
                                        <th>Tarih</th>
                                        <th>Tutar</th>
                                        <th class="text-end">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bookings as $booking)
                                        <tr>
                                            {{-- Tip İkonu --}}
                                            <td class="text-center">
                                                @php
                                                    $icon = match ($booking->type) {
                                                        'flight' => 'fa-plane',
                                                        'hotel' => 'fa-hotel',
                                                        'car_rental' => 'fa-car',
                                                        'train' => 'fa-train',
                                                        'bus' => 'fa-bus',
                                                        default => 'fa-clipboard-list',
                                                    };
                                                @endphp
                                                <div class="type-badge {{ $booking->type }}"
                                                    title="{{ ucfirst($booking->type) }}">
                                                    <i class="fa-solid {{ $icon }}"></i>
                                                </div>
                                            </td>

                                            {{-- Sağlayıcı Bilgisi --}}
                                            <td>
                                                <div class="fw-bold text-dark">{{ $booking->provider_name }}</div>
                                                @if ($booking->confirmation_code)
                                                    <div class="small text-muted">
                                                        <i
                                                            class="fa-solid fa-barcode me-1"></i>{{ $booking->confirmation_code }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- BAĞLI KAYIT (POLİMORFİK YAPI) --}}
                                            <td>
                                                @if ($booking->bookable)
                                                    @php
                                                        $route = '#';
                                                        $name = '-';
                                                        $typeLabel = '';
                                                        $badgeClass = 'bg-secondary';

                                                        // SEYAHAT İSE
                                                        if ($booking->bookable_type === 'App\Models\Travel') {
                                                            $route = route('travels.show', $booking->bookable_id);
                                                            $name = $booking->bookable->name;
                                                            $typeLabel = 'Seyahat';
                                                            $badgeClass = 'bg-info text-dark';
                                                        }
                                                        // ETKİNLİK (FUAR) İSE
                                                        elseif ($booking->bookable_type === 'App\Models\Event') {
                                                            // Rotayı 'service.' prefix'i ile çağırıyoruz
    $route = route(
        'service.events.show',
        $booking->bookable_id,
    );
    $name = $booking->bookable->title;
    $typeLabel = 'Etkinlik';
    $badgeClass = 'bg-warning text-dark';
                                                        }
                                                    @endphp

                                                    <a href="{{ $route }}" class="text-decoration-none fw-semibold"
                                                        style="color: #667EEA;">
                                                        {{ Str::limit($name, 40) }}
                                                    </a>

                                                    <div class="small text-muted mt-1 d-flex align-items-center gap-2">
                                                        {{-- Tür Rozeti --}}
                                                        <span class="badge {{ $badgeClass }} border px-2 py-0"
                                                            style="font-size: 0.7rem;">
                                                            {{ $typeLabel }}
                                                        </span>

                                                        {{-- Kullanıcı Adı --}}
                                                        <span>
                                                            <i
                                                                class="fa-regular fa-user me-1"></i>{{ $booking->user->name ?? 'Bilinmiyor' }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="text-muted fst-italic">
                                                        <i class="fa-solid fa-ban me-1"></i> Kayıt Silinmiş
                                                    </span>
                                                    <div class="small text-muted mt-1">
                                                        <i
                                                            class="fa-regular fa-user me-1"></i>{{ $booking->user->name ?? 'Bilinmiyor' }}
                                                    </div>
                                                @endif
                                            </td>

                                            {{-- Tarih --}}
                                            <td>
                                                <div class="fw-bold text-dark">
                                                    {{ \Carbon\Carbon::parse($booking->start_datetime)->format('d.m.Y') }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ \Carbon\Carbon::parse($booking->start_datetime)->format('H:i') }}
                                                    @if ($booking->end_datetime)
                                                        -
                                                        {{ \Carbon\Carbon::parse($booking->end_datetime)->format('H:i') }}
                                                    @endif
                                                </div>
                                            </td>

                                            {{-- Tutar --}}
                                            <td>
                                                @if (isset($booking->cost) && $booking->cost > 0)
                                                    <span class="badge bg-light text-dark border">
                                                        {{ number_format($booking->cost, 2) }} ₺
                                                    </span>
                                                @else
                                                    <span class="text-muted small">-</span>
                                                @endif
                                            </td>

                                            {{-- İşlemler --}}
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-1">
                                                    @if (Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager'))
                                                        {{-- 24 SAAT KURALI KONTROLÜ --}}
                                                        @if ($booking->is_editable || Auth::user()->can('is-global-manager'))
                                                            {{-- Düzenle Butonu --}}
                                                            <a href="{{ route('bookings.edit', $booking) }}"
                                                                class="btn btn-sm-modern text-primary" title="Düzenle">
                                                                <i class="fa-solid fa-pen"></i>
                                                            </a>

                                                            {{-- Sil Butonu --}}
                                                            <form action="{{ route('bookings.destroy', $booking) }}"
                                                                method="POST" ...>
                                                                {{-- ... form içeriği ... --}}
                                                                <button type="submit" class="btn btn-sm-modern text-danger"
                                                                    title="Sil">
                                                                    <i class="fa-solid fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            {{-- Süre Dolduysa Kilit İkonu Göster --}}
                                                            <span class="text-muted d-inline-flex align-items-center px-2"
                                                                title="24 saatten az kaldığı için işlem yapılamaz">
                                                                <i class="fa-solid fa-lock me-1"></i> Kilitli
                                                            </span>
                                                        @endif
                                                    @endif

                                                    {{-- Dosya Varsa Göster --}}
                                                    @if ($booking->getMedia('attachments')->count() > 0)
                                                        <div class="vr mx-1"></div>
                                                        <a href="{{ $booking->getMedia('attachments')->first()->getUrl() }}"
                                                            target="_blank" class="btn btn-sm-modern text-secondary"
                                                            title="Dosyayı Görüntüle">
                                                            <i class="fa-solid fa-paperclip"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
