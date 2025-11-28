@extends('layouts.app')

@section('title', $pageTitle)

@push('styles')
    <style>
        /* === 1. SAYFA ARKA PLANI === */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(135deg, #4c1d95 0%, #3b82f6 100%);
            /* Biraz daha koyu/derin bir arka plan */
            position: relative;
            background-attachment: fixed;
            /* Scroll yaparken arka plan sabit kalsın */
            overflow-x: hidden;
        }

        /* Arka plan desenleri */
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

        /* === 2. ANA DASHBOARD KAPSAYICISI (BOXED LAYOUT) === */
        /* İşte yayılmayı engelleyen ve "Kart" yapısını kuran kısım burası */
        .dashboard-wrapper {
            max-width: 1400px;
            /* İçerik en fazla bu kadar geniş olsun */
            width: 95%;
            /* Mobilde kenarlardan boşluk kalsın */
            margin: 0 auto;
            /* Ortala */

            background: rgba(255, 255, 255, 0.1);
            /* Hafif beyaz şeffaflık */
            backdrop-filter: blur(20px);
            /* Buzlu cam efekti */
            -webkit-backdrop-filter: blur(20px);

            border-radius: 24px;
            /* Köşeleri yuvarla */
            border: 1px solid rgba(255, 255, 255, 0.2);
            /* İnce beyaz çerçeve */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            /* Derin gölge */

            padding: 2rem;
            /* İç boşluk */
            position: relative;
            z-index: 10;
        }

        /* === 3. İÇ KARTLAR (MEVCUT TASARIMIN) === */
        /* Ana kartın içindeki alt kartların arka planını biraz daha belirgin yapıyoruz */
        .modern-card {
            background: rgba(255, 255, 255, 0.85);
            /* Daha opak beyaz */
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

        /* Page Header - Artık Wrapper içinde olduğu için gölgeyi azalttık */
        .page-header {
            background: transparent;
            /* Wrapper zaten arka planlı */
            padding: 0 0 1.5rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 0;
            box-shadow: none;
        }

        .page-title {
            font-size: 2.25rem;
            font-weight: 800;
            color: #ffffff;
            /* Koyu arka plan üzerinde beyaz yazı */
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background: none;
            -webkit-text-fill-color: initial;
            margin: 0;
        }

        /* Admin Filter Panel - İçerideki stil */
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

        /* Butonlar */
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

    {{-- YENİ YAPININ ANAHTARI BURADA: "container-fluid" YERİNE "dashboard-wrapper" --}}
    <div class="dashboard-wrapper">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <h1 class="page-title">{{ $pageTitle }}</h1>
                        @if ($viewLevel === 'full')
                            <span class="role-badge"><i class="fa-solid fa-user-tie me-1"></i> Yönetici Görünümü</span>
                        @else
                            <span class="role-badge"><i class="fa-solid fa-user-gear me-1"></i> Personel Görünümü</span>
                        @endif
                    </div>

                    @if ($departmentSlug !== 'genel')
                        <div class="mt-3">
                            <span class="dept-badge"><i class="fa-solid fa-building me-1"></i>
                                {{ $departmentName ?? $pageTitle }}</span>
                        </div>
                    @endif
                </div>
                <div class="col-md-5 text-md-end mt-4 mt-md-0">
                    <a href="{{ route('home') }}" class="btn btn-modern btn-modern-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i> Takvime Dön
                    </a>
                </div>
            </div>
        </div>

        {{-- SENARYO A: YÖNETİCİ/MÜDÜR İÇİN FİLTRE PANELİ --}}
        @if ($isManager || $isSuperUser || (isset($allowedDepartments) && $allowedDepartments->count() > 1))
            <div class="admin-filter-panel">
                <div class="filter-section-title">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Yönetim Filtreleri</span>
                    </div>
                </div>
                <form method="GET" action="{{ route('statistics.index') }}" id="adminFilterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="modern-label">Departman</label>
                            <select name="target_dept" id="deptSelect" class="form-select modern-select">
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
                        <div class="col-md-3">
                            <label class="modern-label">Başlangıç</label>
                            <input type="date" name="date_from" id="adminDateFrom" class="form-control modern-input"
                                value="{{ $filters['date_from'] }}">
                        </div>
                        <div class="col-md-3">
                            <label class="modern-label">Bitiş</label>
                            <input type="date" name="date_to" id="adminDateTo" class="form-control modern-input"
                                value="{{ $filters['date_to'] }}">
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('statistics.index') }}"
                                class="btn btn-modern btn-modern-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="fa-solid fa-rotate-right"></i> Sıfırla
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        @else
            {{-- SENARYO B: STANDART KULLANICI FİLTRE PANELİ --}}
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

        {{-- DATA CONTAINER (Gizli Veri Taşıyıcı - GÜNCELLENDİ) --}}
        <div id="stats-data-container" style="display: none;" data-chart-data='@json($chartData ?? [])'
            data-department-slug="{{ $departmentSlug ?? '' }}"
            @if ($departmentSlug === 'lojistik') data-shipments='@json($shipmentsForFiltering ?? [])'

            @elseif ($departmentSlug === 'uretim') 
                data-production-plans='@json($productionPlansForFiltering ?? [])'

            @elseif ($departmentSlug === 'hizmet') 
                {{-- Hizmet sadece etkinlikleri alır --}}
                data-events='@json($eventsForFiltering ?? [])'

            @elseif ($departmentSlug === 'ulastirma') 
                {{-- Ulaştırma araç ve görev verilerini alır --}}
                data-assignments='@json($assignmentsForFiltering ?? [])'
                data-vehicles='@json($vehiclesForFiltering ?? [])'

            @elseif ($departmentSlug === 'bakim')
                data-maintenance-plans='@json($maintenancePlansForFiltering ?? [])'
                data-maintenance-types='@json($maintenanceTypes ?? [])'
                data-assets='@json($assets ?? [])' @endif>
        </div>

        {{-- DEPARTMANA ÖZEL HIZLI FİLTRELER --}}
        @if ($departmentSlug !== 'genel')
            <div class="modern-card mb-4">
                <div class="card-body p-4">
                    @if ($departmentSlug === 'lojistik')
                        <h6 class="modern-label mb-3 text-primary"><i class="fa-solid fa-truck-fast me-2"></i> Lojistik
                            Detay Filtrele</h6>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="modern-label">Sevkiyat Türü</label><select
                                    id="shipmentTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                            <div class="col-md-4"><label class="modern-label">Araç Tipi</label><select
                                    id="vehicleTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                            <div class="col-md-4"><label class="modern-label">Kargo İçeriği</label><select
                                    id="cargoContentFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                        </div>
                    @elseif ($departmentSlug === 'uretim')
                        <h6 class="modern-label mb-3 text-primary"><i class="fa-solid fa-gears me-2"></i> Üretim Detay
                            Filtrele</h6>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="modern-label">Makine</label><select id="machineFilter"
                                    class="form-select modern-select">
                                    <option value="all">Tüm Makineler</option>
                                </select></div>
                            <div class="col-md-6"><label class="modern-label">Ürün</label><select id="productFilter"
                                    class="form-select modern-select">
                                    <option value="all">Tüm Ürünler</option>
                                </select></div>
                        </div>
                    @elseif ($departmentSlug === 'hizmet')
                        <h6 class="modern-label mb-3 text-primary"><i class="fa-solid fa-briefcase me-2"></i> İdari İşler
                            Detay Filtrele</h6>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="modern-label">Etkinlik Tipi</label><select
                                    id="eventTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                            <div class="col-md-6"><label class="modern-label">Araç</label><select id="vehicleFilter"
                                    class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                        </div>
                    @elseif ($departmentSlug === 'bakim')
                        <h6 class="modern-label mb-3 text-primary"><i class="fa-solid fa-screwdriver-wrench me-2"></i>
                            Bakım Detay Filtrele</h6>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="modern-label">Bakım Türü</label><select
                                    id="maintenanceTypeFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                            <div class="col-md-4"><label class="modern-label">Makine / Varlık</label><select
                                    id="assetFilter" class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                </select></div>
                            <div class="col-md-4"><label class="modern-label">Durum</label><select id="statusFilter"
                                    class="form-select modern-select">
                                    <option value="all">Tümü</option>
                                    <option value="pending">Bekleyenler</option>
                                    <option value="in_progress">Devam Edenler</option>
                                    <option value="completed">Tamamlananlar</option>
                                </select></div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- GRAFİK ALANLARI --}}
        @if ($departmentSlug === 'genel')
            {{-- GENEL BAKIŞ --}}
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">📊 Departman Aktivite Özeti</div>
                        <div id="department-summary-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="stat-card mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="stat-label">Toplam Aktivite</div>
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
            {{-- LOJİSTİK --}}
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-header">🚛 Araç Tipi Dağılımı</div>
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
            {{-- ÜRETİM --}}
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
            {{-- HİZMET --}}
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-header">📅 Etkinlik Dağılımı</div>
                        <div id="event-type-pie-chart" style="height: 350px;"></div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="modern-alert modern-alert-info h-100 d-flex align-items-center">
                        <div>
                            <strong>Bilgi</strong>
                            <p class="mb-0">Araç görevleri ve analizleri için lütfen <b>Ulaştırma</b> departmanına
                                geçiniz.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- YENİ EKLENEN KISIM: ZİNCİRİN İÇİNE ALDIK --}}
        @elseif($departmentSlug === 'ulastirma')
            {{-- ULAŞTIRMA DEPARTMANI --}}
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
            {{-- BAKIM --}}
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
            {{-- HİÇBİRİ DEĞİLSE BURASI ÇALIŞIR --}}
            <div class="modern-alert modern-alert-info">
                <div class="d-flex align-items-center"><i class="fa-solid fa-info-circle me-3"
                        style="font-size: 1.5rem;"></i>
                    <div><strong>Veri Bulunamadı</strong>
                        <p class="mb-0 mt-1">Bu departman için henüz istatistik verisi bulunmamaktadır.</p>
                    </div>
                </div>
            </div>
        @endif

    </div> {{-- End Dashboard Wrapper --}}
