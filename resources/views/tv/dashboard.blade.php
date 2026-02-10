@extends('layouts.app')

@section('title', 'KÖKSAN Canlı İzleme Paneli')

@push('styles')
    <style>
        /* --- 1. TV MODU VE GENEL DÜZEN --- */
        nav.navbar,
        .sidebar,
        footer,
        .breadcrumb,
        #kt_header_mobile {
            display: none !important;
        }

        #app>main.py-4,
        body,
        .content {
            padding: 1rem !important;
            min-height: 100vh;
            width: 100vw;
            margin: 0 !important;
            background: linear-gradient(-45deg, #f3f4f6, #e2e8f0, #edf2f7, #e6fffa);
            background-size: 400% 400%;
            animation: gradientWave 60s ease infinite;
            overflow: hidden;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
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

        .container-fluid {
            max-width: 100% !important;
        }

        body {
            cursor: none;
        }

        /* --- 2. KART TASARIMLARI --- */
        .tv-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            backdrop-filter: blur(10px);
            padding: 1.25rem;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* --- 3. ÜST KPI KARTLARI --- */
        .kpi-mini-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .kpi-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
        }

        .kpi-label {
            font-size: 0.9rem;
            font-weight: 700;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-icon {
            font-size: 2.2rem;
            opacity: 0.8;
        }

        /* --- 4. FABRİKA BİRİM KARTLARI --- */
        .unit-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            height: 100%;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.3s ease;
        }

        .unit-icon-bg {
            position: absolute;
            top: -15px;
            right: -15px;
            font-size: 7rem;
            opacity: 0.15;
            transform: rotate(10deg);
            z-index: 0;
        }

        .unit-content {
            position: relative;
            z-index: 1;
        }

        .unit-title {
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .unit-count {
            font-size: 4rem;
            font-weight: 900;
            line-height: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .unit-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
        }

        /* --- 5. BİLDİRİM LİSTESİ --- */
        .important-item {
            background: white;
            border-left: 5px solid #ccc;
            border-radius: 8px;
            padding: 0.9rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            animation: slideIn 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* --- 6. GİZLİ ÇIKIŞ --- */
        .emergency-exit-btn {
            position: fixed;
            bottom: 0;
            right: 0;
            width: 80px;
            height: 80px;
            z-index: 9999;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid d-flex flex-column" style="height: 97vh;">

        {{-- A. ÜST BAŞLIK & SAAT --}}
        <div class="d-flex justify-content-between align-items-center mb-3 px-1">
            <div>
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-layer-group text-primary fa-2x me-3"></i>
                    <div>
                        <h1 class="fw-bold mb-0" style="font-size: 2rem; color: #1a202c; letter-spacing: -0.5px;">KÖKSAN
                            HOLDİNG</h1>
                        <span class="text-secondary fw-bold fs-5">Entegre İş Süreçleri Paneli</span>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <h1 class="fw-bold mb-0" id="live-clock"
                    style="font-size: 3.5rem; font-family: 'Courier New', monospace; color: #2d3748; line-height: 1;">--:--
                </h1>
                <span class="text-secondary fw-medium fs-5">{{ \Carbon\Carbon::now()->translatedFormat('d F Y, l') }}</span>
            </div>
        </div>

        {{-- B. GENEL İSTATİSTİKLER (KPI) --}}
        <div class="row g-3 mb-3">
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #667EEA;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value">{{ $kpiData['sevkiyat_sayisi'] }}</div>
                            <div class="kpi-label">Sevkiyat</div>
                        </div>
                        <i class="fa-solid fa-truck-fast kpi-icon" style="color: #667EEA;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #4FD1C5;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value">{{ $kpiData['plan_sayisi'] }}</div>
                            <div class="kpi-label">Toplam Üretim</div>
                        </div>
                        <i class="fa-solid fa-industry kpi-icon" style="color: #4FD1C5;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #F093FB;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value">{{ $kpiData['etkinlik_sayisi'] }}</div>
                            <div class="kpi-label">Etkinlik & Ziyaret</div>
                        </div>
                        <i class="fa-solid fa-users-viewfinder kpi-icon" style="color: #F093FB;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #F6E05E;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value">{{ $kpiData['arac_gorevi_sayisi'] }}</div>
                            <div class="kpi-label">Araç Görevi</div>
                        </div>
                        <i class="fa-solid fa-car kpi-icon" style="color: #F6E05E;"></i>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="tv-card" style="border-bottom: 4px solid #ED8936;">
                    <div class="kpi-mini-card">
                        <div>
                            <div class="kpi-value">{{ $kpiData['bakim_sayisi'] }}</div>
                            <div class="kpi-label">Bakım & Arıza</div>
                        </div>
                        <i class="fa-solid fa-wrench kpi-icon" style="color: #ED8936;"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- C. FABRİKA BİRİMLERİ (DİNAMİK DÖNGÜ) --}}
        <div class="row g-3 mb-3" style="min-height: 180px;">
            @php
                // Dinamik Renk Paleti (Sırayla döner)
                $gradients = [
                    'linear-gradient(135deg, #2c7a7b 0%, #319795 100%)', // Teal (Kopet tarzı)
                    'linear-gradient(135deg, #2b6cb0 0%, #3182ce 100%)', // Blue (Preform tarzı)
                    'linear-gradient(135deg, #c05621 0%, #dd6b20 100%)', // Orange (Levha tarzı)
                    'linear-gradient(135deg, #553C9A 0%, #805AD5 100%)', // Purple
                    'linear-gradient(135deg, #C53030 0%, #E53E3E 100%)', // Red
                    'linear-gradient(135deg, #2F855A 0%, #48BB78 100%)', // Green
                    'linear-gradient(135deg, #2d3748 0%, #4a5568 100%)', // Gray
                ];

                // İkonlar (Birim adına göre özel ikon atamak istersen, yoksa varsayılan)
                $bgIcons = [
                    'KOPET' => 'fa-bottle-water',
                    'PREFORM' => 'fa-flask',
                    'LEVHA' => 'fa-sheet-plastic',
                    'KAPAK' => 'fa-circle-notch',
                    'STREÇ' => 'fa-scroll',
                    'GERİ' => 'fa-recycle',
                ];
            @endphp

            @foreach ($kpiData['unit_stats'] as $index => $unit)
                @php
                    // Rengi sırayla seç
                    $bgStyle = $gradients[$index % count($gradients)];

                    // İkonu ismin içinde geçen kelimeye göre bulmaya çalış
                    $bgIconClass = 'fa-industry';
                    foreach ($bgIcons as $key => $icon) {
                        if (str_contains($unit['name'], $key)) {
                            $bgIconClass = $icon;
                            break;
                        }
                    }
                @endphp

                <div class="col-md-4">
                    <div class="unit-card" style="background: {{ $bgStyle }};">
                        <i class="fa-solid {{ $bgIconClass }} unit-icon-bg"></i>
                        <div class="unit-content h-100 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="unit-title">{{ $unit['name'] }}</div>
                                <span class="unit-badge">
                                    <i class="fa-solid {{ $unit['icon'] }} me-2"></i>{{ $unit['status'] }}
                                </span>
                            </div>
                            <div class="d-flex align-items-end justify-content-between">
                                <div class="unit-count">{{ $unit['count'] }}</div>
                                <div class="text-end" style="opacity: 0.9;">
                                    <div class="fs-6 fw-bold">{{ $unit['sub_label'] }}</div>
                                </div>
                            </div>
                            <div class="progress mt-2" style="height: 4px; background: rgba(255,255,255,0.3);">
                                <div class="progress-bar bg-white" style="width: {{ $unit['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- D. ALT BÖLÜM: BİLDİRİMLER & AKIŞ GRAFİĞİ --}}
        <div class="row g-3 flex-grow-1" style="min-height: 0;">
            {{-- SOL: BİLDİRİMLER --}}
            <div class="col-md-4 h-100">
                <div class="tv-card">
                    <h5 class="fw-bold mb-3 pb-2 border-bottom text-danger d-flex align-items-center">
                        <i class="fas fa-bell fa-shake me-2"></i>GÜNCEL BİLDİRİMLER
                    </h5>

                    <div style="overflow-y: hidden; position: relative; flex-grow: 1;">
                        @if ($importantItems->isEmpty())
                            <div
                                class="d-flex flex-column align-items-center justify-content-center h-100 text-muted opacity-50">
                                <i class="fa-regular fa-circle-check fa-4x mb-3"></i>
                                <span class="fs-4">Her şey yolunda, işlem yok.</span>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                @foreach ($importantItems as $item)
                                    @php
                                        $borderColor = match ($item->type) {
                                            'shipment' => '#667EEA',
                                            'productionplan' => '#4FD1C5',
                                            'event' => '#F093FB',
                                            'maintenance' => '#ED8936',
                                            'vehicleassignment' => '#F6E05E',
                                            'travel' => '#E53E3E',
                                            default => '#CBD5E0',
                                        };
                                        $icon = match ($item->type) {
                                            'shipment' => 'fa-truck',
                                            'productionplan' => 'fa-industry',
                                            'event' => 'fa-calendar',
                                            'maintenance' => 'fa-wrench',
                                            'vehicleassignment' => 'fa-car',
                                            'travel' => 'fa-plane',
                                            default => 'fa-info-circle',
                                        };
                                    @endphp
                                    <div class="important-item" style="border-left-color: {{ $borderColor }};">
                                        <div class="me-3 text-center" style="width: 35px;">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 35px; height: 35px; background-color: {{ $borderColor }}20;">
                                                <i class="fa-solid {{ $icon }}"
                                                    style="color: {{ $borderColor }}; font-size: 1rem;"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-uppercase"
                                                style="font-size: 0.7rem; color: {{ $borderColor }}; letter-spacing: 0.5px;">
                                                {{ $item->category }}</div>
                                            <div class="fw-bold text-dark" style="font-size: 0.95rem; line-height: 1.2;">
                                                {{ $item->content }}</div>
                                        </div>
                                        <div class="text-end ps-2 ms-1">
                                            <div class="fw-bold text-dark" style="font-size: 1rem;">
                                                {{ $item->date->format('H:i') }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SAĞ: DİNAMİK FABRİKA MATRİSİ --}}
            <div class="col-md-8 h-100">
                <div class="tv-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                        <h5 class="fw-bold mb-0 text-primary">
                            <i class="fas fa-network-wired me-2"></i>FABRİKA & DEPARTMAN DURUMU
                        </h5>
                        <span class="badge bg-light text-dark border">
                            <i class="fa-solid fa-clock me-1 text-primary"></i>
                            Anlık Veri Akışı
                        </span>
                    </div>

                    <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #e2e8f0;">
                                <tr class="text-uppercase text-secondary"
                                    style="font-size: 0.85rem; letter-spacing: 1px;">
                                    <th class="ps-3">FABRİKA BİRİMİ</th>

                                    {{-- 1. BAŞLIKLARI DÖNGÜYLE BAS --}}
                                    @foreach ($matrixHeaders as $header)
                                        <th class="text-center">{{ $header }}</th>
                                    @endforeach

                                    <th class="text-end pe-3">DURUM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (empty($matrixData))
                                    <tr>
                                        {{-- colspan sayısını dinamik hesapla: İsim + Sütunlar + Durum --}}
                                        <td colspan="{{ count($matrixHeaders) + 2 }}"
                                            class="text-center py-5 text-muted">
                                            <i class="fa-solid fa-bed fa-3x mb-3 opacity-50"></i><br>
                                            Şu an aktif bir iş akışı bulunmuyor.
                                        </td>
                                    </tr>
                                @else
                                    @foreach ($matrixData as $row)
                                        <tr
                                            style="border-bottom: 1px solid rgba(0,0,0,0.05); transition: background 0.3s;">

                                            {{-- FABRİKA ADI VE DURUM NOKTASI --}}
                                            <td class="fw-bold text-dark ps-3 py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 10px; height: 10px; 
                                                                background-color: var(--bs-{{ $row['status_dot_color'] }}); 
                                                                box-shadow: 0 0 10px var(--bs-{{ $row['status_dot_color'] }});">
                                                    </div>
                                                    {{ $row['name'] }}
                                                </div>
                                            </td>

                                            {{-- 2. DİNAMİK SÜTUNLAR (İçerik ne olursa olsun basar) --}}
                                            @foreach ($row['columns'] as $col)
                                                <td class="text-center">
                                                    <span class="{{ $col['badge_class'] }}">
                                                        {!! $col['icon_html'] !!}
                                                    </span>
                                                </td>
                                            @endforeach

                                            {{-- DURUM METNİ --}}
                                            <td class="text-end pe-3">
                                                <span class="{{ $row['status_text_class'] }}"
                                                    style="font-size: 0.85rem;">
                                                    {{ $row['status_text'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        <div onclick="document.getElementById('logout-form').submit();" class="emergency-exit-btn" title="Çıkış Yap">
        </div>
    </div>
@endsection

@section('page_scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. OTOMATİK YENİLEME SİSTEMİ ---
            let currentHash = "{{ $currentDataHash }}";
            setInterval(() => {
                fetch("{{ route('tv.check_updates') }}")
                    .then(response => response.json())
                    .then(data => {
                        if (data.hash !== currentHash) {
                            console.log('Veri değişikliği algılandı, sayfa yenileniyor...');
                            location.reload();
                        }
                    })
                    .catch(err => console.error('Bağlantı hatası:', err));
            }, 10000);

            // --- 2. CANLI DİJİTAL SAAT ---
            setInterval(() => {
                const now = new Date();
                const timeString = now.toLocaleTimeString('tr-TR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                document.getElementById('live-clock').textContent = timeString;
            }, 1000);

            // --- 4. SCROLL LOOP ---
            setTimeout(() => {
                if (document.body.scrollHeight > window.innerHeight) {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                    setTimeout(() => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }, 5000);
                }
            }, 15000);

            // Klavye ile çıkış
            document.addEventListener('keydown', (e) => {
                if (e.key === "Escape") document.getElementById('logout-form').submit();
            });
        });
    </script>
@endsection
