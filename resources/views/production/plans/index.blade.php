@extends('layouts.app')
@section('title', 'Üretim Planları')

@push('styles')
    <style>
        /* Ana içerik alanı - Modern gradient arka plan */
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

        /* Container için max-width */
        .modern-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Üst başlık bölümü */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-header p {
            margin: 0.5rem 0 0 0;
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* Alert mesajları - Modern stil */
        .alert {
            border-radius: 15px;
            border: none;
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .alert-success {
            background: rgba(40, 199, 111, 0.15);
            color: #1e7e34;
        }

        .alert-danger {
            background: rgba(235, 87, 87, 0.15);
            color: #c82333;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.15);
            color: #856404;
        }

        /* Filtre butonu */
        .btn-filter-toggle {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(102, 126, 234, 0.3);
            border-radius: 15px;
            color: #667eea;
            font-weight: 600;
            padding: 1rem 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        }

        .btn-filter-toggle:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-filter-toggle[aria-expanded="true"] {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .btn-filter-toggle i {
            transition: transform 0.3s ease;
        }

        .btn-filter-toggle[aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }

        /* Filtre kartı */
        .filter-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-card .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .filter-card .form-label i {
            margin-right: 0.5rem;
            color: #667eea;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background-color: white;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        /* Butonlar */
        .btn-apply-filter {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-apply-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-clear-filter {
            background: white;
            border: 2px solid #e9ecef;
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .btn-clear-filter:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
            transform: translateY(-2px);
        }

        /* YENİ: Global Export Butonu Stili */
        .btn-export-global {
            background: linear-gradient(135deg, #28c76f 0%, #1e7e34 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 199, 111, 0.3);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .btn-export-global:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 199, 111, 0.4);
            color: white;
        }

        /* Ana liste kartı */
        .list-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .list-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            border: none;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .list-card .card-header i {
            font-size: 1.5rem;
        }

        /* Tablo stilleri */
        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(102, 126, 234, 0.05);
        }

        .table thead th {
            color: #495057;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            padding: 1.25rem 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.08) !important;
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .table td {
            vertical-align: middle;
            padding: 1.25rem 1rem;
            color: #495057;
            font-size: 0.95rem;
        }

        /* Önemli satır vurgusu */
        .row-important {
            background: linear-gradient(90deg, rgba(255, 107, 107, 0.15) 0%, rgba(255, 107, 107, 0.05) 100%) !important;
            border-left: 4px solid #ff6b6b !important;
        }

        .row-important td {
            color: #c92a2a;
            font-weight: 600;
        }

        .row-important:hover {
            background: linear-gradient(90deg, rgba(255, 107, 107, 0.25) 0%, rgba(255, 107, 107, 0.1) 100%) !important;
        }

        /* Aksiyon butonları */
        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            margin: 0 0.25rem;
        }

        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-action.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        /* YENİ: Tablo İçi Yeşil Export Butonu */
        .btn-action.btn-success {
            background: linear-gradient(135deg, #28c76f 0%, #1e7e34 100%);
            color: white;
        }

        /* Pagination */
        .pagination {
            margin: 0;
            gap: 0.5rem;
        }

        .page-item .page-link {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            color: #667eea;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            margin: 0;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .page-item .page-link:hover {
            background: #667eea;
            border-color: #667eea;
            color: white;
            transform: translateY(-2px);
        }

        .card-footer {
            background: rgba(255, 255, 255, 0.5);
            border-top: 1px solid rgba(0, 0, 0, 0.05) !important;
            padding: 1.5rem 2rem !important;
        }

        /* Badge stilleri */
        .badge-custom {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-important {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        /* Responsive iyileştirmeler */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .btn-filter-toggle {
                font-size: 0.9rem;
                padding: 0.75rem 1rem;
            }

            .table {
                font-size: 0.85rem;
            }

            .btn-action {
                width: 35px;
                height: 35px;
                font-size: 0.8rem;
            }

            .btn-export-global {
                width: 100%;
                justify-content: center;
                margin-top: 10px;
            }
        }

        html {
            scroll-behavior: smooth;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid modern-container">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Sayfa Başlığı --}}
                <div class="page-header">
                    <h1><i class="fas fa-clipboard-list"></i> Üretim Planları</h1>
                    <p>Haftalık üretim planlarınızı görüntüleyin, düzenleyin ve yönetin.</p>
                </div>

                {{-- Başarı/Hata Mesajları --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Filtre Bölümü --}}
                <div class="mb-4">
                    <div class="d-grid">
                        <button class="btn btn-filter-toggle" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-sliders-h me-2"></i> Filtre Seçenekleri
                            <i class="fas fa-chevron-down ms-2"></i>
                        </button>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <div class="card filter-card">
                            <form method="GET" action="{{ route('production.plans.index') }}">
                                <div class="row">
                                    @php
                                        $isAdminOrManager = in_array(Auth::user()->role, ['admin', 'yönetici']);
                                    @endphp

                                    {{-- Plan Başlığı --}}
                                    <div class="{{ $isAdminOrManager ? 'col-lg-3 col-md-6' : 'col-md-6' }}">
                                        <label for="plan_title" class="form-label">
                                            <i class="fas fa-search"></i>Plan Başlığı
                                        </label>
                                        <input type="text" class="form-control" id="plan_title" name="plan_title"
                                            value="{{ $filters['plan_title'] ?? '' }}" placeholder="Plan başlığı girin...">
                                    </div>

                                    {{-- Önem Durumu --}}
                                    @if ($isAdminOrManager)
                                        <div class="col-lg-3 col-md-6">
                                            <label for="is_important" class="form-label">
                                                <i class="fas fa-exclamation-triangle"></i>Önem Durumu
                                            </label>
                                            <select class="form-select" id="is_important" name="is_important">
                                                <option value="all"
                                                    {{ ($filters['is_important'] ?? 'all') == 'all' ? 'selected' : '' }}>
                                                    Tümü
                                                </option>
                                                <option value="yes"
                                                    {{ ($filters['is_important'] ?? '') == 'yes' ? 'selected' : '' }}>
                                                    Sadece Önemliler
                                                </option>
                                                <option value="no"
                                                    {{ ($filters['is_important'] ?? '') == 'no' ? 'selected' : '' }}>
                                                    Önemli Olmayanlar
                                                </option>
                                            </select>
                                        </div>
                                    @endif

                                    {{-- Tarih Aralığı --}}
                                    <div class="col-lg-3 col-md-6">
                                        <label for="date_from" class="form-label">
                                            <i class="fas fa-calendar-alt"></i>Başlangıç Tarihi
                                        </label>
                                        <input type="date" class="form-control" id="date_from" name="date_from"
                                            value="{{ $filters['date_from'] ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-6">
                                        <label for="date_to" class="form-label">
                                            <i class="fas fa-calendar-check"></i>Bitiş Tarihi
                                        </label>
                                        <input type="date" class="form-control" id="date_to" name="date_to"
                                            value="{{ $filters['date_to'] ?? '' }}">
                                    </div>
                                </div>

                                {{-- Butonlar --}}
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('production.plans.index') }}" class="btn btn-clear-filter">
                                        <i class="fas fa-times me-2"></i>Temizle
                                    </a>
                                    <button type="submit" class="btn btn-apply-filter">
                                        <i class="fas fa-check me-2"></i>Filtrele
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- YENİ EKLENDİ: Global Excel Export Butonu --}}
                <div class="mb-4 text-end">
                    <a href="{{ route('production.plans.export_list') }}" class="btn-export-global">
                        <i class="fas fa-file-excel me-2"></i> Tüm Plan Listesini Excel'e Aktar
                    </a>
                </div>

                {{-- Ana Liste Kartı --}}
                <div class="card list-card">
                    <div class="card-header">
                        <i class="fas fa-list"></i>
                        <span>Plan Listesi</span>
                    </div>

                    <div class="card-body p-0">
                        @if ($plans->isEmpty())
                            <div class="alert alert-warning m-4" role="alert">
                                <i class="fas fa-info-circle me-2"></i>Kayıtlı üretim planı bulunamadı.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="ps-3">Plan Başlığı</th>
                                            <th scope="col">Hafta Başlangıcı</th>
                                            <th scope="col">Oluşturan</th>
                                            <th scope="col">Kayıt Tarihi</th>
                                            <th scope="col" class="text-end pe-3">İşlemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($plans as $plan)
                                            <tr class="{{ $plan->is_important ? 'row-important' : '' }}">
                                                <td class="ps-3">
                                                    <strong>{{ $plan->plan_title }}</strong>
                                                    @if ($plan->is_important)
                                                        <span class="badge badge-custom badge-important ms-2">
                                                            <i class="fas fa-star"></i> Önemli
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar me-2"></i>
                                                    {{ $plan->week_start_date ? \Carbon\Carbon::parse($plan->week_start_date)->format('d.m.Y') : '-' }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-user me-2"></i>
                                                    {{ $plan->user->name ?? 'Bilinmiyor' }}
                                                </td>
                                                <td>
                                                    <i class="fas fa-clock me-2"></i>
                                                    {{ $plan->created_at ? \Carbon\Carbon::parse($plan->created_at)->format('d.m.Y H:i') : '-' }}
                                                </td>
                                                <td class="text-end pe-3">
                                                    {{-- İş Emri İndir Butonu --}}
                                                    <a href="{{ route('production.plans.export', $plan) }}"
                                                        class="btn btn-success btn-sm text-white"
                                                        title="İş Emri Fişini İndir">
                                                        <i class="fas fa-file-excel me-1"></i> Planı Excel'e Aktar
                                                    </a>

                                                    @if (!in_array(Auth::user()->role, ['izleyici']))
                                                        {{-- Düzenle Butonu --}}
                                                        <a href="{{ route('production.plans.edit', $plan) }}"
                                                            class="btn btn-primary btn-sm text-white" title="Düzenle">
                                                            <i class="fas fa-edit me-1"></i> Düzenle
                                                        </a>

                                                        {{-- Sil Butonu --}}
                                                        <form action="{{ route('production.plans.destroy', $plan) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Bu planı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm text-white" title="Sil">
                                                                <i class="fas fa-trash me-1"></i> Sil
                                                            </button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Sayfalama --}}
                            @if ($plans->hasPages())
                                <div class="card-footer">
                                    {{ $plans->appends($filters ?? [])->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtre toggle animasyonu
            const filterCollapse = document.getElementById('filterCollapse');
            const filterButton = document.querySelector('.btn-filter-toggle');
            const chevronIcon = filterButton.querySelector('.fa-chevron-down');

            if (filterCollapse) {
                filterCollapse.addEventListener('show.bs.collapse', function() {
                    chevronIcon.style.transform = 'rotate(180deg)';
                });

                filterCollapse.addEventListener('hide.bs.collapse', function() {
                    chevronIcon.style.transform = 'rotate(0deg)';
                });

                // Sayfa yüklendiğinde filtre açıksa icon'u döndür
                if (filterCollapse.classList.contains('show')) {
                    chevronIcon.style.transform = 'rotate(180deg)';
                }
            }

            // Alert otomatik kapanma
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Tablo satırı animasyonu
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animation = `slideDown 0.3s ease ${index * 0.05}s forwards`;
                row.style.opacity = '0';
            });
        });
    </script>
@endsection
