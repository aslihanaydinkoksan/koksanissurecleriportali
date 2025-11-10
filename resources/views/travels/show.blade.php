@extends('layouts.app')

@section('title', 'Seyahat Detayı')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">{{ $travel->name }}</h4>
                            <span class="text-muted">
                                {{ \Carbon\Carbon::parse($travel->start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($travel->end_date)->format('d/m/Y') }}
                            </span>
                        </div>
                        <div>
                            @if (Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager'))
                                <a href="{{ route('travels.edit', $travel) }}"
                                    class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body px-4">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Kayıt eklenirken bir hata oluştu:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- YENİ YAPI: HIZLI REZERVASYON EKLEME FORMU --}}
                        <div class="quick-add-form mb-4"
                            style="background-color: #f8f9fa; border-radius: 0.5rem; padding: 1.5rem; border: 1px solid #e9ecef;">
                            <h5><i class="fa-solid fa-ticket me-2"></i> Bu Seyahate Yeni Rezervasyon Ekle</h5>
                            <form action="{{ route('travels.bookings.store', $travel) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @include('bookings._form', ['booking' => null])
                                <button type="submit" class="btn btn-primary-gradient px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    Rezervasyonu Ekle
                                </button>
                            </form>
                        </div>

                        <hr class="my-4">

                        {{-- YENİ YAPI: REZERVASYON LİSTESİ --}}
                        <h5><i class="fa-solid fa-list-check me-2"></i> Kayıtlı Rezervasyonlar
                            ({{ $travel->bookings->count() }})</h5>

                        @if ($travel->bookings->isEmpty())
                            <div class="alert alert-secondary">Bu seyahat planına ait bir rezervasyon (uçuş, otel vb.) kaydı
                                bulunamadı.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tip</th>
                                            <th>Sağlayıcı / Detay</th>
                                            <th>Kod</th>
                                            <th>Tarih</th>
                                            <th>Dosyalar</th>
                                            <th>Eylem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($travel->bookings as $booking)
                                            <tr>
                                                <td>
                                                    @if ($booking->type == 'flight')
                                                        ✈️ Uçuş
                                                    @endif
                                                    @if ($booking->type == 'hotel')
                                                        🏨 Otel
                                                    @endif
                                                    @if ($booking->type == 'car_rental')
                                                        🚗 Araç
                                                    @endif
                                                    @if ($booking->type == 'train')
                                                        🚆 Tren
                                                    @endif
                                                    @if ($booking->type == 'other')
                                                        Diğer
                                                    @endif
                                                </td>
                                                <td><strong>{{ $booking->provider_name }}</strong></td>
                                                <td>{{ $booking->confirmation_code }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->start_datetime)->format('d/m/Y H:i') }}
                                                </td>
                                                <td>
                                                    {{-- Dosya Listesi --}}
                                                    @foreach ($booking->getMedia('attachments') as $media)
                                                        <div class="file-list-item"
                                                            style="display: flex; align-items: center; justify-content: space-between; padding: 0.2rem 0.5rem; background-color: #f1f3f5; border-radius: 0.25rem; margin-bottom: 0.2rem;">
                                                            <span><i
                                                                    class="fa-solid fa-paperclip me-2"></i>{{ $media->file_name }}</span>
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{-- Düzenleme ve Silme Butonu --}}
                                                    @if (Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager'))
                                                        <a href="{{ route('bookings.edit', $booking) }}"
                                                            class="btn btn-sm btn-outline-secondary"
                                                            title="Düzenle">Rezervasyon Detaylarını Düzenle
                                                            <i class="fa-solid fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('bookings.destroy', $booking) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bu rezervasyon kaydını silmek istediğinizden emin misiniz?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Sil"
                                                                style="border: none; background: transparent;"> Rezervasyonu
                                                                Sil
                                                                <i class="fa-solid fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <hr class="my-4">
                        <h5>Bu Seyahate Bağlı Ziyaretler</h5>

                        @if ($travel->customerVisits->isEmpty())
                            <div class="alert alert-secondary">Bu seyahat planına bağlı bir ziyaret (etkinlik) bulunamadı.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Müşteri</th>
                                            <th>Ziyaret Başlığı (Etkinlik)</th>
                                            <th>Ziyaret Tarihi</th>
                                            <th>Ziyaret Amacı</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($travel->customerVisits as $visit)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('customers.show', $visit->customer) }}">
                                                        {{ $visit->customer->name ?? 'Bilinmiyor' }}
                                                    </a>
                                                </td>
                                                <td>{{ $visit->event->title ?? 'N/A' }}</td>
                                                <td>{{ $visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $visit->visit_purpose }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