@endsection

@section('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Yükleme Ekranı ve Form Gönderimi
            const loadingOverlay = document.getElementById('loadingOverlay');
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

            function submitForm(formId) {
                const form = document.getElementById(formId);
                if (form) {
                    if (loadingOverlay) loadingOverlay.classList.add('active');
                    form.submit();
                }
            }

            forms.forEach(item => {
                const formEl = document.getElementById(item.id);
                if (formEl) {
                    const dateFromEl = document.getElementById(item.from);
                    const dateToEl = document.getElementById(item.to);
                    const selectEl = item.select ? document.getElementById(item.select) : null;
                    if (selectEl) selectEl.addEventListener('change', () => submitForm(item.id));
                    if (dateFromEl) dateFromEl.addEventListener('change', function() {
                        if (dateToEl && dateToEl.value) submitForm(item.id);
                    });
                    if (dateToEl) dateToEl.addEventListener('change', function() {
                        if (dateFromEl && dateFromEl.value) submitForm(item.id);
                    });
                }
            });

            // 2. Grafik Ayarları ve Veri Okuma
            const colorPalette = ['#667EEA', '#764BA2', '#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];
            const statsContainer = document.getElementById('stats-data-container');
            if (!statsContainer) return;

            let chartData = {},
                departmentSlug = '';
            try {
                chartData = JSON.parse(statsContainer.dataset.chartData || '{}');
                departmentSlug = statsContainer.dataset.departmentSlug || '';
            } catch (e) {
                return;
            }

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

            // 3. Departman Bazlı Grafik Çizimi

            // --- GENEL ---
            if (departmentSlug === 'genel') {
                if (chartData.departmentSummary) safeRender("#department-summary-chart", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Aktivite',
                        data: chartData.departmentSummary.data || []
                    }],
                    xaxis: {
                        categories: chartData.departmentSummary.labels || []
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.departmentSummary.title
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '60%',
                            distributed: true,
                            borderRadius: 8
                        }
                    },
                    dataLabels: {
                        enabled: true
                    }
                });

                // --- LOJİSTİK ---
            } else if (departmentSlug === 'lojistik') {
                const allShipments = JSON.parse(statsContainer.dataset.shipments || '[]');
                let vChart, cChart;
                if (document.querySelector("#vehicle-type-chart")) {
                    vChart = new ApexCharts(document.querySelector("#vehicle-type-chart"), {
                        ...commonBarOptions,
                        series: [{
                            name: 'Kullanım',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        }
                    });
                    vChart.render();
                }
                if (document.querySelector("#cargo-content-chart")) {
                    cChart = new ApexCharts(document.querySelector("#cargo-content-chart"), {
                        ...commonBarOptions,
                        series: [{
                            name: 'Kargo',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        }
                    });
                    cChart.render();
                }

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
                        ...commonAreaOptions.title,
                        text: chartData.monthly.title
                    }
                });
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
                        ...commonBarOptions.title,
                        text: chartData.hourly.title
                    },
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
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
                        ...commonBarOptions.title,
                        text: chartData.daily.title
                    },
                    chart: {
                        ...commonBarOptions.chart,
                        height: 300
                    }
                });
                if (chartData.yearly) safeRender("#yearly-chart-lojistik", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Sevkiyat',
                        data: chartData.yearly.data
                    }],
                    xaxis: {
                        categories: chartData.yearly.labels
                    },
                    title: {
                        ...commonBarOptions.title,
                        text: chartData.yearly.title
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            distributed: true
                        }
                    },
                    chart: {
                        ...commonBarOptions.chart,
                        height: 350
                    }
                });

                const typeDd = document.getElementById('shipmentTypeFilter'),
                    vehDd = document.getElementById('vehicleTypeFilter'),
                    cargoDd = document.getElementById('cargoContentFilter');

                function popLojistik() {
                    if (!allShipments) return;
                    new Set(allShipments.map(s => s.shipment_type)).forEach(t => {
                        if (t) typeDd.innerHTML +=
                            `<option value="${t}">${t==='import'?'İthalat':'İhracat'}</option>`;
                    });
                    new Set(allShipments.map(s => s.vehicle)).forEach(v => {
                        if (v) vehDd.innerHTML += `<option value="${v}">${v}</option>`;
                    });
                    new Set(allShipments.map(s => s.cargo)).forEach(c => {
                        if (c) cargoDd.innerHTML += `<option value="${c}">${c}</option>`;
                    });
                }

                function updLojistik() {
                    if (!vChart || !cChart) return;
                    let d = allShipments,
                        t = typeDd.value,
                        v = vehDd.value,
                        c = cargoDd.value;
                    if (t !== 'all') d = d.filter(s => s.shipment_type === t);
                    if (v !== 'all') d = d.filter(s => s.vehicle === v);
                    if (c !== 'all') d = d.filter(s => s.cargo === c);
                    let vc = {},
                        cc = {};
                    d.forEach(s => {
                        if (s.vehicle) vc[s.vehicle] = (vc[s.vehicle] || 0) + 1;
                        if (s.cargo) cc[s.cargo] = (cc[s.cargo] || 0) + 1;
                    });
                    let sv = Object.entries(vc).sort((a, b) => b[1] - a[1]),
                        sc = Object.entries(cc).sort((a, b) => b[1] - a[1]);
                    vChart.updateOptions({
                        xaxis: {
                            categories: sv.map(x => x[0])
                        }
                    });
                    vChart.updateSeries([{
                        data: sv.map(x => x[1])
                    }]);
                    cChart.updateOptions({
                        xaxis: {
                            categories: sc.map(x => x[0])
                        }
                    });
                    cChart.updateSeries([{
                        data: sc.map(x => x[1])
                    }]);
                }
                if (typeDd) {
                    typeDd.addEventListener('change', updLojistik);
                    vehDd.addEventListener('change', updLojistik);
                    cargoDd.addEventListener('change', updLojistik);
                    popLojistik();
                    updLojistik();
                }

                // --- ÜRETİM ---
            } else if (departmentSlug === 'uretim') {
                const allPlans = JSON.parse(statsContainer.dataset.productionPlans || '[]');
                let mChart, pChart;
                if (document.querySelector("#machine-chart-uretim")) {
                    mChart = new ApexCharts(document.querySelector("#machine-chart-uretim"), {
                        ...commonBarOptions,
                        series: [{
                            name: 'Adet',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        }
                    });
                    mChart.render();
                }
                if (document.querySelector("#product-chart-uretim")) {
                    pChart = new ApexCharts(document.querySelector("#product-chart-uretim"), {
                        ...commonBarOptions,
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true
                            }
                        },
                        series: [{
                            name: 'Adet',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        }
                    });
                    pChart.render();
                }
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
                        ...commonAreaOptions.title,
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
                        ...commonAreaOptions.title,
                        text: chartData.monthly_prod.title
                    }
                });
                const mDd = document.getElementById('machineFilter'),
                    pDd = document.getElementById('productFilter');

                function popUretim() {
                    new Set(allPlans.map(p => p.machine)).forEach(m => {
                        if (m) mDd.innerHTML += `<option value="${m}">${m}</option>`
                    });
                    new Set(allPlans.map(p => p.product)).forEach(p => {
                        if (p) pDd.innerHTML += `<option value="${p}">${p}</option>`
                    });
                }

                function updUretim() {
                    if (!mChart || !pChart) return;
                    let d = allPlans,
                        m = mDd.value,
                        p = pDd.value;
                    if (m !== 'all') d = d.filter(x => x.machine === m);
                    if (p !== 'all') d = d.filter(x => x.product === p);
                    let mc = {},
                        pc = {};
                    d.forEach(x => {
                        if (x.machine) mc[x.machine] = (mc[x.machine] || 0) + 1;
                        if (x.product) pc[x.product] = (pc[x.product] || 0) + x.quantity;
                    });
                    let sm = Object.entries(mc).sort((a, b) => b[1] - a[1]),
                        sp = Object.entries(pc).sort((a, b) => b[1] - a[1]).slice(0, 15);
                    mChart.updateOptions({
                        xaxis: {
                            categories: sm.map(x => x[0])
                        }
                    });
                    mChart.updateSeries([{
                        data: sm.map(x => x[1])
                    }]);
                    pChart.updateOptions({
                        xaxis: {
                            categories: sp.map(x => x[0])
                        }
                    });
                    pChart.updateSeries([{
                        data: sp.map(x => x[1])
                    }]);
                }
                if (mDd) {
                    mDd.addEventListener('change', updUretim);
                    pDd.addEventListener('change', updUretim);
                    popUretim();
                    updUretim();
                }

                // --- HİZMET ---
            } else if (departmentSlug === 'hizmet') {
                const events = JSON.parse(statsContainer.dataset.events || '[]');
                let eChart;
                if (document.querySelector("#event-type-pie-chart")) {
                    eChart = new ApexCharts(document.querySelector("#event-type-pie-chart"), {
                        ...commonPieOptions,
                        series: [],
                        labels: []
                    });
                    eChart.render();
                }
                const eDd = document.getElementById('eventTypeFilter');

                function popHizmet() {
                    let types = new Map();
                    events.forEach(e => {
                        if (e.type_name) types.set(e.type_slug, e.type_name)
                    });
                    types.forEach((n, s) => eDd.innerHTML += `<option value="${s}">${n}</option>`);
                }

                function updHizmet() {
                    let de = events,
                        et = eDd.value;
                    if (et !== 'all') de = de.filter(e => e.type_slug === et);
                    let ec = {};
                    de.forEach(e => {
                        ec[e.type_name] = (ec[e.type_name] || 0) + 1
                    });
                    let se = Object.entries(ec).sort((a, b) => b[1] - a[1]);
                    if (eChart) eChart.updateOptions({
                        labels: se.map(x => x[0]),
                        series: se.map(x => x[1])
                    });
                }
                if (eDd) {
                    eDd.addEventListener('change', updHizmet);
                    popHizmet();
                    updHizmet();
                }

                // --- ULAŞTIRMA (Burası Eksikti!) ---
            } else if (departmentSlug === 'ulastirma') {
                const allAssignments = JSON.parse(statsContainer.dataset.assignments || '[]');
                const allVehicles = JSON.parse(statsContainer.dataset.vehicles || '[]');

                let sChart, vChart;

                // A. Durum Grafiği (Pie)
                if (document.querySelector("#status-pie-chart")) {
                    sChart = new ApexCharts(document.querySelector("#status-pie-chart"), {
                        ...commonPieOptions,
                        series: chartData.status_pie?.data || [],
                        labels: chartData.status_pie?.labels || [],
                        colors: ['#F6E05E', '#48BB78', '#3182CE', '#805AD5', '#E53E3E']
                    });
                    sChart.render();
                }

                // B. En Çok Kullanılan Araçlar (Bar)
                if (document.querySelector("#top-vehicles-chart") && chartData.top_vehicles) {
                    vChart = new ApexCharts(document.querySelector("#top-vehicles-chart"), {
                        ...commonBarOptions,
                        series: [{
                            name: 'Görev Sayısı',
                            data: chartData.top_vehicles.data
                        }],
                        xaxis: {
                            categories: chartData.top_vehicles.labels
                        },
                        colors: ['#3182CE']
                    });
                    vChart.render();
                }

                // C. Aylık Trend (Area)
                if (document.querySelector("#monthly-trend-chart") && chartData.monthly_trend) {
                    safeRender("#monthly-trend-chart", {
                        ...commonAreaOptions,
                        series: [{
                            name: 'Toplam Görev',
                            data: chartData.monthly_trend.data
                        }],
                        xaxis: {
                            categories: chartData.monthly_trend.labels
                        },
                        colors: ['#805AD5']
                    });
                }

                // Client-Side Filtreleme (Dropdownlar)
                const vDd = document.getElementById('vehicleFilter');
                const sDd = document.getElementById('statusFilter');

                function popUlastirma() {
                    if (allVehicles.length > 0) {
                        allVehicles.forEach(v => {
                            if (v.plate_number) vDd.innerHTML +=
                                `<option value="${v.plate_number}">${v.plate_number}</option>`;
                        });
                    } else {
                        let uniquePlates = [...new Set(allAssignments.map(a => a.vehicle_plate))];
                        uniquePlates.sort().forEach(p => {
                            if (p) vDd.innerHTML += `<option value="${p}">${p}</option>`;
                        });
                    }
                }

                function updUlastirma() {
                    let d = allAssignments;
                    const selV = vDd.value;
                    const selS = sDd.value;

                    if (selV !== 'all') d = d.filter(a => a.vehicle_plate === selV);
                    if (selS !== 'all') d = d.filter(a => a.status === selS);

                    if (sChart) {
                        let statusCounts = {};
                        d.forEach(a => {
                            let sn = a.status;
                            if (sn === 'pending') sn = 'Bekleyen';
                            else if (sn === 'approved') sn = 'Onaylı';
                            else if (sn === 'in_progress') sn = 'Sürüyor';
                            else if (sn === 'completed') sn = 'Tamamlandı';
                            else if (sn === 'cancelled') sn = 'İptal';
                            statusCounts[sn] = (statusCounts[sn] || 0) + 1;
                        });
                        let sortedStatus = Object.entries(statusCounts).sort((a, b) => b[1] - a[1]);
                        sChart.updateOptions({
                            labels: sortedStatus.map(x => x[0]),
                            series: sortedStatus.map(x => x[1])
                        });
                    }

                    if (vChart) {
                        let vc = {};
                        d.forEach(a => {
                            let p = a.vehicle_plate || 'Bilinmiyor';
                            vc[p] = (vc[p] || 0) + 1;
                        });
                        let sortedVehicles = Object.entries(vc).sort((a, b) => b[1] - a[1]).slice(0, 5);
                        vChart.updateOptions({
                            xaxis: {
                                categories: sortedVehicles.map(x => x[0])
                            }
                        });
                        vChart.updateSeries([{
                            data: sortedVehicles.map(x => x[1])
                        }]);
                    }
                }

                if (vDd) {
                    vDd.addEventListener('change', updUlastirma);
                    sDd.addEventListener('change', updUlastirma);
                    popUlastirma();
                    // İlk yüklemede filtreyi tetiklemiyoruz, zaten server verisiyle çizildi.
                }

                // --- BAKIM ---
            } else if (departmentSlug === 'bakim') {
                const maint = JSON.parse(statsContainer.dataset.maintenancePlans || '[]');
                let tChart;
                if (document.querySelector("#maintenance-type-chart")) {
                    tChart = new ApexCharts(document.querySelector("#maintenance-type-chart"), {
                        ...commonPieOptions,
                        series: chartData.type_dist?.data || [],
                        labels: chartData.type_dist?.labels || []
                    });
                    tChart.render();
                }
                if (chartData.top_assets) safeRender("#top-assets-chart", {
                    ...commonBarOptions,
                    series: [{
                        name: 'Bakım',
                        data: chartData.top_assets.data
                    }],
                    xaxis: {
                        categories: chartData.top_assets.labels
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
                    colors: ['#ED8936']
                });
                const tDd = document.getElementById('maintenanceTypeFilter'),
                    aDd = document.getElementById('assetFilter'),
                    sDd = document.getElementById('statusFilter');

                function updBakim() {
                    if (!tChart) return;
                    let d = maint,
                        t = tDd.value,
                        a = aDd.value,
                        s = sDd.value;
                    if (t !== 'all') d = d.filter(x => x.type_id == t);
                    if (a !== 'all') d = d.filter(x => x.asset_id == a);
                    if (s !== 'all') d = d.filter(x => x.status === s);
                    let tc = {};
                    const types = JSON.parse(statsContainer.dataset.maintenanceTypes || '[]');
                    d.forEach(x => {
                        let tn = types.find(type => type.id == x.type_id)?.name || 'Bilinmiyor';
                        tc[tn] = (tc[tn] || 0) + 1;
                    });
                    tChart.updateOptions({
                        labels: Object.keys(tc),
                        series: Object.values(tc)
                    });
                }
                if (tDd) {
                    const types = JSON.parse(statsContainer.dataset.maintenanceTypes || '[]');
                    const assets = JSON.parse(statsContainer.dataset.assets || '[]');
                    types.forEach(t => tDd.innerHTML += `<option value="${t.id}">${t.name}</option>`);
                    assets.forEach(a => aDd.innerHTML += `<option value="${a.id}">${a.name}</option>`);
                    tDd.addEventListener('change', updBakim);
                    aDd.addEventListener('change', updBakim);
                    sDd.addEventListener('change', updBakim);
                    updBakim();
                }
            }
        });
    </script>
@endsection
