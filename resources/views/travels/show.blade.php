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
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="type" class="form-label">Tip (*)</label>
                                        <select name="type" class="form-select" required>
                                            <option value="flight">✈️ Uçuş</option>
                                            <option value="hotel">🏨 Otel</option>
                                            <option value="car_rental">🚗 Araç Kiralama</option>
                                            <option value="train">🚆 Tren</option>
                                            <option value="other">Diğer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="provider_name" class="form-label">Sağlayıcı (*)</label>
                                        <input type="text" name="provider_name" class="form-control"
                                            placeholder="Örn: Türk Hava Yolları, Hilton..." required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="confirmation_code" class="form-label">Rezervasyon Kodu (PNR vb.)</label>
                                        <input type="text" name="confirmation_code" class="form-control"
                                            placeholder="Örn: ABC123">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç / Kalkış (*)</label>
                                        <input type="datetime-local" name="start_datetime" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="end_datetime" class="form-label">Bitiş / Varış</label>
                                        <input type="datetime-local" name="end_datetime" class="form-control">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="cost" class="form-label">Masraf (TL)</label>
                                        <input type="number" step="0.01" name="cost" class="form-control"
                                            placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="booking_files" class="form-label">Bilet / Voucher (PDF, JPG...)</label>
                                        <input type="file" name="booking_files[]" class="form-control" multiple>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notlar</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Örn: 1 adet kabin bagajı dahil..."></textarea>
                                </div>
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
                                                    {{-- Silme Butonu --}}
                                                    @if (Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager'))
                                                        <form action="{{ route('bookings.destroy', $booking) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Bu rezervasyon kaydını silmek istediğinizden emin misiniz?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                style="border: none; background: transparent;">
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
