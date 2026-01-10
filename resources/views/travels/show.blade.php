@extends('layouts.app')

@section('title', 'Seyahat Detayı')

@section('content')
    <style>
        .travel-hero {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            border-radius: 1rem;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .travel-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .travel-hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .travel-hero-content {
            position: relative;
            z-index: 1;
        }

        .quick-add-card {
            background: linear-gradient(135deg, #f6f8fb 0%, #ffffff 100%);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e9ecef;
        }

        .section-title i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .booking-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.15);
            border-color: #667EEA;
        }

        .booking-type {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .booking-type.flight {
            background: linear-gradient(135deg, #667EEA20, #667EEA10);
            color: #667EEA;
        }

        .booking-type.bus {
            background: linear-gradient(135deg, #FF6B6B20, #FF6B6B10);
            color: #FF6B6B;
        }

        .booking-type.hotel {
            background: linear-gradient(135deg, #4FD1C520, #4FD1C510);
            color: #2c9e91;
        }

        .booking-type.car {
            background: linear-gradient(135deg, #F093FB20, #F093FB10);
            color: #c965d6;
        }

        .booking-type.train {
            background: linear-gradient(135deg, #FBD38D20, #FBD38D10);
            color: #d4a043;
        }

        .file-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 0.8rem;
            background: #f8f9fa;
            border-radius: 0.5rem;
            margin: 0.25rem;
            font-size: 0.875rem;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .file-badge:hover {
            background: #667EEA;
            color: white;
            border-color: #667EEA;
            transform: translateX(2px);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-edit {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            backdrop-filter: blur(10px);
            padding: 0.6rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
            transform: translateY(-2px);
        }

        .stats-row {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .stat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1rem;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-box h4 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .stat-box p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .visit-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667EEA;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .visit-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            border-radius: 1rem;
            border: 2px dashed #dee2e6;
        }

        .empty-state i {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm-modern {
            padding: 0.4rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-modern {
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .btn-outline-modern:hover {
            background: #667EEA;
            border-color: #667EEA;
            color: white;
            transform: translateY(-1px);
        }
    </style>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                {{-- Hero Section --}}
                <div class="travel-hero">
                    <div class="travel-hero-content">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h2 class="mb-2">✈️ {{ $travel->name }}</h2>
                                <p class="mb-0" style="font-size: 1.1rem; opacity: 0.95;">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    {{ \Carbon\Carbon::parse($travel->start_date)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($travel->end_date)->format('d/m/Y') }}
                                </p>
                            </div>
                            @if (Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager'))
                                <a href="{{ route('travels.edit', $travel) }}" class="btn-edit">
                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                </a>
                            @endif
                        </div>

                        {{-- Stats --}}
                        <div class="stats-row">
                            <div class="stat-box">
                                <h4>{{ $travel->bookings->count() }}</h4>
                                <p>Rezervasyon</p>
                            </div>
                            <div class="stat-box">
                                <h4>{{ $travel->customerVisits->count() }}</h4>
                                <p>Ziyaret</p>
                            </div>
                            <div class="stat-box">
                                <h4>
                                    {{ \Carbon\Carbon::parse($travel->start_date)->diffInDays(\Carbon\Carbon::parse($travel->end_date), false) }}
                                </h4>
                                <p>Gün</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong><i class="fa-solid fa-exclamation-triangle me-2"></i>Kayıt eklenirken bir hata
                            oluştu:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Quick Add Form --}}
                <div class="quick-add-card">
                    <div class="section-title">
                        <i class="fa-solid fa-circle-plus"></i>
                        <h5 class="mb-0">Yeni Rezervasyon Ekle</h5>
                    </div>
                    <form action="{{ route('travels.bookings.store', $travel) }}" method="POST" autocomplete="off"
                        enctype="multipart/form-data">
                        @csrf
                        @include('bookings._form', ['booking' => null])
                        <button type="submit" class="btn-gradient">
                            <i class="fa-solid fa-plus me-2"></i>Rezervasyonu Ekle
                        </button>
                    </form>
                </div>

                {{-- Bookings Section --}}
                <div class="section-title">
                    <i class="fa-solid fa-ticket"></i>
                    <h5 class="mb-0">Kayıtlı Rezervasyonlar <span
                            class="badge bg-primary rounded-pill">{{ $travel->bookings->count() }}</span></h5>
                </div>

                @if ($travel->bookings->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-inbox"></i>
                        <h5 class="text-muted">Henüz Rezervasyon Yok</h5>
                        <p class="text-muted mb-0">Bu seyahate ait rezervasyon bulunmuyor.</p>
                    </div>
                @else
                    @foreach ($travel->bookings as $booking)
                        <div class="booking-card">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <span class="booking-type {{ $booking->type }}">
                                        @if ($booking->type == 'flight')
                                            ✈️ Uçuş
                                        @elseif($booking->type == 'bus')
                                            🚌 Otobüs
                                        @elseif($booking->type == 'hotel')
                                            🏨 Otel
                                        @elseif($booking->type == 'car_rental')
                                            🚗 Araç
                                        @elseif($booking->type == 'train')
                                            🚆 Tren
                                        @else
                                            📋 Diğer
                                        @endif
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <strong style="font-size: 1.1rem;">{{ $booking->provider_name }}</strong>
                                    <div class="text-muted small">Kod: {{ $booking->confirmation_code }}</div>
                                </div>
                                <div class="col-md-2">
                                    <div class="text-muted small">Tarih</div>
                                    <strong>{{ \Carbon\Carbon::parse($booking->start_datetime)->format('d/m/Y H:i') }}</strong>
                                </div>
                                <div class="col-md-3">
                                    @if ($booking->getMedia('attachments')->count() > 0)
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($booking->getMedia('attachments') as $media)
                                                <a href="{{ $media->getUrl() }}" target="_blank" class="file-badge">
                                                    <i class="fa-solid fa-paperclip"></i>
                                                    {{ Str::limit($media->file_name, 15) }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small">Dosya yok</span>
                                    @endif
                                </div>
                                <div class="col-md-2 text-end">
                                    @if (Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager'))
                                        <div class="action-buttons">
                                            <a href="{{ route('bookings.edit', $booking) }}"
                                                class="btn btn-sm-modern btn-outline-modern" title="Düzenle">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                                onsubmit="return confirm('Bu rezervasyonu silmek istediğinizden emin misiniz?');"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-sm-modern btn-outline-modern text-danger" title="Sil">
                                                    <i class="fa-solid fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Visits Section --}}
                <div class="section-title mt-5">
                    <i class="fa-solid fa-handshake"></i>
                    <h5 class="mb-0">Bağlı Ziyaretler <span
                            class="badge bg-primary rounded-pill">{{ $travel->customerVisits->count() }}</span></h5>
                </div>

                @if ($travel->customerVisits->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-calendar-xmark"></i>
                        <h5 class="text-muted">Henüz Ziyaret Yok</h5>
                        <p class="text-muted mb-0">Bu seyahate bağlı ziyaret bulunmuyor.</p>
                    </div>
                @else
                    @foreach ($travel->customerVisits as $visit)
                        <div class="visit-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="text-muted small">Müşteri</div>
                                    @if ($visit->customer)
                                        <a href="{{ route('customers.show', $visit->customer) }}"
                                            style="font-weight: 600; font-size: 1.05rem; color: #667EEA;">
                                            {{ $visit->customer->name }}
                                        </a>
                                    @else
                                        <span class="text-danger fst-italic">Müşteri Silinmiş</span>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Etkinlik</div>
                                    <strong>{{ $visit->event->title ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Tarih</div>
                                    <strong>{{ $visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-' }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-muted small">Amaç</div>
                                    <strong>{{ $visit->visit_purpose }}</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- ============================== --}}
                {{-- MASRAF YÖNETİMİ (EXPENSES) --}}
                {{-- ============================== --}}
                <div class="section-title mt-5 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <h5 class="mb-0">Masraflar</h5>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $travel->expenses->count() }}</span>
                    </div>

                    {{-- Masraf Ekle Butonu --}}
                    <button type="button" class="btn btn-warning btn-sm text-white" data-bs-toggle="modal"
                        data-bs-target="#addExpenseModal">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Masraf Ekle
                    </button>
                </div>

                @if ($travel->expenses->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-receipt"></i>
                        <h5 class="text-muted">Henüz Masraf Girilmedi</h5>
                        <p class="text-muted mb-0">Bu seyahate ait masraf kaydı bulunmuyor.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 bg-white border rounded shadow-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Kategori</th>
                                    <th>Açıklama</th>
                                    <th>Tarih</th>
                                    <th class="text-end">Tutar</th>
                                    <th class="text-end pe-4">İşlem</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($travel->expenses as $expense)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-light text-dark border">
                                                {{ $expense->category }}
                                            </span>
                                        </td>
                                        <td class="text-muted small">{{ $expense->description ?? '-' }}</td>
                                        <td>
                                            @if ($expense->receipt_date)
                                                <i class="fa-regular fa-calendar me-1 text-muted"></i>
                                                {{ $expense->receipt_date->format('d.m.Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($expense->amount, 2) }} {{ $expense->currency }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bu masrafı silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                @if ($travel->expenses->isNotEmpty())
                                    {{-- Araya ince bir çizgi atalım --}}
                                    <tr class="border-top border-2">
                                        <td colspan="5" class="p-0"></td>
                                    </tr>

                                    @foreach ($travel->expenses->groupBy('currency') as $currency => $groupedExpenses)
                                        @php
                                            $total = $groupedExpenses->sum('amount');
                                            // Para birimi ikonunu belirleyelim
                                            $symbol = match ($currency) {
                                                'TRY' => '₺',
                                                'USD' => '$',
                                                'EUR' => '€',
                                                'GBP' => '£',
                                                default => $currency,
                                            };
                                        @endphp
                                        <tr class="bg-light fw-bold" style="font-size: 0.95rem;">
                                            <td colspan="3" class="text-end text-muted">
                                                Toplam ({{ $currency }}):
                                            </td>
                                            <td class="text-end text-dark font-monospace">
                                                {{ number_format($total, 2) }} {{ $symbol }}
                                            </td>
                                            <td></td> {{-- İşlem butonu boşluğu --}}
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- MASRAF EKLEME MODALI --}}
                <div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg">
                            <div class="modal-header bg-warning text-white">
                                <h5 class="modal-title">
                                    <i class="fa-solid fa-file-invoice-dollar me-2"></i>Yeni Masraf Ekle
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Kapat"></button>
                            </div>

                            <form action="{{ route('expenses.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="travel_id" value="{{ $travel->id }}">

                                <div class="modal-body p-4">
                                    {{-- 1. KATEGORİ SEÇİMİ --}}
                                    <div class="mb-4">
                                        <label for="expenseCategory"
                                            class="form-label fw-bold text-secondary">Kategori</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i
                                                    class="fa-solid fa-list text-muted"></i></span>
                                            <select name="category" id="expenseCategory"
                                                class="form-select border-start-0 ps-0" required>
                                                <option value="" selected disabled>Lütfen bir kategori seçin...
                                                </option>
                                                <option value="Ulaşım">✈️ Ulaşım (Uçak, Taksi, Yakıt)</option>
                                                <option value="Konaklama">🏨 Konaklama / Otel</option>
                                                <option value="Yemek">🍽️ Yemek / Restoran</option>
                                                <option value="Temsil">🤝 Temsil & Ağırlama</option>
                                                <option value="Diğer">🔹 Diğer</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- 2. DİNAMİK AÇIKLAMA ALANI (Sadece 'Diğer' seçilince açılır) --}}
                                    <div class="mb-4 d-none" id="otherDescriptionContainer">
                                        <label for="expenseDescription" class="form-label fw-bold text-danger">
                                            Masraf Detayı (Lütfen Belirtiniz) <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-danger text-white border-0"><i
                                                    class="fa-solid fa-pen"></i></span>
                                            <input type="text" name="description" id="expenseDescription"
                                                class="form-control" placeholder="Örn: Vize ücreti, Otopark fişi...">
                                        </div>
                                    </div>

                                    {{-- 3. TUTAR VE PARA BİRİMİ --}}
                                    <div class="row g-3 mb-4">
                                        <div class="col-8">
                                            <label for="expenseAmount"
                                                class="form-label fw-bold text-secondary">Tutar</label>
                                            <div class="input-group">
                                                <input type="number" step="0.01" name="amount" id="expenseAmount"
                                                    class="form-control" placeholder="0.00" required>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label for="expenseCurrency" class="form-label fw-bold text-secondary">Para
                                                Birimi</label>
                                            <select name="currency" id="expenseCurrency"
                                                class="form-select font-monospace fw-bold">
                                                <option value="TRY" selected>₺ TRY</option>
                                                <option value="USD">$ USD</option>
                                                <option value="EUR">€ EUR</option>
                                                <option value="GBP">£ GBP</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- 4. TARİH ALANI --}}
                                    <div class="mb-2">
                                        <label for="expenseDate" class="form-label fw-bold text-secondary">Fiş / Fatura
                                            Tarihi</label>
                                        <input type="date" name="receipt_date" id="expenseDate" class="form-control"
                                            value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="modal-footer bg-light border-0">
                                    <button type="button" class="btn btn-outline-secondary"
                                        data-bs-dismiss="modal">İptal</button>
                                    <button type="submit" class="btn btn-warning text-white px-4">
                                        <i class="fa-regular fa-save me-1"></i> Kaydet
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- JAVASCRIPT: Dinamik Alan Yönetimi --}}
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const categorySelect = document.getElementById('expenseCategory');
                        const otherDescContainer = document.getElementById('otherDescriptionContainer');
                        const otherDescInput = document.getElementById('expenseDescription');

                        // Kategori değiştiğinde çalışacak fonksiyon
                        categorySelect.addEventListener('change', function() {
                            if (this.value === 'Diğer') {
                                // "Diğer" seçildiyse alanı göster ve inputu zorunlu yap
                                otherDescContainer.classList.remove('d-none');

                                // Animasyonlu açılış efekti
                                otherDescContainer.style.opacity = 0;
                                setTimeout(() => {
                                    otherDescContainer.style.opacity = 1;
                                    otherDescContainer.style.transition = 'opacity 0.3s';
                                }, 10);

                                otherDescInput.setAttribute('required', 'required');
                                otherDescInput.focus();
                            } else {
                                // Başka bir şey seçildiyse gizle ve zorunluluğu kaldır
                                otherDescContainer.classList.add('d-none');
                                otherDescInput.removeAttribute('required');
                                otherDescInput.value = '';
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
