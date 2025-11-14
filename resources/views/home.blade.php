@extends('layouts.app')

@section('title', 'Benim Takvimim')

@push('styles')
    <style>
        /* ... (Tüm CSS stiliniz burada, değişiklik yok) ... */
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

        .create-shipment-card {
            border-radius: 1.25rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
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
            color: #2d3748;
        }

        .create-shipment-card .form-label {
            color: #2d3748;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .create-shipment-card .form-control,
        .create-shipment-card .form-select {
            border-radius: 0.75rem;
            background-color: rgba(255, 255, 255, 0.95);
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .create-shipment-card .form-control:focus,
        .create-shipment-card .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }


        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 700;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .alert {
            border-radius: 1rem;
            border: none;
            padding: 1rem 1.25rem;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.4s ease;
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


        #calendar {
            background: transparent;
            /* Şeffaf arka plan */
            border-radius: 0;
            padding: 0;
        }

        .fc .fc-button-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }

        .fc .fc-button-primary:hover {
            background: linear-gradient(135deg, #764BA2, #667EEA);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background: linear-gradient(135deg, #764BA2, #667EEA);
        }

        .fc-event {
            border-radius: 0.5rem;
            border: none;
            padding: 2px 6px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            user-select: none;
        }

        .fc-event:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .fc .fc-daygrid-day-number {
            font-weight: 600;
            color: #4a5568;
        }

        .fc .fc-col-header-cell-cushion {
            font-weight: 700;
            color: #2d3748;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .fc .fc-daygrid-day.fc-day-today {
            background: rgba(102, 126, 234, 0.1) !important;
        }


        .table {
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table thead th {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: #2d3748;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .table tbody tr {
            background: rgba(255, 255, 255, 0.7);
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.95);
            transform: scale(1.01);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .table tbody td {
            border: none;
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr td:first-child {
            border-radius: 0.5rem 0 0 0.5rem;
        }

        .table tbody tr td:last-child {
            border-radius: 0 0.5rem 0.5rem 0;
        }


        .badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 0.8rem;
        }

        .bg-primary {
            background: linear-gradient(135deg, #667EEA, #764BA2) !important;
        }

        .bg-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC) !important;
        }

        .bg-secondary {
            background: linear-gradient(135deg, #718096, #4a5568) !important;
        }

        .bg-success {
            background: linear-gradient(135deg, #48BB78, #38A169) !important;
        }

        .btn {
            border-radius: 0.75rem;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            transition: all 0.2s ease;
            border: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .btn-info {
            background: linear-gradient(135deg, #4FD1C5, #38B2AC);
            color: white !important;
        }

        .btn-secondary {
            background: linear-gradient(135deg, #718096, #4a5568);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #F6AD55, #ED8936);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #48BB78, #38A169);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #FC8181, #F56565);
            color: white;
        }

        .btn-outline-primary {
            border: 2px solid #667EEA;
            color: #667EEA;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            border-color: #667EEA;
            color: white;
        }


        .modal-content {
            border-radius: 1.25rem;
            border: none;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .modal-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            border-radius: 1.25rem 1.25rem 0 0;
            padding: 1.5rem;
        }

        .modal-title {
            font-weight: 700;
            color: #2d3748;
            font-size: 1.5rem;
        }

        .modal-body {
            padding: 2rem;
            color: #2d3748;
        }

        .modal-body p {
            margin-bottom: 0.75rem;
            line-height: 1.8;
        }

        .modal-body strong {
            color: #667EEA;
            font-weight: 700;
            display: inline-block;
            min-width: 180px;
        }

        .modal-body hr {
            border: none;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.3), transparent);
            margin: 1.5rem 0;
        }

        .modal-footer {
            border-top: 2px solid rgba(102, 126, 234, 0.1);
            padding: 1.5rem;
            background: rgba(249, 250, 251, 0.5);
            border-radius: 0 0 1.25rem 1.25rem;
        }


        #stats-card-body {
            padding: 1.5rem;
        }

        #stats-card-body a {
            color: #667EEA;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        #stats-card-body a:hover {
            color: #764BA2;
            transform: translateX(5px);
        }

        #stats-card-body a::after {
            content: '→';
            font-size: 1.2rem;
            transition: transform 0.2s ease;
        }

        #stats-card-body a:hover::after {
            transform: translateX(3px);
        }


        @media (max-width: 768px) {
            .create-shipment-card .card-header {
                font-size: 1rem;
                padding: 1rem;
            }

            .modal-body strong {
                min-width: 120px;
                font-size: 0.9rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }


        html {
            scroll-behavior: smooth;
        }


        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner-border {
            animation: spin 0.75s linear infinite;
        }

        .event-important-pulse {
            /* "Kutucuk" görünümü için bir kenarlık veya gölge */
            border: 2px solid #ff4136 !important;
            /* !important, fc-event'i ezmek için */
            box-shadow: 0 0 0 rgba(255, 65, 54, 0.4);
            /* Gölgenin başlangıç durumu */

            /* Animasyon tanımı */
            animation: pulse-animation 2s infinite;
        }

        /* Animasyon Keyframes */
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

        .fc-event-holiday {
            font-weight: 600;
            border: none !important;
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
            border-radius: 4px;
            position: relative;
            overflow: hidden;

            /* Taşmayı önle */
            padding: 3px 6px;
            font-size: 11px;
            white-space: nowrap;
            text-overflow: ellipsis;
            max-width: 100%;
            display: block;

            /* Hover efekti */
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
            /* Hover'ı engellemez */
        }

        /* Hover'da tam metin göster */
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

        /* Mobil için optimizasyon */
        @media (max-width: 768px) {
            .fc-event-holiday {
                font-size: 9px;
                padding: 2px 4px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">

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
            <div class="col-md-8">
                <div class="card create-shipment-card">
                    <div class="card-header">
                        📅 {{ $departmentName }} Takvimi
                    </div>
                    <div class="card-body">
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
                                <input class="form-check-input" type="checkbox" value="uretim" id="filterUretim" checked>
                                <label class="form-check-label" for="filterUretim" style="color: #4FD1C5;">Üretim</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="hizmet" id="filterHizmet" checked>
                                <label class="form-check-label" for="filterHizmet" style="color: #F093FB;">İdari
                                    İşler</label>
                            </div>

                            <span class="mx-2">|</span>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" value="important" id="filterImportant">
                                <label class="form-check-label" for="filterImportant" style="color: #dc3545;"><strong>Sadece
                                        Önemliler</strong></label>
                            </div>
                        </div>
                        {{-- === DÜZELTME: SADECE BİR TANE CALENDAR DIV'İ KALDI === --}}
                        <div id="calendar" data-events='@json($events)'
                            data-is-authorized="{{ in_array(Auth::user()->role, ['admin', 'yönetici']) ? 'true' : 'false' }}"
                            data-current-user-id="{{ Auth::id() }}">
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-md-4">
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
                                <p class="text-muted text-center small mt-3">Yakında daha fazla üretim istatistiği
                                    eklenecektir.</p>
                            @elseif($departmentSlug === 'hizmet')
                                <div id="daily-events-chart"></div>
                                <hr>
                                <div id="daily-assignments-chart"></div>
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
                <div class="col-md-8">
                    <div class="card create-shipment-card">
                        <div class="card-header">
                            👥 {{ __('Sistemdeki Mevcut Kullanıcılar') }}
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
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
                                                <th scope="row">{{ $user->id }}</th>
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
                                                <td>
                                                    {{-- Departman adı gösterimi --}}
                                                    {{ $user->department?->name ?? '-' }}
                                                </td>
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
                                                                @csrf
                                                                @method('DELETE')
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

            // === YARDIMCI FONKSİYON: Tarih/Saat Ayırıcı (Blade Hatası Düzeltilmiş) ===
            /**
             * Bir tarih-saat dizesini (örn: "19.05.2025 11:30")
             * tarih ve saat olarak ayırır.
             * @@param {string} dateTimeString - Ayırılacak dize.
             * @@returns @{{ date: string, time: string }}
             */
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

            function openUniversalModal(props) {
                if (!props || !props.eventType) {
                    console.error("Modal için geçersiz veri:", props);
                    return;
                }
                if (isAuthorized) {
                    // Checkbox'ı görünür yap
                    modalImportantContainer.style.display = 'block';

                    // Gelen veriye göre 'checked' durumunu ayarla
                    modalImportantCheckbox.checked = props.is_important || false;

                    // AJAX isteği için gerekli verileri checkbox'a ata
                    modalImportantCheckbox.dataset.modelType = props.model_type;
                    modalImportantCheckbox.dataset.modelId = props.id;
                } else {
                    // Yetkisi yoksa checkbox'ı gizle
                    modalImportantContainer.style.display = 'none';
                }

                modalTitle.textContent = props.title || 'Detaylar';
                let showButtons = false;

                if (props.eventType === 'production' || props.eventType === 'service_event' || props.eventType ===
                    'vehicle_assignment') {
                    if (isAuthorized) {
                        showButtons = true;
                    } else if (props.user_id) {
                        showButtons = (props.user_id === currentUserId);
                    } else {

                        showButtons = false;
                        console.warn(
                            `'${props.eventType}' etkinliğinde 'user_id' prop'u eksik. Butonlar gizlendi.`);
                    }
                } else {

                    showButtons = true;
                }

                // Düzenle Butonu
                if (showButtons && props.editUrl && props.editUrl !== '#') {
                    modalEditButton.href = props.editUrl;
                    modalEditButton.style.display = 'inline-block';
                } else {
                    modalEditButton.style.display = 'none';
                }

                // Silme Butonu
                if (modalDeleteForm) {
                    if (showButtons && props.deleteUrl) {
                        modalDeleteForm.action = props.deleteUrl;
                        modalDeleteForm.style.display = 'inline-block';
                    } else {
                        modalDeleteForm.style.display = 'none';
                    }
                }


                let html = '<div class="row">';


                if (props.eventType === 'shipment') {
                    modalExportButton.href = props.exportUrl || '#';
                    modalExportButton.style.display = 'inline-block';

                    if (props.details['Onay Durumu']) {
                        modalOnayForm.style.display = 'none';
                        if (modalOnayKaldirForm) {
                            modalOnayKaldirForm.action = props.onayKaldirUrl;
                            modalOnayKaldirForm.style.display = 'inline-block';
                        }
                        modalOnayBadge.style.display = 'inline-block';
                        document.getElementById('modalOnayBadgeTarih').textContent = props.details['Onay Durumu'];
                        document.getElementById('modalOnayBadgeKullanici').textContent = props.details[
                            'Onaylayan'] || '';
                    } else {
                        modalOnayForm.action = props.onayUrl;
                        modalOnayForm.style.display = 'inline-block';
                        if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                        modalOnayBadge.style.display = 'none';
                    }


                    const isGemi = (props.details['Araç Tipi'] || '').toLowerCase().includes('gemi');
                    const col1 = [],
                        col2 = [];


                    col1.push(`<strong>🚛 Araç Tipi:</strong> ${props.details['Araç Tipi'] || '-'}`);
                    if (!isGemi) {
                        col1.push(`<strong>🔢 Plaka:</strong> ${props.details['Plaka'] || '-'}`);
                        col1.push(`<strong>🔢 Dorse Plakası:</strong> ${props.details['Dorse Plakası'] || '-'}`);
                        col1.push(`<strong>👨‍✈️ Şoför Adı:</strong> ${props.details['Şoför Adı'] || '-'}`);
                    } else {
                        col1.push(`<strong>⚓ IMO Numarası:</strong> ${props.details['IMO Numarası'] || '-'}`);
                        col1.push(`<strong>🚢 Gemi Adı:</strong> ${props.details['Gemi Adı'] || '-'}`);
                    }


                    if (!isGemi) {
                        col2.push(`<strong>📍 Kalkış Noktası:</strong> ${props.details['Kalkış Noktası'] || '-'}`);
                        col2.push(`<strong>📍 Varış Noktası:</strong> ${props.details['Varış Noktası'] || '-'}`);
                    } else {
                        col2.push(`<strong>🏁 Kalkış Limanı:</strong> ${props.details['Kalkış Limanı'] || '-'}`);
                        col2.push(`<strong>🎯 Varış Limanı:</strong> ${props.details['Varış Limanı'] || '-'}`);
                    }
                    col2.push(`<strong>🔄 Sevkiyat Türü:</strong> ${props.details['Sevkiyat Türü'] || '-'}`);

                    html += `<div class="col-md-6">${col1.map(item => `<p>${item}</p>`).join('')}</div>`;
                    html += `<div class="col-md-6">${col2.map(item => `<p>${item}</p>`).join('')}</div>`;
                    html += '</div><hr><div class="row">'; // Yeni satır
                    html +=
                        `<div class="col-md-12"><p><strong>📦 Kargo Yükü:</strong> ${props.details['Kargo Yükü'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>🏷️ Kargo Tipi:</strong> ${props.details['Kargo Tipi'] || '-'}</p></div>`;
                    html +=
                        `<div class="col-md-6"><p><strong>⚖️ Kargo Miktarı:</strong> ${props.details['Kargo Miktarı'] || '-'}</p></div>`;
                    html += '</div><hr><div class="row">';

                    const cikis = splitDateTime(props.details['Çıkış Tarihi']);
                    const varis = splitDateTime(props.details['Tahmini Varış']);


                    html += '<div class="col-md-6">';
                    html += `    <p><strong>📅 Çıkış Tarihi:</strong> ${cikis.date}</p>`;
                    if (cikis.time !== '-') {
                        html += `    <p><strong>🕒 Çıkış Saati:</strong> ${cikis.time}</p>`;
                    }
                    html += '</div>';

                    html += '<div class="col-md-6">';
                    html += `    <p><strong>📅 Tahmini Varış:</strong> ${varis.date}</p>`;
                    if (varis.time !== '-') {
                        html += `    <p><strong>🕒 Varış Saati:</strong> ${varis.time}</p>`;
                    }
                    html += '</div>';


                } else {
                    modalExportButton.style.display = 'none';
                    modalOnayForm.style.display = 'none';
                    if (modalOnayKaldirForm) modalOnayKaldirForm.style.display = 'none';
                    modalOnayBadge.style.display = 'none';


                    if (props.eventType === 'production') {
                        html += `<div class="col-md-12">`;
                        html += `<p><strong>Plan Başlığı:</strong> ${props.details['Plan Başlığı'] || '-'}</p>`;
                        html +=
                            `<p><strong>Hafta Başlangıcı:</strong> ${props.details['Hafta Başlangıcı'] || '-'}</p>`;
                        html += `<p><strong>Oluşturan:</strong> ${props.details['Oluşturan'] || '-'}</p>`;

                        if (props.details['Plan Detayları'] && props.details['Plan Detayları'].length > 0) {
                            html +=
                                '<strong>Plan Detayları:</strong><table class="table table-sm table-bordered mt-2" style="background: rgba(255,255,255,0.5);">';
                            html += '<thead><tr><th>Makine</th><th>Ürün</th><th>Adet</th></tr></thead><tbody>';
                            props.details['Plan Detayları'].forEach(item => {
                                html +=
                                    `<tr><td>${item.machine || '-'}</td><td>${item.product || '-'}</td><td>${item.quantity || '-'}</td></tr>`;
                            });
                            html += '</tbody></table>';
                        }
                        html += `</div>`;
                    } else if (props.eventType === 'service_event') {


                        html += `<div class="col-md-12">`;
                        html +=
                            `    <p><strong>Etkinlik Tipi:</strong> ${props.details['Etkinlik Tipi'] || '-'}</p>`;
                        html += `    <p><strong>Konum:</strong> ${props.details['Konum'] || '-'}</p>`;
                        html += `</div>`;


                        const baslangic = splitDateTime(props.details['Başlangıç']);
                        const bitis = splitDateTime(props.details['Bitiş']);

                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                        if (baslangic.time !== '-') {
                            html += `    <p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                        }
                        html += '</div>';

                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                        if (bitis.time !== '-') {
                            html += `    <p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                        }
                        html += '</div>';


                        html += `<div class="col-md-12 mt-3">`;
                        html += `    <p><strong>Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p>`;
                        html += `</div>`;

                    } else if (props.eventType === 'vehicle_assignment') {


                        html += `<div class="col-md-12">`;
                        html += `    <p><strong>Araç:</strong> ${props.details['Araç'] || '-'}</p>`;
                        html += `    <p><strong>Görev:</strong> ${props.details['Görev'] || '-'}</p>`;
                        html += `    <p><strong>Yer:</strong> ${props.details['Yer'] || '-'}</p>`;
                        html += `    <p><strong>Talep Eden:</strong> ${props.details['Talep Eden'] || '-'}</p>`;
                        html += `</div>`;
                        const baslangic = splitDateTime(props.details['Başlangıç']);
                        const bitis = splitDateTime(props.details['Bitiş']);

                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Başlangıç Tarihi:</strong> ${baslangic.date}</p>`;
                        if (baslangic.time !== '-') {
                            html += `    <p><strong>🕒 Başlangıç Saati:</strong> ${baslangic.time}</p>`;
                        }
                        html += '</div>';

                        html += '<div class="col-md-6">';
                        html += `    <p><strong>📅 Bitiş Tarihi:</strong> ${bitis.date}</p>`;
                        if (bitis.time !== '-') {
                            html += `    <p><strong>🕒 Bitiş Saati:</strong> ${bitis.time}</p>`;
                        }
                        html += '</div>';

                        html += `<div class="col-md-12 mt-3">`;
                        html += `    <p><strong>Kayıt Yapan:</strong> ${props.details['Kayıt Yapan'] || '-'}</p>`;
                        html += `</div>`;
                    } else if (props.eventType === 'travel') {
                        html += `<div class="col-md-12">
                                <p><strong>✈️ Plan Adı:</strong> ${props.details['Plan Adı'] || '-'}</p>
                                <p><strong>👤 Oluşturan:</strong> ${props.details['Oluşturan'] || '-'}</p>
                                <p><strong>📅 Başlangıç:</strong> ${props.details['Başlangıç'] || '-'}</p>
                                <p><strong>📅 Bitiş:</strong> ${props.details['Bitiş'] || '-'}</p>
                                <p><strong>📊 Durum:</strong> ${props.details['Durum'] || '-'}</p>
                               </div>`;
                        if (props.url) {
                            modalExportButton.href = props.url;
                            modalExportButton.target = "_blank";
                            modalExportButton.textContent = "✈️ Seyahat Detayına Git";
                            modalExportButton.style.display = 'inline-block';
                        }
                    }
                }
                html += '</div>';

                const aciklama = props.details['Açıklamalar'] || props.details['Notlar'] || props.details[
                    'Açıklama'];
                if (aciklama) {
                    html += '<hr>';
                    html += '<p><strong>📝 Notlar / Açıklamalar:</strong></p>';
                    html +=
                        `<p style="margin-left: 1rem; padding: 1rem; background: rgba(102, 126, 234, 0.05); border-radius: 0.5rem;">${aciklama}</p>`;
                }
                if (props.eventType === 'shipment' && props.details['Dosya Yolu']) {
                    html += '<hr>';
                    html += '<p><strong>📎 Ek Dosya:</strong></p>';
                    html +=
                        `<a href="${props.details['Dosya Yolu']}" target="_blank" class="btn btn-outline-primary btn-sm">📄 Dosyayı Görüntüle / İndir</a>`;
                }
                modalBody.innerHTML = html;
                detailModal.show();
            }
            const editButton = document.getElementById('editShipmentButton');
            const exportButton = document.getElementById('exportExcelButton');
            const onayForm = document.getElementById('onayForm');
            const onayKaldirForm = document.getElementById('onayKaldirForm');
            const deleteForm = document.getElementById('deleteShipmentForm');
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
                eventSources: [{
                        id: 'databaseEvents',
                        events: eventsData
                    },

                    // 2. Kaynak: Türkiye Resmi Tatilleri (Google Takvim)
                    {
                        googleCalendarId: 'tr.turkish#holiday@group.v.calendar.google.com',
                        color: '#dc3545', // Kırmızı (Bootstrap danger rengi)
                        textColor: 'white',
                        className: 'fc-event-holiday', // Özel stil için
                        googleCalendarApiKey: 'AIzaSyAQmEWGR-krGzcCk1r8R69ER-NyZM2BeWM'
                    }
                ],
                timeZone: appTimezone,
                dayMaxEvents: true,
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
                    if (info.event.extendedProps.is_important) {
                        info.el.classList.add('event-important-pulse');
                    }
                }
            });
            calendar.render();

            function applyCalendarFilters() {
                const showLojistik = document.getElementById('filterLojistik').checked;
                const showUretim = document.getElementById('filterUretim').checked;
                const showHizmet = document.getElementById('filterHizmet').checked;
                const showImportant = document.getElementById('filterImportant').checked;

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
                    if (eventType === 'shipment') {
                        return showLojistik;
                    }

                    if (eventType === 'production') {
                        return showUretim;
                    }
                    const isHizmet = (
                        eventType === 'service_event' ||
                        eventType === 'vehicle_assignment' ||
                        eventType === 'travel'
                    );
                    if (isHizmet) {
                        return showHizmet;
                    }
                    return true;
                });

                // 5. Filtrelenmiş veriyi takvime geri ekle
                calendar.addEventSource({
                    id: 'databaseEvents', // Aynı ID'yi ver
                    events: filteredDbEvents
                });
            }

            const filters = document.querySelectorAll('.calendar-filters .form-check-input');
            filters.forEach(filter => filter.addEventListener('change', applyCalendarFilters));

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


            const urlParams = new URLSearchParams(window.location.search);
            const modalIdToOpen = urlParams.get('open_modal_id');
            const modalTypeToOpen = urlParams.get('open_modal_type');

            if (modalIdToOpen && modalTypeToOpen) {
                const allEvents = calendar.getEvents();
                const modalIdNum = parseInt(modalIdToOpen, 10);

                const eventToOpen = allEvents.find(event =>
                    event.extendedProps.id === modalIdNum &&
                    event.extendedProps.model_type === modalTypeToOpen
                );

                if (eventToOpen) {
                    console.log('URL\'den modal tetikleniyor:', eventToOpen.extendedProps);
                    openUniversalModal(eventToOpen.extendedProps);
                } else {
                    console.warn('Modal açılmak istendi ancak ' + modalTypeToOpen + ' (ID:' +
                        modalIdNum + ') takvimde bulunamadı.');
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
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) throw new Error(data.message || 'Güncelleme başarısız.');

                            console.log('Güncelleme başarılı:', data.message);

                            // KRİTİK FARK: Bu takvim veriyi AJAX ile çekmiyor,
                            // @json($events) ile sayfaya gömüyor.
                            // 'refetchEvents()' burada çalışmaz.
                            // En basit ve güvenilir yol, değişikliğin
                            // görünmesi için sayfayı yeniden yüklemektir.
                            location.reload();
                        })
                        .catch(error => {
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
                colors: ['#A78BFA', '#60D9A0', '#FDB4C8', '#FFB84D', '#9DECF9'], // Renk paleti
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

            // Lojistik Grafikleri
            if (departmentSlug === 'lojistik' && chartData.hourly && chartData.daily) {
                if (chartData.hourly.labels.length > 0 && document.querySelector("#hourly-chart-lojistik")) {
                    let hourlyOptions = {
                        ...commonChartOptions, // Ortak ayarları kopyala
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
            }
            // Üretim Grafikleri
            else if (departmentSlug === 'uretim' && chartData.weekly_plans) {
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
                    // Çizgi grafiği daha uygun olabilir? type: 'line'
                    // weeklyOptions.chart.type = 'line';
                    // weeklyOptions.stroke = { curve: 'smooth' }; // Yumuşak çizgi
                    new ApexCharts(document.querySelector("#weekly-plans-chart"), weeklyOptions).render();
                }
                // Başka üretim grafiği varsa buraya eklenebilir
            }
            // Hizmet Grafikleri
            else if (departmentSlug === 'hizmet' && chartData.daily_events && chartData.daily_assignments) {

                // Etkinlik Grafiği Ayarları
                if (chartData.daily_events.labels.length > 0 && document.querySelector("#daily-events-chart")) {
                    let eventOptions = {
                        ...commonChartOptions, // Ortak ayarları alalım ama bazılarını değiştireceğiz
                        series: [{
                            name: 'Etkinlik Sayısı',
                            data: chartData.daily_events.data
                        }],
                        chart: { // Chart ayarlarını override edelim
                            type: 'area', // Alan grafiği yapalım
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            } // Yakınlaştırmayı kapatalım
                        },
                        colors: [commonChartOptions.colors[1]], // Farklı bir renk seçelim (örn: yeşil)
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_events.title || 'Günlük Etkinlik Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_events.labels,
                            tickAmount: 6, // Daha az etiket gösterelim
                            labels: {
                                rotate: -45, // Etiketleri 45 derece döndürelim
                                rotateAlways: true,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        yaxis: { // Y ekseninde sadece tam sayıları gösterelim
                            labels: {
                                formatter: function(val) {
                                    return val.toFixed(0);
                                }
                            },
                            min: 0 // Minimum değer 0 olsun
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        }, // Yumuşak çizgi
                        fill: {
                            type: 'gradient',
                            gradient: {
                                opacityFrom: 0.6,
                                opacityTo: 0.1
                            }
                        }, // Alan dolgusu
                        tooltip: { // Üzerine gelince gösterilecek bilgi
                            x: {
                                format: 'dd MMM'
                            }, // Tarih formatı
                            y: {
                                formatter: function(val) {
                                    return val.toFixed(0) + " etkinlik"
                                }
                            }
                        },
                        grid: { // Arka plan çizgileri (isteğe bağlı)
                            borderColor: '#e7e7e7',
                            row: {
                                colors: ['#f3f3f3', 'transparent'],
                                opacity: 0.5
                            }
                        },
                    };
                    new ApexCharts(document.querySelector("#daily-events-chart"), eventOptions).render();
                }

                // Araç Atama Grafiği Ayarları
                if (chartData.daily_assignments.labels.length > 0 && document.querySelector(
                        "#daily-assignments-chart")) {
                    let assignmentOptions = {
                        ...commonChartOptions, // Ortak ayarları al
                        series: [{
                            name: 'Atama Sayısı',
                            data: chartData.daily_assignments.data
                        }],
                        chart: { // Chart ayarlarını override edelim
                            type: 'area', // Alan grafiği yapalım
                            height: 250,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        colors: [commonChartOptions.colors[3]], // Farklı bir renk seçelim (örn: sarı)
                        title: {
                            ...commonChartOptions.title,
                            text: chartData.daily_assignments.title || 'Günlük Araç Atama Sayısı'
                        },
                        xaxis: {
                            categories: chartData.daily_assignments.labels, // Aynı etiketler
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
        }); // DOMContentLoaded sonu
    </script>
@endsection
