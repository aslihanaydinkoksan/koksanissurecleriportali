@extends('layouts.app')

@section('title', $customer->name)

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {
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

        .customer-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            backdrop-filter: blur(10px);
        }

        /* Temel Sekme Tasarımı */
        .nav-tabs {
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
            gap: 0.6rem;
            display: flex;
            flex-wrap: wrap;
            padding-bottom: 0.5rem;
        }

        .nav-tabs .nav-item {
            flex: 0 0 auto;
            margin-bottom: 0.5rem;
        }

        .nav-tabs .nav-link {
            border-radius: 0.75rem;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            border: 1px solid transparent;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .nav-tabs .nav-link i {
            margin-right: 0.5rem;
            font-size: 1.1em;
        }

        /* Sekme Renklendirmeleri */
        .nav-tabs .nav-link.tab-details {
            background-color: rgba(59, 130, 246, 0.15);
            color: #1d4ed8;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .nav-tabs .nav-link.tab-details:hover {
            background-color: rgba(59, 130, 246, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.15);
        }

        .nav-tabs .nav-link.tab-details.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-products {
            background-color: rgba(99, 102, 241, 0.15);
            color: #4338ca;
            border-color: rgba(99, 102, 241, 0.3);
        }

        .nav-tabs .nav-link.tab-products:hover {
            background-color: rgba(99, 102, 241, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.15);
        }

        .nav-tabs .nav-link.tab-products.active {
            background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-opportunities {
            background-color: rgba(245, 158, 11, 0.15);
            color: #b45309;
            border-color: rgba(245, 158, 11, 0.3);
        }

        .nav-tabs .nav-link.tab-opportunities:hover {
            background-color: rgba(245, 158, 11, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.15);
        }

        .nav-tabs .nav-link.tab-opportunities.active {
            background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-activities {
            background-color: rgba(16, 185, 129, 0.15);
            color: #047857;
            border-color: rgba(16, 185, 129, 0.3);
        }

        .nav-tabs .nav-link.tab-activities:hover {
            background-color: rgba(16, 185, 129, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
        }

        .nav-tabs .nav-link.tab-activities.active {
            background: linear-gradient(135deg, #10b981 0%, #047857 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-visits {
            background-color: rgba(6, 182, 212, 0.15);
            color: #0369a1;
            border-color: rgba(6, 182, 212, 0.3);
        }

        .nav-tabs .nav-link.tab-visits:hover {
            background-color: rgba(6, 182, 212, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(6, 182, 212, 0.15);
        }

        .nav-tabs .nav-link.tab-visits.active {
            background: linear-gradient(135deg, #06b6d4 0%, #0369a1 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-samples {
            background-color: rgba(139, 92, 246, 0.15);
            color: #6d28d9;
            border-color: rgba(139, 92, 246, 0.3);
        }

        .nav-tabs .nav-link.tab-samples:hover {
            background-color: rgba(139, 92, 246, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(139, 92, 246, 0.15);
        }

        .nav-tabs .nav-link.tab-samples.active {
            background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(139, 92, 246, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-returns {
            background-color: rgba(244, 63, 94, 0.15);
            color: #be123c;
            border-color: rgba(244, 63, 94, 0.3);
        }

        .nav-tabs .nav-link.tab-returns:hover {
            background-color: rgba(244, 63, 94, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(244, 63, 94, 0.15);
        }

        .nav-tabs .nav-link.tab-returns.active {
            background: linear-gradient(135deg, #f43f5e 0%, #be123c 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(244, 63, 94, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-complaints {
            background-color: rgba(220, 38, 38, 0.15);
            color: #991b1b;
            border-color: rgba(220, 38, 38, 0.3);
        }

        .nav-tabs .nav-link.tab-complaints:hover {
            background-color: rgba(220, 38, 38, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.15);
        }

        .nav-tabs .nav-link.tab-complaints.active {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-machines {
            background-color: rgba(71, 85, 105, 0.15);
            color: #334155;
            border-color: rgba(71, 85, 105, 0.3);
        }

        .nav-tabs .nav-link.tab-machines:hover {
            background-color: rgba(71, 85, 105, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(71, 85, 105, 0.15);
        }

        .nav-tabs .nav-link.tab-machines.active {
            background: linear-gradient(135deg, #64748b 0%, #334155 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(100, 116, 139, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-tests {
            background-color: rgba(217, 70, 239, 0.15);
            color: #a21caf;
            border-color: rgba(217, 70, 239, 0.3);
        }

        .nav-tabs .nav-link.tab-tests:hover {
            background-color: rgba(217, 70, 239, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(217, 70, 239, 0.15);
        }

        .nav-tabs .nav-link.tab-tests.active {
            background: linear-gradient(135deg, #d946ef 0%, #a21caf 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(217, 70, 239, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.tab-logistics {
            background-color: rgba(13, 148, 136, 0.15);
            color: #0f766e;
            border-color: rgba(13, 148, 136, 0.3);
        }

        .nav-tabs .nav-link.tab-logistics:hover {
            background-color: rgba(13, 148, 136, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.15);
        }

        .nav-tabs .nav-link.tab-logistics.active {
            background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
            color: white !important;
            box-shadow: 0 4px 15px rgba(13, 148, 136, 0.4);
            border-color: transparent;
        }

        .nav-tabs .nav-link.active .badge {
            background-color: rgba(255, 255, 255, 0.25) !important;
            color: white !important;
            border-color: transparent !important;
        }

        .tab-content {
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .quick-add-form {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 2px dashed rgba(102, 126, 234, 0.2);
        }

        .quick-add-form .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .quick-add-form .form-control,
        .quick-add-form .form-select {
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .quick-add-form .form-control:focus,
        .quick-add-form .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        /* Ses Kayıt Animasyonu */
        .pulsing {
            animation: pulse-red 1.5s infinite;
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .table {
            background-color: transparent;
        }

        .table thead th {
            background-color: rgba(102, 126, 234, 0.08);
            border-bottom: 2px solid #667EEA;
        }

        .filter-bar {
            background-color: rgba(245, 247, 250, 0.7);
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .status-select {
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .status-select.status-planlandi,
        .status-select.status-pending,
        .status-select.status-open,
        .status-select.status-preparing {
            color: #d97706;
            background-color: #fffbeb;
            border-color: #fcd34d;
        }

        .status-select.status-gerceklesti,
        .status-select.status-approved,
        .status-select.status-resolved,
        .status-select.status-delivered {
            color: #059669;
            background-color: #ecfdf5;
            border-color: #6ee7b7;
        }

        .status-select.status-iptal,
        .status-select.status-rejected {
            color: #dc2626;
            background-color: #fef2f2;
            border-color: #fca5a5;
        }

        .status-select.status-ertelendi,
        .status-select.status-completed,
        .status-select.status-in_progress,
        .status-select.status-sent {
            color: #0891b2;
            background-color: #ecfeff;
            border-color: #67e8f9;
        }

        .opp-card {
            transition: all 0.3s ease;
            border-left: 5px solid transparent;
        }

        .opp-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
        }

        .opp-duyum {
            border-left-color: #6c757d;
        }

        .opp-teklif {
            border-left-color: #f59e0b;
        }

        .opp-gorusme {
            border-left-color: #3b82f6;
        }

        .opp-kazanildi {
            border-left-color: #10b981;
        }

        .opp-kaybedildi {
            border-left-color: #ef4444;
            opacity: 0.7;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card">
                    {{-- Header Kısmı --}}
                    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                            <div>
                                <h2 class="mb-2 fw-bold" style="color: #2d3748;">
                                    <i class="fa-solid fa-building me-2" style="color: #667EEA;"></i>{{ $customer->name }}
                                    @if ($customer->is_active ?? true)
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success border border-success fs-6 align-middle ms-2"
                                            style="border-radius: 8px;"><i class="fa-solid fa-circle-check me-1"></i>
                                            Aktif</span>
                                    @else
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger border border-danger fs-6 align-middle ms-2"
                                            style="border-radius: 8px;"><i class="fa-solid fa-ban me-1"></i> Pasif</span>
                                    @endif
                                </h2>
                                <p class="text-muted mb-0">Müşteri Detayları ve İşlemler</p>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('customers.edit', $customer) }}"
                                    class="btn btn-animated-gradient rounded-pill px-4"><i
                                        class="fa-solid fa-pen me-2"></i>Bilgileri Düzenle</a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"
                                        onclick="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?');"><i
                                            class="fa-solid fa-trash-alt me-2"></i>Kaydı Sil</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4">
                        @if (session('success'))
                            <div class="alert alert-success d-flex align-items-center"><i
                                    class="fa-solid fa-circle-check me-3 fs-4"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center"><i
                                    class="fa-solid fa-circle-xmark me-3 fs-4"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Kayıt eklenirken bir hata
                                    oluştu:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Sekme Başlıkları --}}
                        <ul class="nav nav-tabs mt-4" id="customerTab" role="tablist">
                            <li class="nav-item"><button class="nav-link active tab-details" id="details-tab"
                                    data-bs-toggle="tab" data-bs-target="#details"><i class="fa-solid fa-user"></i>
                                    Detaylar</button></li>
                            <li class="nav-item"><button class="nav-link tab-products" id="products-tab"
                                    data-bs-toggle="tab" data-bs-target="#products"><i class="fa-solid fa-box-open"></i>
                                    Ürünler @if ($customer->products->count() > 0)
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill ms-1">{{ $customer->products->count() }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item"><button class="nav-link tab-opportunities" id="opportunities-tab"
                                    data-bs-toggle="tab" data-bs-target="#opportunities"><i
                                        class="fa-solid fa-bullseye"></i> Fırsatlar @if ($customer->opportunities->count() > 0)
                                        <span
                                            class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill ms-1">{{ $customer->opportunities->count() }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item"><button class="nav-link tab-activities" id="activities-tab"
                                    data-bs-toggle="tab" data-bs-target="#activities"><i class="fas fa-history"></i>
                                    İletişim ({{ $customer->activities->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-visits" id="visits-tab" data-bs-toggle="tab"
                                    data-bs-target="#visits"><i class="fa-solid fa-calendar-days"></i> Ziyaretler
                                    ({{ $customer->visits->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-samples" id="samples-tab" data-bs-toggle="tab"
                                    data-bs-target="#samples"><i class="fa-solid fa-flask"></i> Numuneler
                                    ({{ $customer->samples->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-returns" id="returns-tab" data-bs-toggle="tab"
                                    data-bs-target="#returns"><i class="fa-solid fa-rotate-left"></i> İadeler
                                    ({{ $customer->returns->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-complaints" id="complaints-tab"
                                    data-bs-toggle="tab" data-bs-target="#complaints"><i
                                        class="fa-solid fa-exclamation-triangle"></i> Şikayetler
                                    ({{ $customer->complaints->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-machines" id="machines-tab"
                                    data-bs-toggle="tab" data-bs-target="#machines"><i class="fa-solid fa-industry"></i>
                                    Makineler ({{ $customer->machines->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-tests" id="tests-tab" data-bs-toggle="tab"
                                    data-bs-target="#tests"><i class="fa-solid fa-vial"></i> Testler
                                    ({{ $customer->testResults->count() }})</button></li>
                            <li class="nav-item"><button class="nav-link tab-logistics" id="logistics-tab"
                                    data-bs-toggle="tab" data-bs-target="#logistics"><i class="fas fa-truck"></i>
                                    Lojistik ({{ $customer->vehicleAssignments->count() }})</button></li>
                        </ul>

                        <div class="tab-content" id="customerTabContent">

                            {{-- DETAYLAR TABI --}}
                            <div class="tab-pane fade show active" id="details" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                            <h5 class="mb-0 border-0"><i class="fa-solid fa-building me-2"></i>Firma
                                                Bilgileri</h5>
                                        </div>
                                        <dl class="row detail-list mt-3">
                                            <dt class="col-sm-4 text-primary">Çalışma Başlangıcı</dt>
                                            <dd class="col-sm-8 fw-bold text-primary">
                                                {{ $customer->start_date ? \Carbon\Carbon::parse($customer->start_date)->format('d.m.Y') : 'Tarih Belirtilmedi' }}
                                            </dd>
                                            @if (!($customer->is_active ?? true) && $customer->end_date)
                                                <dt class="col-sm-4 text-danger">Çalışma Bitişi</dt>
                                                <dd class="col-sm-8 fw-bold text-danger">
                                                    {{ \Carbon\Carbon::parse($customer->end_date)->format('d.m.Y') }}</dd>
                                            @endif
                                            <dt class="col-sm-4 mt-2">Adres</dt>
                                            <dd class="col-sm-8 mt-2">{{ $customer->address ?: '-' }}</dd>
                                            <dt class="col-sm-4">Genel Tel</dt>
                                            <dd class="col-sm-8">{{ $customer->phone ?: '-' }}</dd>
                                            <dt class="col-sm-4">Genel Email</dt>
                                            <dd class="col-sm-8">{{ $customer->email ?: '-' }}</dd>
                                        </dl>
                                    </div>
                                    <div class="col-md-6">
                                        <div
                                            class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                            <h5 class="mb-0 border-0"><i class="fa-solid fa-users me-2"></i>İletişim
                                                Kişileri</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Ad Soyad</th>
                                                        <th>Ünvan</th>
                                                        <th>İletişim</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($customer->contacts as $contact)
                                                        <tr>
                                                            <td class="align-middle"><span
                                                                    class="fw-bold text-dark">{{ $contact->name }}</span>
                                                                @if ($contact->is_primary)
                                                                    <i class="fa-solid fa-star text-warning small ms-1"
                                                                        title="Ana İletişim"></i>
                                                                @endif
                                                            </td>
                                                            <td class="align-middle text-muted small">
                                                                {{ $contact->title ?? '-' }}</td>
                                                            <td class="small">
                                                                @if ($contact->email)
                                                                    <div class="mb-1"><i
                                                                            class="fa-solid fa-envelope text-primary me-1"></i>{{ $contact->email }}
                                                                    </div>
                                                                @endif
                                                                @if ($contact->phone)
                                                                    <div><i
                                                                            class="fa-solid fa-phone text-success me-1"></i>{{ $contact->phone }}
                                                                    </div>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center text-muted">Kayıtlı kişi
                                                                yok.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ÜRÜNLER TABI --}}
                            <div class="tab-pane fade" id="products" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2 text-primary"></i>Yeni Ürün / Proje Ekle</h5>
                                <form action="{{ route('customers.products.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-5">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><label class="form-label">Ürün Adı (*)</label><input
                                                type="text" name="name" class="form-control" required
                                                placeholder="Örn: 19L Damacana"></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Kategori</label><input
                                                type="text" name="category" class="form-control"
                                                placeholder="Örn: Su"></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Yıllık Tahmini
                                                Hacim</label><input type="text" name="annual_volume"
                                                class="form-control" placeholder="Örn: 5 Milyon"></div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Notlar</label>
                                            <div class="input-group">
                                                <input type="text" name="notes" id="new_product_notes"
                                                    class="form-control" placeholder="Ekstra detaylar...">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="btn_new_prod_note"
                                                    onclick="toggleVoiceInput('new_product_notes', 'btn_new_prod_note')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4"><i
                                                class="fa-solid fa-save me-2"></i> Ürünü Kaydet</button></div>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-box-open me-2 text-primary"></i>Kayıtlı
                                        Ürünler & Projeler</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="text"
                                            id="filterProdSearch" class="filter-input bg-white"
                                            placeholder="Ürün veya kategori ara..."></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Ürün / Proje Adı</th>
                                                <th>Kategori</th>
                                                <th>Yıllık Hacim</th>
                                                <th>Notlar</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productsList">
                                            @forelse($customer->products as $prod)
                                                <tr class="product-item"
                                                    data-search="{{ mb_strtolower($prod->name . ' ' . $prod->category) }}">
                                                    <td class="fw-bold text-dark">{{ $prod->name }}</td>
                                                    <td><span
                                                            class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $prod->category ?? 'Belirtilmedi' }}</span>
                                                    </td>
                                                    <td>{{ $prod->annual_volume ?? '-' }}</td>
                                                    <td>{{ Str::limit($prod->notes, 30) }}</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editProductModal{{ $prod->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('customer-products.destroy', $prod->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="fa-solid fa-trash-alt"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="5" class="text-center text-muted py-4"><i
                                                            class="fa-solid fa-box-open fa-2x mb-2 opacity-50"></i>
                                                        <p class="mb-0">Müşteriye ait kaydedilmiş bir ürün bulunamadı.
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- FIRSATLAR VE DUYUMLAR TABI --}}
                            <div class="tab-pane fade" id="opportunities" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2 text-warning"></i>Yeni Fırsat / Duyum Ekle</h5>
                                <form action="{{ route('customers.opportunities.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-5">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">Başlık / Konu
                                                (*)</label><input type="text" name="title" class="form-control"
                                                required placeholder="Örn: 2025 Yılı Preform İhalesi"></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Tahmini Tutar & Para
                                                Birimi</label>
                                            <div class="input-group"><input type="number" name="amount"
                                                    class="form-control" step="0.01" placeholder="0.00"><select
                                                    name="currency" class="form-select" style="max-width: 80px;">
                                                    <option value="TRY">₺</option>
                                                    <option value="USD">$</option>
                                                    <option value="EUR">€</option>
                                                </select></div>
                                        </div>
                                        <div class="col-md-2 mb-3"><label class="form-label">Beklenen Karar
                                                Trh.</label><input type="date" name="expected_close_date"
                                                class="form-control"></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Aşama (*)</label><select
                                                name="stage" class="form-select" required>
                                                <option value="duyum">Söylenti / Duyum</option>
                                                <option value="teklif">Teklif Verildi</option>
                                                <option value="gorusme">Görüşme Aşamasında</option>
                                            </select></div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Detaylar / Kaynak Bilgisi</label>
                                            <div class="input-group">
                                                <textarea name="description" id="new_opp_desc" class="form-control" rows="2"
                                                    placeholder="Fırsatın kaynağı nedir? Müşterinin mevcut durumu nedir?"></textarea>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="btn_new_opp_desc"
                                                    onclick="toggleVoiceInput('new_opp_desc', 'btn_new_opp_desc')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4"><i
                                                class="fa-solid fa-save me-2"></i> Fırsatı Kaydet</button></div>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-bullseye me-2 text-warning"></i>Açık
                                        Fırsatlar ve Duyumlar</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
                                        <i class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterOppDate" class="filter-input bg-white"><input type="text"
                                            id="filterOppSearch" class="filter-input bg-white"
                                            placeholder="Başlık ara..."><select id="filterOppStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 140px;">
                                            <option value="">Tüm Aşamalar</option>
                                            <option value="duyum">Duyum</option>
                                            <option value="teklif">Teklif Verildi</option>
                                            <option value="gorusme">Görüşme</option>
                                            <option value="kazanildi">Kazanıldı</option>
                                            <option value="kaybedildi">Kaybedildi</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="opportunitiesList">
                                    @forelse($customer->opportunities as $opp)
                                        <div class="col-md-6 col-lg-4 mb-4 opp-item"
                                            data-date="{{ $opp->created_at->format('Y-m-d') }}"
                                            data-search="{{ mb_strtolower($opp->title . ' ' . ($opp->user->name ?? '')) }}"
                                            data-status="{{ $opp->stage }}">
                                            <div class="card h-100 shadow-sm opp-card opp-{{ $opp->stage }}">
                                                <div class="card-body position-relative">
                                                    <div class="position-absolute top-0 end-0 mt-3 me-3">
                                                        <form action="{{ route('opportunities.update-stage', $opp->id) }}"
                                                            method="POST">@csrf @method('PATCH')
                                                            <select name="stage"
                                                                class="form-select form-select-sm border-0 bg-light fw-bold text-muted shadow-none"
                                                                onchange="this.form.submit()" style="cursor: pointer;">
                                                                <option value="duyum"
                                                                    {{ $opp->stage == 'duyum' ? 'selected' : '' }}>Duyum
                                                                </option>
                                                                <option value="teklif"
                                                                    {{ $opp->stage == 'teklif' ? 'selected' : '' }}>Teklif
                                                                    Verildi</option>
                                                                <option value="gorusme"
                                                                    {{ $opp->stage == 'gorusme' ? 'selected' : '' }}>
                                                                    Görüşme</option>
                                                                <option value="kazanildi"
                                                                    {{ $opp->stage == 'kazanildi' ? 'selected' : '' }}>🎉
                                                                    Kazanıldı</option>
                                                                <option value="kaybedildi"
                                                                    {{ $opp->stage == 'kaybedildi' ? 'selected' : '' }}>❌
                                                                    Kaybedildi</option>
                                                            </select>
                                                        </form>
                                                    </div>
                                                    <h6 class="fw-bold text-dark mb-1 pe-5">{{ $opp->title }}</h6>
                                                    <div class="text-muted small mb-3"><i
                                                            class="fa-solid fa-user-circle me-1"></i>{{ $opp->user ? $opp->user->name : 'Sistem' }}
                                                        &bull; {{ $opp->created_at->format('d.m.Y') }}</div>
                                                    @if ($opp->amount)
                                                        <h4 class="text-success mb-3 fw-bold">
                                                            {{ number_format($opp->amount, 2, ',', '.') }}
                                                            {{ $opp->currency }}</h4>
                                                    @else
                                                        <h4 class="text-secondary mb-3 fs-6 fst-italic">Tutar Belirtilmemiş
                                                        </h4>
                                                    @endif
                                                    <p class="small text-dark mb-3" style="min-height: 40px;">
                                                        {{ Str::limit($opp->description, 100) }}</p>
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                                                        <span class="small fw-semibold text-muted"><i
                                                                class="fa-regular fa-calendar-check me-1"></i>{{ $opp->expected_close_date ? $opp->expected_close_date->format('d.m.Y') : 'Tarih Yok' }}</span>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-sm btn-light border"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editOppModal{{ $opp->id }}"
                                                                title="Düzenle"><i
                                                                    class="fa-solid fa-pen text-primary"></i></button>
                                                            <form action="{{ route('opportunities.destroy', $opp->id) }}"
                                                                method="POST">@csrf @method('DELETE')<button
                                                                    type="submit" class="btn btn-sm btn-light border"
                                                                    onclick="return confirm('Bu fırsatı silmek istediğinize emin misiniz?');"><i
                                                                        class="fa-solid fa-trash-alt text-danger"></i></button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty <div class="col-12 empty-message-row">
                                            <div
                                                class="alert alert-light text-center py-5 border border-dashed text-muted">
                                                <i class="fa-solid fa-ghost fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">Bu müşteriye ait kayıtlı bir fırsat veya duyum
                                                    bulunmuyor.</p>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- İLETİŞİM GEÇMİŞİ TABI --}}
                            <div class="tab-pane fade" id="activities" role="tabpanel">
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm" style="background: #f8f9fa;">
                                            <div class="card-body">
                                                <h6 class="fw-bold mb-3 text-primary"><i
                                                        class="fas fa-plus-circle me-1"></i> Yeni İşlem Gir</h6>
                                                <form action="{{ route('customers.activities.store', $customer->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="mb-3"><label
                                                            class="form-label small fw-bold text-muted">İşlem
                                                            Tipi</label><select name="type" class="form-select">
                                                            <option value="phone">📞 Telefon Görüşmesi</option>
                                                            <option value="meeting">🤝 Yüz Yüze Toplantı</option>
                                                            <option value="email">✉️ E-Posta</option>
                                                            <option value="visit">🏢 Müşteri Ziyareti</option>
                                                            <option value="note">📝 Genel Not</option>
                                                        </select></div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">Görüşülen
                                                            Kişiler</label>
                                                        <div class="dropdown mb-2"><button
                                                                class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white"
                                                                type="button" data-bs-toggle="dropdown"
                                                                data-bs-auto-close="outside">Müşteri yetkililerini
                                                                seçin...</button>
                                                            <ul class="dropdown-menu w-100 p-2 shadow-sm"
                                                                style="max-height: 200px; overflow-y: auto;">
                                                                @forelse($customer->contacts as $contact)
                                                                    <li>
                                                                        <div class="form-check m-1"><input
                                                                                class="form-check-input" type="checkbox"
                                                                                name="contact_persons[]"
                                                                                value="{{ $contact->name }}"
                                                                                id="new_contact_{{ $contact->id }}"
                                                                                style="cursor: pointer;"><label
                                                                                class="form-check-label"
                                                                                for="new_contact_{{ $contact->id }}"
                                                                                style="cursor: pointer;">{{ $contact->name }}
                                                                                <small
                                                                                    class="text-muted">({{ $contact->title ?? 'Ünvan Yok' }})</small></label>
                                                                        </div>
                                                                    </li>
                                                                @empty <li class="text-muted small text-center p-2">
                                                                        Kayıtlı kişi bulunamadı.</li>
                                                                @endforelse
                                                            </ul>
                                                        </div>
                                                        <input type="text" name="other_contact_persons"
                                                            class="form-control form-control-sm"
                                                            placeholder="Farklı biri varsa yazın (Virgülle ayırın)...">
                                                    </div>
                                                    <div class="mb-3"><label
                                                            class="form-label small fw-bold text-muted">Tarih &
                                                            Saat</label><input type="datetime-local" name="activity_date"
                                                            class="form-control"
                                                            value="{{ now()->format('Y-m-d\TH:i') }}"></div>
                                                    <div class="mb-3">
                                                        <label class="form-label small fw-bold text-muted">Detaylar</label>
                                                        <div class="input-group">
                                                            <textarea name="description" id="new_activity_desc" class="form-control" rows="4"
                                                                placeholder="Neler konuşuldu? Sonuç ne?" required></textarea>
                                                            <button class="btn btn-outline-secondary" type="button"
                                                                id="btn_new_act_desc"
                                                                onclick="toggleVoiceInput('new_activity_desc', 'btn_new_act_desc')"><i
                                                                    class="fa-solid fa-microphone"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="d-grid"><button type="submit"
                                                            class="btn btn-primary text-white"
                                                            style="background: linear-gradient(135deg, #667EEA, #764BA2); border:none;">Kaydet</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="fw-bold mb-0 text-secondary">Geçmiş Hareketler</h6>
                                            <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                                    class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                                    id="filterActDate" class="filter-input bg-white"><input
                                                    type="text" id="filterActSearch" class="filter-input bg-white"
                                                    placeholder="İçerik ara..."><select id="filterActStatus"
                                                    class="form-select filter-input bg-white py-1"
                                                    style="min-width: 130px;">
                                                    <option value="">Tüm Tipler</option>
                                                    <option value="phone">Telefon</option>
                                                    <option value="meeting">Toplantı</option>
                                                    <option value="email">E-Posta</option>
                                                    <option value="visit">Ziyaret</option>
                                                    <option value="note">Not</option>
                                                </select></div>
                                        </div>
                                        <div class="timeline" id="activitiesList">
                                            @forelse($customer->activities as $activity)
                                                <div class="card mb-3 border-0 shadow-sm activity-item"
                                                    data-date="{{ $activity->activity_date->format('Y-m-d') }}"
                                                    data-search="{{ mb_strtolower($activity->description . ' ' . ($activity->user->name ?? '') . ' ' . implode(' ', $activity->contact_persons ?? [])) }}"
                                                    data-status="{{ $activity->type }}">
                                                    <div class="card-body position-relative">
                                                        <div class="position-absolute top-0 start-0 bottom-0 rounded-start"
                                                            style="width: 5px; background: {{ $activity->type == 'phone' ? '#3b82f6' : ($activity->type == 'meeting' ? '#10b981' : ($activity->type == 'email' ? '#f59e0b' : '#6b7280')) }};">
                                                        </div>
                                                        <div class="position-absolute top-0 end-0 mt-3 me-3 d-flex gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-link text-primary p-0"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editActivityModal{{ $activity->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('customer-activities.destroy', $activity->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bu kaydı silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-link text-danger p-0"><i
                                                                        class="fa-solid fa-trash-alt"></i></button></form>
                                                        </div>
                                                        <div
                                                            class="d-flex justify-content-between align-items-center mb-2 ps-2 pe-5">
                                                            <div>
                                                                <span class="badge bg-light text-dark border me-2">
                                                                    @if ($activity->type == 'phone')
                                                                        <i class="fas fa-phone text-primary"></i> Telefon
                                                                    @elseif($activity->type == 'meeting')
                                                                        <i class="fas fa-handshake text-success"></i>
                                                                        Toplantı
                                                                    @elseif($activity->type == 'email')
                                                                        <i class="fas fa-envelope text-warning"></i>
                                                                        E-Posta
                                                                    @elseif($activity->type == 'visit')
                                                                        <i class="fas fa-building text-info"></i> Ziyaret
                                                                    @else
                                                                        <i class="fas fa-sticky-note text-secondary"></i>
                                                                        Not
                                                                    @endif
                                                                </span>
                                                                <span
                                                                    class="text-muted small">{{ $activity->activity_date->format('d.m.Y H:i') }}</span>
                                                            </div>
                                                            <small class="text-muted fst-italic"><i
                                                                    class="fas fa-user-circle me-1"></i>{{ $activity->user->name }}</small>
                                                        </div>
                                                        <div class="ps-2 mt-2">
                                                            @if (!empty($activity->contact_persons))
                                                                <div class="mb-2"><small class="text-muted fw-bold"><i
                                                                            class="fa-solid fa-users me-1"></i>Görüşülen
                                                                        Kişiler:</small>
                                                                    <div class="mt-1">
                                                                        @foreach ($activity->contact_persons as $person)
                                                                            <span
                                                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-2 py-1 me-1 mb-1">{{ $person }}</span>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="text-dark" style="white-space: pre-line;">
                                                                {{ $activity->description }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty <div
                                                    class="alert alert-light text-center border border-dashed p-4 empty-message-row">
                                                    <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0 text-muted">Henüz bu müşteriyle ilgili kaydedilmiş bir
                                                        aktivite yok.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ZİYARETLER / SERVİS FORMU SEKME İÇERİĞİ --}}
                            <div class="tab-pane fade" id="visits" role="tabpanel">
                                <h5><i class="fa-solid fa-clipboard-list me-2 text-primary"></i>Yeni Müşteri Ziyaret Formu
                                </h5>
                                <form action="{{ route('customers.visits.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-5" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row mb-3">
                                        <div class="col-md-3"><label class="form-label">Ziyaret Tarihi (*)</label><input
                                                type="datetime-local" name="visit_date" class="form-control"
                                                value="{{ now()->format('Y-m-d\TH:i') }}" required></div>
                                        <div class="col-md-9"><label class="form-label d-block">Ziyaret Sebebi (*)</label>
                                            <div class="btn-group w-100" role="group"><input type="radio"
                                                    class="btn-check" name="visit_reason" id="reason1" value="Şikayet"
                                                    autocomplete="off"><label class="btn btn-outline-danger"
                                                    for="reason1"><i class="fa-solid fa-triangle-exclamation me-1"></i>
                                                    Şikayet</label><input type="radio" class="btn-check"
                                                    name="visit_reason" id="reason2" value="Periyodik Ziyaret"
                                                    autocomplete="off"><label class="btn btn-outline-primary"
                                                    for="reason2"><i class="fa-solid fa-calendar-check me-1"></i>
                                                    Ziyaret</label><input type="radio" class="btn-check"
                                                    name="visit_reason" id="reason3" value="Ürün Denemesi"
                                                    autocomplete="off"><label class="btn btn-outline-success"
                                                    for="reason3"><i class="fa-solid fa-flask me-1"></i> Ürün
                                                    Denemesi</label><input type="radio" class="btn-check"
                                                    name="visit_reason" id="reason4" value="Diğer" autocomplete="off"
                                                    checked><label class="btn btn-outline-secondary"
                                                    for="reason4">Diğer</label></div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6"><label class="form-label">Görüşülen Kişiler</label>
                                            <div class="dropdown mb-2"><button
                                                    class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white"
                                                    type="button" data-bs-toggle="dropdown"
                                                    data-bs-auto-close="outside">Listeden Seçiniz...</button>
                                                <ul class="dropdown-menu w-100 p-2 shadow-sm"
                                                    style="max-height: 200px; overflow-y: auto;">
                                                    @forelse($customer->contacts as $contact)
                                                        <li>
                                                            <div class="form-check m-1"><input class="form-check-input"
                                                                    type="checkbox" name="contact_persons[]"
                                                                    value="{{ $contact->name }}"
                                                                    id="vis_contact_{{ $contact->id }}"><label
                                                                    class="form-check-label"
                                                                    for="vis_contact_{{ $contact->id }}">{{ $contact->name }}
                                                                    <small
                                                                        class="text-muted">({{ $contact->title ?? '-' }})</small></label>
                                                            </div>
                                                    </li> @empty <li class="text-muted small p-2">Kayıtlı kişi yok.
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div><input type="text" name="other_contact_persons"
                                                class="form-control form-control-sm"
                                                placeholder="Listede olmayanlar (virgülle ayırın)...">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Açıklama (Opsiyonel)</label>
                                            <div class="input-group">
                                                <textarea name="visit_notes" id="new_visit_notes" class="form-control" rows="3"
                                                    placeholder="Ziyaret sebebi hakkında kısa not..."></textarea>
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="btn_new_visit_notes"
                                                    onclick="toggleVoiceInput('new_visit_notes', 'btn_new_visit_notes')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-md-3"><label class="form-label">Ürün Tanımı</label><select
                                                name="customer_product_id" class="form-select">
                                                <option value="">Seçiniz...</option>
                                                @foreach ($customer->products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3"><label class="form-label">Barkod No</label><input
                                                type="text" name="barcode" class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">Lot No</label><input
                                                type="text" name="lot_no" class="form-control"></div>
                                        <div class="col-md-3"><label class="form-label">Şikayet Kayıt No</label><select
                                                name="complaint_id" class="form-select">
                                                <option value="">Yok / Bağımsız</option>
                                                @foreach ($customer->complaints as $c)
                                                    <option value="{{ $c->id }}">#{{ $c->id }} -
                                                        {{ Str::limit($c->title, 15) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-primary">Tespitler / Yapılan İşlemler
                                                (*)</label>
                                            <div class="input-group">
                                                <textarea name="findings" id="new_visit_findings" class="form-control" rows="4" required
                                                    placeholder="Sahada ne gördünüz? Ne işlem yaptınız?"></textarea>
                                                <button class="btn btn-outline-primary" type="button"
                                                    id="btn_new_visit_findings"
                                                    onclick="toggleVoiceInput('new_visit_findings', 'btn_new_visit_findings')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-success">Sonuç / Karar (*)</label>
                                            <div class="input-group">
                                                <textarea name="result" id="new_visit_result" class="form-control" rows="4" required
                                                    placeholder="Sonuç ne oldu? Problem çözüldü mü?"></textarea>
                                                <button class="btn btn-outline-success" type="button"
                                                    id="btn_new_visit_result"
                                                    onclick="toggleVoiceInput('new_visit_result', 'btn_new_visit_result')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3"><label class="form-label fw-bold"><i
                                                    class="fa-solid fa-paperclip me-1"></i> Dosya / Fotoğraf
                                                Ekle</label><input type="file" name="visit_files[]"
                                                class="form-control" multiple><small class="text-muted">Resim, PDF, Excel
                                                veya Word dosyaları ekleyebilirsiniz.</small></div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-5 py-2"><i
                                                class="fa-solid fa-save me-2"></i> Formu Kaydet</button></div>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h5 class="mb-0"><i class="fa-solid fa-file-contract me-2 text-primary"></i>Kayıtlı
                                        Ziyaret Formları</h5>
                                    <div
                                        class="filter-bar p-2 rounded d-flex gap-2 align-items-center flex-grow-1 mx-lg-3">
                                        <i class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterVisDate" class="filter-input bg-white"><input type="text"
                                            id="filterVisSearch" class="filter-input bg-white flex-grow-1"
                                            placeholder="Ürün, kişi, sonuç ara..."><select id="filterVisStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 140px;">
                                            <option value="">Tüm Sebepler</option>
                                            <option value="Şikayet">Şikayet</option>
                                            <option value="Periyodik Ziyaret"> Ziyaret</option>
                                            <option value="Ürün Denemesi">Ürün Denemesi</option>
                                            <option value="Diğer">Diğer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="visitsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Sebep</th>
                                                <th>Ürün / Lot</th>
                                                <th>Görüşülen</th>
                                                <th>Sonuç</th>
                                                <th>Dosyalar</th>
                                                <th class="text-end">İşlemler</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($customer->visits as $visit)
                                                <tr class="visit-item"
                                                    data-date="{{ $visit->visit_date ? $visit->visit_date->format('Y-m-d') : $visit->created_at->format('Y-m-d') }}"
                                                    data-search="{{ mb_strtolower(($visit->product->name ?? '') . ' ' . implode(' ', $visit->contact_persons ?? []) . ' ' . $visit->result . ' ' . $visit->findings) }}"
                                                    data-status="{{ $visit->visit_reason }}">
                                                    <td>{{ $visit->visit_date ? $visit->visit_date->format('d.m.Y H:i') : $visit->created_at->format('d.m.Y H:i') }}
                                                    </td>
                                                    <td><span
                                                            class="badge bg-light text-dark border">{{ $visit->visit_reason ?? 'Belirtilmedi' }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($visit->product)
                                                            <strong
                                                                class="text-primary">{{ $visit->product->name }}</strong>
                                                            @if ($visit->lot_no)
                                                                <br><small class="text-muted">Lot:
                                                                    {{ $visit->lot_no }}</small>
                                                            @endif
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($visit->contact_persons)
                                                            @foreach ($visit->contact_persons as $p)
                                                                <span
                                                                    class="badge bg-secondary bg-opacity-10 text-secondary border">{{ $p }}</span>
                                                            @endforeach
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ Str::limit($visit->result, 40) }}</td>
                                                    <td>
                                                        @if ($visit->getMedia('visit_attachments')->count() > 0)
                                                            <div class="dropdown"><button
                                                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"><i
                                                                        class="fa-solid fa-paperclip"></i>
                                                                    {{ $visit->getMedia('visit_attachments')->count() }}</button>
                                                                <ul class="dropdown-menu">
                                                                    @foreach ($visit->getMedia('visit_attachments') as $media)
                                                                        <li><a class="dropdown-item small"
                                                                                href="{{ $media->getUrl() }}"
                                                                                target="_blank"><i
                                                                                    class="fa-regular fa-file me-1"></i>
                                                                                {{ Str::limit($media->file_name, 20) }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex justify-content-end gap-2">
                                                            <a href="{{ route('visits.print', $visit->id) }}"
                                                                target="_blank" class="btn btn-sm btn-outline-dark"
                                                                title="Yazdır / PDF"><i class="fa-solid fa-print"></i></a>
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editVisitModal{{ $visit->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-edit"></i></button>
                                                            <form action="{{ route('visits.destroy', $visit->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Bu formu silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="fa-solid fa-trash-alt"></i></button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="7" class="text-center text-muted py-5"><i
                                                            class="fa-solid fa-folder-open fa-2x mb-3 opacity-25"></i>
                                                        <p class="mb-0">Henüz kayıtlı bir ziyaret formu yok.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- NUMUNELER TABI --}}
                            <div class="tab-pane fade" id="samples" role="tabpanel">
                                <h5><i class="fa-solid fa-flask me-2"></i>Hızlı Numune Kaydı Ekle</h5>
                                <form action="{{ route('customers.samples.store', $customer) }}" method="POST"
                                    class="quick-add-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><label class="form-label">Konu (*)</label><input
                                                type="text" name="subject" class="form-control" required
                                                placeholder="Örn: Yeni Preform Denemesi"></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">İlgili
                                                Ürün/Proje</label><input type="text" name="product_name"
                                                list="productList" class="form-control"
                                                placeholder="Listeden seçin veya yeni yazın..."></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Miktar & Birim (*)</label>
                                            <div class="input-group"><input type="number" name="quantity"
                                                    class="form-control" required step="0.01" value="1"><select
                                                    name="unit" class="form-select" style="max-width: 90px;" required>
                                                    @foreach ($birimler as $birim)
                                                        <option value="{{ $birim->ad }}">{{ $birim->ad }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Gönderim Tarihi</label><input
                                                type="date" name="sent_date" class="form-control"
                                                value="{{ date('Y-m-d') }}"></div>
                                        <div class="col-md-6 mb-3"><label class="form-label">Kargo Firması ve Takip
                                                No</label>
                                            <div class="input-group"><input type="text" name="cargo_company"
                                                    class="form-control" placeholder="Kargo Firması"><input
                                                    type="text" name="tracking_number" class="form-control"
                                                    placeholder="Takip No"></div>
                                        </div>
                                        <div class="col-md-6 mb-3"><label class="form-label">Ekstra Ürün
                                                Bilgisi</label><input type="text" name="product_info"
                                                class="form-control" placeholder="Gerekirse detay girin..."></div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4"><i
                                                class="fa-solid fa-save me-2"></i> Kaydet</button></div>
                                </form>
                                <hr class="my-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Gönderilen Numuneler</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterSamDate" class="filter-input bg-white"><input type="text"
                                            id="filterSamSearch" class="filter-input bg-white"
                                            placeholder="Konu, ürün ara..."><select id="filterSamStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                                            <option value="">Tüm Durumlar</option>
                                            <option value="preparing">Hazırlanıyor</option>
                                            <option value="sent">Gönderildi</option>
                                            <option value="delivered">Teslim Edildi</option>
                                            <option value="approved">Onaylandı</option>
                                            <option value="rejected">Reddedildi</option>
                                        </select></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="samplesTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Konu</th>
                                                <th>Bağlı Ürün</th>
                                                <th>Miktar</th>
                                                <th>Kargo</th>
                                                <th>Durum</th>
                                                <th>Geri Bildirim</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($customer->samples as $sample)
                                                <tr class="sample-item"
                                                    data-date="{{ $sample->sent_date ? $sample->sent_date->format('Y-m-d') : '' }}"
                                                    data-search="{{ mb_strtolower($sample->subject . ' ' . $sample->product_info . ' ' . ($sample->product->name ?? '')) }}"
                                                    data-status="{{ $sample->status }}">
                                                    <td>{{ $sample->sent_date ? $sample->sent_date->format('d.m.Y') : '-' }}
                                                    </td>
                                                    <td><span class="fw-bold">{{ $sample->subject }}</span><br><small
                                                            class="text-muted">{{ $sample->product_info }}</small></td>
                                                    <td>
                                                        @if ($sample->product)
                                                            <span
                                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $sample->product->name }}</span>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $sample->quantity }} {{ $sample->unit }}</td>
                                                    <td>
                                                        @if ($sample->cargo_company)
                                                            {{ $sample->cargo_company }}
                                                            <br><small
                                                                class="text-muted">{{ $sample->tracking_number }}</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form
                                                            action="{{ route('customer-samples.update-status', $sample->id) }}"
                                                            method="POST" class="m-0">@csrf @method('PATCH')<input
                                                                type="hidden" name="feedback"
                                                                value="{{ $sample->feedback }}"><select name="status"
                                                                class="form-select status-select status-{{ $sample->status }}"
                                                                onchange="this.form.submit()" style="min-width: 130px;">
                                                                <option value="preparing"
                                                                    {{ $sample->status == 'preparing' ? 'selected' : '' }}>
                                                                    Hazırlanıyor</option>
                                                                <option value="sent"
                                                                    {{ $sample->status == 'sent' ? 'selected' : '' }}>
                                                                    Gönderildi</option>
                                                                <option value="delivered"
                                                                    {{ $sample->status == 'delivered' ? 'selected' : '' }}>
                                                                    Teslim Edildi</option>
                                                                <option value="approved"
                                                                    {{ $sample->status == 'approved' ? 'selected' : '' }}>
                                                                    Onaylandı</option>
                                                                <option value="rejected"
                                                                    {{ $sample->status == 'rejected' ? 'selected' : '' }}>
                                                                    Reddedildi</option>
                                                            </select></form>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-start justify-content-between">
                                                            <div class="text-muted small text-wrap text-break"
                                                                style="width: 180px; hyphens: auto; cursor: pointer; line-height: 1.5; text-align: justify;"
                                                                lang="tr" data-bs-toggle="modal"
                                                                data-bs-target="#feedbackModal{{ $sample->id }}"
                                                                title="Düzenle/Oku">
                                                                {{ $sample->feedback ?: 'Henüz girilmedi' }}</div>
                                                            <button type="button"
                                                                class="btn btn-sm btn-link text-primary p-0 ms-2 flex-shrink-0 mt-1"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#feedbackModal{{ $sample->id }}"><i
                                                                    class="fa-solid fa-pen-to-square fs-5"></i></button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editSampleModal{{ $sample->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('customer-samples.destroy', $sample->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="fa-solid fa-trash-alt"></i></button></form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="8" class="text-center text-muted py-4"><i
                                                            class="fa-solid fa-flask fa-2x mb-2 opacity-50"></i>
                                                        <p class="mb-0">Henüz numune kaydı bulunamadı.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- İADELER SEKME İÇERİĞİ --}}
                            <div class="tab-pane fade" id="returns" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı İade Kaydı Ekle</h5>
                                <form action="{{ route('customers.returns.store', $customer) }}" method="POST"
                                    class="quick-add-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">Ürün/Proje Adı
                                                (*)</label><input type="text" name="product_name" list="productList"
                                                class="form-control" required placeholder="Listeden seçin veya yazın...">
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label text-primary">Gönderilen
                                                Miktar & Birim (*)</label>
                                            <div class="input-group"><input type="number" name="shipped_quantity"
                                                    class="form-control border-primary" required step="0.01"
                                                    placeholder="0.00"><select name="shipped_unit"
                                                    class="form-select border-primary" style="max-width: 100px;" required>
                                                    @foreach ($birimler as $birim)
                                                        <option value="{{ $birim->ad }}">{{ $birim->ad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label text-danger">İade Gelen Miktar
                                                & Birim (*)</label>
                                            <div class="input-group"><input type="number" name="quantity"
                                                    class="form-control border-danger" required step="0.01"
                                                    placeholder="0.00"><select name="unit"
                                                    class="form-select border-danger" style="max-width: 100px;" required>
                                                    @foreach ($birimler as $birim)
                                                        <option value="{{ $birim->ad }}">{{ $birim->ad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">Bağlı Şikayet
                                                (Opsiyonel)</label><select name="complaint_id" class="form-select">
                                                <option value="">Bağımsız İade (Seçilmedi)</option>
                                                @foreach ($customer->complaints as $c)
                                                    <option value="{{ $c->id }}">#{{ $c->id }} -
                                                        {{ Str::limit($c->title, 20) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Tarih (*)</label><input
                                                type="date" name="return_date" class="form-control"
                                                value="{{ date('Y-m-d') }}" required></div>
                                        <div class="col-md-5 mb-3"><label class="form-label">İade Nedeni (*)</label><input
                                                type="text" name="reason" class="form-control" required
                                                placeholder="İade sebebini kısaca yazın..."></div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4"><i
                                                class="fa-solid fa-save me-2"></i> Kaydet</button></div>
                                </form>
                                <hr class="my-4">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı İadeler ve Oranlar
                                    </h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterRetDate" class="filter-input bg-white"><input type="text"
                                            id="filterRetSearch" class="filter-input bg-white"
                                            placeholder="Ürün, Neden ara..."><select id="filterRetStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                                            <option value="">Tüm Durumlar</option>
                                            <option value="pending">Beklemede</option>
                                            <option value="approved">Onaylandı</option>
                                            <option value="completed">Gerçekleşti</option>
                                            <option value="rejected">Reddedildi</option>
                                        </select></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle" id="returnsTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Tarih</th>
                                                <th>Ürün</th>
                                                <th>Gönderilen</th>
                                                <th>İade Gelen</th>
                                                <th>İade Oranı</th>
                                                <th>Durum</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($customer->returns as $return)
                                                <tr class="return-item"
                                                    data-date="{{ $return->return_date->format('Y-m-d') }}"
                                                    data-search="{{ mb_strtolower($return->product_name . ' ' . $return->reason) }}"
                                                    data-status="{{ $return->status }}">
                                                    <td>{{ $return->return_date->format('d.m.Y') }}</td>
                                                    <td><span
                                                            class="fw-bold">{{ $return->product_name }}</span><br><small
                                                            class="text-muted">{{ Str::limit($return->reason, 30) }}</small>
                                                    </td>
                                                    <td class="text-primary fw-semibold">{{ $return->shipped_quantity }}
                                                        {{ $return->shipped_unit }}</td>
                                                    <td class="text-danger fw-semibold">{{ $return->quantity }}
                                                        {{ $return->unit }}</td>
                                                    <td>
                                                        @php
                                                            $rate = 0;
                                                            $showRate = false;
                                                            $shippedUnit = strtolower($return->shipped_unit);
                                                            $returnUnit = strtolower($return->unit);
                                                            if ($return->shipped_quantity > 0) {
                                                                if ($shippedUnit === $returnUnit) {
                                                                    $rate =
                                                                        ($return->quantity /
                                                                            $return->shipped_quantity) *
                                                                        100;
                                                                    $showRate = true;
                                                                } elseif (
                                                                    $shippedUnit == 'ton' &&
                                                                    $returnUnit == 'kg'
                                                                ) {
                                                                    $rate =
                                                                        ($return->quantity /
                                                                            ($return->shipped_quantity * 1000)) *
                                                                        100;
                                                                    $showRate = true;
                                                                } elseif ($shippedUnit == 'kg' && $returnUnit == 'gr') {
                                                                    $rate =
                                                                        ($return->quantity /
                                                                            ($return->shipped_quantity * 1000)) *
                                                                        100;
                                                                    $showRate = true;
                                                                }
                                                            }
                                                            $badgeClass =
                                                                $rate <= 2
                                                                    ? 'bg-success'
                                                                    : ($rate <= 5
                                                                        ? 'bg-warning text-dark'
                                                                        : 'bg-danger');
                                                        @endphp
                                                        @if ($showRate)
                                                            <span
                                                                class="badge {{ $badgeClass }} rounded-pill px-3 py-2">%{{ number_format($rate, 2, ',', '.') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2 py-1"
                                                                title="Birimler farklı olduğu için hesaplanamadı.">Birim
                                                                Farkı</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form
                                                            action="{{ route('customer-returns.update-status', $return->id) }}"
                                                            method="POST">@csrf @method('PATCH')<select name="status"
                                                                class="form-select status-select status-{{ $return->status }}"
                                                                onchange="this.form.submit()">
                                                                <option value="pending"
                                                                    {{ $return->status == 'pending' ? 'selected' : '' }}>
                                                                    Beklemede</option>
                                                                <option value="approved"
                                                                    {{ $return->status == 'approved' ? 'selected' : '' }}>
                                                                    Onaylandı</option>
                                                                <option value="completed"
                                                                    {{ $return->status == 'completed' ? 'selected' : '' }}>
                                                                    İade Gerçekleşti</option>
                                                                <option value="rejected"
                                                                    {{ $return->status == 'rejected' ? 'selected' : '' }}>
                                                                    Reddedildi</option>
                                                            </select></form>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editReturnModal{{ $return->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('customer-returns.destroy', $return->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-outline-danger"><i
                                                                        class="fa-solid fa-trash-alt"></i></button></form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="7" class="text-center text-muted py-4"><i
                                                            class="fa-solid fa-rotate-left fa-2x mb-2 opacity-50"></i>
                                                        <p class="mb-0">Henüz iade kaydı bulunamadı.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- ŞİKAYETLER TABI --}}
                            <div class="tab-pane fade" id="complaints" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Şikayet Kaydı Ekle</h5>
                                <form action="{{ route('customers.complaints.store', $customer) }}" method="POST"
                                    autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 mb-3"><label for="title" class="form-label">Şikayet
                                                Başlığı (*)</label><input type="text" name="title"
                                                class="form-control" required></div>
                                        <div class="col-md-4 mb-3"><label for="status" class="form-label">Durum
                                                (*)</label><select name="status" class="form-select" required>
                                                <option value="open">Açık</option>
                                                <option value="in_progress">İşlemde</option>
                                                <option value="resolved">Çözüldü</option>
                                            </select></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Detaylı Açıklama (*)</label>
                                        <div class="input-group">
                                            <textarea name="description" id="new_complaint_desc" class="form-control" rows="3" required></textarea>
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="btn_new_comp_desc"
                                                onclick="toggleVoiceInput('new_complaint_desc', 'btn_new_comp_desc')"><i
                                                    class="fa-solid fa-microphone"></i></button>
                                        </div>
                                    </div>
                                    <div class="mb-3"><label for="complaint_files" class="form-label">Kanıt
                                            Dosyaları</label><input type="file" name="complaint_files[]"
                                            class="form-control" multiple></div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                                            class="fa-solid fa-plus me-2"></i>Şikayeti Ekle</button>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Şikayetler</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterCompDate" class="filter-input bg-white"><input type="text"
                                            id="filterCompSearch" class="filter-input bg-white"
                                            placeholder="Başlık veya içerik ara..."><select id="filterCompStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                                            <option value="">Tüm Durumlar</option>
                                            <option value="open">Açık</option>
                                            <option value="in_progress">İşlemde</option>
                                            <option value="resolved">Çözüldü</option>
                                        </select></div>
                                </div>
                                <div id="complaintsList">
                                    @forelse ($customer->complaints as $complaint)
                                        <div class="alert complaint-item {{ $complaint->status == 'resolved' ? 'alert-success' : 'alert-warning' }}"
                                            data-date="{{ $complaint->created_at->format('Y-m-d') }}"
                                            data-search="{{ mb_strtolower($complaint->title . ' ' . $complaint->description) }}"
                                            data-status="{{ $complaint->status }}">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-2"><i
                                                            class="fa-solid fa-exclamation-circle me-2"></i><strong>{{ $complaint->title }}</strong>
                                                    </h6>
                                                    <div class="d-flex gap-3 mb-2 small"><span><i
                                                                class="fa-regular fa-calendar me-1"></i>{{ $complaint->created_at->format('d.m.Y') }}</span><span>Durum:
                                                            <strong>{{ $complaint->status }}</strong></span></div>
                                                    <p class="mb-3">{{ $complaint->description }}</p>
                                                    @foreach ($complaint->getMedia('complaint_attachments') as $media)
                                                        <div class="file-list-item d-inline-flex me-2 mb-2"
                                                            style="width: auto;"><span><i
                                                                    class="fa-solid fa-paperclip me-2"></i>{{ Str::limit($media->file_name, 20) }}</span><a
                                                                href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-link text-primary ms-2 p-0"><i
                                                                    class="fa-solid fa-eye"></i></a></div>
                                                    @endforeach
                                                </div>
                                                <div class="d-flex gap-2 ms-3 flex-shrink-0">
                                                    <button type="button" class="btn btn-sm btn-light border"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editComplaintModal{{ $complaint->id }}"
                                                        title="Düzenle"><i
                                                            class="fa-solid fa-pen text-primary"></i></button>
                                                    <form action="{{ route('complaints.destroy', $complaint->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                        @csrf @method('DELETE')<button type="submit"
                                                            class="btn btn-sm btn-light border"><i
                                                                class="fa-solid fa-trash-alt text-danger"></i></button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @empty <div class="alert alert-secondary text-center empty-message-row"><i
                                                class="fa-solid fa-inbox fa-2x mb-3 d-block"
                                                style="opacity: 0.3;"></i>Bu müşteriye ait şikayet kaydı bulunamadı.</div>
                                    @endforelse
                                </div>
                            </div>

                            {{-- MAKİNELER TABI --}}
                            <div class="tab-pane fade" id="machines" role="tabpanel">
                                <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Makine Ekle</h5>
                                <form action="{{ route('customers.machines.store', $customer) }}" method="POST"
                                    autocomplete="off" class="quick-add-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><label for="model" class="form-label">Model
                                                (*)</label><input type="text" name="model" class="form-control"
                                                required></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">Çalıştığı
                                                Ürün</label><input type="text" name="product_name"
                                                list="productList" class="form-control"
                                                placeholder="Listeden seçin veya yeni yazın..."></div>
                                        <div class="col-md-3 mb-3"><label for="serial_number" class="form-label">Seri
                                                No</label><input type="text" name="serial_number"
                                                class="form-control"></div>
                                        <div class="col-md-3 mb-3"><label for="installation_date"
                                                class="form-label">Kurulum Tarihi</label><input type="date"
                                                name="installation_date" class="form-control"></div>
                                    </div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                                            class="fa-solid fa-plus me-2"></i>Makineyi Ekle</button>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Makineler</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterMacDate" class="filter-input bg-white"
                                            title="Kurulum Tarihi"><input type="text" id="filterMacSearch"
                                            class="filter-input bg-white" placeholder="Model, Seri No veya Ürün ara...">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover" id="machinesTable">
                                        <thead>
                                            <tr>
                                                <th>Model</th>
                                                <th>Bağlı Ürün</th>
                                                <th>Seri No</th>
                                                <th>Kurulum Tarihi</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($customer->machines as $machine)
                                                <tr class="machine-item"
                                                    data-date="{{ $machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('Y-m-d') : '' }}"
                                                    data-search="{{ mb_strtolower($machine->model . ' ' . $machine->serial_number . ' ' . ($machine->product->name ?? '')) }}">
                                                    <td><strong>{{ $machine->model }}</strong></td>
                                                    <td>
                                                        @if ($machine->product)
                                                            <span
                                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $machine->product->name }}</span>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $machine->serial_number ?? '-' }}</td>
                                                    <td>{{ $machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('d.m.Y') : '-' }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editMachineModal{{ $machine->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('machines.destroy', $machine->id ?? 0) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-outline-danger p-0 px-2"><i
                                                                        class="fa-solid fa-trash-alt"></i></button></form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="5" class="text-center text-muted py-4">Bu müşteriye
                                                        ait makine kaydı bulunamadı.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TEST SONUÇLARI TABI --}}
                            <div class="tab-pane fade" id="tests" role="tabpanel">
                                <h5><i class="fa-solid fa-upload me-2"></i>Hızlı Test Sonucu Yükle</h5>
                                <form action="{{ route('customers.test-results.store', $customer) }}" method="POST"
                                    autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-3 mb-3"><label for="test_name" class="form-label">Test Adı
                                                (*)</label><input type="text" name="test_name" class="form-control"
                                                required></div>
                                        <div class="col-md-3 mb-3"><label class="form-label">İlgili Ürün</label><input
                                                type="text" name="product_name" list="productList"
                                                class="form-control" placeholder="Listeden seçin veya yeni yazın...">
                                        </div>
                                        <div class="col-md-3 mb-3"><label for="test_date" class="form-label">Test
                                                Tarihi (*)</label><input type="date" name="test_date"
                                                class="form-control" required></div>
                                        <div class="col-md-3 mb-3"><label for="test_files"
                                                class="form-label">Dosya(lar)</label><input type="file"
                                                name="test_files[]" class="form-control" multiple></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="summary" class="form-label">Özet</label>
                                        <div class="input-group">
                                            <textarea name="summary" id="new_test_summary" class="form-control" rows="2"></textarea>
                                            <button class="btn btn-outline-secondary" type="button"
                                                id="btn_new_test_sum"
                                                onclick="toggleVoiceInput('new_test_summary', 'btn_new_test_sum')"><i
                                                    class="fa-solid fa-microphone"></i></button>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                                            class="fa-solid fa-plus me-2"></i>Test Sonucunu Ekle</button>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Test Sonuçları</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterTestDate" class="filter-input bg-white"><input type="text"
                                            id="filterTestSearch" class="filter-input bg-white"
                                            placeholder="Test Adı, Ürün veya Özet ara..."></div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped table-hover" id="testsTable">
                                        <thead>
                                            <tr>
                                                <th>Test Adı</th>
                                                <th>Bağlı Ürün</th>
                                                <th>Tarih</th>
                                                <th>Özet</th>
                                                <th>Dosyalar</th>
                                                <th>İşlem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($customer->testResults as $result)
                                                <tr class="test-item"
                                                    data-date="{{ \Carbon\Carbon::parse($result->test_date)->format('Y-m-d') }}"
                                                    data-search="{{ mb_strtolower($result->test_name . ' ' . $result->summary . ' ' . ($result->product->name ?? '')) }}">
                                                    <td><strong>{{ $result->test_name }}</strong></td>
                                                    <td>
                                                        @if ($result->product)
                                                            <span
                                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $result->product->name }}</span>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($result->test_date)->format('d.m.Y') }}
                                                    </td>
                                                    <td>{{ $result->summary ?? '-' }}</td>
                                                    <td>
                                                        @foreach ($result->getMedia('test_reports') as $media)
                                                            <div class="file-list-item d-inline-flex me-2 mb-2"
                                                                style="width:auto; padding: 0.2rem 0.5rem;"><span><i
                                                                        class="fa-solid fa-file-pdf me-2"></i>{{ Str::limit($media->file_name, 15) }}</span><a
                                                                    href="{{ $media->getUrl() }}" target="_blank"
                                                                    class="btn btn-sm btn-link text-primary ms-2 p-0"><i
                                                                        class="fa-solid fa-eye"></i></a></div>
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-primary"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editTestModal{{ $result->id }}"
                                                                title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                                            <form
                                                                action="{{ route('test-results.destroy', $result->id ?? 0) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                @csrf @method('DELETE')<button type="submit"
                                                                    class="btn btn-sm btn-outline-danger p-0 px-2"><i
                                                                        class="fa-solid fa-trash-alt"></i></button></form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty <tr class="empty-message-row">
                                                    <td colspan="6" class="text-center text-muted py-4">Bu müşteriye
                                                        ait test sonucu bulunamadı.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- LOJİSTİK VE SEVKİYAT SEKME İÇERİĞİ --}}
                            <div class="tab-pane fade" id="logistics" role="tabpanel">
                                <h5><i class="fa-solid fa-truck-fast me-2 text-info"></i>Yeni Lojistik / Sevkiyat Görevi
                                </h5>
                                <form action="{{ route('service.vehicle-assignments.store') }}" method="POST"
                                    class="quick-add-form mb-5">
                                    @csrf
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="hidden" name="type" value="logistics">
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">Görev Başlığı
                                                (*)</label><input type="text" name="title" class="form-control"
                                                required placeholder="Örn: İstanbul Sevkiyatı"></div>
                                        <div class="col-md-4 mb-3"><label class="form-label">Araç Seçimi</label><select
                                                name="vehicle_id" class="form-select">
                                                <option value="">Araç Seçiniz...</option>
                                                @foreach (\App\Models\Vehicle::all() as $vehicle)
                                                    <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} -
                                                        {{ $vehicle->model }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">Planlanan Çıkış
                                                (*)</label><input type="datetime-local" name="start_time"
                                                class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}"
                                                required></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3"><label class="form-label">Taşınacak
                                                Ürün</label><select name="customer_product_id" class="form-select">
                                                <option value="">Ürün Seçiniz...</option>
                                                @foreach ($customer->products as $prod)
                                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">Miktar & Birim</label>
                                            <div class="input-group"><input type="number" name="quantity"
                                                    class="form-control" placeholder="0.00" step="0.01"><select
                                                    name="unit" class="form-select" style="max-width: 90px;">
                                                    @foreach ($birimler as $birim)
                                                        <option value="{{ $birim->ad }}">{{ $birim->ad }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3"><label class="form-label">Sorumlu
                                                Şoför</label><select name="user_id" class="form-select">
                                                <option value="{{ Auth::id() }}">{{ Auth::user()->name }} (Ben)
                                                </option>
                                                @foreach (\App\Models\User::all() as $user)
                                                    @if ($user->id !== Auth::id())
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Açıklama / Adres Detayı</label>
                                            <div class="input-group">
                                                <input type="text" name="description" id="new_logistics_desc"
                                                    class="form-control" placeholder="Sevkiyat hakkında notlar...">
                                                <button class="btn btn-outline-secondary" type="button"
                                                    id="btn_new_log_desc"
                                                    onclick="toggleVoiceInput('new_logistics_desc', 'btn_new_log_desc')"><i
                                                        class="fa-solid fa-microphone"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end"><button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4"><i
                                                class="fa-solid fa-calendar-check me-2"></i> Görevi Planla</button></div>
                                </form>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><i class="fa-solid fa-truck-ramp-box me-2 text-info"></i>Lojistik
                                        Hareketleri</h5>
                                    <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center"><i
                                            class="fa-solid fa-filter text-muted mx-1"></i><input type="date"
                                            id="filterLogDate" class="filter-input bg-white"><input type="text"
                                            id="filterLogSearch" class="filter-input bg-white"
                                            placeholder="Görev, araç, ürün ara..."><select id="filterLogStatus"
                                            class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                                            <option value="">Tüm Durumlar</option>
                                            <option value="pending">Beklemede</option>
                                            <option value="on_road">Yolda</option>
                                            <option value="completed">Tamamlandı</option>
                                        </select></div>
                                </div>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover align-middle mb-0" id="logisticsTable">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="ps-4 py-3">Tarih</th>
                                                        <th>Görev & Ürün</th>
                                                        <th>Araç / Plaka</th>
                                                        <th>Sorumlu</th>
                                                        <th>Durum</th>
                                                        <th>İşlem</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($customer->vehicleAssignments as $assignment)
                                                        <tr class="logistic-item"
                                                            data-date="{{ $assignment->start_time->format('Y-m-d') }}"
                                                            data-search="{{ mb_strtolower($assignment->title . ' ' . ($assignment->vehicle->plate_number ?? '') . ' ' . ($assignment->product->name ?? '')) }}"
                                                            data-status="{{ $assignment->status }}">
                                                            <td class="ps-4">
                                                                {{ $assignment->start_time->format('d.m.Y H:i') }}</td>
                                                            <td><span
                                                                    class="fw-bold d-block">{{ $assignment->title }}</span>
                                                                @if ($assignment->product)
                                                                    <small class="text-primary"><i
                                                                            class="fa-solid fa-box me-1"></i>{{ $assignment->quantity }}
                                                                        {{ $assignment->unit }} -
                                                                        {{ $assignment->product->name }}</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($assignment->vehicle)
                                                                    <span class="badge bg-dark"><i
                                                                            class="fa-solid fa-truck me-1"></i>{{ $assignment->vehicle->plate_number }}</span>
                                                                @else
                                                                    <span class="text-muted small">Araçsız</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $assignment->responsible->name ?? '-' }}</td>
                                                            <td>
                                                                @if ($assignment->status == 'completed')
                                                                    <span
                                                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">Tamamlandı</span>
                                                                @elseif($assignment->status == 'on_road')
                                                                    <span
                                                                        class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">Yolda</span>
                                                                @elseif($assignment->status == 'cancelled')
                                                                    <span
                                                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">İptal</span>
                                                                @else
                                                                    <span
                                                                        class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">Planlandı</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-outline-primary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#editLogisticsModal{{ $assignment->id }}"
                                                                        title="Düzenle"><i
                                                                            class="fa-solid fa-pen"></i></button>
                                                                    <form
                                                                        action="{{ route('service.vehicle-assignments.destroy', $assignment->id) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                                                        @csrf @method('DELETE')<button type="submit"
                                                                            class="btn btn-sm btn-outline-danger"><i
                                                                                class="fa-solid fa-trash-alt"></i></button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty <tr class="empty-message-row">
                                                            <td colspan="6" class="text-center py-5 text-muted"><i
                                                                    class="fas fa-truck-loading fa-2x mb-3 opacity-50"></i>
                                                                <p class="mb-0">Bu müşteriye planlanmış bir lojistik
                                                                    görevi bulunamadı.</p>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODALLAR --}}

    {{-- ZİYARET DÜZENLEME MODALI (YENİ EKLENEN) --}}
    @foreach ($customer->visits as $visit)
        <div class="modal fade" id="editVisitModal{{ $visit->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-edit me-2"></i>Ziyaret
                            Formunu Düzenle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customers.visits.update', [$customer->id, $visit->id]) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Ziyaret
                                        Tarihi</label><input type="datetime-local" name="visit_date"
                                        class="form-control"
                                        value="{{ $visit->visit_date ? $visit->visit_date->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Ziyaret
                                        Sebebi</label><select name="visit_reason" class="form-select">
                                        <option value="Şikayet"
                                            {{ $visit->visit_reason == 'Şikayet' ? 'selected' : '' }}>Şikayet</option>
                                        <option value="Periyodik Ziyaret"
                                            {{ $visit->visit_reason == 'Periyodik Ziyaret' ? 'selected' : '' }}>Periyodik
                                            Ziyaret</option>
                                        <option value="Ürün Denemesi"
                                            {{ $visit->visit_reason == 'Ürün Denemesi' ? 'selected' : '' }}>Ürün Denemesi
                                        </option>
                                        <option value="Diğer" {{ $visit->visit_reason == 'Diğer' ? 'selected' : '' }}>
                                            Diğer</option>
                                    </select></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Barkod</label><input
                                        type="text" name="barcode" class="form-control"
                                        value="{{ $visit->barcode }}"></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Lot No</label><input
                                        type="text" name="lot_no" class="form-control"
                                        value="{{ $visit->lot_no }}"></div>
                                <div class="col-12 mb-3"><label class="form-label fw-bold small">Tespitler</label>
                                    <div class="input-group">
                                        <textarea name="findings" id="edit_findings_{{ $visit->id }}" class="form-control" rows="3">{{ $visit->findings }}</textarea><button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="toggleVoiceInput('edit_findings_{{ $visit->id }}', this)"><i
                                                class="fa-solid fa-microphone"></i></button>
                                    </div>
                                </div>
                                <div class="col-12 mb-3"><label class="form-label fw-bold small">Sonuç & Karar</label>
                                    <div class="input-group">
                                        <textarea name="result" id="edit_result_{{ $visit->id }}" class="form-control" rows="3">{{ $visit->result }}</textarea><button class="btn btn-outline-secondary"
                                            type="button"
                                            onclick="toggleVoiceInput('edit_result_{{ $visit->id }}', this)"><i
                                                class="fa-solid fa-microphone"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light">
                            <button type="button" class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button>
                            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Numune Geri Bildirim Modalları --}}
    @foreach ($customer->samples as $sample)
        <div class="modal fade" id="feedbackModal{{ $sample->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-comment-dots me-2"></i>Geri
                            Bildirim Ekle</h5><button type="button" class="btn-close"
                            data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer-samples.update-status', $sample->id) }}" method="POST">@csrf
                        @method('PATCH')<input type="hidden" name="status" value="{{ $sample->status }}">
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label fw-bold text-muted small">Numune Konusu</label>
                                <p class="mb-2 fw-semibold">{{ $sample->subject }}</p>
                            </div>
                            <div class="mb-3"><label class="form-label fw-bold text-muted small">Müşteri Geri
                                    Bildirimi</label>
                                <div class="input-group">
                                    <textarea name="feedback" id="feedback_input_{{ $sample->id }}" class="form-control" rows="4">{{ $sample->feedback }}</textarea><button class="btn btn-outline-secondary" type="button"
                                        id="btn_feedback_{{ $sample->id }}"
                                        onclick="toggleVoiceInput('feedback_input_{{ $sample->id }}', 'btn_feedback_{{ $sample->id }}')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">Kapat</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Numune Tam Düzenleme --}}
        <div class="modal fade" id="editSampleModal{{ $sample->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-flask me-2"></i>Numuneyi
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer-samples.update', $sample->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Konu
                                        (*)</label><input type="text" name="subject" class="form-control"
                                        value="{{ $sample->subject }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">İlgili
                                        Ürün/Proje</label><input type="text" name="product_name" list="productList"
                                        class="form-control" value="{{ $sample->product->name ?? '' }}"></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Miktar</label><input
                                        type="number" name="quantity" class="form-control"
                                        value="{{ $sample->quantity }}" step="0.01" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Birim</label><select
                                        name="unit" class="form-select" required>
                                        @foreach ($birimler as $birim)
                                            <option value="{{ $birim->ad }}"
                                                {{ $sample->unit == $birim->ad ? 'selected' : '' }}>{{ $birim->ad }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Gönderim
                                        Tarihi</label><input type="date" name="sent_date" class="form-control"
                                        value="{{ $sample->sent_date ? $sample->sent_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Kargo
                                        Firması</label><input type="text" name="cargo_company" class="form-control"
                                        value="{{ $sample->cargo_company }}"></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Takip
                                        No</label><input type="text" name="tracking_number" class="form-control"
                                        value="{{ $sample->tracking_number }}"></div>
                                <div class="col-12 mb-3"><label class="form-label fw-bold small">Ekstra Ürün
                                        Bilgisi</label><input type="text" name="product_info" class="form-control"
                                        value="{{ $sample->product_info }}"></div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- İade Düzenleme --}}
    @foreach ($customer->returns as $return)
        <div class="modal fade" id="editReturnModal{{ $return->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-rotate-left me-2"></i>İadeyi
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer-returns.update', $return->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3"><label class="form-label fw-bold small">Ürün Adı
                                        (*)</label><input type="text" name="product_name" list="productList"
                                        class="form-control" value="{{ $return->product_name }}" required></div>
                                <div class="col-md-6 mb-3"><label
                                        class="form-label fw-bold small text-primary">Gönderilen Miktar & Birim
                                        (*)</label>
                                    <div class="input-group"><input type="number" name="shipped_quantity"
                                            class="form-control border-primary"
                                            value="{{ $return->shipped_quantity }}" step="0.01" required><select
                                            name="shipped_unit" class="form-select border-primary" required>
                                            @foreach ($birimler as $birim)
                                                <option value="{{ $birim->ad }}"
                                                    {{ $return->shipped_unit == $birim->ad ? 'selected' : '' }}>
                                                    {{ $birim->ad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small text-danger">İade
                                        Gelen Miktar & Birim (*)</label>
                                    <div class="input-group"><input type="number" name="quantity"
                                            class="form-control border-danger" value="{{ $return->quantity }}"
                                            step="0.01" required><select name="unit"
                                            class="form-select border-danger" required>
                                            @foreach ($birimler as $birim)
                                                <option value="{{ $birim->ad }}"
                                                    {{ $return->unit == $birim->ad ? 'selected' : '' }}>
                                                    {{ $birim->ad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Tarih
                                        (*)</label><input type="date" name="return_date" class="form-control"
                                        value="{{ $return->return_date->format('Y-m-d') }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Bağlı
                                        Şikayet</label><select name="complaint_id" class="form-select">
                                        <option value="">Bağımsız İade</option>
                                        @foreach ($customer->complaints as $c)
                                            <option value="{{ $c->id }}"
                                                {{ $return->complaint_id == $c->id ? 'selected' : '' }}>
                                                #{{ $c->id }} - {{ Str::limit($c->title, 20) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3"><label class="form-label fw-bold small">İade Nedeni (*)</label>
                                    <textarea name="reason" class="form-control" rows="2" required>{{ $return->reason }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Şikayet Düzenleme --}}
    @foreach ($customer->complaints as $complaint)
        <div class="modal fade" id="editComplaintModal{{ $complaint->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i
                                class="fa-solid fa-exclamation-triangle me-2"></i>Şikayeti Düzenle</h5><button
                            type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-8 mb-3"><label class="form-label fw-bold small">Şikayet Başlığı
                                        (*)</label><input type="text" name="title" class="form-control"
                                        value="{{ $complaint->title }}" required></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Durum
                                        (*)</label><select name="status" class="form-select" required>
                                        <option value="open" {{ $complaint->status == 'open' ? 'selected' : '' }}>Açık
                                        </option>
                                        <option value="in_progress"
                                            {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>İşlemde</option>
                                        <option value="resolved"
                                            {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Çözüldü</option>
                                    </select></div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold small">Detaylı Açıklama (*)</label>
                                    <div class="input-group">
                                        <textarea name="description" id="comp_desc_edit_{{ $complaint->id }}" class="form-control" rows="4"
                                            required>{{ $complaint->description }}</textarea><button class="btn btn-outline-secondary"
                                            type="button" id="btn_comp_desc_{{ $complaint->id }}"
                                            onclick="toggleVoiceInput('comp_desc_edit_{{ $complaint->id }}', 'btn_comp_desc_{{ $complaint->id }}')"><i
                                                class="fa-solid fa-microphone"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Test Düzenleme --}}
    @foreach ($customer->testResults as $result)
        <div class="modal fade" id="editTestModal{{ $result->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-vial me-2"></i>Test Sonucunu
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('test-results.update', $result->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label fw-bold small">Test Adı (*)</label><input
                                    type="text" name="test_name" class="form-control"
                                    value="{{ $result->test_name }}" required></div>
                            <div class="mb-3"><label class="form-label fw-bold small">İlgili Ürün</label><input
                                    type="text" name="product_name" list="productList" class="form-control"
                                    value="{{ $result->product->name ?? '' }}"></div>
                            <div class="mb-3"><label class="form-label fw-bold small">Test Tarihi (*)</label><input
                                    type="date" name="test_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($result->test_date)->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Özet</label>
                                <div class="input-group">
                                    <textarea name="summary" id="test_sum_edit_{{ $result->id }}" class="form-control" rows="3">{{ $result->summary }}</textarea><button class="btn btn-outline-secondary" type="button"
                                        id="btn_test_sum_{{ $result->id }}"
                                        onclick="toggleVoiceInput('test_sum_edit_{{ $result->id }}', 'btn_test_sum_{{ $result->id }}')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Makine Düzenleme --}}
    @foreach ($customer->machines as $machine)
        <div class="modal fade" id="editMachineModal{{ $machine->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-industry me-2"></i>Makineyi
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('machines.update', $machine->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">Model
                                    (*)</label><input type="text" name="model" class="form-control"
                                    value="{{ $machine->model }}" required></div>
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">Çalıştığı
                                    Ürün</label><input type="text" name="product_name" list="productList"
                                    class="form-control" value="{{ $machine->product->name ?? '' }}"
                                    placeholder="Listeden seçin veya yeni yazın..."></div>
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">Seri No</label><input
                                    type="text" name="serial_number" class="form-control"
                                    value="{{ $machine->serial_number }}"></div>
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">Kurulum
                                    Tarihi</label><input type="date" name="installation_date" class="form-control"
                                    value="{{ $machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('Y-m-d') : '' }}">
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Ürün Düzenleme --}}
    @foreach ($customer->products as $prod)
        <div class="modal fade" id="editProductModal{{ $prod->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-box-open me-2"></i>Ürünü
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer-products.update', $prod->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label fw-bold small">Ürün Adı (*)</label><input
                                    type="text" name="name" class="form-control" value="{{ $prod->name }}"
                                    required></div>
                            <div class="mb-3"><label class="form-label fw-bold small">Kategori</label><input
                                    type="text" name="category" class="form-control"
                                    value="{{ $prod->category }}"></div>
                            <div class="mb-3"><label class="form-label fw-bold small">Yıllık Hacim</label><input
                                    type="text" name="annual_volume" class="form-control"
                                    value="{{ $prod->annual_volume }}"></div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Notlar</label>
                                <div class="input-group">
                                    <textarea name="notes" id="prod_notes_edit_{{ $prod->id }}" class="form-control" rows="2">{{ $prod->notes }}</textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btn_prod_notes_{{ $prod->id }}"
                                        onclick="toggleVoiceInput('prod_notes_edit_{{ $prod->id }}', 'btn_prod_notes_{{ $prod->id }}')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Fırsat Düzenleme --}}
    @foreach ($customer->opportunities as $opp)
        <div class="modal fade" id="editOppModal{{ $opp->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-bullseye me-2"></i>Fırsatı
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('opportunities.update', $opp->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Başlık / Konu
                                        (*)</label><input type="text" name="title" class="form-control"
                                        value="{{ $opp->title }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Aşama
                                        (*)</label><select name="stage" class="form-select" required>
                                        <option value="duyum" {{ $opp->stage == 'duyum' ? 'selected' : '' }}>Söylenti /
                                            Duyum</option>
                                        <option value="teklif" {{ $opp->stage == 'teklif' ? 'selected' : '' }}>Teklif
                                            Verildi</option>
                                        <option value="gorusme" {{ $opp->stage == 'gorusme' ? 'selected' : '' }}>Görüşme
                                            Aşamasında</option>
                                        <option value="kazanildi" {{ $opp->stage == 'kazanildi' ? 'selected' : '' }}>
                                            Kazanıldı</option>
                                        <option value="kaybedildi" {{ $opp->stage == 'kaybedildi' ? 'selected' : '' }}>
                                            Kaybedildi</option>
                                    </select></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Tutar</label><input
                                        type="number" name="amount" class="form-control" step="0.01"
                                        value="{{ $opp->amount }}"></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Para
                                        Birimi</label><select name="currency" class="form-select">
                                        <option value="TRY" {{ $opp->currency == 'TRY' ? 'selected' : '' }}>₺ TRY
                                        </option>
                                        <option value="USD" {{ $opp->currency == 'USD' ? 'selected' : '' }}>$ USD
                                        </option>
                                        <option value="EUR" {{ $opp->currency == 'EUR' ? 'selected' : '' }}>€ EUR
                                        </option>
                                    </select></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Karar
                                        Tarihi</label><input type="date" name="expected_close_date"
                                        class="form-control"
                                        value="{{ $opp->expected_close_date ? $opp->expected_close_date->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold small">Detaylar</label>
                                    <div class="input-group">
                                        <textarea name="description" id="opp_desc_edit_{{ $opp->id }}" class="form-control" rows="3">{{ $opp->description }}</textarea>
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="btn_opp_desc_{{ $opp->id }}"
                                            onclick="toggleVoiceInput('opp_desc_edit_{{ $opp->id }}', 'btn_opp_desc_{{ $opp->id }}')"><i
                                                class="fa-solid fa-microphone"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Aktivite Düzenleme --}}
    @foreach ($customer->activities as $activity)
        @php
            $registeredContactNames = $customer->contacts->pluck('name')->toArray();
            $activityContacts = is_array($activity->contact_persons) ? $activity->contact_persons : [];
            $otherContactsArr = array_diff($activityContacts, $registeredContactNames);
            $otherContactsStr = implode(', ', $otherContactsArr);
        @endphp
        <div class="modal fade" id="editActivityModal{{ $activity->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-history me-2"></i>İşlemi
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('customer-activities.update', $activity->id) }}" method="POST">@csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">İşlem
                                    Tipi</label><select name="type" class="form-select">
                                    <option value="phone" {{ $activity->type == 'phone' ? 'selected' : '' }}>📞 Telefon
                                        Görüşmesi</option>
                                    <option value="meeting" {{ $activity->type == 'meeting' ? 'selected' : '' }}>🤝 Yüz
                                        Yüze Toplantı</option>
                                    <option value="email" {{ $activity->type == 'email' ? 'selected' : '' }}>✉️ E-Posta
                                    </option>
                                    <option value="visit" {{ $activity->type == 'visit' ? 'selected' : '' }}>🏢 Müşteri
                                        Ziyareti</option>
                                    <option value="note" {{ $activity->type == 'note' ? 'selected' : '' }}>📝 Genel
                                        Not</option>
                                </select></div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Görüşülen Kişiler</label>
                                <div class="dropdown mb-2"><button
                                        class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white"
                                        type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">Müşteri
                                        yetkililerini seçin...</button>
                                    <ul class="dropdown-menu w-100 p-2 shadow-sm"
                                        style="max-height: 200px; overflow-y: auto;">
                                        @forelse($customer->contacts as $contact)
                                            <li>
                                                <div class="form-check m-1"><input class="form-check-input"
                                                        type="checkbox" name="contact_persons[]"
                                                        value="{{ $contact->name }}"
                                                        id="edit_{{ $activity->id }}_contact_{{ $contact->id }}"
                                                        style="cursor: pointer;"
                                                        {{ in_array($contact->name, $activityContacts) ? 'checked' : '' }}><label
                                                        class="form-check-label"
                                                        for="edit_{{ $activity->id }}_contact_{{ $contact->id }}"
                                                        style="cursor: pointer;">{{ $contact->name }} <small
                                                            class="text-muted">({{ $contact->title ?? 'Ünvan Yok' }})</small></label>
                                                </div>
                                        </li> @empty <li class="text-muted small text-center p-2">Kayıtlı kişi
                                                bulunamadı.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <input type="text" name="other_contact_persons"
                                    class="form-control form-control-sm" value="{{ $otherContactsStr }}"
                                    placeholder="Listede olmayanlar için yazın (virgülle ayırın)...">
                            </div>
                            <div class="mb-3"><label class="form-label fw-bold small text-muted">Tarih &
                                    Saat</label><input type="datetime-local" name="activity_date" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($activity->activity_date)->format('Y-m-d\TH:i') }}"
                                    required></div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Detaylar</label>
                                <div class="input-group">
                                    <textarea name="description" id="act_desc_edit_{{ $activity->id }}" class="form-control" rows="4"
                                        required>{{ $activity->description }}</textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btn_act_desc_{{ $activity->id }}"
                                        onclick="toggleVoiceInput('act_desc_edit_{{ $activity->id }}', 'btn_act_desc_{{ $activity->id }}')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Lojistik Düzenleme --}}
    @foreach ($customer->vehicleAssignments as $assignment)
        <div class="modal fade" id="editLogisticsModal{{ $assignment->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-light border-0">
                        <h5 class="modal-title fw-bold text-info"><i class="fa-solid fa-truck-fast me-2"></i>Görevi
                            Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('service.assignments.update', $assignment->id) }}" method="POST">@csrf
                        @method('PUT')<input type="hidden" name="type" value="logistics"><input
                            type="hidden" name="customer_id" value="{{ $customer->id }}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Görev
                                        Başlığı</label><input type="text" name="title" class="form-control"
                                        value="{{ $assignment->title }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Durum</label><select
                                        name="status" class="form-select">
                                        <option value="pending"
                                            {{ $assignment->status == 'pending' ? 'selected' : '' }}>Beklemede</option>
                                        <option value="on_road"
                                            {{ $assignment->status == 'on_road' ? 'selected' : '' }}>Yolda</option>
                                        <option value="completed"
                                            {{ $assignment->status == 'completed' ? 'selected' : '' }}>Tamamlandı</option>
                                        <option value="cancelled"
                                            {{ $assignment->status == 'cancelled' ? 'selected' : '' }}>İptal</option>
                                    </select></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Araç</label><select
                                        name="vehicle_id" class="form-select">
                                        <option value="">Araçsız</option>
                                        @foreach (\App\Models\Vehicle::all() as $v)
                                            <option value="{{ $v->id }}"
                                                {{ $assignment->vehicle_id == $v->id ? 'selected' : '' }}>
                                                {{ $v->plate_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Tarih</label><input
                                        type="datetime-local" name="start_time" class="form-control"
                                        value="{{ $assignment->start_time ? $assignment->start_time->format('Y-m-d\TH:i') : '' }}"
                                        required></div>
                                <div class="col-md-4 mb-3"><label
                                        class="form-label fw-bold small">Sorumlu</label><select name="user_id"
                                        class="form-select">
                                        @foreach (\App\Models\User::all() as $u)
                                            <option value="{{ $u->id }}"
                                                {{ $assignment->responsible_id == $u->id ? 'selected' : '' }}>
                                                {{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Ürün</label><select
                                        name="customer_product_id" class="form-select">
                                        <option value="">Seçiniz...</option>
                                        @foreach ($customer->products as $prod)
                                            <option value="{{ $prod->id }}"
                                                {{ $assignment->customer_product_id == $prod->id ? 'selected' : '' }}>
                                                {{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Miktar</label><input
                                        type="number" name="quantity" class="form-control"
                                        value="{{ $assignment->quantity }}" step="0.01"></div>
                                <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Birim</label><select
                                        name="unit" class="form-select">
                                        @foreach ($birimler as $birim)
                                            <option value="{{ $birim->ad }}"
                                                {{ $assignment->unit == $birim->ad ? 'selected' : '' }}>
                                                {{ $birim->ad }}</option>
                                        @endforeach
                                    </select></div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold small">Açıklama</label>
                                    <div class="input-group">
                                        <textarea name="description" id="log_desc_edit_{{ $assignment->id }}" class="form-control" rows="2">{{ $assignment->task_description }}</textarea>
                                        <button class="btn btn-outline-secondary" type="button"
                                            id="btn_log_desc_{{ $assignment->id }}"
                                            onclick="toggleVoiceInput('log_desc_edit_{{ $assignment->id }}', 'btn_log_desc_{{ $assignment->id }}')"><i
                                                class="fa-solid fa-microphone"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 bg-light"><button type="button"
                                class="btn btn-secondary rounded-pill px-4"
                                data-bs-dismiss="modal">İptal</button><button type="submit"
                                class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- Akıllı Filtreleme Scripti --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function filterList(dateInputId, searchInputId, statusInputId, itemSelector) {
                const dateVal = document.getElementById(dateInputId)?.value || '';
                const searchVal = document.getElementById(searchInputId)?.value.toLowerCase().trim() || '';
                const statusVal = document.getElementById(statusInputId)?.value || '';

                const items = document.querySelectorAll(itemSelector);

                items.forEach(item => {
                    if (item.classList.contains('empty-message-row')) return;
                    const itemDate = item.getAttribute('data-date') || '';
                    const itemSearch = item.getAttribute('data-search') || '';
                    const itemStatus = item.getAttribute('data-status') || '';

                    const matchDate = !dateVal || itemDate === dateVal;
                    const matchSearch = !searchVal || itemSearch.includes(searchVal);
                    const matchStatus = !statusVal || itemStatus === statusVal || (statusVal ===
                        'pending' && ['pending', 'open', 'preparing'].includes(itemStatus));

                    if (matchDate && matchSearch && matchStatus) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            }

            function attachFilters(prefix, itemSelector) {
                const dateEl = document.getElementById(`filter${prefix}Date`);
                const searchEl = document.getElementById(`filter${prefix}Search`);
                const statusEl = document.getElementById(`filter${prefix}Status`);

                const trigger = () => filterList(`filter${prefix}Date`, `filter${prefix}Search`,
                    `filter${prefix}Status`, itemSelector);

                if (dateEl) dateEl.addEventListener('input', trigger);
                if (searchEl) searchEl.addEventListener('input', trigger);
                if (statusEl) statusEl.addEventListener('change', trigger);
            }

            attachFilters('Prod', '.product-item');
            attachFilters('Opp', '.opp-item');
            attachFilters('Act', '.activity-item');
            attachFilters('Vis', '.visit-item');
            attachFilters('Sam', '.sample-item');
            attachFilters('Ret', '.return-item');
            attachFilters('Comp', '.complaint-item');
            attachFilters('Mac', '.machine-item');
            attachFilters('Test', '.test-item');
            attachFilters('Log', '.logistic-item');
        });
    </script>

    {{-- AKILLI ÜRÜN ÖNERİ LİSTESİ --}}
    <datalist id="productList">
        @foreach ($customer->products as $prod)
            <option value="{{ $prod->name }}"></option>
        @endforeach
    </datalist>
@endsection

@section('page_scripts')
    {{-- SPEECH TO TEXT (SESLİ NOT) MODÜLÜ --}}
    <script>
        function toggleVoiceInput(inputId, buttonId) {
            if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
                alert("Tarayıcınız sesli yazdırmayı desteklemiyor. Lütfen Google Chrome kullanın.");
                return;
            }

            const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
            const inputField = document.getElementById(inputId);
            const btnIcon = document.querySelector(`#${buttonId} i`);
            const btnElement = document.getElementById(buttonId);

            recognition.lang = 'tr-TR';
            recognition.continuous = false;
            recognition.interimResults = false;

            recognition.onstart = function() {
                if (btnIcon) {
                    btnIcon.classList.remove('fa-microphone');
                    btnIcon.classList.add('fa-spinner', 'fa-spin');
                }
                if (btnElement) {
                    btnElement.classList.remove('btn-outline-secondary', 'btn-secondary');
                    btnElement.classList.add('btn-danger', 'pulsing');
                }
            };

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                if (inputField.value.trim() === "") {
                    inputField.value = transcript.charAt(0).toUpperCase() + transcript.slice(1);
                } else {
                    inputField.value += " " + transcript;
                }
            };

            recognition.onend = function() {
                resetButton(btnIcon, btnElement);
            };
            recognition.onerror = function(event) {
                console.error("Ses tanıma hatası:", event.error);
                resetButton(btnIcon, btnElement);
            };

            recognition.start();
        }

        function resetButton(icon, btn) {
            if (icon) {
                icon.classList.remove('fa-spinner', 'fa-spin');
                icon.classList.add('fa-microphone');
            }
            if (btn) {
                btn.classList.remove('btn-danger', 'pulsing');
                btn.classList.add('btn-outline-secondary');
            }
        }
    </script>
@endsection
