@extends('layouts.app')

@section('title', 'Rezervasyon Düzenle')

@section('content')
    <div class="container py-4">

        {{-- 1. PHP Mantığı: Bağlam Duyarlı (Context-Aware) Verileri Hazırlama --}}
        @php
            $backRoute = '#';
            $planTitle = 'Belirsiz Plan';
            $contextLabel = 'Bağlı Olduğu Plan';

            if ($booking->bookable) {
                if ($booking->bookable_type === 'App\Models\Travel') {
                    $backRoute = route('travels.show', $booking->bookable_id);
                    // Seyahat modelinde isim sütunu genellikle 'name'dir
                    $planTitle = $booking->bookable->name ?? 'İsimsiz Seyahat';
                    $contextLabel = 'Seyahat';
                } elseif ($booking->bookable_type === 'App\Models\Event') {
                    $backRoute = route('service.events.show', $booking->bookable_id);
                    // Etkinlik modelinde isim sütunu genellikle 'title'dır
                    $planTitle = $booking->bookable->title ?? 'İsimsiz Etkinlik';
                    $contextLabel = 'Etkinlik';
                }
            }
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-11">

                {{-- 2. Üst Başlık Alanı --}}
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h6 class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 1.2px;">
                            <i class="fa-solid fa-chevron-right me-1 small text-primary"></i> {{ $contextLabel }} YÖNETİMİ
                        </h6>
                        <h2 class="fw-bold text-dark mb-0">Rezervasyonu Güncelle</h2>
                        <div class="d-flex align-items-center text-secondary mt-2">
                            <span class="badge bg-light text-dark border shadow-sm px-3 py-2">
                                <i class="fa-solid fa-layer-group me-2 text-primary"></i>
                                {{ $planTitle }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <a href="{{ $backRoute }}" class="btn btn-white border shadow-sm text-muted rounded-pill px-4">
                            <i class="fa-solid fa-arrow-left-long me-2"></i> Vazgeç ve Dön
                        </a>
                    </div>
                </div>

                {{-- 3. Ana Form Kartı --}}
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    {{-- Kurumsal Aksan Çizgisi --}}
                    <div style="height: 5px; background: linear-gradient(90deg, #667EEA 0%, #764BA2 100%); border: none;">
                    </div>

                    <div class="card-body p-4 p-md-5 bg-white">

                        <form action="{{ route('bookings.update', $booking) }}" method="POST" enctype="multipart/form-data"
                            autocomplete="off">
                            @csrf
                            @method('PUT')

                            {{-- Merkezi Form Parçası --}}
                            {{-- Bu dosya artık origin, destination ve location alanlarını otomatik yönetiyor --}}
                            @include('bookings._form', ['booking' => $booking])

                            <div class="mt-5 pt-4 border-top">
                                <div class="row align-items-center">
                                    <div class="col-md-6 text-muted small">
                                        <i class="fa-solid fa-circle-info me-1"></i>
                                        Bu rezervasyon en son
                                        <strong>{{ $booking->updated_at->format('d.m.Y H:i') }}</strong> tarihinde
                                        güncellendi.
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <div class="d-flex justify-content-end align-items-center gap-3">
                                            <a href="{{ $backRoute }}"
                                                class="text-decoration-none text-secondary fw-medium">
                                                İptal Et
                                            </a>
                                            <button type="submit"
                                                class="btn btn-primary shadow px-5 py-2 rounded-pill fw-bold">
                                                <i class="fa-solid fa-check-double me-2"></i> Değişiklikleri Uygula
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                {{-- Ek Bilgi Paneli (Opsiyonel) --}}
                <div class="mt-4 p-3 bg-light rounded-3 border d-flex align-items-center justify-content-between">
                    <div class="small text-muted">
                        <i class="fa-solid fa-user-clock me-2 text-primary"></i>
                        Kayıt Sahibi: <strong>{{ $booking->user->name ?? 'Sistem' }}</strong>
                    </div>
                    <div class="small text-muted">
                        Oluşturulma: {{ $booking->created_at->format('d.m.Y H:i') }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
