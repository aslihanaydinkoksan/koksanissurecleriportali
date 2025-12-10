@extends('layouts.master')

@section('title', 'Konaklama Raporları & Dashboard')

@section('content')

    <x-page-layout title="Konaklama Raporları" :count="$stays->total()" create-label="" {{-- Ekleme butonu yok --}}>
        {{-- ÜST BUTONLAR --}}
        <x-slot:filters>
            <button onclick="window.print()" class="btn btn-sm btn-outline-dark no-print">
                <i class="fa fa-print me-1"></i> Yazdır / PDF
            </button>
        </x-slot:filters>

        {{-- 1. FİLTRELEME KARTI (Aynı kalıyor) --}}
        <div class="col-12 no-print mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-primary small">Arama</label>
                                <div class="input-group bg-white rounded-3 shadow-sm border-0">
                                    <span class="input-group-text bg-transparent border-0"><i
                                            class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-0 bg-transparent ps-0"
                                        placeholder="Kişi veya Oda Adı..." value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Yeni: Lokasyon Filtresi --}}
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-primary small">Mekan Filtresi</label>
                                <select name="location_id" class="form-select border-0 shadow-sm rounded-3">
                                    <option value="">Tüm Mekanlar</option>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->id }}"
                                            {{ request('location_id') == $loc->id ? 'selected' : '' }}>
                                            {{ $loc->name }} ({{ ucfirst($loc->type) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tarih Filtresi --}}
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-primary small">Başlangıç Tarihi</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm rounded-3"
                                    value="{{ request('start_date') }}">
                            </div>

                            <div class="col-md-3 d-flex gap-2">
                                <div class="w-50">
                                    <label class="form-label fw-bold text-primary small">Bitiş Tarihi</label>
                                    <input type="date" name="end_date" class="form-control border-0 shadow-sm rounded-3"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="w-50 d-flex gap-2 align-self-end">
                                    <button type="submit" class="btn btn-primary shadow-sm flex-grow-1 rounded-3">
                                        <i class="fa fa-filter me-1"></i> Filtrele
                                    </button>
                                    <a href="{{ route('reports.index') }}"
                                        class="btn btn-light shadow-sm text-secondary rounded-3" title="Temizle">
                                        <i class="fa fa-sync-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. GRAFİK KARTLARI (DASHBOARD) --}}
        <div class="col-12 mb-4">
            <div class="row g-4">

                {{-- Grafik 1: Mülkiyet Durumuna Göre Dağılım --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom-0 fw-bold pt-4 pb-0 px-4">
                            <i class="fa fa-chart-pie me-2 text-info"></i> Mülkiyet Durumu (Daireler)
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <div style="max-width: 300px;">
                                <canvas id="ownershipChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Grafik 2: Mekân Tipi Dağılımı --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom-0 fw-bold pt-4 pb-0 px-4">
                            <i class="fa fa-chart-bar me-2 text-warning"></i> Konaklama Yoğunluğu (Mekan Tipi)
                        </div>
                        <div class="card-body">
                            <canvas id="typeChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik 3: Aylık Konaklama Trendi --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-bottom-0 fw-bold pt-4 pb-0 px-4">
                            <i class="fa fa-chart-line me-2 text-success"></i> Aylık Konaklama Trendi (Giriş)
                        </div>
                        <div class="card-body">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="fw-bold mb-3 text-dark">Detaylı Geçmiş Tablosu</h5>
        </div>

        {{-- 4. SONUÇ TABLOSU (Aynı kalıyor) --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary small fw-bold border-0 text-uppercase">Personel /
                                    Misafir</th>
                                <th class="py-3 text-secondary small fw-bold border-0 text-uppercase">Konaklanan Yer</th>
                                <th class="py-3 text-secondary small fw-bold border-0 text-uppercase">Giriş / Çıkış</th>
                                <th class="py-3 text-secondary small fw-bold border-0 text-uppercase">Süre</th>
                                <th class="pe-4 py-3 text-end text-secondary small fw-bold border-0 text-uppercase">Durum
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stays as $stay)
                                <tr>
                                    {{-- Personel Bilgisi --}}
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-secondary border d-none d-md-flex"
                                                style="width: 40px; height: 40px;">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">
                                                    {{ $stay->resident?->first_name ?? 'Silinmiş' }}
                                                    {{ $stay->resident?->last_name ?? 'Personel' }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $stay->resident?->department ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Konum Bilgisi --}}
                                    <td>
                                        @if ($stay->location)
                                            <a href="{{ route('locations.show', $stay->location_id) }}"
                                                class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                                <i class="fa fa-map-marker-alt text-danger opacity-75"></i>
                                                <span class="fw-medium">{{ $stay->location->name }}</span>
                                            </a>
                                        @else
                                            <div class="text-muted small fst-italic">
                                                <i class="fa fa-ban me-1"></i> Mekan Silinmiş
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Tarihler --}}
                                    <td>
                                        <div class="d-flex flex-column small">
                                            <div class="text-success">
                                                <i class="fa fa-sign-in-alt me-1 w-15"></i>
                                                {{ $stay->check_in_date ? $stay->check_in_date->format('d.m.Y H:i') : '-' }}
                                            </div>
                                            <div class="text-danger mt-1">
                                                <i class="fa fa-sign-out-alt me-1 w-15"></i>
                                                @if ($stay->check_out_date)
                                                    {{ $stay->check_out_date->format('d.m.Y H:i') }}
                                                @else
                                                    <span class="text-muted fst-italic">Hala Konaklıyor</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Süre --}}
                                    <td>
                                        <div class="badge bg-light text-secondary border fw-normal">
                                            @if ($stay->check_in_date)
                                                @if ($stay->check_out_date)
                                                    {{ $stay->check_in_date->diffForHumans($stay->check_out_date, ['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE, 'parts' => 2]) }}
                                                @else
                                                    {{ $stay->check_in_date->diffForHumans(now(), ['syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE, 'parts' => 2]) }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Durum --}}
                                    <td class="pe-4 text-end">
                                        @if ($stay->check_out_date)
                                            <span
                                                class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">
                                                Tamamlandı
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">
                                                Aktif
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3"
                                                style="width: 60px; height: 60px;">
                                                <i class="fa fa-filter text-secondary opacity-50 fa-lg"></i>
                                            </div>
                                            <h6 class="text-muted fw-bold">Kayıt Bulunamadı</h6>
                                            <p class="text-secondary small mb-0">Seçilen tarih aralığında veya arama
                                                kriterinde kayıt yok.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Alt Pagination --}}
                <div class="card-footer bg-white border-top-0 py-3 no-print">
                    {{ $stays->links() }}
                </div>
            </div>
        </div>

    </x-page-layout>

    @push('scripts')
        {{-- Chart.js Kütüphanesini Ekle (Bu kısım aynı kalıyor) --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

        <script>
            // Controller'dan gelen verileri yakala (JSON encode edildiği varsayılır)
            const ownershipData = @json($ownershipData);
            const typeData = @json($typeData);
            const monthlyData = @json($monthlyData);

            // Rastgele Renk Üretici (Grafik estetiği için)
            function generateColors(count) {
                const colors = [];
                const baseColors = [
                    '#36A2EB', '#FF6384', '#FF9F40', '#4BC0C0', '#9966FF', '#FFCD56', '#C9CBCE'
                ];
                for (let i = 0; i < count; i++) {
                    colors.push(baseColors[i % baseColors.length]);
                }
                return colors;
            }

            // --- GRAFİK 1: Mülkiyet Durumu (Doughnut Chart) ---
            // (Y Ekseni olmadığı için bu kısım değişmiyor)
            new Chart(document.getElementById('ownershipChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(ownershipData),
                    datasets: [{
                        data: Object.values(ownershipData),
                        backgroundColor: generateColors(Object.keys(ownershipData).length),
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: false,
                        }
                    }
                }
            });

            // --- GRAFİK 2: Mekân Tipi Dağılımı (Bar Chart) ---
            new Chart(document.getElementById('typeChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(typeData),
                    datasets: [{
                        label: 'Konaklama Sayısı',
                        data: Object.values(typeData),
                        backgroundColor: generateColors(Object.keys(typeData).length),
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            // !!! KRİTİK DEĞİŞİKLİK: Y Ekseni tam sayıları göstersin
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                },
                                stepSize: 1 // Adım boyutunu 1 olarak ayarla
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false,
                        }
                    }
                }
            });

            // --- GRAFİK 3: Aylık Konaklama Trendi (Line Chart) ---
            const monthlyLabels = Object.keys(monthlyData).map(m => {
                const [year, month] = m.split('-');
                return `${month}/${year.slice(2)}`; // Örn: 12/25
            });

            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Yeni Giriş Sayısı',
                        data: Object.values(monthlyData),
                        borderColor: '#28a745', // Yeşil
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            // !!! KRİTİK DEĞİŞİKLİK: Y Ekseni tam sayıları göstersin
                            ticks: {
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                },
                                stepSize: 1 // Adım boyutunu 1 olarak ayarla
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        </script>
    @endpush

    <style>
        .w-15 {
            width: 15px;
            display: inline-block;
            text-align: center;
        }

        /* Print Stilleri (Aynı kalıyor) */
        @media print {
            .no-print {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }

            .badge {
                border: 1px solid #000;
                color: #000 !important;
            }

            a {
                text-decoration: none !important;
                color: #000 !important;
            }
        }
    </style>

@endsection
