@extends('layouts.app')

@section('title', 'Müşteri Yönetimi')

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

        /* Modern kart tasarımı */
        .customer-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .customer-card:hover {
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        }

        /* Tablo hover efekti */
        .table-hover tbody tr {
            transition: all 0.2s ease;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(102, 126, 234, 0.08) !important;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Modern butonlar */
        .btn-primary-gradient {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-outline-primary {
            border-color: #667EEA;
            color: #667EEA;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: #667EEA;
            border-color: #667EEA;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Arama kutusu */
        .search-input {
            border: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem 0.75rem 3rem;
            transition: all 0.3s ease;
            background-color: rgba(255, 255, 255, 0.9);
        }

        .search-input:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
            background-color: white;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #667EEA;
        }

        /* Başarı mesajı animasyonu */
        .success-alert {
            animation: slideInDown 0.5s ease-out;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tablo styling */
        .table thead th {
            background-color: rgba(102, 126, 234, 0.08);
            border-bottom: 2px solid #667EEA;
            font-weight: 600;
            color: #444;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
        }

        /* Sayfa numaraları */
        .pagination .page-link {
            color: #667EEA;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 0.5rem;
            margin: 0 0.25rem;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background-color: #667EEA;
            color: white;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background-color: #667EEA;
            border-color: #667EEA;
        }

        /* Responsive iyileştirmeleri */
        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card">
                    <!-- Header Section -->
                    <div class="card-header bg-transparent border-0 px-4 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h2 class="mb-2 fw-bold" style="color: #2d3748;">
                                    <i class="fa-solid fa-users me-2" style="color: #667EEA;"></i>
                                    Müşteri Listesi
                                </h2>
                                <p class="text-muted mb-0">Tüm müşterilerinizi görüntüleyin ve yönetin</p>
                            </div>
                            <a href="{{ route('customers.create') }}"
                                class="btn btn-primary-gradient rounded-pill px-4 py-2">
                                <i class="fa-solid fa-plus me-2"></i>
                                Yeni Müşteri Ekle
                            </a>
                        </div>
                    </div>

                    <div class="card-body px-4">
                        <!-- Arama Formu -->
                        <form method="GET" action="{{ route('customers.index') }}" class="mb-4" autocomplete="off">
                            <div class="position-relative">
                                <i class="fa-solid fa-search search-icon"></i>
                                <input type="text" name="search" class="form-control search-input"
                                    placeholder="Müşteri adı, email veya telefona göre ara..." value="{{ $search ?? '' }}">
                            </div>
                        </form>

                        <!-- Başarı Mesajı -->
                        @if (session('success'))
                            <div class="alert success-alert d-flex align-items-center mb-4" role="alert">
                                <i class="fa-solid fa-circle-check me-3 fs-4"></i>
                                <div>
                                    <strong>Başarılı!</strong> {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        <!-- Müşteri Tablosu -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <i class="fa-solid fa-building me-2"></i>Müşteri Adı
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-envelope me-2"></i>Email
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-phone me-2"></i>Telefon
                                        </th>
                                        <th scope="col">
                                            <i class="fa-solid fa-user me-2"></i>İlgili Kişi
                                        </th>
                                        <th scope="col" class="text-end">
                                            <i class="fa-solid fa-cog me-2"></i>Eylemler
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($customers as $customer)
                                        <tr>
                                            <td>
                                                <strong class="text-dark">{{ $customer->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $customer->email ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $customer->phone ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $customer->contact_person ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('customers.show', $customer) }}"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2"
                                                        title="Görüntüle">
                                                        <i class="fa-solid fa-eye me-1"></i>
                                                        Müşteri Detaylarını Görüntüle
                                                    </a>
                                                    <a href="{{ route('customers.edit', $customer) }}"
                                                        class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                                                        title="Düzenle">
                                                        <i class="fa-solid fa-pen me-1"></i>
                                                        Düzenle
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="text-muted">
                                                    <i class="fa-solid fa-inbox fa-3x mb-3 d-block"
                                                        style="opacity: 0.3;"></i>
                                                    <p class="mb-0 fs-5">Kayıtlı müşteri bulunamadı.</p>
                                                    <p class="small">Yeni bir müşteri ekleyerek başlayın.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Sayfalama -->
                        @if ($customers->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $customers->appends(['search' => $search])->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
