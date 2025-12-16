@extends('layouts.app')
@section('title', 'Hoş Geldiniz')

@push('styles')
    <style>
        /* --- GENEL BACKGROUND --- */
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

        /* --- KART TASARIMLARI (Create Shipment Card Style - SENİN ORİJİNAL YAPIN) --- */
        .create-shipment-card {
            border-radius: 1rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.6);
            background-color: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15) !important;
        }

        .create-shipment-card .card-body {
            padding: 1.5rem;
        }

        /* --- KPI KARTLARI (OPERASYON ÖZETİ) --- */
        .kpi-card {
            background-color: white;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            border-color: #cbd5e0;
        }

        .kpi-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .kpi-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            opacity: 0.8;
            transition: transform 0.3s;
        }

        .kpi-card:hover .kpi-icon {
            transform: scale(1.1);
            opacity: 1;
        }

        .kpi-value {
            font-size: 2.25rem;
            font-weight: 800;
            color: #2d3748;
            line-height: 1.2;
            margin-bottom: 0.25rem;
        }

        .kpi-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* KPI RENKLERİ */
        /* Hizmet / İdari İşler */
        .kpi-hizmet::after {
            background: linear-gradient(90deg, #F093FB, #F5576C);
        }

        .kpi-hizmet .kpi-icon {
            color: #F5576C;
        }

        .kpi-hizmet-seyahat::after {
            background: #4facfe;
        }

        .kpi-hizmet-seyahat .kpi-icon {
            color: #4facfe;
        }

        /* Diğerleri */
        .kpi-lojistik::after {
            background: #667EEA;
        }

        .kpi-lojistik .kpi-icon {
            color: #667EEA;
        }

        .kpi-uretim::after {
            background: #4FD1C5;
        }

        .kpi-uretim .kpi-icon {
            color: #4FD1C5;
        }

        .kpi-ulastirma::after {
            background: #3182CE;
        }

        .kpi-ulastirma .kpi-icon {
            color: #3182CE;
        }

        /* --- ÖNEMLİ BİLDİRİM --- */
        .event-important-pulse-welcome {
            border-left: 4px solid #ff4136;
            background: rgba(255, 255, 255, 0.6);
            margin-bottom: 0.5rem;
            transition: background 0.2s;
        }

        .event-important-pulse-welcome:hover {
            background: white;
        }

        /* --- GRAFİK --- */
        .sankey-container-wrapper {
            overflow-x: auto;
            padding-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    {{-- Hızlı Seçim Modalı (Backend aynı kalıyor) --}}
    <div class="modal fade" id="createSelectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); border:none; border-radius: 1rem;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Ne Oluşturmak İstersiniz?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-4">
                    @php
                        $currentUser = Auth::user();
                        $userDept = $currentUser->department ? $currentUser->department->slug : null;
                        $isAdmin = in_array($currentUser->role, ['admin', 'yönetici']);
                    @endphp

                    <div class="d-grid gap-3">
                        @if (Route::has('production.plans.create') && ($isAdmin || $userDept === 'uretim'))
                            <a href="{{ route('production.plans.create') }}"
                                class="btn btn-lg btn-outline-success d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-industry me-2"></i> Yeni Üretim Planı</span> <i
                                    class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                        @if (Route::has('shipments.create') && ($isAdmin || $userDept === 'lojistik'))
                            <a href="{{ route('shipments.create') }}"
                                class="btn btn-lg btn-outline-primary d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-truck-fast me-2"></i> Yeni Sevkiyat</span> <i
                                    class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                        {{-- Etkinlik Butonu --}}
                        @php
                            $eventRoute = Route::has('service.events.create') ? route('service.events.create') : '#';
                        @endphp
                        @if ($eventRoute !== '#' && ($isAdmin || $userDept === 'hizmet'))
                            <a href="{{ $eventRoute }}"
                                class="btn btn-lg btn-outline-warning d-flex align-items-center justify-content-between p-3"
                                style="border-color: #F5576C; color: #F5576C; background: rgba(245, 87, 108, 0.05);">
                                <span><i class="fa-solid fa-calendar-plus me-2"></i> Yeni Etkinlik / Ziyaret</span> <i
                                    class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                        @if (Route::has('service.assignments.create') && ($isAdmin || $userDept === 'ulastirma'))
                            <a href="{{ route('service.assignments.create') }}"
                                class="btn btn-lg btn-outline-info d-flex align-items-center justify-content-between p-3">
                                <span><i class="fa-solid fa-car-side me-2"></i> Yeni Araç Görevi</span> <i
                                    class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                        @if (Route::has('maintenance.create') && ($isAdmin || $userDept === 'bakim'))
                            <a href="{{ route('maintenance.create') }}"
                                class="btn btn-lg btn-outline-secondary d-flex align-items-center justify-content-between p-3"
                                style="border-color: #ED8936; color: #C05621; background-color: rgba(237, 137, 54, 0.05);">
                                <span><i class="fa-solid fa-screwdriver-wrench me-2"></i> Yeni Bakım Planı</span> <i
                                    class="fa-solid fa-chevron-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                {{-- ANA KART --}}
                <div class="card create-shipment-card mb-4 border-0">
                    <div class="card-body p-4">

                        {{-- ÜST BİLGİ --}}
                        <div class="row align-items-center mb-4">
                            <div class="col-md-8">
                                <h2 class="card-title mb-0 fw-bold" style="color: #1a202c;">
                                    Hoş Geldiniz, {{ Auth::user()->name }}!
                                </h2>
                                <p class="mb-0 text-muted mt-1">
                                    <span
                                        class="badge bg-dark bg-opacity-10 text-dark border">{{ ucfirst(Auth::user()->role) }}</span>
                                    @if (Auth::user()->department)
                                        <span class="text-muted ms-2"><i class="fa-solid fa-building me-1"></i>
                                            {{ Auth::user()->department->name }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- ORİJİNAL MENÜ KARTLARI (RESTORE EDİLDİ) --}}
                        <div class="row g-4 mb-2">
                            {{-- 1. Hızlı İşlem Menüsü --}}
                            <div class="col-md-4">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#createSelectionModal"
                                    class="text-decoration-none">
                                    <div class="card create-shipment-card h-100 hover-effect bg-white border">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                                                style="background: rgba(102, 126, 234, 0.15); width: 64px; height: 64px;">
                                                <i class="fa-solid fa-plus fa-xl" style="color: #667EEA;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">Hızlı İşlem Menüsü</h6>
                                                <small class="text-muted">Yeni kayıt oluştur...</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- 2. Detaylı Raporlar --}}
                            <div class="col-md-4">
                                <a href="{{ route('statistics.index') }}" class="text-decoration-none">
                                    <div class="card create-shipment-card h-100 hover-effect bg-white border">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                                                style="background: rgba(240, 147, 251, 0.15); width: 64px; height: 64px;">
                                                <i class="fa-solid fa-chart-pie fa-xl" style="color: #d53f8c;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">Detaylı Raporlar</h6>
                                                <small class="text-muted">Geçmiş verileri analiz et</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            {{-- 3. Takvim & Planlama --}}
                            <div class="col-md-4">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <div class="card create-shipment-card h-100 hover-effect bg-white border">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="rounded-circle p-3 me-3 d-flex align-items-center justify-content-center"
                                                style="background: rgba(79, 209, 197, 0.15); width: 64px; height: 64px;">
                                                <i class="fa-solid fa-calendar-check fa-xl" style="color: #319795;"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark fs-5">Takvim & Planlama</h6>
                                                <small class="text-muted">Haftalık planı görüntüle</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ÖNEMLİ BİLDİRİMLER (Varsa) --}}
                @if (isset($importantItems) && $importantItems->isNotEmpty())
                    <div class="card create-shipment-card mb-4 border-0">
                        <div class="card-header bg-white border-bottom pt-3 pb-2">
                            <h6 class="mb-0 text-danger fw-bold"><i class="fas fa-bell me-2"></i> Önemli Bildirimler</h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($importantItems as $item)
                                    @php
                                        $params = [];
                                        if ($item->date) {
                                            $params['date'] = $item->date->format('Y-m-d');
                                        }
                                        $params['open_modal_id'] = $item->model_id;
                                        $params['open_modal_type'] = $item->model_type;
                                        $url = route('general.calendar', $params);
                                        $isOverdue = $item->is_overdue ?? false;
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="list-group-item list-group-item-action event-important-pulse-welcome d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center overflow-hidden">
                                            <i
                                                class="fas {{ $isOverdue ? 'fa-exclamation-circle' : 'fa-exclamation-triangle' }} text-danger me-3"></i>
                                            <span class="fw-bold text-dark text-truncate">{{ $item->title }}</span>
                                        </div>
                                        @if ($item->date)
                                            <div class="text-end ms-3">
                                                <div class="fw-bold text-danger">{{ $item->date->format('H:i') }}</div>
                                                <small class="text-muted">{{ $item->date->format('d.m.Y') }}</small>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- TO-DO WIDGET --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 1rem; overflow: hidden;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-check-square me-2"></i> Yapılacaklar</h6>
                        <button class="btn btn-sm btn-light text-primary rounded-circle" data-bs-toggle="modal"
                            data-bs-target="#addTodoModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @php
                            $myTodos = \App\Models\Todo::where('user_id', auth()->id())
                                ->where('is_completed', false)
                                ->orderBy('priority', 'desc') // High -> Low
                                ->orderBy('due_date', 'asc')
                                ->take(5)
                                ->get();
                        @endphp

                        <ul class="list-group list-group-flush" id="todo-list-widget">
                            @forelse($myTodos as $todo)
                                <li class="list-group-item d-flex align-items-center justify-content-between px-4 py-3"
                                    id="todo-item-{{ $todo->id }}">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3 todo-checkbox" type="checkbox"
                                            value="{{ $todo->id }}"
                                            style="width: 1.2em; height: 1.2em; cursor: pointer;">
                                        <div>
                                            <div
                                                class="fw-semibold text-dark {{ $todo->priority == 'high' ? 'text-danger' : '' }}">
                                                {{ $todo->title }}
                                            </div>
                                            @if ($todo->due_date)
                                                <small class="text-muted" style="font-size: 0.75rem">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $todo->due_date->format('d.m H:i') }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted py-4">
                                    <small>Harika! Yapılacak işin yok.</small>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                {{-- TO-DO EKLEME MODALI --}}
                <div class="modal fade" id="addTodoModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                            <form id="addTodoForm">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold">Yeni Görev Ekle</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <input type="text" name="title" class="form-control form-control-lg"
                                            placeholder="Ne yapılması gerekiyor?" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 mb-3">
                                            <label class="small text-muted fw-bold">Son Tarih</label>
                                            <input type="datetime-local" name="due_date" class="form-control">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label class="small text-muted fw-bold">Öncelik</label>
                                            <select name="priority" class="form-select">
                                                <option value="medium">Orta</option>
                                                <option value="high">Yüksek 🔥</option>
                                                <option value="low">Düşük</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <textarea name="description" class="form-control" rows="2" placeholder="Detaylar (Opsiyonel)"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">İptal</button>
                                    <button type="submit" class="btn btn-primary px-4">Ekle</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KPI KARTLARI (OPERASYON ÖZETİ) --}}
                @if (isset($kpiData) && !empty($kpiData))
                    <div class="mb-4">
                        <h5 class="fw-bold text-dark mb-3 ps-1 opacity-75">
                            <i class="fa-solid fa-chart-line me-2 text-primary"></i>Günlük Operasyon Özeti
                        </h5>
                        <div class="row g-4">
                            @php
                                $showAll = (isset($isAdmin) && $isAdmin) || empty($userDept);
                            @endphp

                            {{-- HİZMET / İDARİ İŞLER ÖZEL KARTLARI --}}
                            @if ($showAll || $userDept === 'hizmet')
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-hizmet">
                                        <div class="kpi-icon"><i class="fa-solid fa-calendar-days"></i></div>
                                        <div class="kpi-value">{{ $kpiData['etkinlik_sayisi'] ?? 0 }}</div>
                                        <div class="kpi-label">Aktif Etkinlik</div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-hizmet-seyahat">
                                        <div class="kpi-icon"><i class="fa-solid fa-plane-departure"></i></div>
                                        <div class="kpi-value">{{ $kpiData['rezervasyon_sayisi'] ?? 0 }}</div>
                                        <div class="kpi-label">Seyahat Planı</div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-hizmet">
                                        <div class="kpi-icon"><i class="fa-solid fa-briefcase"></i></div>
                                        <div class="kpi-value">{{ $kpiData['musteri_ziyareti'] ?? 0 }}</div>
                                        <div class="kpi-label">Müşteri Ziyareti</div>
                                    </div>
                                </div>
                            @endif

                            {{-- ULAŞTIRMA KARTLARI --}}
                            @if ($userDept === 'ulastirma')
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-ulastirma">
                                        <div class="kpi-icon"><i class="fa-solid fa-road"></i></div>
                                        <div class="kpi-value">{{ $kpiData['aktif_gorev'] ?? 0 }}</div>
                                        <div class="kpi-label">Yoldaki Araçlar</div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-ulastirma">
                                        <div class="kpi-icon"><i class="fa-solid fa-clock"></i></div>
                                        <div class="kpi-value">{{ $kpiData['bekleyen_talep'] ?? 0 }}</div>
                                        <div class="kpi-label">Bekleyen Talep</div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-ulastirma">
                                        <div class="kpi-icon"><i class="fa-solid fa-car"></i></div>
                                        <div class="kpi-value">{{ $kpiData['toplam_arac'] ?? 0 }}</div>
                                        <div class="kpi-label">Araç Filosu</div>
                                    </div>
                                </div>
                            @endif

                            {{-- GENEL / ADMİN KARTLARI --}}
                            @if ($showAll && $userDept !== 'hizmet')
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-lojistik">
                                        <div class="kpi-icon"><i class="fa-solid fa-truck-fast"></i></div>
                                        <div class="kpi-value">{{ $kpiData['sevkiyat_sayisi'] ?? 0 }}</div>
                                        <div class="kpi-label">Sevkiyat</div>
                                    </div>
                                </div>
                                <div class="col-lg col-md-4 col-6">
                                    <div class="kpi-card kpi-uretim">
                                        <div class="kpi-icon"><i class="fa-solid fa-industry"></i></div>
                                        <div class="kpi-value">{{ $kpiData['plan_sayisi'] ?? 0 }}</div>
                                        <div class="kpi-label">Üretim Planı</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- GRAFİK KARTI (SANKEY) --}}
                @if (isset($chartData) && !empty($chartData))
                    <div class="card create-shipment-card border-0 mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold text-dark">
                                @if ($userDept === 'hizmet')
                                    <i class="fa-solid fa-route me-2 text-warning"></i> Etkinlik & Seyahat Akışı
                                @else
                                    <i class="fa-solid fa-chart-line me-2 text-primary"></i>
                                    {{ $chartTitle ?? 'Veri Akışı' }}
                                @endif
                            </h6>
                        </div>
                        <div class="card-body bg-white rounded-bottom">
                            <div class="sankey-container-wrapper">
                                <div id="sankey-chart" data-sankey='@json($chartData)'
                                    style="width: 100%; height: 500px;">
                                    <p class="text-center text-muted p-5">Grafik yükleniyor...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const sankeyChartEl = document.getElementById('sankey-chart');
            if (sankeyChartEl) {
                let sankeyData = [];
                try {
                    const dataAttr = sankeyChartEl.dataset.sankey;
                    if (dataAttr) sankeyData = JSON.parse(dataAttr);
                } catch (error) {
                    console.error('JSON hatası:', error);
                }

                if (sankeyData.length > 0) {
                    google.charts.load('current', {
                        'packages': ['sankey']
                    });
                    google.charts.setOnLoadCallback(() => drawChart(sankeyData, sankeyChartEl));
                }
            }

            function drawChart(sankeyData, chartElement) {
                if (sankeyData.length === 1 && (sankeyData[0][0] === 'Veri Yok' || sankeyData[0][0] === 'Sistem')) {
                    chartElement.innerHTML =
                        '<div class="d-flex flex-column align-items-center justify-content-center p-5 opacity-50"><i class="fa-solid fa-chart-simple fa-3x mb-3"></i><p class="mb-0">Grafik için yeterli veri akışı bulunamadı.</p></div>';
                    return;
                }
                try {
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Kaynak');
                    data.addColumn('string', 'Hedef');
                    data.addColumn('number', 'Değer');
                    data.addColumn({
                        type: 'string',
                        role: 'tooltip'
                    });

                    // Hizmet için renkler
                    const colors = ['#F093FB', '#4FD1C5', '#667EEA', '#FBD38D', '#FC8181', '#A0AEC0'];

                    const options = {
                        width: '100%',
                        height: 500,
                        sankey: {
                            node: {
                                label: {
                                    fontName: 'Inter',
                                    fontSize: 13,
                                    color: '#2d3748',
                                    bold: true
                                },
                                interactivity: true,
                                nodePadding: 30,
                                width: 10,
                                colors: colors
                            },
                            link: {
                                colorMode: 'gradient',
                                colors: colors
                            }
                        }
                    };

                    const initialData = sankeyData.map(row => [row[0], row[1], row[2],
                        `${row[0]} -> ${row[1]}\nSayı: ${row[2]}`
                    ]);
                    data.addRows(initialData);
                    const chart = new google.visualization.Sankey(chartElement);
                    chart.draw(data, options);

                    window.addEventListener('resize', function() {
                        chart.draw(data, options);
                    });

                } catch (error) {
                    chartElement.innerHTML = '<p class="text-center text-danger">Grafik çizilemedi.</p>';
                }
            }
        });
        // To-Do Ekleme
        document.getElementById('addTodoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);

            fetch("{{ route('todos.store') }}", {
                    method: "POST",
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Sayfayı yenilemeden listeye eklemek JS ile yapılabilir ama
                        // Şimdilik en temizi sayfayı yenilemek.
                        window.location.reload();
                    }
                });
        });

        // To-Do Tamamlama (Checkbox)
        document.querySelectorAll('.todo-checkbox').forEach(chk => {
            chk.addEventListener('change', function() {
                let id = this.value;
                let item = document.getElementById('todo-item-' + id);

                // Görsel efekt (Üstünü çiz ve kaybet)
                item.style.opacity = '0.5';
                item.style.textDecoration = 'line-through';

                fetch(`/todos/${id}/toggle`, {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(() => {
                        setTimeout(() => item.remove(), 500); // Listeden sil
                    });
            });
        });
    </script>
@endsection
