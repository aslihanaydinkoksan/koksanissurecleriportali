@extends('layouts.app')

@section('title', 'Benim Takvimim')

@push('styles')
    <style>
        /* === 1. SAYFA VE LAYOUT === */
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
            max-width: 1600px;
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
            min-height: 100px;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background-color: rgba(102, 126, 234, 0.08) !important;
        }

        .fc-event {
            border: none !important;
            margin: 1px 2px !important;
            padding: 3px 6px;
            font-size: 0.8rem;
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

        <div class="row">
            <div class="col-md-9">
                <div class="card create-shipment-card">
                    <div class="card-header">
                        📅 {{ $departmentName }} Takvimi
                    </div>
                    <div class="card-body">
                        {{-- FİLTRELEME ALANI --}}
                        {{-- Sadece Admin veya Departmanı Olmayan Yönetici Görebilir --}}
                        @php
                            $canFilter =
                                Auth::user()->role === 'admin' ||
                                (Auth::user()->role === 'yönetici' && is_null(Auth::user()->department_id));
                        @endphp

                        @if ($canFilter)
                            <div class="calendar-filters p-3 mb-3"
                                style="background: rgba(102, 126, 234, 0.05); border-radius: 0.75rem;">
                                <strong class="me-3"><i class="fa-solid fa-filter"></i> Filtrele:</strong>

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
                                {{-- YENİ: BAKIM FİLTRESİ --}}
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="bakim" id="filterBakim" checked>
                                    <label class="form-check-label" for="filterBakim" style="color: #ED8936;">Bakım</label>
                                </div>

                                <span class="mx-2">|</span>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" value="important" id="filterImportant">
                                    <label class="form-check-label" for="filterImportant"
                                        style="color: #dc3545;"><strong>Sadece
                                            Önemliler</strong></label>
                                </div>
                            </div>
                        @endif
                        {{-- FİLTRELEME ALANI SONU --}}

                        <div id="calendar" data-events='@json($events)'
                            data-is-authorized="{{ in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false' }}"
                            data-current-user-id="{{ Auth::id() }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                @can('is-global-manager')
                    <div class="card create-shipment-card mb-3">
                        <div class="card-header">⚡ {{ __('Hızlı Eylemler') }}</div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('users.create') }}" class="btn btn-animated-gradient"
                                    style="text-transform: none; color:#fff">👤 Yeni Kullanıcı Ekle</a>
                                <button type="button" class="btn btn-info text-white" id="toggleUsersButton">👥 Mevcut
                                    Kullanıcıları Görüntüle</button>
                            </div>
                        </div>
                    </div>
                @endcan

                @if (!empty($chartData))
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            📊 {{ $statsTitle }}
                        </div>
                        <div class="card-body" id="stats-card-body">
                            @if ($departmentSlug === 'lojistik')
                                <div id="hourly-chart-lojistik"></div>
                                <hr>
                                <div id="daily-chart-lojistik"></div>
                            @elseif($departmentSlug === 'uretim')
                                <div id="weekly-plans-chart"></div>
                                <hr>
                            @elseif($departmentSlug === 'hizmet')
                                <div id="daily-events-chart"></div>
                                <hr>
                                <div id="daily-assignments-chart"></div>
                                {{-- YENİ: BAKIM GRAFİĞİ ALANI --}}
                            @elseif($departmentSlug === 'bakim')
                                <div id="maintenance-plans-chart"></div>
                            @else
                                <p class="text-center">Bu departman için özel istatistikler henüz tanımlanmamıştır.</p>
                            @endif

                            @if (Route::has('statistics.index'))
                                <hr>
                                <div class="text-center">
                                    <a href="{{ route('statistics.index') }}">Daha Fazla İstatistik Görüntüle</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>


        @if (in_array(Auth::user()->role, ['admin', 'yönetici']))
            <div class="row mt-4" id="userListContainer" style="display: none;">
                <div class="col-md-9">
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            👥 {{ __('Sistemdeki Mevcut Kullanıcılar') }}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Ad Soyad</th>
                                            <th scope="col">E-posta</th>
                                            <th scope="col">Rol</th>
                                            <th scope="col">Birim</th>
                                            <th scope="col">Kayıt Tarihi</th>
                                            <th scope="col">Eylemler</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $user)
                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                @php
                                                    $roleClass = 'bg-secondary';
                                                    if ($user->role === 'admin') {
                                                        $roleClass = 'bg-primary';
                                                    }
                                                    if ($user->role === 'yönetici') {
                                                        $roleClass = 'bg-info';
                                                    }
                                                @endphp
                                                <td><span
                                                        class="badge {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                                </td>
                                                <td>{{ $user->department?->name ?? '-' }}</td>
                                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        @if (Auth::user()->role === 'admin' || $user->role !== 'admin')
                                                            <a href="{{ route('users.edit', $user->id) }}"
                                                                class="btn btn-sm btn-secondary">✏️ Düzenle</a>
                                                        @endif
                                                        @if (Auth::user()->role === 'admin' && Auth::user()->id !== $user->id && $user->role !== 'admin')
                                                            <form action="{{ route('users.destroy', $user->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('{{ $user->name }} adlı kullanıcıyı silmek istediğinizden emin misiniz?');">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger">🗑️
                                                                    Sil</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Sistemde gösterilecek kullanıcı
                                                    bulunamadı.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        @endif
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
    @include('partials.calendar-modal')
