@extends('layouts.app')

@section('title', 'Hoş Geldiniz')

<style>
    /* ... (Mevcut CSS stiliniz aynı kalır) ... */
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* Arka plan dalgalanma animasyonu */
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

    /* === YARI ŞEFFAF "FROSTED GLASS" KART STİLİ === */
    .create-shipment-card {
        border-radius: 1rem;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        background-color: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
    }

    /* Okunabilirlik için metin stilleri */
    .create-shipment-card .card-header,
    .create-shipment-card .form-label,
    .create-shipment-card .card-body {
        color: #000;
        font-weight: 500;
    }

    .create-shipment-card .card-header {
        font-weight: bold;
    }

    /* Welcome sayfasındaki 'Bugün Yaklaşan Sevkiyatlar' listesi için */
    .create-shipment-card .list-group-item {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .create-shipment-card .list-group-item:last-child {
        border-bottom: 0;
    }

    /* Araç tipi ikon renkleri */
    .icon-gemi {
        color: #9DECF9 !important;
    }

    .icon-tır,
    .icon-tir {
        color: #FFB84D !important;
    }

    .icon-kamyon {
        color: #60D9A0 !important;
    }

    .icon-uretim {
        color: #4FD1C5 !important;
    }

    .icon-aracgorevi {
        color: #FBD38D !important;
    }

    .icon-etkinlik-genel {
        color: #F093FB !important;
    }

    .icon-egitim {
        color: #B794F4 !important;
    }

    .icon-toplanti {
        color: #667EEA !important;
    }

    .icon-misafir {
        color: #4FD1C5 !important;
    }

    .icon-musteri {
        color: #0456f9 !important;
    }

    .icon-fuar {
        color: #F6AD55 !important;
    }

    .icon-gezi {
        color: #68D391 !important;
    }

    .vehicle-icon {
        width: 40px;
        text-align: center;
    }

    /* Buton animasyon stili */
    .btn-animated-gradient {
        background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Hoş Geldiniz Mesajı --}}
                <div class="card create-shipment-card mb-4">
                    {{-- ... (card-body ve list-group kısmı aynı kalır, Adım 10'da zaten güncellenmişti) ... --}}
                    <div class="card-body p-4">

                        <div class="row align-items-center mb-3">
                            <div class="col-md-7">
                                <h2 class="card-title mb-0">Hoş Geldiniz, {{ Auth::user()->name }}!</h2>
                                <p class="mb-0 text-muted fs-5">{{ Auth::user()->department?->name ?? 'Genel' }} Departmanı
                                </p>
                            </div>
                            <div class="col-md-5 text-md-end mt-2 mt-md-0">
                                <a href="{{ route('statistics.index') }}" class="btn btn-light ms-2">
                                    <i class="fa-solid fa-chart-simple me-1" style="color: #A78BFA;"></i> İstatistikler
                                    &raquo;
                                </a>
                                <a href="{{ route('home') }}" class="btn btn-light ms-2">
                                    <i class="fa-solid fa-calendar-alt me-1" style="color: #F093FB"></i> Takvim &raquo;
                                </a>
                            </div>
                        </div>

                        <hr>

                        <h4 class="mt-4">
                            <i class="fa-solid fa-clock me-1" style="color: #A78BFA;"></i>
                            {{ $welcomeTitle }}
                        </h4>

                        @php
                            // İkon haritası (Aynı kalır)
                            $iconMap = [
                                'gemi' => ['icon' => 'fa-ship', 'class' => 'icon-gemi'],
                                'tır' => ['icon' => 'fa-truck-moving', 'class' => 'icon-tir'],
                                'tir' => ['icon' => 'fa-truck-moving', 'class' => 'icon-tir'],
                                'kamyon' => ['icon' => 'fa-truck', 'class' => 'icon-kamyon'],
                                'uretim' => ['icon' => 'fa-industry', 'class' => 'icon-uretim'],
                                'aracgorevi' => ['icon' => 'fa-car-side', 'class' => 'icon-aracgorevi'],
                                'etkinlik' => ['icon' => 'fa-calendar-star', 'class' => 'icon-etkinlik-genel'],
                                'egitim' => ['icon' => 'fa-chalkboard-user', 'class' => 'icon-egitim'],
                                'toplanti' => ['icon' => 'fa-users', 'class' => 'icon-toplanti'],
                                'misafir_karsilama' => ['icon' => 'fa-people-arrows', 'class' => 'icon-misafir'],
                                'musteri_ziyareti' => ['icon' => 'fa-hands-helping', 'class' => 'icon-musteri'],
                                'fuar' => ['icon' => 'fa-store', 'class' => 'icon-fuar'],
                                'gezi' => ['icon' => 'fa-map-signs', 'class' => 'icon-gezi'],
                                'diger' => ['icon' => 'fa-calendar-star', 'class' => 'icon-etkinlik-genel'],
                            ];
                        @endphp

                        <div class="list-group mt-3">
                            @forelse($todayItems as $item)
                                {{-- Lojistik VEYA Varsayılan (Admin) Gösterimi --}}
                                @if ($departmentSlug === 'lojistik' || is_null($departmentSlug) || in_array(Auth::user()->role, ['admin', 'yönetici']))
                                    @php
                                        $aracTipi = strtolower($item->arac_tipi ?? '');
                                        $iconInfo = $iconMap[$aracTipi] ?? [
                                            'icon' => 'fa-question-circle',
                                            'class' => 'text-muted',
                                        ];
                                    @endphp
                                    <div class="list-group-item d-flex align-items-center py-3">
                                        <div class="me-3"><i
                                                class="fa-solid {{ $iconInfo['icon'] }} fa-2x vehicle-icon {{ $iconInfo['class'] }}"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h5 class="mb-1 fw-bold">Yaklaşan {{ $item->kargo_icerigi }} Sevkiyatı</h5>
                                            <p class="mb-0 text-muted"><strong>Araç:</strong> {{ ucfirst($aracTipi) }} <span
                                                    class="mx-2">|</span> <strong>Varış Saati:</strong> <span
                                                    class="fw-bold text-dark">{{ \Carbon\Carbon::parse($item->tahmini_varis_tarihi)->format('H:i') }}</span>
                                            </p>
                                        </div>
                                        <a href="{{ route('home') }}?open_modal={{ $item->id }}"
                                            class="btn btn-outline-secondary btn-sm">Detay <i
                                                class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                    </div>

                                    {{-- Üretim Gösterimi --}}
                                @elseif($departmentSlug === 'uretim')
                                    @php $iconInfo = $iconMap['uretim']; @endphp
                                    <div class="list-group-item d-flex align-items-center py-3">
                                        <div class="me-3"><i
                                                class="fa-solid {{ $iconInfo['icon'] }} fa-2x vehicle-icon {{ $iconInfo['class'] }}"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h5 class="mb-1 fw-bold">{{ $item->plan_title }}</h5>
                                            <p class="mb-0 text-muted"><strong>Başlangıç:</strong>
                                                {{ $item->week_start_date->format('d.m.Y') }}</p>
                                        </div>
                                        <a href="{{ route('home') }}?open_modal={{ $item->id }}"
                                            class="btn btn-outline-secondary btn-sm">Detay <i
                                                class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                    </div>

                                    {{-- Hizmet Gösterimi (Etkinlik veya Atama) --}}
                                @elseif($departmentSlug === 'hizmet')
                                    @php
                                        $isEvent = $item instanceof \App\Models\Event;
                                        $itemType = $isEvent ? $item->event_type : 'aracgorevi';
                                        $iconInfo =
                                            $iconMap[$itemType] ??
                                            ($isEvent
                                                ? $iconMap['etkinlik']
                                                : ['icon' => 'fa-question-circle', 'class' => 'text-muted']);
                                        $saat = $isEvent
                                            ? $item->start_datetime->format('H:i')
                                            : $item->start_time->format('H:i');
                                        $baslik = $isEvent ? $item->title : 'Araç Görevi: ' . $item->task_description;
                                        $detayLink = $isEvent
                                            ? route('service.events.edit', $item->id)
                                            : route('service.assignments.edit', $item->id);
                                    @endphp
                                    <div class="list-group-item d-flex align-items-center py-3">
                                        <div class="me-3"><i
                                                class="fa-solid {{ $iconInfo['icon'] }} fa-2x vehicle-icon {{ $iconInfo['class'] }}"></i>
                                        </div>
                                        <div class="d-flex flex-column flex-grow-1">
                                            <h5 class="mb-1 fw-bold">{{ $baslik }}</h5>
                                            <p class="mb-0 text-muted"><strong>Konum:</strong>
                                                {{ $item->location ?? ($item->destination ?? '-') }} <span
                                                    class="mx-2">|</span> <strong>Saat:</strong> <span
                                                    class="fw-bold text-dark">{{ $saat }}</span></p>
                                        </div>
                                        <a href="{{ route('home') }}?open_modal={{ $item->id }}">Detay <i
                                                class="fa-solid fa-arrow-right-long ms-1"></i></a>
                                    </div>
                                @endif

                            @empty
                                {{-- Görev yoksa gösterilecek ortak mesaj --}}
                                <div class="list-group-item d-flex align-items-center py-3">
                                    <i class="fa-solid fa-info-circle fa-2x me-3 text-muted"></i>
                                    <h5 class="mb-0 text-muted">Bugün için planlanmış bir görev bulunmamaktadır.</h5>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </div>

                {{-- =============================================== --}}
                {{-- GÜNCELLENEN GRAFİK KARTI (Her Zaman Sankey) --}}
                {{-- =============================================== --}}
                <div class="card create-shipment-card">
                    {{-- YENİ: Dinamik Grafik Başlığı --}}
                    <div class="card-header">
                        📊 {{ $chartTitle ?? 'Genel Veri Akışı' }}
                    </div>
                    <div class="card-body">

                        {{-- Artık @if @elseif kontrolü yok --}}
                        <div id="sankey-chart" data-sankey='@json($chartData)'
                            style="width: 100%; height: 500px;">
                            <p class="text-center text-muted p-5">Grafik yükleniyor...</p>
                        </div>

                    </div>
                </div>
                {{-- =============================================== --}}
                {{-- GÜNCELLENEN GRAFİK KARTI BİTİŞİ --}}
                {{-- =============================================== --}}
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM yüklendi, Sankey grafiği hazırlanıyor...');

            const sankeyChartEl = document.getElementById('sankey-chart');

            if (!sankeyChartEl) {
                console.error('sankey-chart elementi bulunamadı!');
                return;
            }

            console.log('sankey-chart elementi bulundu');

            // Veriyi al
            let sankeyData = [];
            try {
                const dataAttr = sankeyChartEl.dataset.sankey;
                console.log('Dataset sankey:', dataAttr);

                if (!dataAttr) {
                    console.error('data-sankey attribute bulunamadı!');
                    sankeyChartEl.innerHTML =
                        '<p class="text-center text-danger p-5">Veri yüklenemedi (attribute eksik)</p>';
                    return;
                }

                sankeyData = JSON.parse(dataAttr);
                console.log('Parse edilen Sankey verisi:', sankeyData);

                if (!Array.isArray(sankeyData) || sankeyData.length === 0) {
                    console.warn('Sankey verisi boş veya geçersiz');
                    sankeyChartEl.innerHTML =
                        '<p class="text-center text-muted p-5">Grafik için yeterli veri bulunamadı.</p>';
                    return;
                }

                console.log('Veri geçerli, Google Charts yükleniyor...');

            } catch (error) {
                console.error('JSON parse hatası:', error);
                sankeyChartEl.innerHTML = '<p class="text-center text-danger p-5">Veri parse hatası: ' + error
                    .message + '</p>';
                return;
            }

            // Google Charts'ı yükle
            google.charts.load('current', {
                packages: ['sankey']
            });

            google.charts.setOnLoadCallback(function() {
                console.log('Google Charts yüklendi, grafik çiziliyor...');
                drawChart(sankeyData, sankeyChartEl);
            });

            function drawChart(sankeyData, chartElement) {
                try {
                    console.log('drawChart fonksiyonu çalışıyor');

                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Kaynak');
                    data.addColumn('string', 'Hedef');
                    data.addColumn('number', 'Değer');
                    data.addColumn({
                        type: 'string',
                        role: 'tooltip'
                    });

                    const chart = new google.visualization.Sankey(chartElement);

                    const options = {
                        width: '100%',
                        height: 500,
                        sankey: {
                            node: {
                                label: {
                                    fontName: 'Arial',
                                    fontSize: 14,
                                    color: '#000'
                                },
                                interactivity: true,
                                nodePadding: 20
                            },
                            link: {
                                colorMode: 'gradient',
                                colors: ['#667EEA', '#F093FB', '#4FD1C5', '#FBD38D', '#FC8181'],
                                highlightOnMouseOver: false
                            }
                        }
                    };

                    let steps = 40;
                    let currentStep = 0;

                    const initialData = sankeyData.map(row => {
                        const from = row[0];
                        const to = row[1];
                        const finalValue = row[2];
                        const tooltipText = `${from} -> ${to}\nDeğer: ${finalValue}`;
                        return [from, to, 0, tooltipText];
                    });

                    data.addRows(initialData);
                    console.log('Başlangıç verisi eklendi, ilk çizim yapılıyor...');

                    function animateFlow() {
                        sankeyData.forEach((row, i) => {
                            const progress = currentStep / steps;
                            const wave = 0.1 * Math.sin(progress * Math.PI * 2 + i);
                            const finalValue = row[2];
                            const value = finalValue * Math.max(0, progress + wave * 0.2);
                            data.setValue(i, 2, Math.max(0, value));
                        });

                        chart.draw(data, options);

                        if (currentStep < steps) {
                            currentStep++;
                            requestAnimationFrame(animateFlow);
                        } else {
                            console.log('Animasyon tamamlandı');
                            setTimeout(addGlowEffect, 400);
                        }
                    }

                    function addGlowEffect() {
                        const svg = chartElement.querySelector('svg');
                        if (!svg) {
                            console.warn('SVG elementi bulunamadı, glow efekti eklenemedi');
                            return;
                        }

                        if (!svg.querySelector('#glow')) {
                            const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
                            const filter = document.createElementNS('http://www.w3.org/2000/svg', 'filter');
                            filter.setAttribute('id', 'glow');
                            filter.innerHTML =
                                `<feGaussianBlur stdDeviation="4" result="coloredBlur"></feGaussianBlur><feMerge><feMergeNode in="coloredBlur"></feMergeNode><feMergeNode in="SourceGraphic"></feMergeNode></feMerge>`;
                            defs.appendChild(filter);
                            svg.prepend(defs);
                        }

                        if (!document.getElementById('flowGlowStyle')) {
                            const style = document.createElement('style');
                            style.id = 'flowGlowStyle';
                            style.textContent = `
                                @keyframes flowGlow { 
                                    0%, 100% { opacity: 0.9; stroke: #33a02c; } 
                                    25% { opacity: 1; stroke: #1f78b4; } 
                                    50% { opacity: 0.9; stroke: #e31a1c; } 
                                    75% { opacity: 1; stroke: #ff7f00; } 
                                }
                                #sankey-chart svg path { 
                                    filter: url(#glow); 
                                    stroke-width: 1.5px; 
                                    transition: stroke 2s ease-in-out; 
                                    animation-name: flowGlow; 
                                    animation-timing-function: ease-in-out; 
                                    animation-iteration-count: infinite; 
                                }
                                #sankey-chart svg path:nth-child(3n+1) { animation-duration: 4s; }
                                #sankey-chart svg path:nth-child(3n+2) { animation-duration: 5s; }
                                #sankey-chart svg path:nth-child(3n) { animation-duration: 3s; }
                            `;
                            document.head.appendChild(style);
                        }
                        console.log('Glow efekti eklendi');
                    }

                    // İlk çizim
                    chart.draw(data, options);
                    console.log('İlk çizim başarılı, animasyon başlatılıyor...');

                    // Animasyonu başlat
                    setTimeout(animateFlow, 100);

                } catch (error) {
                    console.error('Grafik çizim hatası:', error);
                    chartElement.innerHTML = '<p class="text-center text-danger p-5">Grafik çizim hatası: ' + error
                        .message + '</p>';
                }
            }
        });
    </script>
@endsection
