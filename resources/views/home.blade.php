@extends('layouts.app')

@section('title', 'Benim Takvimim')

@push('styles')
    <style>
        /* === 1. SAYFA VE LAYOUT (ORİJİNAL TASARIM) === */
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

        .wide-container {
            max-width: 95% !important;
            margin-left: auto;
            margin-right: auto;
        }

        /* === 2. KART TASARIMLARI === */
        .create-shipment-card {
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .create-shipment-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .create-shipment-card .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            font-weight: 700;
            font-size: 1.1rem;
            color: #2d3748;
            padding: 1.25rem 1.5rem;
            border-radius: 1.25rem 1.25rem 0 0;
        }

        .create-shipment-card .card-body {
            padding: 1.5rem;
        }

        /* === 3. FULLCALENDAR İYİLEŞTİRMELERİ === */
        #calendar {
            background: rgba(255, 255, 255, 0.4);
            border-radius: 1rem;
            padding: 10px;
        }

        .fc .fc-daygrid-day-frame {
            min-height: 160px !important;
            transition: background-color 0.2s;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(102, 126, 234, 0.08) !important;
        }

        /* === TAKVİM YAZI BOYUTLARI === */
        .fc .fc-col-header-cell-cushion {
            /* Gün isimlerini (Pzt, Sal) büyütelim */
            font-size: 1.1rem;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .fc-daygrid-day-number {
            /* Ayın gün numaralarını büyütelim */
            font-size: 1.1rem;
            font-weight: bold;
            color: #4a5568;
            padding: 8px !important;
        }

        .fc-event {
            border: none !important;
            margin: 1px 2px !important;
            padding: 4px 8px;
            font-size: 0.9rem !important;
            margin-bottom: 2px !important;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .fc-event:hover {
            transform: scale(1.03);
            z-index: 50;
        }

        .fc-event-main {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block !important;
        }

        .fc-daygrid-more-link {
            color: #667EEA !important;
            font-weight: 700;
            font-size: 0.75rem;
            text-decoration: none;
        }

        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
            font-weight: 600;
        }

        /* === 4. BUTON VE ALERT STİLLERİ === */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 700;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC);
            color: white !important;
            border: none;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #FC8181, #F56565);
            color: white;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 1rem;
            border: none;
            backdrop-filter: blur(10px);
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.15);
            color: #2f855a;
            border-left: 4px solid #48bb78;
        }

        .alert-danger {
            background: rgba(245, 101, 101, 0.15);
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        /* === 5. MODAL STİLLERİ === */
        .modal-backdrop.show {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
        }

        #detailModal .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
            background: rgba(255, 255, 255, 0.98);
        }

        #detailModal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 2.5rem;
            position: relative;
            overflow: hidden;
        }

        #detailModal .modal-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center / cover no-repeat;
            opacity: 0.3;
            pointer-events: none;
        }

        #detailModal .btn-close {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
            opacity: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #detailModal .btn-close:hover {
            background-color: rgba(255, 255, 255, 0.4);
            transform: rotate(90deg);
        }

        .modal-info-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.15);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .modal-info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        #modalEditButton {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 167, 38, 0.4);
        }

        #modalExportButton {
            background: linear-gradient(135deg, #4fd1c5, #38b2ac);
            color: white;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.4);
        }

        #modalOnayForm .btn-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.4);
        }

        #modalOnayKaldirForm .btn-warning {
            background: linear-gradient(135deg, #f6ad55, #ed8936);
            color: white;
            box-shadow: 0 4px 15px rgba(246, 173, 85, 0.4);
        }

        #modalDeleteForm .btn-danger {
            background: linear-gradient(135deg, #fc8181, #f56565);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 101, 101, 0.4);
        }

        .modal-loading {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 200px;
        }

        .modal-spinner {
            border: 4px solid rgba(102, 126, 234, 0.2);
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            animation: spin 1s linear infinite;
        }

        .fc-event-holiday {
            font-weight: 600;
            border: none !important;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            padding: 3px 6px;
            font-size: 11px;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .fc-event-holiday::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            animation: shine 3s infinite;
            pointer-events: none;
        }

        .fc-event-holiday:hover {
            transform: scale(1.05);
            z-index: 1000;
            white-space: normal;
            min-width: max-content;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.5);
            overflow: visible;
        }

        @keyframes shine {
            to {
                left: 100%;
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .event-important-pulse {
            border: 2px solid #ff4136 !important;
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(255, 65, 54, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 65, 54, 0);
            }
        }

        .custom-calendar-tooltip .tooltip-inner {
            background-color: #ffffff !important;
            color: #2d3748 !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 12px !important;
            padding: 12px 16px !important;
            font-size: 0.9rem;
            max-width: 300px;
            text-align: left;
            font-family: system-ui, -apple-system, sans-serif;
            z-index: 10000;
        }

        .custom-calendar-tooltip .tooltip-arrow::before {
            border-top-color: #ffffff !important;
            border-bottom-color: #ffffff !important;
            border-left-color: #ffffff !important;
            border-right-color: #ffffff !important;
        }

        .tooltip-title-styled {
            font-weight: 700;
            color: #667EEA;
            margin-bottom: 4px;
            display: block;
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 4px;
        }

        .tooltip-desc-styled {
            font-size: 0.8rem;
            color: #718096;
            line-height: 1.4;
        }

        /* === MOBİL İÇİN ÖZEL AYARLAR (RESPONSIVE KORUMA) === */
        @media (max-width: 768px) {
            .wide-container {
                max-width: 100% !important;
                padding: 0 10px;
            }

            .fc .fc-daygrid-day-frame {
                min-height: 80px !important;
            }

            .fc-event {
                font-size: 0.75rem !important;
                padding: 2px 4px !important;
            }

            .fc .fc-toolbar-title {
                font-size: 1.2rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid wide-container">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>✓</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>✗</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- DİNAMİK LAYOUT MANTIĞI --}}
        @php
            $hasStats = !empty($chartData);
        @endphp

        <div class="row justify-content-center">

            {{-- 
                TAKVİM SÜTUNU 
                Mantık: Eğer istatistik varsa ($hasStats true) -> col-md-9 (sola yaslı, yanına bir şey gelecek)
                Eğer istatistik yoksa ($hasStats false) -> col-md-12 (tam genişlik)
            --}}
            <div class="{{ $hasStats ? 'col-md-9' : 'col-md-12' }} transition-all">
                <div class="card create-shipment-card">
                    <div class="card-header">
                        📅 {{ $departmentName }} Takvimi
                    </div>
                    <div class="card-body">
                        @php
                            $canFilter =
                                Auth::user()->role === 'admin' ||
                                (Auth::user()->role === 'yönetici' && is_null(Auth::user()->department_id));
                        @endphp

                        @if ($canFilter)
                            {{-- FİLTRELEME KODLARIN BURADA AYNI KALACAK --}}
                            <div class="calendar-filters p-3 mb-3"
                                style="background: rgba(102, 126, 234, 0.05); border-radius: 0.75rem;">
                                <strong class="me-3"><i class="fa-solid fa-filter"></i> Filtrele:</strong>
                                {{-- ... (Filtre inputları buraya gelecek, senin kodunla aynı) ... --}}
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="lojistik" id="filterLojistik"
                                        checked>
                                    <label class="form-check-label" for="filterLojistik"
                                        style="color: #667EEA;">Lojistik</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="uretim" id="filterUretim"
                                        checked>
                                    <label class="form-check-label" for="filterUretim"
                                        style="color: #4FD1C5;">Üretim</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="hizmet" id="filterHizmet"
                                        checked>
                                    <label class="form-check-label" for="filterHizmet" style="color: #F093FB;">İdari
                                        İşler</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="bakim" id="filterBakim" checked>
                                    <label class="form-check-label" for="filterBakim" style="color: #ED8936;">Bakım</label>
                                </div>
                                <span class="mx-2">|</span>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="important" id="filterImportant">
                                    <label class="form-check-label" for="filterImportant"
                                        style="color: #dc3545;"><strong>Sadece Önemliler</strong></label>
                                </div>
                            </div>
                        @endif

                        <div id="calendar" data-events='@json($events)'
                            data-is-authorized="{{ in_array(mb_strtolower(Auth::user()->role), ['admin', 'yönetici', 'müdür']) ? 'true' : 'false' }}"
                            data-current-user-id="{{ Auth::id() }}" data-can-filter="{{ $canFilter ? 'true' : 'false' }}"
                            data-user-dept="{{ $departmentSlug ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- 
                İSTATİSTİK SÜTUNU
                Mantık: @if kontrolü ile sarmaladık. 
                Eğer veri yoksa bu div (col-md-3) HİÇ OLUŞTURULMAZ.
                Böylece sağ tarafta boşluk kalmaz.
            --}}
            @if ($hasStats)
                <div class="col-md-3">
                    {{-- İSTATİSTİK KARTI --}}
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            📊 {{ $statsTitle ?? 'İstatistikler' }}
                        </div>
                        <div class="card-body" id="stats-card-body">
                            {{-- SENİN İSTATİSTİK KODLARIN BURADA AYNEN DURACAK --}}
                            @if ($departmentSlug === 'lojistik')
                                <div id="chart-lojistik-1" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-lojistik-2" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-lojistik-3"></div>
                            @elseif($departmentSlug === 'uretim')
                                <div id="chart-uretim-1" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-uretim-2" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-uretim-3"></div>
                            @elseif($departmentSlug === 'hizmet' || $departmentSlug === 'idari-isler')
                                <div id="chart-hizmet-1" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-hizmet-2" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-hizmet-3"></div>
                            @elseif($departmentSlug === 'bakim')
                                <div id="chart-bakim-1" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-bakim-2" class="mb-3"></div>
                                <hr class="my-3">
                                <div id="chart-bakim-3"></div>
                            @endif

                            @if (Route::has('statistics.index'))
                                <hr>
                                <div class="text-center">
                                    <a href="{{ route('statistics.index') }}">Daha Fazla İstatistik Görüntüle</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL YAPISI --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"><i class="fas fa-info-circle"></i> <span>Detaylar</span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
                            class="fa-solid fa-xmark"></i></button>
                </div>

                <div class="modal-body">
                    <div id="modalOnayBadge" style="display: none;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <strong>✓ Onaylandı</strong>
                                <div class="small mt-1"><i class="fas fa-calendar-check me-1"></i> <span
                                        id="modalOnayBadgeTarih"></span></div>
                                <div class="small"><i class="fas fa-user me-1"></i> <span
                                        id="modalOnayBadgeKullanici"></span></div>
                            </div>
                            <i class="fas fa-check-circle fa-3x" style="opacity: 0.5;"></i>
                        </div>
                    </div>

                    <div id="modalImportantCheckboxContainer" class="d-flex align-items-center justify-content-between"
                        style="display: none;">
                        <label for="modalImportantCheckbox" class="form-check-label"><i
                                class="fas fa-exclamation-circle me-1"></i> Bu Veriyi Önemli Olarak İşaretle</label>
                        <input type="checkbox" id="modalImportantCheckbox" class="form-check-input">
                    </div>

                    <div id="modalDynamicBody">
                        <div class="modal-loading">
                            <div class="modal-spinner"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="#" id="modalEditButton" class="btn" style="display: none;"><i
                            class="fas fa-edit me-2"></i> Düzenle</a>
                    <a href="#" id="modalExportButton" class="btn" style="display: none;"><i
                            class="fas fa-file-excel me-2"></i> Excel İndir</a>

                    <form method="POST" id="modalOnayForm" style="display: none;" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i> Tesise
                            Ulaştı</button>
                    </form>

                    <form method="POST" id="modalOnayKaldirForm" style="display: none;" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-warning"><i class="fas fa-undo me-2"></i> Onayı
                            Kaldır</button>
                    </form>

                    <form method="POST" id="modalDeleteForm" style="display: none;" class="d-inline"
                        onsubmit="return confirm('Bu kaydı silmek istediğinizden emin misiniz?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-2"></i> Sil</button>
                    </form>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="fas fa-times me-2"></i> Kapat</button>
                </div>
            </div>
        </div>
    </div>
    {{-- @include('partials.calendar-modal') --}}
@endsection

@section('page_scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js'></script>
    {{-- GRAFİKLER İÇİN APEXCHARTS --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const dateFromUrl = urlParams.get('date');
            const urlModalId = urlParams.get('open_modal_id');
            const urlModalType = urlParams.get('open_modal_type');
            const currentUserRole = "{{ Auth::user()->role }}";
            const calendarEventsUrl = "{{ route('web.calendar.events') }}";
            const toggleImportantUrl = "{{ route('calendar.toggleImportant') }}";

            // === 1. PHP'DEN GELEN VERİYİ AL (JSON) ===
            // Eğer backend veri göndermediyse boş obje kabul et
            const chartData = @json($chartData ?? []);
            const deptSlug = "{{ $departmentSlug ?? '' }}";

            // Ortak Grafik Ayarları
            const commonChartOptions = {
                chart: {
                    background: 'transparent',
                    toolbar: {
                        show: false
                    },
                    fontFamily: 'inherit'
                },
                colors: ['#667EEA', '#764BA2', '#F093FB', '#4FD1C5', '#FBD38D', '#FF6B6B'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                }
            };

            function renderChart(id, options) {
                if (document.querySelector("#" + id)) {
                    var chart = new ApexCharts(document.querySelector("#" + id), options);
                    chart.render();
                }
            }

            // === 2. DİNAMİK GRAFİK ÇİZİMİ ===
            // Burada artık chartData değişkeni kullanılıyor.

            // --- LOJİSTİK ---
            if (deptSlug === 'lojistik' && chartData.lojistik) {
                // Grafik 1: Sefer Durumları
                if (chartData.lojistik.pie_series) {
                    renderChart('chart-lojistik-1', {
                        ...commonChartOptions,
                        series: chartData.lojistik.pie_series, // Örn: [10, 5, 2]
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        labels: chartData.lojistik.pie_labels || ['Tamamlanan', 'Yolda', 'Bekleyen'],
                        title: {
                            text: 'Sevkiyat Durumu',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }

                // Grafik 2: Günlük Sefer
                if (chartData.lojistik.bar_series) {
                    renderChart('chart-lojistik-2', {
                        ...commonChartOptions,
                        series: [{
                            name: 'Seferler',
                            data: chartData.lojistik.bar_series
                        }],
                        chart: {
                            type: 'bar',
                            height: 200
                        },
                        xaxis: {
                            categories: chartData.lojistik.bar_categories || ['Pzt', 'Sal', 'Çar', 'Per',
                                'Cum', 'Cmt', 'Paz'
                            ]
                        },
                        title: {
                            text: 'Günlük Sefer Sayısı',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }

                // Grafik 3: Doluluk
                if (chartData.lojistik.radial_series) {
                    renderChart('chart-lojistik-3', {
                        ...commonChartOptions,
                        series: chartData.lojistik.radial_series, // Örn: [75]
                        chart: {
                            type: 'radialBar',
                            height: 200
                        },
                        plotOptions: {
                            radialBar: {
                                dataLabels: {
                                    name: {
                                        show: true
                                    },
                                    value: {
                                        show: true
                                    }
                                }
                            }
                        },
                        labels: ['Verimlilik'],
                        title: {
                            text: 'Araç Doluluk Oranı',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
            }

            // --- ÜRETİM ---
            if (deptSlug === 'uretim' && chartData.uretim) {
                if (chartData.uretim.area_series) {
                    renderChart('chart-uretim-1', {
                        ...commonChartOptions,
                        series: chartData.uretim.area_series,
                        chart: {
                            type: 'area',
                            height: 220
                        },
                        title: {
                            text: 'Haftalık Üretim',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
                if (chartData.uretim.bar_series) {
                    renderChart('chart-uretim-2', {
                        ...commonChartOptions,
                        series: [{
                            data: chartData.uretim.bar_series
                        }],
                        chart: {
                            type: 'bar',
                            height: 200,
                            horizontal: true
                        },
                        xaxis: {
                            categories: chartData.uretim.bar_categories || []
                        },
                        title: {
                            text: 'Makine Verimliliği',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
                if (chartData.uretim.pie_series) {
                    renderChart('chart-uretim-3', {
                        ...commonChartOptions,
                        series: chartData.uretim.pie_series,
                        chart: {
                            type: 'pie',
                            height: 200
                        },
                        labels: ['Sağlam', 'Fire'],
                        colors: ['#4FD1C5', '#FF6B6B'],
                        title: {
                            text: 'Fire Oranı',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
            }

            // --- HİZMET / İDARİ ---
            if ((deptSlug === 'hizmet' || deptSlug === 'idari-isler') && chartData.hizmet) {
                if (chartData.hizmet.donut_series) {
                    renderChart('chart-hizmet-1', {
                        ...commonChartOptions,
                        series: chartData.hizmet.donut_series,
                        chart: {
                            type: 'donut',
                            height: 250
                        },
                        labels: chartData.hizmet.donut_labels || ['Tamamlanan', 'Devam Eden', 'Bekleyen'],
                        title: {
                            text: 'Görev Durumları',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
                if (chartData.hizmet.line_series) {
                    renderChart('chart-hizmet-2', {
                        ...commonChartOptions,
                        series: [{
                            name: 'Talepler',
                            data: chartData.hizmet.line_series
                        }],
                        chart: {
                            type: 'line',
                            height: 200
                        },
                        xaxis: {
                            categories: chartData.hizmet.line_categories || []
                        },
                        title: {
                            text: 'Departman Talepleri',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
                if (chartData.hizmet.polar_series) {
                    renderChart('chart-hizmet-3', {
                        ...commonChartOptions,
                        series: chartData.hizmet.polar_series,
                        chart: {
                            type: 'polarArea',
                            height: 220
                        },
                        labels: chartData.hizmet.polar_labels || [],
                        title: {
                            text: 'Gider Dağılımı',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
            }

            // --- BAKIM (YENİ EKLENEN) ---
            if (deptSlug === 'bakim' && chartData.bakim) {
                // 1. Bakım Türleri
                if (chartData.bakim.pie_series) {
                    renderChart('chart-bakim-1', {
                        ...commonChartOptions,
                        series: chartData.bakim.pie_series, // [Planlı Sayısı, Arıza Sayısı]
                        chart: {
                            type: 'pie',
                            height: 220
                        },
                        labels: chartData.bakim.pie_labels || ['Periyodik Bakım', 'Arıza Müdahale'],
                        colors: ['#48bb78', '#f56565'],
                        title: {
                            text: 'Bakım Türü Dağılımı',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }

                // 2. Müdahale Süreleri
                if (chartData.bakim.bar_series) {
                    renderChart('chart-bakim-2', {
                        ...commonChartOptions,
                        series: [{
                            name: 'Dakika',
                            data: chartData.bakim.bar_series
                        }],
                        chart: {
                            type: 'bar',
                            height: 200
                        },
                        xaxis: {
                            categories: chartData.bakim.bar_categories || []
                        },
                        title: {
                            text: 'Ort. Müdahale Süresi',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }

                // 3. Sağlık Skoru
                if (chartData.bakim.radial_series) {
                    renderChart('chart-bakim-3', {
                        ...commonChartOptions,
                        series: chartData.bakim.radial_series, // [85]
                        chart: {
                            type: 'radialBar',
                            height: 220
                        },
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '70%'
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        fontSize: '16px'
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '14px',
                                        formatter: function(val) {
                                            return val + "%";
                                        }
                                    }
                                }
                            }
                        },
                        colors: ['#ED8936'],
                        labels: ['Tesis Sağlık Skoru'],
                        title: {
                            text: 'Genel Durum',
                            align: 'left',
                            style: {
                                fontSize: '14px',
                                color: '#718096'
                            }
                        }
                    });
                }
            }

            function hardResetModalUI() {
                const ids = ['modalEditButton', 'modalExportButton', 'modalOnayForm', 'modalOnayKaldirForm',
                    'modalDeleteForm', 'modalOnayBadge', 'modalImportantCheckboxContainer'
                ];
                ids.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.style.display = 'none';
                        el.classList.remove('d-inline', 'd-block');
                    }
                });
                document.getElementById('modalTitle').innerHTML = '';
                document.getElementById('modalDynamicBody').innerHTML =
                    '<div class="modal-loading"><div class="modal-spinner"></div></div>';
                const exportBtn = document.getElementById('modalExportButton');
                if (exportBtn) exportBtn.innerHTML = '<i class="fas fa-file-excel me-2"></i> Excel İndir';
            }

            function openUniversalModal(props) {
                // 1. HATA AYIKLAMA: Konsola gelen veriyi basar (F12 -> Console'dan kontrol edebilirsiniz)
                console.log('Tıklanan Veri Paketi:', props);

                hardResetModalUI();

                // 2. Güvenlik Kontrolü
                if (!props || (!props.eventType && !props.model_type)) {
                    console.warn('Eksik veri: eventType veya model_type bulunamadı.');
                    return;
                }

                // Renkli Badge Tanımları (Durumlar için)
                const statusMap = {
                    'Critical': {
                        text: 'Kritik',
                        class: 'bg-danger text-white'
                    },
                    'High': {
                        text: 'Yüksek',
                        class: 'bg-warning text-dark'
                    },
                    'Medium': {
                        text: 'Orta',
                        class: 'bg-info text-white'
                    },
                    'Low': {
                        text: 'Düşük',
                        class: 'bg-success text-white'
                    },
                    'Normal': {
                        text: 'Normal',
                        class: 'bg-secondary text-white'
                    },
                    'Pending': {
                        text: 'Beklemede',
                        class: 'bg-warning text-dark'
                    },
                    'In_progress': {
                        text: 'İşlemde',
                        class: 'bg-primary text-white'
                    },
                    'Completed': {
                        text: 'Tamamlandı',
                        class: 'bg-success text-white'
                    },
                    'Cancelled': {
                        text: 'İptal',
                        class: 'bg-danger text-white'
                    },
                    'Active': {
                        text: 'Aktif',
                        class: 'bg-success text-white'
                    }
                };

                // Elementleri Seç
                const modalTitle = document.getElementById('modalTitle');
                const modalBody = document.getElementById('modalDynamicBody');
                const modalEditButton = document.getElementById('modalEditButton');
                const modalExportButton = document.getElementById('modalExportButton');
                const modalDeleteForm = document.getElementById('modalDeleteForm');
                const modalOnayForm = document.getElementById('modalOnayForm');
                const modalOnayKaldirForm = document.getElementById('modalOnayKaldirForm');
                const modalOnayBadge = document.getElementById('modalOnayBadge');
                const modalImportantContainer = document.getElementById('modalImportantCheckboxContainer');
                const modalImportantCheckbox = document.getElementById('modalImportantCheckbox');

                const calendarEl = document.getElementById('calendar');

                // Yetki Kontrolleri
                const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
                const currentUserDept = calendarEl.dataset.userDept;
                const canMarkImportant = calendarEl.dataset.canMarkImportant === 'true';
                const userRole = "{{ Auth::user()->role }}";

                const eventOwnerId = props.user_id;
                const eventDeptId = props.department_id;

                let canModify = false;
                if (userRole === 'admin') {
                    canModify = true;
                } else if (eventOwnerId && eventOwnerId === currentUserId) {
                    canModify = true;
                } else if (userRole === 'yönetici') {
                    if (!currentUserDept || (eventDeptId && String(currentUserDept) === String(eventDeptId))) {
                        canModify = true;
                    }
                }

                // "Önemli İşaretle" Checkbox Mantığı
                if (modalImportantContainer) {
                    const isVehicleTask = (props.model_type === 'vehicle_assignment');
                    let shouldShowCheckbox = false;
                    if (canMarkImportant) shouldShowCheckbox = true;
                    else if (isVehicleTask && canModify) shouldShowCheckbox = true;

                    if (shouldShowCheckbox) {
                        modalImportantContainer.style.display = 'flex';
                        modalImportantCheckbox.checked = props.is_important || false;
                        modalImportantCheckbox.disabled = false;
                        modalImportantCheckbox.dataset.modelType = props.model_type;
                        modalImportantCheckbox.dataset.modelId = props.id;
                    } else {
                        modalImportantContainer.style.setProperty('display', 'none', 'important');
                    }
                }

                // Başlık Ayarla
                modalTitle.innerHTML = `<span>${props.title || 'Detaylar'}</span>`;

                // Düzenle/Sil Butonları Göster/Gizle
                if (canModify && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                }
                if (canModify && props.deleteUrl && modalDeleteForm) {
                    modalDeleteForm.action = props.deleteUrl;
                    modalDeleteForm.style.display = 'inline-block';
                }

                // İkon ve Başlık Belirleme
                let html = '';
                let icon = 'fa-info-circle';
                let typeTitle = 'Detaylar';

                if (props.eventType === 'shipment') {
                    icon = 'fa-truck';
                    typeTitle = 'Sevkiyat Bilgileri';
                } else if (props.eventType === 'travel') {
                    icon = 'fa-plane';
                    typeTitle = 'Seyahat Bilgileri';
                } else if (props.eventType === 'maintenance') {
                    icon = 'fa-screwdriver-wrench';
                    typeTitle = 'Bakım Bilgileri';
                } else if (props.eventType === 'production') {
                    icon = 'fa-industry';
                    typeTitle = 'Üretim Bilgileri';
                } else if (props.eventType === 'vehicle_assignment') {
                    icon = 'fa-car';
                    typeTitle = 'Araç Görev Bilgileri';
                } else if (props.eventType === 'todo' || props.model_type === 'todo') {
                    icon = 'fa-check-square';
                    typeTitle = 'Yapılacak İş';

                    // To-Do için özel "Durum Değiştir" butonu
                    html += `<div class="text-center mb-3">
                                <button onclick="toggleTodoFromModal(${props.id})" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-check me-2"></i> Durumu Değiştir (Tamamla/Geri Al)
                                </button>
                             </div>`;
                }

                // --- 1. TABLO OLUŞTURMA (GENEL DETAYLAR) ---
                html +=
                    `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fas ${icon} me-2"></i>${typeTitle}</h6><div class="table-responsive"><table class="table table-borderless table-sm m-0 align-middle"><tbody>`;

                // Tabloda GÖSTERİLMEYECEK anahtarlar (Bunlar özel alanlarda gösterilecek)
                const excludeKeys = ['Açıklama', 'Notlar', 'Açıklamalar', 'Dosya Yolu', 'Plan Detayları',
                    'Onay Durumu', 'Onaylayan', 'Sonuç Notu'
                ];

                if (props.details && typeof props.details === 'object') {
                    Object.entries(props.details).forEach(([key, value]) => {
                        // 1. Hariç tutulanları atla
                        if (excludeKeys.includes(key)) return;

                        // 2. Boş veya null değerleri atla (Ekranda boş satır olmasın)
                        if (value === null || value === undefined || value === '' || value === '-') return;

                        let displayValue = String(value).trim();

                        // 3. Durumları Renkli Badge Yap (Pending, High vb.)
                        if (statusMap[displayValue]) {
                            const mapItem = statusMap[displayValue];
                            displayValue =
                                `<span class="badge ${mapItem.class} px-3 py-2 rounded-pill fw-normal">${mapItem.text}</span>`;
                        }
                        // Veya İngilizce kelimeleri Türkçeleştirip Badge Yap
                        else if (['Kritik', 'Yüksek', 'Orta', 'Düşük', 'Bekliyor', 'Tamamlandı'].includes(
                                displayValue)) {
                            let badgeClass = 'bg-secondary';
                            if (displayValue === 'Kritik' || displayValue === 'Yüksek') badgeClass =
                                'bg-danger';
                            if (displayValue === 'Tamamlandı') badgeClass = 'bg-success';
                            if (displayValue === 'Bekliyor') badgeClass = 'bg-warning text-dark';
                            displayValue =
                                `<span class="badge ${badgeClass} text-white px-2 py-1 rounded">${displayValue}</span>`;
                        }

                        // Satırı ekle
                        html +=
                            `<tr><td class="text-dark fw-bolder" style="width: 35%;">${key}:</td><td class="text-dark">${displayValue}</td></tr>`;
                    });
                }
                html += `</tbody></table></div></div>`;

                // --- 2. ÖZEL ALANLAR (TABLO DIŞI) ---

                // A. Üretim Kalemleri Tablosu
                if (props.eventType === 'production' && props.details && props.details['Plan Detayları']) {
                    let planItems = [];
                    let rawData = props.details['Plan Detayları'];

                    if (Array.isArray(rawData)) {
                        planItems = rawData;
                    } else if (typeof rawData === 'string') {
                        try {
                            planItems = JSON.parse(rawData);
                        } catch (e) {}
                    }

                    if (planItems.length > 0) {
                        html += '<div class="modal-info-card bg-light border mt-3">';
                        html +=
                            '<h6 class="text-primary fw-bold mb-2"><i class="fas fa-list-ol me-2"></i>Üretim Kalemleri</h6>';
                        html +=
                            '<div class="table-responsive"><table class="table table-sm table-striped table-hover bg-white rounded mb-0">';
                        html +=
                            '<thead class="table-light"><tr><th>Makine</th><th>Ürün</th><th class="text-end">Adet</th></tr></thead><tbody>';
                        planItems.forEach(i => {
                            html +=
                                `<tr><td>${i.machine || '-'}</td><td>${i.product || '-'}</td><td class="text-end fw-bold">${i.quantity || 0}</td></tr>`;
                        });
                        html += '</tbody></table></div></div>';
                    }
                }

                // B. Dosya Yolu
                if (props.details && props.details['Dosya Yolu']) {
                    html +=
                        `<div class="text-center mt-3 mb-3"><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-paperclip me-2"></i> Ekli Dosyayı Görüntüle</a></div>`;
                }

                // C. Açıklama / Notlar Kutusu
                const aciklama = (props.details) ? (props.details['Açıklamalar'] || props.details['Notlar'] || props
                    .details['Açıklama']) : null;
                if (aciklama && aciklama !== 'Açıklama yok') {
                    html += `<div class="modal-notes-box mt-3 p-3 bg-light rounded border">
                                <div class="modal-notes-title fw-bold mb-2 text-primary"><i class="fas fa-sticky-note me-1"></i> Açıklama / Notlar</div>
                                <p class="mb-0 text-secondary" style="white-space: pre-wrap;">${aciklama}</p>
                             </div>`;
                }

                // D. Sonuç Notu (Bakım vb. için)
                if (props.details && props.details['Sonuç Notu']) {
                    html += `<div class="alert alert-success mt-3 border-0 shadow-sm">
                                <div class="fw-bold mb-1"><i class="fas fa-check-circle me-1"></i> Tamamlanma Raporu</div>
                                <div class="small">${props.details['Sonuç Notu']}</div>
                             </div>`;
                }

                // --- 3. ALT BUTONLAR VE AKSİYONLAR ---

                // Sevkiyat Onay Durumu
                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';

                    if (props.details && props.details['Onay Durumu']) {
                        modalOnayBadge.style.display = 'block';
                        document.getElementById('modalOnayBadgeTarih').textContent = props.details['Onay Durumu'];
                        document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                            'Onaylayan'] || '';
                        if (modalOnayKaldirForm) {
                            modalOnayKaldirForm.action = props.onayKaldirUrl;
                            modalOnayKaldirForm.style.display = 'inline-block';
                        }
                    } else {
                        if (modalOnayForm) {
                            modalOnayForm.action = props.onayUrl;
                            modalOnayForm.style.display = 'inline-block';
                        }
                    }
                }
                // Diğer Modeller için "Detaya Git" Butonu
                else if ((props.eventType === 'travel' || props.eventType === 'maintenance') && canModify && props
                    .url) {
                    if (modalOnayForm) modalOnayForm.style.display = 'none';
                    modalExportButton.href = props.url;
                    modalExportButton.target = (props.eventType === 'travel') ? "_blank" : "_self";
                    modalExportButton.innerHTML = '<i class="fas fa-external-link-alt me-2"></i> Detaya Git';
                    modalExportButton.style.display = 'inline-block';
                }

                // --- 4. MODALI GÖSTER ---
                modalBody.innerHTML = html;

                try {
                    var modalElement = document.getElementById('detailModal');
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var myModal = bootstrap.Modal.getOrCreateInstance(modalElement);
                        myModal.show();
                    } else if (typeof $ !== 'undefined' && typeof $.fn.modal !== 'undefined') {
                        $(modalElement).modal('show');
                    } else {
                        alert('Bootstrap yüklenemediği için modal açılamıyor.');
                    }
                } catch (e) {
                    console.error('Modal hatası:', e);
                }
            }

            // function openUniversalModal(props) {
            //     hardResetModalUI();
            //     if (!props || (!props.eventType && !props.model_type)) return;

            //     const statusMap = {
            //         'Critical': {
            //             text: 'Kritik',
            //             class: 'bg-danger text-white'
            //         },
            //         'High': {
            //             text: 'Yüksek',
            //             class: 'bg-warning text-dark'
            //         },
            //         'Medium': {
            //             text: 'Orta',
            //             class: 'bg-info text-white'
            //         },
            //         'Low': {
            //             text: 'Düşük',
            //             class: 'bg-success text-white'
            //         },
            //         'Normal': {
            //             text: 'Normal',
            //             class: 'bg-secondary text-white'
            //         },
            //         'Pending': {
            //             text: 'Beklemede',
            //             class: 'bg-warning text-dark'
            //         },
            //         'Active': {
            //             text: 'Aktif',
            //             class: 'bg-success text-white'
            //         }
            //     };

            //     const modalTitle = document.getElementById('modalTitle');
            //     const modalBody = document.getElementById('modalDynamicBody');
            //     const modalEditButton = document.getElementById('modalEditButton');
            //     const modalExportButton = document.getElementById('modalExportButton');
            //     const modalDeleteForm = document.getElementById('modalDeleteForm');
            //     const modalOnayForm = document.getElementById('modalOnayForm');
            //     const modalOnayKaldirForm = document.getElementById('modalOnayKaldirForm');
            //     const modalOnayBadge = document.getElementById('modalOnayBadge');
            //     const modalImportantContainer = document.getElementById('modalImportantCheckboxContainer');
            //     const modalImportantCheckbox = document.getElementById('modalImportantCheckbox');
            //     const calendarEl = document.getElementById('calendar');

            //     const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
            //     const currentUserDept = calendarEl.dataset.userDept;
            //     const canMarkImportant = calendarEl.dataset.canMarkImportant === 'true';

            //     const eventOwnerId = props.user_id;
            //     const eventDeptId = props.department_id;

            //     let canModify = false;

            //     if (currentUserRole === 'admin') {
            //         canModify = true;
            //     } else if (eventOwnerId && eventOwnerId === currentUserId) {
            //         canModify = true;
            //     } else if (currentUserRole === 'yönetici') {
            //         if (!currentUserDept || (eventDeptId && String(currentUserDept) === String(eventDeptId))) {
            //             canModify = true;
            //         }
            //     }

            //     if (modalImportantContainer) {
            //         const isVehicleTask = (props.model_type === 'vehicle_assignment');
            //         let shouldShowCheckbox = false;
            //         if (canMarkImportant) shouldShowCheckbox = true;
            //         else if (isVehicleTask && canModify) shouldShowCheckbox = true;

            //         if (shouldShowCheckbox) {
            //             modalImportantContainer.style.display = 'flex';
            //             modalImportantCheckbox.checked = props.is_important || false;
            //             modalImportantCheckbox.disabled = false;
            //             modalImportantCheckbox.dataset.modelType = props.model_type;
            //             modalImportantCheckbox.dataset.modelId = props.id;
            //         } else {
            //             modalImportantContainer.style.setProperty('display', 'none', 'important');
            //         }
            //     }

            //     modalTitle.innerHTML = `<span>${props.title || 'Detaylar'}</span>`;

            //     if (canModify && props.editUrl && props.editUrl !== '#') {
            //         modalEditButton.href = props.editUrl;
            //         modalEditButton.style.display = 'inline-block';
            //     }
            //     if (canModify && props.deleteUrl && modalDeleteForm) {
            //         modalDeleteForm.action = props.deleteUrl;
            //         modalDeleteForm.style.display = 'inline-block';
            //     }

            //     let html = '';
            //     let icon = 'fa-info-circle';
            //     let typeTitle = 'Etkinlik Detayları';

            //     if (props.eventType === 'shipment') {
            //         icon = 'fa-truck';
            //         typeTitle = 'Sevkiyat Bilgileri';
            //     } else if (props.eventType === 'travel') {
            //         icon = 'fa-plane';
            //         typeTitle = 'Seyahat Bilgileri';
            //     } else if (props.eventType === 'maintenance') {
            //         icon = 'fa-screwdriver-wrench';
            //         typeTitle = 'Bakım Bilgileri';
            //     } else if (props.eventType === 'production') {
            //         icon = 'fa-industry';
            //         typeTitle = 'Üretim Bilgileri';
            //     } else if (props.eventType === 'todo' || props.model_type === 'todo') {
            //         const todoNote = props.details['Not'] || props.details['Açıklama'];

            //         if (todoNote && todoNote !== 'Açıklama yok') {
            //             html += `<div class="p-3 bg-light rounded border mb-3">
        //         <h6 class="fw-bold text-primary mb-2"><i class="fas fa-sticky-note me-2"></i>Notlar</h6>
        //         <p class="mb-0 text-dark" style="white-space: pre-wrap;">${todoNote}</p>
        //      </div>`;
            //         }
            //         html += `<div class="text-center mt-3">
        //     <button onclick="toggleTodoFromModal(${props.id})" class="btn btn-outline-success btn-sm w-100">
        //         <i class="fas fa-check me-2"></i> Durumu Değiştir (Tamamla/Geri Al)
        //     </button>
        //  </div>`;
            //     }

            //     html +=
            //         `<div class="modal-info-card"><h6 class="text-primary fw-bold mb-3 border-bottom pb-2"><i class="fas ${icon} me-2"></i>${typeTitle}</h6><div class="table-responsive"><table class="table table-borderless table-sm m-0 align-middle"><tbody>`;

            //     const excludeKeys = ['Açıklama', 'Notlar', 'Açıklamalar', 'Dosya Yolu', 'Plan Detayları',
            //         'Onay Durumu', 'Onaylayan'
            //     ];
            //     if (props.details && typeof props.details === 'object') {
            //         Object.entries(props.details).forEach(([key, value]) => {
            //             if (excludeKeys.includes(key)) return;
            //             if (value === null || value === undefined || value === '' || value === '-') return;
            //             let displayValue = String(value).trim();
            //             if (value && typeof value === 'object' && value.is_badge) {
            //                 displayValue =
            //                     `<span class="badge ${value.class} px-3 py-2 rounded-pill fw-normal">${value.text}</span>`;
            //             } else if (statusMap[displayValue]) {
            //                 const mapItem = statusMap[displayValue];
            //                 displayValue =
            //                     `<span class="badge ${mapItem.class} px-3 py-2 rounded-pill fw-normal">${mapItem.text}</span>`;
            //             }
            //             html +=
            //                 `<tr><td class="text-dark fw-bolder" style="width: 35%;">${key}:</td><td class="text-dark">${displayValue}</td></tr>`;
            //         });
            //     }
            //     html += `</tbody></table></div></div>`;

            //     if (props.eventType === 'production' && props.details['Plan Detayları']) {
            //         html +=
            //             '<div class="modal-info-card"><h6 class="text-primary fw-bold mb-2">Üretim Kalemleri</h6><table class="table table-sm table-striped"><thead><tr><th>Makine</th><th>Ürün</th><th>Adet</th></tr></thead><tbody>';
            //         props.details['Plan Detayları'].forEach(i => {
            //             html += `<tr><td>${i.machine}</td><td>${i.product}</td><td>${i.quantity}</td></tr>`;
            //         });
            //         html += '</tbody></table></div>';
            //     }

            //     if (props.details && props.details['Dosya Yolu']) {
            //         html +=
            //             `<div class="text-center mt-3 mb-3"><a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm"><i class="fas fa-paperclip me-2"></i> Dosyayı Görüntüle</a></div>`;
            //     }

            //     const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
            //         'Açıklama'];
            //     if (aciklama) {
            //         html +=
            //             `<div class="modal-notes-box mt-3 p-3 bg-light rounded border"><div class="modal-notes-title fw-bold mb-2 text-primary"><i class="fas fa-sticky-note me-1"></i> Açıklama / Notlar</div><p class="mb-0 text-secondary" style="white-space: pre-wrap;">${aciklama}</p></div>`;
            //     }

            //     if (props.eventType === 'shipment') {
            //         modalExportButton.href = props.exportUrl || '#';
            //         modalExportButton.style.display = 'inline-block';
            //         if (props.details['Onay Durumu']) {
            //             modalOnayBadge.style.display = 'block';
            //             document.getElementById('modalOnayBadgeTarih').textContent = props.details['Onay Durumu'];
            //             document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
            //                 'Onaylayan'] || '';
            //             if (modalOnayKaldirForm) {
            //                 modalOnayKaldirForm.action = props.onayKaldirUrl;
            //                 modalOnayKaldirForm.style.display = 'inline-block';
            //             }
            //         } else {
            //             if (modalOnayForm) {
            //                 modalOnayForm.action = props.onayUrl;
            //                 modalOnayForm.style.display = 'inline-block';
            //             }
            //         }
            //     } else if (props.eventType === 'travel' && canModify && props.url) {
            //         if (modalOnayForm) modalOnayForm.style.display = 'none';
            //         modalExportButton.href = props.url;
            //         modalExportButton.target = "_blank";
            //         modalExportButton.innerHTML = '<i class="fas fa-plane-departure me-2"></i> Detaya Git';
            //         modalExportButton.style.display = 'inline-block';
            //     } else if (props.eventType === 'maintenance' && canModify && props.url) {
            //         modalExportButton.href = props.url;
            //         modalExportButton.innerHTML = '<i class="fas fa-eye me-2"></i> Detaya Git';
            //         modalExportButton.style.display = 'inline-block';
            //     } else if (props.eventType === 'todo' || props.model_type === 'todo') {
            //         icon = 'fa-check-square';
            //         typeTitle = 'Yapılacak İş Detayı';
            //         // To-Do için özel buton veya işlem gerekirse buraya ekle
            //         // Örneğin "Tamamla" butonu eklenebilir ama widget'ta var zaten.
            //     }
            //     modalBody.innerHTML = html;
            //     var modalEl = document.getElementById('detailModal');
            //     var myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            //     myModal.show();
            // }

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: dateFromUrl || new Date(),
                locale: 'tr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Bugün',
                    month: 'Ay',
                    week: 'Hafta',
                    day: 'Gün',
                    list: 'Liste'
                },
                slotEventOverlap: false,
                dayMaxEvents: 5,
                height: 'auto',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    meridiem: false,
                    hour12: false
                },
                displayEventEnd: true,
                eventDisplay: 'list-item',
                eventSources: [{
                    url: calendarEventsUrl,
                    failure: () => alert('Veri hatası!')
                }, , {
                    googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                    color: '#dc3545',
                    className: 'fc-event-holiday',
                    googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                }],
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    const props = info.event.extendedProps;
                    if (props && (props.eventType || props.model_type)) {
                        openUniversalModal(props);
                    }
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps && info.event.extendedProps.is_important)
                        info.el.classList.add('event-important-pulse');
                    try {
                        let title = info.event.title;
                        let desc = '';
                        if (info.event.extendedProps?.details?.['Açıklama']) desc = info.event
                            .extendedProps.details['Açıklama'];
                        else if (info.event.start) {
                            let start = info.event.start.toLocaleTimeString('tr-TR', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                            if (start !== '00:00') desc = `Saat: ${start}`;
                        }
                        let tooltipContent =
                            `<div class="text-start"><span class="tooltip-title-styled">${title}</span>${desc ? `<span class="tooltip-desc-styled">${desc}</span>` : ''}</div>`;
                        if (typeof bootstrap !== 'undefined') {
                            new bootstrap.Tooltip(info.el, {
                                title: tooltipContent,
                                html: true,
                                placement: 'top',
                                trigger: 'hover',
                                container: 'body',
                                customClass: 'custom-calendar-tooltip',
                                delay: {
                                    "show": 100,
                                    "hide": 100
                                }
                            });
                        } else {
                            info.el.setAttribute('title', title + (desc ? ' - ' + desc : ''));
                        }
                    } catch (error) {
                        console.warn('Tooltip hatası:', error);
                    }
                }
            });

            function checkAndOpenModalFromUrl(events) {
                if (urlModalId && urlModalType) {
                    const idNum = parseInt(urlModalId, 10);
                    const foundEvent = events.find(e => {
                        const props = e.extendedProps || {};
                        return props.id === idNum && props.model_type === urlModalType;
                    });
                    if (foundEvent) {
                        openUniversalModal(foundEvent.extendedProps);
                        const newUrl = window.location.pathname + window.location.search.replace(
                            /[\?&]open_modal_id=[^&]+/, '').replace(/[\?&]open_modal_type=[^&]+/, '');
                        window.history.replaceState({}, document.title, newUrl);
                    }
                }
            }

            function applyCalendarFilters() {
                const calendarEl = document.getElementById('calendar');
                const canFilter = calendarEl.dataset.canFilter === 'true';
                let userDept = (calendarEl.dataset.userDept || '').toLowerCase().trim();
                userDept = userDept.replace(/ı/g, 'i').replace(/İ/g, 'i').replace(/ş/g, 's').replace(/ğ/g, 'g')
                    .replace(/ü/g, 'u').replace(/ö/g, 'o').replace(/ç/g, 'c');
                const isChecked = (id) => {
                    const el = document.getElementById(id);
                    return el ? el.checked : false;
                };

                let params = {};
                if (canFilter) {
                    params.lojistik = isChecked('filterLojistik') ? 1 : 0;
                    params.uretim = isChecked('filterUretim') ? 1 : 0;
                    params.hizmet = isChecked('filterHizmet') ? 1 : 0;
                    params.bakim = isChecked('filterBakim') ? 1 : 0;
                } else {
                    params.lojistik = userDept.includes('lojistik') ? 1 : 0;
                    params.uretim = (userDept.includes('uretim') || userDept.includes('planlama')) ? 1 : 0;
                    params.hizmet = (userDept.includes('hizmet') || userDept.includes('idari')) ? 1 : 0;
                    params.bakim = userDept.includes('bakim') ? 1 : 0;
                }
                params.important_only = document.getElementById('filterImportant')?.checked ? 1 : 0;

                let eventSource = calendar.getEventSources()[0];
                if (eventSource && eventSource.internalEventSource.meta.url === calendarEventsUrl) eventSource
                    .remove();
                else if (calendar.getEventSources().length > 0) calendar.getEventSources().forEach(src => {
                    if (src.internalEventSource?.meta?.url === calendarEventsUrl) src.remove();
                });

                calendar.addEventSource({
                    url: calendarEventsUrl,
                    extraParams: params,
                    success: function(rawEvents) {
                        setTimeout(() => {
                            checkAndOpenModalFromUrl(calendar.getEvents());
                        }, 500);
                    }
                });
            }

            const filters = document.querySelectorAll('.calendar-filters .form-check-input');
            filters.forEach(filter => filter.addEventListener('change', applyCalendarFilters));
            applyCalendarFilters();
            calendar.render();
            setInterval(function() {
                calendar.refetchEvents();
            }, 30000);

            const btnOnay = document.getElementById('modalOnayForm');
            if (btnOnay) btnOnay.addEventListener('submit', e => {
                if (!confirm('Onaylıyor musunuz?')) e.preventDefault();
            });
            const btnDelete = document.getElementById('modalDeleteForm');
            if (btnDelete) btnDelete.addEventListener('submit', e => {
                if (!confirm('Silinsin mi?')) e.preventDefault();
            });

            const chkImportant = document.getElementById('modalImportantCheckbox');
            if (chkImportant) {
                chkImportant.addEventListener('change', function() {
                    const isChecked = this.checked;
                    this.disabled = true;
                    fetch(toggleImportantUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                model_id: this.dataset.modelId,
                                model_type: this.dataset.modelType,
                                is_important: isChecked
                            })
                        }).then(res => res.json()).then(data => {
                            if (data.success === false) {
                                alert('HATA: ' + (data.message || 'Yetkiniz yok.'));
                                this.checked = !isChecked;
                            } else {
                                calendar.refetchEvents();
                            }
                        }).catch(err => {
                            console.error(err);
                            alert('Bir bağlantı hatası oluştu.');
                            this.checked = !isChecked;
                        })
                        .finally(() => {
                            this.disabled = false;
                        });
                });
            }
        });
        // Modal içinden To-Do durumunu değiştirme
        window.toggleTodoFromModal = function(id) {
            fetch(`/todos/${id}/toggle`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        // Modalı kapat ve sayfayı yenile (veya takvimi güncelle)
                        const modalEl = document.getElementById('detailModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        // Kullanıcıya bildirim ver
                        Swal.fire({
                            icon: 'success',
                            title: 'Güncellendi',
                            text: 'Görev durumu başarıyla değiştirildi.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload(); // Listeyi ve takvimi yenile
                        });
                    }
                });
        }
    </script>
@endsection