@endsection

@section('page_scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.13/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js'></script>
    <script>
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        }
        document.addEventListener('DOMContentLoaded', function() {
            const colorPalette = ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'];

            var calendarEl = document.getElementById('calendar');
            const isAuthorized = calendarEl.dataset.isAuthorized === 'true';
            const currentUserId = parseInt(calendarEl.dataset.currentUserId, 10);
            const eventsData = JSON.parse(calendarEl.dataset.events || '[]');
            const appTimezone = calendarEl.dataset.timezone;

            var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
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

            function splitDateTime(dateTimeString) {
                const dt = String(dateTimeString || '');
                const parts = dt.split(' ');
                const date = parts[0] || '-';
                let time = parts[1] || '-';
                if (date === '-' || time === '') {
                    time = '-';
                }
                return {
                    date: date,
                    time: time
                };
            }

            function hardResetModalUI() {
                const idsToHide = ['modalEditButton', 'modalExportButton', 'modalOnayForm', 'modalOnayKaldirForm',
                    'modalDeleteForm', 'modalOnayBadge', 'modalImportantCheckboxContainer'
                ];
                idsToHide.forEach(id => {
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
                console.log('--- MODAL AÇILIYOR (HOME) ---', props.eventType);
                hardResetModalUI();

                if (!props || !props.eventType) {
                    console.error("Modal için geçersiz veri:", props);
                    return;
                }

                // Yetki ve Checkbox işlemleri
                if (isAuthorized) {
                    modalImportantContainer.style.display = 'block';
                    modalImportantCheckbox.checked = props.is_important || false;
                    modalImportantCheckbox.dataset.modelType = props.model_type;
                    modalImportantCheckbox.dataset.modelId = props.id;
                }

                // Başlık Ayarı
                modalTitle.innerHTML = `<span>${props.title || 'Detaylar'}</span>`;

                // Düzenle/Sil Butonları
                let canModify = false;
                if (isAuthorized) {
                    canModify = true;
                } else if (props.user_id && props.user_id === currentUserId) {
                    canModify = true;
                }

                if (canModify && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                }
                if (canModify && props.deleteUrl && modalDeleteForm) {
                    modalDeleteForm.action = props.deleteUrl;
                    modalDeleteForm.style.display = 'inline-block';
                }

                // === DİNAMİK İÇERİK OLUŞTURMA ===
                let html = '';

                // 1. Üst Kısım: Simge ve Başlık
                let icon = 'fa-info-circle';
                let typeTitle = 'Etkinlik Detayları';

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
                }

                html += `<div class="modal-info-card">
                            <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">
                                <i class="fas ${icon} me-2"></i>${typeTitle}
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-borderless table-sm m-0 align-middle">
                                    <tbody>`;

                // 2. DÖNGÜ VE TARİH FORMATLAMA
                const excludeKeys = ['Açıklama', 'Notlar', 'Açıklamalar', 'Dosya Yolu'];

                if (props.details && typeof props.details === 'object') {
                    Object.entries(props.details).forEach(([key, value]) => {
                        if (excludeKeys.includes(key)) return;
                        if (value === null || value === undefined || value === '' || value === '-') {
                            return;
                        }

                        let displayValue = '-';

                        // SENARYO 1: Backend'den özel Badge Objesi geldiyse (Modelden gelen yapı)
                        if (value && typeof value === 'object' && value.is_badge) {
                            displayValue = `<span class="badge ${value.class} px-3 py-2 rounded-pill fw-normal">
                                                ${value.text}
                                            </span>`;
                        }
                        // SENARYO 2: Normal Metin veya Tarih geldiyse
                        else if (value !== null && value !== undefined && value !== '') {
                            const strValue = String(value).trim();

                            // Tarih Kontrolü (GG.AA.YYYY SS:DD)
                            const dateRegex = /^(\d{1,2}\.\d{1,2}\.\d{4})\s(\d{1,2}:\d{2})$/;
                            const match = strValue.match(dateRegex);

                            if (match) {
                                displayValue = `
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-light text-dark border border-secondary fw-normal">
                                            <i class="fas fa-calendar-alt text-primary me-1"></i> ${match[1]}
                                        </span>
                                        <span class="badge bg-light text-dark border fw-normal">
                                            <i class="fas fa-clock text-warning me-1"></i> ${match[2]}
                                        </span>
                                    </div>`;
                            } else {
                                displayValue = strValue;
                            }
                        }

                        html += `<tr>
                                    <td class="text-dark fw-bolder" style="width: 35%;">${key}:</td>
                                    <td class="text-dark">${displayValue}</td>
                                 </tr>`;
                    });
                }

                html += `       </tbody>
                                </table>
                            </div>
                         </div>`;

                // 3. Dosya Varsa
                if (props.details && props.details['Dosya Yolu']) {
                    html += `<div class="text-center mt-3 mb-3">
                                <a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-paperclip me-2"></i> Dosyayı Görüntüle
                                </a>
                             </div>`;
                }

                // 4. Açıklama / Notlar
                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html += `<div class="modal-notes-box mt-3 p-3 bg-light rounded border">
                                <div class="modal-notes-title fw-bold mb-2 text-primary">
                                    <i class="fas fa-sticky-note me-1"></i> Açıklama / Notlar
                                </div>
                                <p class="mb-0 text-secondary" style="white-space: pre-wrap;">${aciklama}</p>
                             </div>`;
                }

                // === BUTONLAR (Mevcut mantık) ===
                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';

                    if (props.details['Onay Durumu']) {
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
                } else if (props.eventType === 'travel') {
                    if (modalOnayForm) modalOnayForm.style.display = 'none';
                    if (canModify && props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.target = "_blank";
                        modalExportButton.innerHTML = '<i class="fas fa-plane-departure me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                } else if (props.eventType === 'maintenance') {
                    if (canModify && props.url) {
                        modalExportButton.href = props.url;
                        modalExportButton.innerHTML = '<i class="fas fa-eye me-2"></i> Detaya Git';
                        modalExportButton.style.display = 'inline-block';
                    }
                }

                modalBody.innerHTML = html;
                detailModal.show();
            }

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                buttonText: {
                    today: 'Bugün',
                    dayGridMonth: 'Ay',
                    timeGridWeek: 'Hafta',
                    timeGridDay: 'Gün',
                    listWeek: 'Liste'
                },
                slotEventOverlap: false,
                dayMaxEvents: 3,
                eventMaxStack: 3,
                slotDuration: '00:30:00',
                height: 'auto',
                slotMinTime: '06:00:00',
                slotMaxTime: '22:00:00',
                scrollTime: '08:00:00',
                nowIndicator: true,
                eventSources: [{
                    id: 'databaseEvents',
                    events: eventsData
                }, {
                    googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                    color: '#dc3545',
                    textColor: 'white',
                    className: 'fc-event-holiday',
                    googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                }],
                timeZone: appTimezone,
                moreLinkText: function(num) {
                    return '+ ' + num + ' tane daha';
                },
                displayEventTime: true,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                eventDisplay: 'list-item',
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    if (info.event.extendedProps && info.event.extendedProps.eventType) {
                        openUniversalModal(info.event.extendedProps);
                    }
                },
                eventDidMount: function(info) {
                    if (info.event.extendedProps && info.event.extendedProps.is_important) {
                        info.el.classList.add('event-important-pulse');
                    }
                    try {
                        let title = info.event.title;
                        let desc = '';
                        if (info.event.extendedProps && info.event.extendedProps.details && info.event
                            .extendedProps.details['Açıklama']) {
                            desc = info.event.extendedProps.details['Açıklama'];
                        } else if (info.event.start) {
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
                },
            });
            calendar.render();
            setInterval(function() {
                console.log('Veriler arkaplanda güncelleniyor...');
                calendar.refetchEvents();
            }, 30000);

            function applyCalendarFilters() {
                const isChecked = (id) => {
                    const el = document.getElementById(id);
                    return el ? el.checked : true;
                };
                const isImportantChecked = (id) => {
                    const el = document.getElementById(id);
                    return el ? el.checked : false;
                };
                const showLojistik = document.getElementById('filterLojistik').checked;
                const showUretim = document.getElementById('filterUretim').checked;
                const showHizmet = document.getElementById('filterHizmet').checked;
                const showBakim = document.getElementById('filterBakim').checked; // YENİ
                const showImportant = isImportantChecked('filterImportant');

                let dbSource = calendar.getEventSourceById('databaseEvents');
                if (dbSource) {
                    dbSource.remove();
                }

                const filteredDbEvents = eventsData.filter(event => {
                    const props = event.extendedProps;
                    if (!props) return true;
                    if (showImportant && !props.is_important) {
                        return false;
                    }

                    const eventType = props.eventType;
                    if (eventType === 'shipment') return showLojistik;
                    if (eventType === 'production') return showUretim;
                    if (eventType === 'maintenance') return showBakim; // YENİ MANTIK

                    const isHizmet = (eventType === 'service_event' || eventType === 'vehicle_assignment' ||
                        eventType === 'travel');
                    if (isHizmet) return showHizmet;

                    return true;
                });
                calendar.addEventSource({
                    id: 'databaseEvents',
                    events: filteredDbEvents
                });
            }

            const filters = document.querySelectorAll('.calendar-filters .form-check-input');
            filters.forEach(filter => filter.addEventListener('change', applyCalendarFilters));

            // ... Listenerlar ...
            if (modalOnayForm) {
                modalOnayForm.addEventListener('submit', function(e) {
                    if (!confirm('Sevkiyatın tesise ulaştığını onaylıyorsunuz?')) e.preventDefault();
                    else this.querySelector('button[type=submit]').disabled = true;
                });
            }
            if (modalOnayKaldirForm) {
                modalOnayKaldirForm.addEventListener('submit', function(e) {
                    if (!confirm('Bu sevkiyatın onayını geri almak istediğinizden emin misiniz?')) e
                        .preventDefault();
                    else this.querySelector('button[type=submit]').disabled = true;
                });
            }
            if (modalDeleteForm) {
                modalDeleteForm.addEventListener('submit', function(e) {
                    this.querySelector('button[type=submit]').disabled = true;
                });
            }

            // URL'den Modal Açma
            const urlParams = new URLSearchParams(window.location.search);
            const modalIdToOpen = urlParams.get('open_modal_id');
            const modalTypeToOpen = urlParams.get('open_modal_type');
            if (modalIdToOpen && modalTypeToOpen) {
                const allEvents = calendar.getEvents();
                const modalIdNum = parseInt(modalIdToOpen, 10);
                const eventToOpen = allEvents.find(event => event.extendedProps.id === modalIdNum && event
                    .extendedProps.model_type === modalTypeToOpen);
                if (eventToOpen) {
                    console.log('URL\'den modal tetikleniyor:', eventToOpen.extendedProps);
                    openUniversalModal(eventToOpen.extendedProps);
                }
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            if (isAuthorized) {
                const toggleBtn = document.getElementById('toggleUsersButton');
                const userList = document.getElementById('userListContainer');
                if (toggleBtn && userList) {
                    toggleBtn.addEventListener('click', function() {
                        if (userList.style.display === 'none') {
                            userList.style.display = 'block';
                            toggleBtn.textContent = '👥 Kullanıcı Listesini Gizle';
                        } else {
                            userList.style.display = 'none';
                            toggleBtn.textContent = '👥 Mevcut Kullanıcıları Görüntüle';
                        }
                    });
                }
            }
            if (modalImportantCheckbox) {
                modalImportantCheckbox.addEventListener('change', function() {
                    const modelId = this.dataset.modelId;
                    const modelType = this.dataset.modelType;
                    const isChecked = this.checked;
                    this.disabled = true;
                    fetch('{{ route('calendar.toggleImportant') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            model_id: modelId,
                            model_type: modelType,
                            is_important: isChecked
                        })
                    }).then(response => response.json()).then(data => {
                        if (!data.success) throw new Error(data.message || 'Güncelleme başarısız.');
                        console.log('Güncelleme başarılı:', data.message);
                        location.reload();
                    }).catch(error => {
                        console.error('Hata:', error);
                        alert('Bir hata oluştu, değişiklik geri alınıyor.');
                        this.checked = !isChecked;
                        this.disabled = false;
                    });
                });
            }

            const statsCard = document.getElementById('stats-card-body');
            const chartData = @json($chartData ?? []);
            const departmentSlug = '{{ $departmentSlug }}';

            const commonChartOptions = {
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'],
                plotOptions: {
                    bar: {
                        distributed: true,
                        borderRadius: 8
                    }
                },
                legend: {
                    show: false
                },
                title: {
                    align: 'left',
                    style: {
                        fontSize: '14px',
                        fontWeight: 700,
                        color: '#2d3748'
                    }
                },
                dataLabels: {
                    enabled: false
                }
            };

            if (departmentSlug === 'lojistik' && chartData.hourly && chartData.daily) {
                // ... Lojistik grafikleri (değişmedi) ...
                if (chartData.hourly.labels.length > 0 && document.querySelector("#hourly-chart-lojistik")) {
                    let hourlyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Sevkiyat Sayısı',
                            data: chartData.hourly.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.hourly.title || 'Saatlik Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.hourly.labels,
                            tickAmount: 6
                        }
                    };
                    new ApexCharts(document.querySelector("#hourly-chart-lojistik"), hourlyOptions).render();
                }
                if (chartData.daily.labels.length > 0 && document.querySelector("#daily-chart-lojistik")) {
                    let dailyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Sevkiyat Sayısı',
                            data: chartData.daily.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily.title || 'Haftalık Yoğunluk'
                        },
                        xaxis: {
                            categories: chartData.daily.labels
                        }
                    };
                    new ApexCharts(document.querySelector("#daily-chart-lojistik"), dailyOptions).render();
                }
            } else if (departmentSlug === 'uretim' && chartData.weekly_plans) {
                // ... Üretim (değişmedi) ...
                if (chartData.weekly_plans.labels.length > 0 && document.querySelector("#weekly-plans-chart")) {
                    let weeklyOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Plan Sayısı',
                            data: chartData.weekly_plans.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.weekly_plans.title || 'Haftalık Plan Sayısı'
                        },
                        xaxis: {
                            categories: chartData.weekly_plans.labels
                        }
                    };
                    new ApexCharts(document.querySelector("#weekly-plans-chart"), weeklyOptions).render();
                }
            } else if (departmentSlug === 'hizmet' && chartData.daily_events && chartData.daily_assignments) {
                // ... Hizmet (değişmedi) ...
                if (chartData.daily_events.labels.length > 0 && document.querySelector("#daily-events-chart")) {
                    let eventOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Etkinlik Sayısı',
                            data: chartData.daily_events.data
                        }],
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: [commonChartOptions.colors[1]],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_events.title || 'Günlük Etkinlik Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_events.labels,
                            tickAmount: 6,
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            },
                            min: 0
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
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM'
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(0) + " etkinlik"
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                    };
                    new ApexCharts(document.querySelector("#daily-events-chart"), eventOptions).render();
                }
                if (chartData.daily_assignments.labels.length > 0 && document.querySelector(
                        "#daily-assignments-chart")) {
                    let assignmentOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Atama Sayısı',
                            data: chartData.daily_assignments.data
                        }],
                        chart: {
                            type: 'area',
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: [commonChartOptions.colors[3]],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_assignments.title || 'Günlük Araç Atama Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_assignments.labels,
                            tickAmount: 6,
                            labels: {
                                rotate: -45,
                                rotateAlways: true,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            },
                            min: 0
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
                        },
                        tooltip: {
                            x: {
                                format: 'dd MMM'
                            },
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(0) + " atama"
                                }
                            }
                        },
                        grid: {
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                    };
                    new ApexCharts(document.querySelector("#daily-assignments-chart"), assignmentOptions).render();
                }
            }
            // === YENİ EKLENEN: BAKIM GRAFİĞİ ===
            else if (departmentSlug === 'bakim' && chartData.maintenance_plans) {
                if (chartData.maintenance_plans.labels.length > 0 && document.querySelector(
                        "#maintenance-plans-chart")) {
                    let maintenanceOptions = {
                        ...commonChartOptions,
                        series: [{
                            name: 'Bakım Sayısı',
                            data: chartData.maintenance_plans.data
                        }],
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.maintenance_plans.title || 'Haftalık Bakım Yoğunluğu'
                        },
                        xaxis: {
                            categories: chartData.maintenance_plans.labels
                        },
                        colors: ['#ED8936', '#F6AD55', '#DD6B20', '#C05621', '#9C4221'] // Turuncu Tonları
                    };
                    new ApexCharts(document.querySelector("#maintenance-plans-chart"), maintenanceOptions).render();
                }
            }
        });
    </script>
@endsection
