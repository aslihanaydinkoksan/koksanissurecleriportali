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

        #app>main.py-4::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -10%;
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

        .modern-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        /* Page Header */
        .page-header {
            background: transparent;
            padding: 0 0 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .page-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0;
        }

        /* Filter Panel */
        .admin-filter-panel {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .filter-section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #4c1d95;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Elemanları */
        .modern-input,
        .modern-select {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }

        .modern-input:focus,
        .modern-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .modern-label {
            color: #4a5568;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Chart Container */
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

        /* Badges */
        .role-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            backdrop-filter: blur(4px);
        }

        .unit-badge {
            background: #FBD38D;
            color: #744210;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .dept-badge {
            background: #ffffff;
            color: #4c1d95;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Loading */
        .loading-overlay {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
        }

        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .btn-modern-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .btn-modern-secondary:hover {
            background: white;
            color: #4c1d95;
        }
    </style>
@endpush

@section('content')
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="dashboard-wrapper">
        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                        {{-- AKTİF FABRİKA ROZETİ --}}
                        <span class="unit-badge"><i class="fa-solid fa-industry me-1"></i> {{ $activeUnitName }}</span>

                        @if ($viewLevel === 'full')
                            <span class="role-badge"><i class="fa-solid fa-user-tie me-1"></i> Yönetici Görünümü</span>
                        @else
                            <span class="role-badge"><i class="fa-solid fa-user-gear me-1"></i> Personel Görünümü</span>
                        @endif
                    </div>
                    <h1 class="page-title">{{ $pageTitle }}</h1>

                    @if ($departmentSlug !== 'genel')
                        <div class="mt-2">
                            <span class="dept-badge"><i class="fa-solid fa-building me-1"></i> {{ $departmentName }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <a href="{{ route('home') }}" class="btn btn-modern btn-modern-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Takvime Dön
                    </a>
                </div>
            </div>
        </div>

        {{-- YÖNETİCİ/MÜDÜR İÇİN FİLTRE PANELİ --}}
        @if ($isManager || $isSuperUser || (isset($allowedDepartments) && $allowedDepartments->count() > 1))
            <div class="admin-filter-panel">
                <div class="filter-section-title">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Rapor Filtreleri</span>
                    </div>
                </div>
                <form method="GET" action="{{ route('statistics.index') }}" id="adminFilterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="modern-label">Departman Seçimi</label>
                            <select name="target_dept" id="deptSelect" class="form-select modern-select">
                                @if ($isSuperUser)
                                    <option value="genel" {{ $departmentSlug == 'genel' ? 'selected' : '' }}>📊 Genel Bakış
                                        ({{ $activeUnitName }})</option>
                                @endif
                                @foreach ($allowedDepartments as $dept)
                                    <option value="{{ $dept->slug }}"
                                        {{ $departmentSlug == $dept->slug ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="modern-label">Başlangıç Tarihi</label>
                            <input type="date" name="date_from" id="adminDateFrom" class="form-control modern-input"
                                value="{{ $filters['date_from'] }}">
                        </div>
                        <div class="col-md-3">
                            <label class="modern-label">Bitiş Tarihi</label>
                            <input type="date" name="date_to" id="adminDateTo" class="form-control modern-input"
                                value="{{ $filters['date_to'] }}">
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('statistics.index') }}"
                                class="btn btn-modern btn-modern-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-rotate-right"></i> Filtreleri Sıfırla
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        @else
            {{-- STANDART KULLANICI FİLTRE PANELİ --}}
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    <div class="filter-section-title mb-4 d-flex align-items-center justify-content-between">
                        <div><i class="fa-solid fa-filter"></i> Tarih Aralığı Seçiniz</div>
                    </div>
                    <form method="GET" action="{{ route('statistics.index') }}" id="standardFilterForm">
                        <input type="hidden" name="target_dept" value="{{ $departmentSlug }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="modern-label">Başlangıç</label>
                                <input type="date" name="date_from" id="std_date_from" class="form-control modern-input"
                                    value="{{ $filters['date_from'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="modern-label">Bitiş</label>
                                <input type="date" name="date_to" id="std_date_to" class="form-control modern-input"
                                    value="{{ $filters['date_to'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('statistics.index') }}"
                                    class="btn btn-modern btn-modern-primary w-100 d-flex align-items-center justify-content-center">
                                    <i class="fa-solid fa-rotate-right me-2"></i> Sıfırla
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- GİZLİ VERİ TAŞIYICI (JavaScript için) --}}
        <div id="stats-data-container" style="display: none;" data-chart-data='@json($chartData ?? [])'
            data-department-slug="{{ $departmentSlug ?? '' }}"
            @if ($departmentSlug === 'lojistik') data-shipments='@json($shipmentsForFiltering ?? [])'
            @elseif ($departmentSlug === 'uretim') data-production-plans='@json($productionPlansForFiltering ?? [])'
            @elseif ($departmentSlug === 'hizmet') data-events='@json($eventsForFiltering ?? [])'
            @elseif ($departmentSlug === 'ulastirma') data-assignments='@json($assignmentsForFiltering ?? [])' data-vehicles='@json($vehiclesForFiltering ?? [])'
            @elseif ($departmentSlug === 'bakim')
                data-maintenance-plans='@json($maintenancePlansForFiltering ?? [])'
                data-maintenance-types='@json($maintenanceTypes ?? [])'
                data-assets='@json($assets ?? [])' @endif>
        </div>

        {{-- GRAFİK ALANLARI --}}
        @if ($departmentSlug === 'genel')
            {{-- GENEL BAKIŞ --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">📊 {{ $activeUnitName }} - Aktivite Özeti</div>
                        <div id="department-summary-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stat-card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stat-label">Toplam İşlem</div>
                        <div class="stat-value">
                            {{ isset($chartData['departmentSummary']['data']) ? array_sum($chartData['departmentSummary']['data']) : 0 }}
                        </div>
                    </div>
                    <div class="stat-card mb-3" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <div class="stat-label">Başlangıç</div>
                        <div class="stat-value" style="font-size: 1.25rem;">{{ $filters['date_from'] }}</div>
                    </div>
                    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                        <div class="stat-label">Bitiş</div>
                        <div class="stat-value" style="font-size: 1.25rem;">{{ $filters['date_to'] }}</div>
                    </div>
                </div>
            </div>
        @elseif ($departmentSlug === 'lojistik')
            {{-- LOJİSTİK GRAFİKLERİ --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">🚛 Araç Tipi</div>
                        <div id="vehicle-type-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">📦 Kargo İçeriği</div>
                        <div id="cargo-content-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div id="hourly-chart-lojistik" style="height: 300px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div id="daily-chart-lojistik" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            @if (!empty($chartData['monthly']) || !empty($chartData['yearly']))
                <hr class="section-divider">
                <h4 class="mb-4 text-white"><i class="fa-solid fa-chart-pie me-2"></i>Yönetici Analizleri</h4>
                <div class="row g-4 mb-4">
                    @if (!empty($chartData['monthly']))
                        <div class="col-lg-8">
                            <div class="chart-container">
                                <div id="monthly-chart-lojistik" style="height: 350px;"></div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($chartData['yearly']))
                        <div class="col-lg-4">
                            <div class="chart-container">
                                <div id="yearly-chart-lojistik" style="height: 350px;"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @elseif($departmentSlug === 'uretim')
            {{-- ÜRETİM GRAFİKLERİ --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">⚙️ Makine Kullanımı</div>
                        <div id="machine-chart-uretim" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📊 Ürün Dağılımı</div>
                        <div id="product-chart-uretim" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="chart-container">
                        <div id="weekly-prod-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            @if (!empty($chartData['monthly_prod']))
                <hr class="section-divider">
                <h4 class="mb-4 text-white"><i class="fa-solid fa-chart-pie me-2"></i>Yönetici Analizleri</h4>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="chart-container">
                            <div id="monthly-prod-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($departmentSlug === 'hizmet')
            {{-- HİZMET GRAFİKLERİ --}}
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📅 Etkinlik Dağılımı</div>
                        <div id="event-type-pie-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="modern-alert modern-alert-info h-100 d-flex align-items-center">
                        <div><strong>Bilgi</strong>
                            <p class="mb-0">Araç görevleri için Ulaştırma departmanına geçiniz.</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($departmentSlug === 'ulastirma')
            {{-- ULAŞTIRMA GRAFİKLERİ --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📊 Görev Durumları</div>
                        <div id="status-pie-chart" style="height: 350px;"></div>
                    </div>
                </div>
                @if (!empty($chartData['top_vehicles']))
                    <div class="col-lg-6">
                        <div class="chart-container">
                            <div class="chart-header">🚗 En Çok Kullanılan Araçlar</div>
                            <div id="top-vehicles-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                @endif
            </div>
            @if (!empty($chartData['monthly_trend']))
                <div class="row g-4">
                    <div class="col-12">
                        <div class="chart-container">
                            <div class="chart-header">📈 Aylık Görev Trendi</div>
                            <div id="monthly-trend-chart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif($departmentSlug === 'bakim')
            {{-- BAKIM GRAFİKLERİ --}}
            <div class="row g-4 mb-4">
                <div class="col-lg-12">
                    <div class="chart-container">
                        <div class="chart-header">📊 Bakım Türü Dağılımı</div>
                        <div id="maintenance-type-chart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
            @if (!empty($chartData['top_assets']) || !empty($chartData['monthly_maintenance']))
                <hr class="section-divider">
                <h4 class="mb-4 text-white"><i class="fa-solid fa-chart-pie me-2"></i>Yönetici Analizleri</h4>
                <div class="row g-4 mb-4">
                    @if (!empty($chartData['top_assets']))
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <div class="chart-header">⚠️ En Çok Arıza Yapanlar</div>
                                <div id="top-assets-chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    @endif
                    @if (!empty($chartData['monthly_maintenance']))
                        <div class="col-lg-6">
                            <div class="chart-container">
                                <div class="chart-header">📅 Aylık Bakım Yükü</div>
                                <div id="monthly-maintenance-chart" style="height: 350px;"></div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @else
            <div class="modern-alert modern-alert-info">
                <div class="d-flex align-items-center"><i class="fa-solid fa-info-circle me-3"
                        style="font-size: 1.5rem;"></i>
                    <div><strong>Veri Bulunamadı</strong>
                        <p class="mb-0">Bu departman için istatistik verisi yok.</p>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@section('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Veri okuma kısmı:
            const statsContainer = document.getElementById('stats-data-container');
            if (!statsContainer) return;
            let chartData = {};
            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
            } catch (e) {}

            // Ortak Grafik Ayarları
            const colorPalette = ['#667EEA', '#764BA2', '#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];
            const commonChartOptions = {
                chart: {
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    zoom: {
                        enabled: false
                    },
                    fontFamily: 'inherit'
                },
                colors: colorPalette,
                legend: {
                    show: false
                },
                title: {
                    align: 'left',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold',
                        color: '#2d3748'
                    }
                },
                noData: {
                    text: 'Veri Yok'
                },
                dataLabels: {
                    enabled: false
                }
            };
            const commonBarOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'bar'
                },
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 8
                    }
                }
            };
            const commonAreaOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'area'
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        opacityFrom: 0.6,
                        opacityTo: 0.1
                    }
                }
            };
            const commonPieOptions = {
                ...commonChartOptions,
                chart: {
                    ...commonChartOptions.chart,
                    type: 'donut'
                },
                legend: {
                    position: 'bottom',
                    show: true
                }
            };

            function safeRender(selector, options) {
                if (document.querySelector(selector)) {
                    new ApexCharts(document.querySelector(selector), options).render();
                }
            }

            // --- GRAFİKLERİ ÇİZ ---
            const deptSlug = statsContainer.dataset.departmentSlug;

            // 1. GENEL BAKIŞ
            if (deptSlug === 'genel' && chartData.departmentSummary) {
                safeRender("#department-summary-chart", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Aktivite',
                        data: chartData.departmentSummary.data || []
                    }],
                    xaxis: {
                        categories: chartData.departmentSummary.labels || []
                    },
                    title: {
                        text: chartData.departmentSummary.title
                    }
                });
            }
            // 2. LOJİSTİK
            else if (deptSlug === 'lojistik') {
                if (chartData.pie) safeRender('#vehicle-type-chart', {
                    ...commonPieOptions,
                    series: chartData.pie.data,
                    labels: chartData.pie.labels,
                    title: {
                        text: chartData.pie.title
                    }
                });
                // Kargo grafiği için ekstra veri çekimi gerekebilir, şimdilik boş bırakıyoruz veya custom veri varsa kullanılır.
                if (chartData.hourly) safeRender("#hourly-chart-lojistik", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.hourly.data
                    }],
                    xaxis: {
                        categories: chartData.hourly.labels
                    },
                    title: {
                        text: chartData.hourly.title
                    }
                });
                if (chartData.daily) safeRender("#daily-chart-lojistik", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.daily.data
                    }],
                    xaxis: {
                        categories: chartData.daily.labels
                    },
                    title: {
                        text: chartData.daily.title
                    }
                });
                if (chartData.monthly) safeRender("#monthly-chart-lojistik", {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.monthly.data
                    }],
                    xaxis: {
                        categories: chartData.monthly.labels
                    },
                    title: {
                        text: chartData.monthly.title
                    }
                });
                if (chartData.yearly) safeRender("#yearly-chart-lojistik", {
                    ...commonBarOptions,
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true
                        }
                    },
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.yearly.data
                    }],
                    xaxis: {
                        categories: chartData.yearly.labels
                    },
                    title: {
                        text: chartData.yearly.title
                    }
                });
            }
            // 3. ÜRETİM
            else if (deptSlug === 'uretim') {
                if (chartData.weekly_prod) safeRender("#weekly-prod-chart", {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan',
                        data: chartData.weekly_prod.data
                    }],
                    xaxis: {
                        categories: chartData.weekly_prod.labels
                    },
                    title: {
                        text: chartData.weekly_prod.title
                    }
                });
                if (chartData.monthly_prod) safeRender("#monthly-prod-chart", {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan',
                        data: chartData.monthly_prod.data
                    }],
                    xaxis: {
                        categories: chartData.monthly_prod.labels
                    },
                    title: {
                        text: chartData.monthly_prod.title
                    }
                });
                // Makine ve Ürün grafikleri JS tarafında filtrelenerek dolduruluyor, burada placeholder render edilebilir.
            }
            // 4. HİZMET
            else if (deptSlug === 'hizmet') {
                if (chartData.event_type_pie) safeRender("#event-type-pie-chart", {
                    ...commonPieOptions,
                    series: chartData.event_type_pie.data,
                    labels: chartData.event_type_pie.labels,
                    title: {
                        text: chartData.event_type_pie.title
                    }
                });
            }
            // 5. ULAŞTIRMA
            else if (deptSlug === 'ulastirma') {
                if (chartData.status_pie) safeRender("#status-pie-chart", {
                    ...commonPieOptions,
                    series: chartData.status_pie.data,
                    labels: chartData.status_pie.labels,
                    title: {
                        text: chartData.status_pie.title
                    }
                });
                if (chartData.top_vehicles) safeRender("#top-vehicles-chart", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Görev',
                        data: chartData.top_vehicles.data
                    }],
                    xaxis: {
                        categories: chartData.top_vehicles.labels
                    },
                    title: {
                        text: chartData.top_vehicles.title
                    },
                    colors: ['#3182CE']
                });
                if (chartData.monthly_trend) safeRender("#monthly-trend-chart", {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Toplam Görev',
                        data: chartData.monthly_trend.data
                    }],
                    xaxis: {
                        categories: chartData.monthly_trend.labels
                    },
                    title: {
                        text: chartData.monthly_trend.title
                    },
                    colors: ['#805AD5']
                });
            }
            // 6. BAKIM
            else if (deptSlug === 'bakim') {
                if (chartData.type_dist) safeRender("#maintenance-type-chart", {
                    ...commonPieOptions,
                    series: chartData.type_dist.data,
                    labels: chartData.type_dist.labels,
                    title: {
                        text: 'Bakım Türü Dağılımı'
                    }
                });
                if (chartData.top_assets) safeRender("#top-assets-chart", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Bakım',
                        data: chartData.top_assets.data
                    }],
                    xaxis: {
                        categories: chartData.top_assets.labels
                    },
                    title: {
                        text: chartData.top_assets.title
                    },
                    colors: ['#F56565']
                });
                if (chartData.monthly_maintenance) safeRender("#monthly-maintenance-chart", {
                    ...commonAreaOptions,
                    series: [{
                        name: 'Plan',
                        data: chartData.monthly_maintenance.data
                    }],
                    xaxis: {
                        categories: chartData.monthly_maintenance.labels
                    },
                    title: {
                        text: chartData.monthly_maintenance.title
                    },
                    colors: ['#ED8936']
                });
            }
        });

        // Form gönderme mantığı
        const forms = [{
            id: 'adminFilterForm',
            from: 'adminDateFrom',
            to: 'adminDateTo',
            select: 'deptSelect'
        }, {
            id: 'standardFilterForm',
            from: 'std_date_from',
            to: 'std_date_to',
            select: null
        }];

        function submitForm(id) {
            document.getElementById(id)?.submit();
        }
        forms.forEach(f => {
            const el = document.getElementById(f.id);
            if (el) {
                if (f.select) document.getElementById(f.select)?.addEventListener('change', () => submitForm(f.id));
                const d1 = document.getElementById(f.from);
                const d2 = document.getElementById(f.to);
                if (d1) d1.addEventListener('change', () => {
                    if (d2.value) submitForm(f.id);
                });
                if (d2) d2.addEventListener('change', () => {
                    if (d1.value) submitForm(f.id);
                });
            }
        });
    </script>
@endsection
