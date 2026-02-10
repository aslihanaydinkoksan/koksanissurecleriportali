@extends('layouts.app')

@section('title', $pageTitle)

@push('styles')
    <style>
        /* === 1. SAYFA ARKA PLANI === */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(135deg, #4c1d95 0%, #3b82f6 100%);
            position: relative;
            background-attachment: fixed;
            overflow-x: hidden;
        }

        #app>main.py-4::before {
            content: '';
            position: absolute;
            top: -10%;
            left: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* === 2. DASHBOARD WRAPPER === */
        .dashboard-wrapper {
            max-width: 1400px;
            width: 95%;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        /* === 3. MODERN KARTLAR === */
        .modern-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .page-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
        }

        .page-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .admin-filter-panel {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .chart-container {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .chart-header {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #edf2f7;
        }

        .unit-badge {
            background: #FBD38D;
            color: #744210;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .dept-badge {
            background: #ffffff;
            color: #4c1d95;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
        }

        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            font-weight: 600;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-wrapper">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        <span class="unit-badge"><i class="fa-solid fa-industry me-1"></i> {{ $activeUnitName }}</span>
                        <span class="role-badge"><i class="fa-solid fa-user-tie me-1"></i>
                            {{ $viewLevel === 'full' ? 'Yönetici Görünümü' : 'Personel Görünümü' }}</span>
                    </div>
                    <h1 class="page-title">{{ $pageTitle }}</h1>
                    @if ($departmentSlug !== 'genel')
                        <div class="mt-2">
                            <span class="dept-badge"><i class="fa-solid fa-building me-1"></i> {{ $departmentName }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <a href="{{ route('home') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="fa-solid fa-arrow-left me-2"></i> Takvime Dön
                    </a>
                </div>
            </div>
        </div>

        {{-- Filtre Paneli --}}
        <div
            class="{{ $isManager || $isSuperUser || (isset($allowedDepartments) && $allowedDepartments->count() > 1) ? 'admin-filter-panel' : 'modern-card p-4' }}">
            <form method="GET" action="{{ route('statistics.index') }}" id="filterForm">
                <div class="row g-3 align-items-end">
                    @if ($isManager || $isSuperUser || (isset($allowedDepartments) && $allowedDepartments->count() > 1))
                        <div class="col-md-3">
                            <label class="small fw-bold text-muted mb-1">Departman Seçimi</label>
                            <select name="target_dept" id="deptSelect" class="form-select border-0 shadow-sm rounded-3">
                                @if ($isSuperUser)
                                    <option value="genel" {{ $departmentSlug == 'genel' ? 'selected' : '' }}>📊 Genel Bakış
                                    </option>
                                @endif
                                @foreach ($allowedDepartments as $dept)
                                    <option value="{{ $dept->slug }}"
                                        {{ $departmentSlug == $dept->slug ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="target_dept" value="{{ $departmentSlug }}">
                    @endif
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Başlangıç Tarihi</label>
                        <input type="date" name="date_from" id="dateFrom"
                            class="form-control border-0 shadow-sm rounded-3" value="{{ $filters['date_from'] }}">
                    </div>
                    <div class="col-md-3">
                        <label class="small fw-bold text-muted mb-1">Bitiş Tarihi</label>
                        <input type="date" name="date_to" id="dateTo"
                            class="form-control border-0 shadow-sm rounded-3" value="{{ $filters['date_to'] }}">
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('statistics.index') }}"
                            class="btn btn-outline-secondary w-100 rounded-3 shadow-sm">
                            <i class="fa-solid fa-rotate-right me-2"></i> Sıfırla
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Gizli Veri Taşıyıcı --}}
        <div id="stats-data-container" style="display: none;" data-chart-data='@json($chartData ?? [])'
            data-department-slug="{{ $departmentSlug ?? '' }}">
        </div>

        {{-- Grafik Alanları --}}
        @if ($departmentSlug === 'genel')
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">📊 Aktivite Özeti</div>
                        <div id="department-summary-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stat-card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stat-label">Toplam Kayıt</div>
                        <div class="stat-value">
                            {{ isset($chartData['departmentSummary']['data']) ? array_sum($chartData['departmentSummary']['data']) : 0 }}
                        </div>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="stat-label">Rapor Aralığı</div>
                        <div class="stat-value" style="font-size: 1.1rem;">{{ $filters['date_from'] }} /
                            {{ $filters['date_to'] }}</div>
                    </div>
                </div>
            </div>
        @elseif ($departmentSlug === 'lojistik')
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">🚛 Araç Tipi Dağılımı</div>
                        <div id="vehicle-type-chart"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">📦 Kargo İçeriği Dağılımı</div>
                        <div id="cargo-content-chart"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">⏰ Saatlik Yoğunluk</div>
                        <div id="hourly-chart-lojistik"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">📅 Günlük Sevkiyat Dağılımı</div>
                        <div id="daily-chart-lojistik"></div>
                    </div>
                </div>
            </div>
        @elseif ($departmentSlug === 'uretim')
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">⚙️ Makine Kullanım Yoğunluğu</div>
                        <div id="machine-chart-uretim"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📊 Ürün Üretim Dağılımı</div>
                        <div id="product-chart-uretim"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="chart-container">
                        <div class="chart-header">📅 Haftalık Plan Sayısı</div>
                        <div id="weekly-prod-chart"></div>
                    </div>
                </div>
            </div>
        @elseif ($departmentSlug === 'hizmet')
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📅 Etkinlik Dağılımı</div>
                        <div id="event-type-pie-chart"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📂 Masraf Kategorileri</div>
                        <div id="expense-categories-pie-chart"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">💰 Para Birimi Bazlı Toplam Harcama</div>
                        <div id="expense-currency-bar-chart"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="modern-card p-4 h-100 d-flex align-items-center bg-info text-white border-0 shadow">
                        <div><i class="fa-solid fa-circle-info fa-2x mb-3"></i>
                            <h5>Finansal İçgörü</h5>
                            <p class="mb-0">Masraf verileri, onaylanmış seyahat ve etkinlik planlarından polimorfik
                                olarak çekilmektedir.</p>
                            <a href="{{ route('statistics.finance') }}" class="btn btn-success shadow-sm">
                                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Finansal Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($departmentSlug === 'ulastirma')
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📊 Görev Durumları</div>
                        <div id="status-pie-chart"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    @if (!empty($chartData['top_vehicles']))
                        <div class="chart-container">
                            <div class="chart-header">🚗 En Çok Görev Yapan Araçlar</div>
                            <div id="top-vehicles-chart"></div>
                        </div>
                    @endif
                </div>
            </div>
        @elseif ($departmentSlug === 'bakim')
            <div class="row g-4 mb-4">
                <div class="col-12">
                    <div class="chart-container">
                        <div class="chart-header">📊 Bakım Türü Dağılımı</div>
                        <div id="maintenance-type-chart"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    @if (!empty($chartData['top_assets']))
                        <div class="chart-container">
                            <div class="chart-header">⚠️ En Sık Bakım Yapılan Varlıklar</div>
                            <div id="top-assets-chart"></div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    @if (!empty($chartData['monthly_maintenance']))
                        <div class="chart-container">
                            <div class="chart-header">📅 Aylık Bakım Yükü</div>
                            <div id="monthly-maintenance-chart"></div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection

@section('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statsContainer = document.getElementById('stats-data-container');
            if (!statsContainer) return;

            let chartData = {};
            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
            } catch (e) {
                console.error("Veri parse hatası:", e);
            }

            const deptSlug = statsContainer.dataset.departmentSlug;
            const colors = ['#667EEA', '#764BA2', '#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];

            function safeRender(selector, options) {
                const el = document.querySelector(selector);
                if (el) {
                    options.chart.fontFamily = 'inherit';
                    options.chart.toolbar = {
                        show: false
                    };
                    options.noData = {
                        text: 'Veri Yok'
                    };
                    new ApexCharts(el, options).render();
                }
            }

            const barDefaults = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                colors: colors,
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        distributed: true
                    }
                }
            };
            const pieDefaults = {
                chart: {
                    type: 'donut',
                    height: 350
                },
                colors: colors,
                legend: {
                    position: 'bottom'
                }
            };
            const areaDefaults = {
                chart: {
                    type: 'area',
                    height: 350
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                }
            };

            // 1. GENEL BAKIŞ
            if (deptSlug === 'genel' && chartData.departmentSummary) {
                safeRender("#department-summary-chart", {
                    ...barDefaults,
                    series: [{
                        name: 'Kayıt',
                        data: chartData.departmentSummary.data
                    }],
                    xaxis: {
                        categories: chartData.departmentSummary.labels
                    }
                });
            }

            // 2. ÜRETİM
            else if (deptSlug === 'uretim') {
                if (chartData.machine_usage) safeRender("#machine-chart-uretim", {
                    ...barDefaults,
                    series: [{
                        name: 'Üretim',
                        data: chartData.machine_usage.data
                    }],
                    xaxis: {
                        categories: chartData.machine_usage.labels
                    }
                });
                if (chartData.product_dist) safeRender("#product-chart-uretim", {
                    ...pieDefaults,
                    series: chartData.product_dist.data,
                    labels: chartData.product_dist.labels
                });
                if (chartData.weekly_prod) safeRender("#weekly-prod-chart", {
                    ...areaDefaults,
                    series: [{
                        name: 'Plan',
                        data: chartData.weekly_prod.data
                    }],
                    xaxis: {
                        categories: chartData.weekly_prod.labels
                    }
                });
            }

            // 3. LOJİSTİK
            else if (deptSlug === 'lojistik') {
                if (chartData.pie) safeRender("#vehicle-type-chart", {
                    ...pieDefaults,
                    series: chartData.pie.data,
                    labels: chartData.pie.labels
                });
                if (chartData.cargo_pie) safeRender("#cargo-content-chart", {
                    ...pieDefaults,
                    series: chartData.cargo_pie.data,
                    labels: chartData.cargo_pie.labels
                });
                if (chartData.hourly) safeRender("#hourly-chart-lojistik", {
                    ...barDefaults,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.hourly.data
                    }],
                    xaxis: {
                        categories: chartData.hourly.labels
                    }
                });
                if (chartData.daily) safeRender("#daily-chart-lojistik", {
                    ...barDefaults,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.daily.data
                    }],
                    xaxis: {
                        categories: chartData.daily.labels
                    }
                });
            }

            // 4. HİZMET (İdari İşler)
            else if (deptSlug === 'hizmet') {
                if (chartData.event_type_pie) safeRender("#event-type-pie-chart", {
                    ...pieDefaults,
                    series: chartData.event_type_pie.data,
                    labels: chartData.event_type_pie.labels
                });
                // Yeni Masraf Grafikleri
                if (chartData.expense_categories) safeRender("#expense-categories-pie-chart", {
                    ...pieDefaults,
                    series: chartData.expense_categories.data,
                    labels: chartData.expense_categories.labels
                });
                if (chartData.expense_currency) safeRender("#expense-currency-bar-chart", {
                    ...barDefaults,
                    series: [{
                        name: 'Toplam',
                        data: chartData.expense_currency.data
                    }],
                    xaxis: {
                        categories: chartData.expense_currency.labels
                    }
                });
            }

            // 5. ULAŞTIRMA
            else if (deptSlug === 'ulastirma') {
                if (chartData.status_pie) safeRender("#status-pie-chart", {
                    ...pieDefaults,
                    series: chartData.status_pie.data,
                    labels: chartData.status_pie.labels
                });
                if (chartData.top_vehicles) safeRender("#top-vehicles-chart", {
                    ...barDefaults,
                    series: [{
                        name: 'Görev',
                        data: chartData.top_vehicles.data
                    }],
                    xaxis: {
                        categories: chartData.top_vehicles.labels
                    }
                });
            }

            // 6. BAKIM
            else if (deptSlug === 'bakim') {
                if (chartData.type_dist) safeRender("#maintenance-type-chart", {
                    ...pieDefaults,
                    series: chartData.type_dist.data,
                    labels: chartData.type_dist.labels
                });
                if (chartData.top_assets) safeRender("#top-assets-chart", {
                    ...barDefaults,
                    series: [{
                        name: 'Bakım',
                        data: chartData.top_assets.data
                    }],
                    xaxis: {
                        categories: chartData.top_assets.labels
                    }
                });
                if (chartData.monthly_maintenance) safeRender("#monthly-maintenance-chart", {
                    ...areaDefaults,
                    series: [{
                        name: 'Yük',
                        data: chartData.monthly_maintenance.data
                    }],
                    xaxis: {
                        categories: chartData.monthly_maintenance.labels
                    }
                });
            }

            const autoSubmit = (id) => document.getElementById(id)?.addEventListener('change', () => document
                .getElementById('filterForm').submit());
            autoSubmit('deptSelect');
            autoSubmit('dateFrom');
            autoSubmit('dateTo');
        });
    </script>
@endsection
