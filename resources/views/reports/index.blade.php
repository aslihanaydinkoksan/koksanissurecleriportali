@extends('layouts.master')

@section('title', 'Raporlar')

@section('content')

    <x-page-layout title="Konaklama Raporları" :count="$stays->total()" create-label="">
        <x-slot:filters>
            <button onclick="window.print()" class="btn btn-sm btn-outline-dark no-print">
                <i class="fa fa-print me-1"></i> PDF Rapor Al
            </button>
        </x-slot:filters>

        {{-- 1. ANALİZ ÖZET KARTLARI --}}
        <div class="col-12 mb-4 no-print">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-primary border-4">
                        <div class="small text-muted fw-bold">Toplam Kapasite</div>
                        <div class="h3 fw-bold mb-0">{{ $totalUnits }} <span class="small fw-normal">Birim</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-danger border-4">
                        <div class="small text-muted fw-bold">Dolu Birim</div>
                        <div class="h3 fw-bold mb-0">{{ $occupiedCount }} <span
                                class="small fw-normal">({{ $totalUnits > 0 ? round(($occupiedCount / $totalUnits) * 100) : 0 }}%)</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-success border-4">
                        <div class="small text-muted fw-bold">Aktif Misafir</div>
                        <div class="h3 fw-bold mb-0">{{ $activeGuests }} <span class="small fw-normal">Kişi</span></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white border-start border-info border-4">
                        <div class="small text-muted fw-bold">Giriş Kaydı (Toplam)</div>
                        <div class="h3 fw-bold mb-0">{{ $stays->total() }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. FİLTRELER --}}
        <div class="col-12 no-print mb-4">
            <div class="card border-0 shadow-sm rounded-4 bg-primary bg-opacity-10">
                <div class="card-body p-4">
                    <form action="{{ route('reports.index') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-primary small">Hızlı Arama</label>
                                <input type="text" name="search" class="form-control border-0 shadow-sm rounded-3"
                                    placeholder="Ad Soyad veya Oda..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold text-primary small">Mekan Filtresi</label>
                                <select name="location_id" class="form-select border-0 shadow-sm rounded-3">
                                    <option value="">Tüm Mekanlar</option>
                                    @foreach ($locations as $loc)
                                        <option value="{{ $loc->id }}"
                                            {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary small">Başlangıç</label>
                                <input type="date" name="start_date" class="form-control border-0 shadow-sm rounded-3"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-bold text-primary small">Bitiş</label>
                                <input type="date" name="end_date" class="form-control border-0 shadow-sm rounded-3"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-2 d-flex gap-1">
                                <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm"><i
                                        class="fa fa-filter"></i> Filtrele </button>
                                <a href="{{ route('reports.index') }}" class="btn btn-light w-50 rounded-3 shadow-sm"><i
                                        class="fa fa-sync"></i></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 3. GRAFİKLER --}}
        <div class="col-12 mb-4">
            <div class="row g-4">
                {{-- Grafik 1: Mülkiyet --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 fw-bold pt-4 px-4 text-center">Mülkiyet Durumu</div>
                        <div class="card-body d-flex justify-content-center">
                            <div style="max-height: 250px; width: 100%;">
                                <canvas id="ownershipChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Grafik 2: Hareketlilik (YENİ - BAR CHART) --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 fw-bold pt-4 px-4">Son 7 Günlük Hareketlilik</div>
                        <div class="card-body">
                            {{-- Yüksekliği biraz artırdık --}}
                            <canvas id="movementChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik 3: Konaklama Süresi --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 fw-bold pt-4 px-4">Ortalama Konaklama Süresi</div>
                        <div class="card-body">
                            <canvas id="durationChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Grafik 4: Trend --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 fw-bold pt-4 px-4">Aylık Konaklama Giriş Trendi</div>
                        <div class="card-body" style="height: 300px;"><canvas id="monthlyChart"></canvas></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. TABLO --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Misafir / Personel</th>
                                <th>Konum</th>
                                <th>Giriş / Çıkış</th>
                                <th>Süre</th>
                                <th class="pe-4 text-end">Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stays as $stay)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold">{{ $stay->resident?->first_name }}
                                            {{ $stay->resident?->last_name }}</div>
                                        <div class="small text-muted">{{ $stay->resident?->department }}</div>
                                    </td>
                                    <td><i class="fa fa-map-marker-alt text-danger me-1"></i> {{ $stay->location?->name }}
                                    </td>
                                    <td>
                                        <div class="small text-success">{{ $stay->check_in_date?->format('d.m.Y H:i') }}
                                        </div>
                                        <div class="small text-danger">
                                            {{ $stay->check_out_date ? $stay->check_out_date->format('d.m.Y H:i') : 'Aktif' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border fw-normal">
                                            {{ $stay->check_in_date?->diffForHumans($stay->check_out_date ?? now(), ['syntax' => 1]) }}
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <span
                                            class="badge {{ $stay->check_out_date ? 'bg-secondary' : 'bg-success' }} rounded-pill px-3">
                                            {{ $stay->check_out_date ? 'Ayrıldı' : 'Konaklıyor' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white py-3 no-print">{{ $stays->links() }}</div>
            </div>
        </div>
    </x-page-layout>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Controller verileri
            const ownershipData = @json($ownershipData);
            const movementData = @json($movementData); // Yeni veri
            const durationData = @json($durationBuckets);
            const monthlyData = @json($monthlyData);

            // 1. Mülkiyet (Doughnut)
            new Chart(document.getElementById('ownershipChart'), {
                type: 'doughnut',
                data: {
                    labels: Object.keys(ownershipData),
                    datasets: [{
                        data: Object.values(ownershipData),
                        backgroundColor: ['#36A2EB', '#FF6384']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // 2. Hareketlilik Grafiği (Bar Chart - YENİ)
            new Chart(document.getElementById('movementChart'), {
                type: 'bar',
                data: {
                    labels: movementData.labels,
                    datasets: [{
                            label: 'Giriş Yapan',
                            data: movementData.check_ins,
                            backgroundColor: '#2ecc71', // Yeşil
                            borderRadius: 4
                        },
                        {
                            label: 'Çıkış Yapan',
                            data: movementData.check_outs,
                            backgroundColor: '#e74c3c', // Kırmızı
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Kart içine tam otursun
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            } // Tam sayı
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // 3. Süre Dağılımı (Bar)
            new Chart(document.getElementById('durationChart'), {
                type: 'bar',
                data: {
                    labels: Object.keys(durationData),
                    datasets: [{
                        label: 'Kişi Sayısı',
                        data: Object.values(durationData),
                        backgroundColor: '#9966FF',
                        borderRadius: 5
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            } // Tam sayı
                        }
                    }
                }
            });

            // 4. Trend (Line)
            new Chart(document.getElementById('monthlyChart'), {
                type: 'line',
                data: {
                    labels: Object.keys(monthlyData).map(m => m.split('-').reverse().join('/')),
                    datasets: [{
                        label: 'Yeni Girişler',
                        data: Object.values(monthlyData),
                        borderColor: '#2ecc71',
                        fill: true,
                        backgroundColor: 'rgba(46, 204, 113, 0.1)',
                        tension: 0.3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            } // Tam sayı
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
