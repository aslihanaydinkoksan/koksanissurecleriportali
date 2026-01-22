@extends('layouts.app')
@section('title', 'Finansal Analiz ')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4">💰 Finansal Analiz Dashboard</h2>

        {{-- 1. Özet Kartları --}}
        <div class="row g-3 mb-4">
            @foreach ($data['totals'] as $total)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                        <small class="text-muted fw-bold d-block mb-1">TOPLAM HARCAMA ({{ $total->currency }})</small>
                        <h3 class="fw-bold text-primary mb-0">{{ number_format($total->total, 2) }} {{ $total->currency }}
                        </h3>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">
            {{-- 2. Kategori Dağılımı --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-pie me-2 text-warning"></i>Kategori Dağılımı</h5>
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            {{-- 3. Modül Dağılımı --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-bar me-2 text-success"></i>Modül Karşılaştırması
                    </h5>
                    <canvas id="moduleChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Kategori Grafiği (Filtrelenmiş Veri - Şimdilik TRY bazlı varsayalım veya döviz seçtirilebilir)
        const ctxCat = document.getElementById('categoryChart');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($data['byCategory']->pluck('category')) !!},
                datasets: [{
                    data: {!! json_encode($data['byCategory']->pluck('total')) !!},
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b']
                }]
            }
        });

        // Modül Grafiği (Travel vs Event)
        const ctxMod = document.getElementById('moduleChart');
        new Chart(ctxMod, {
            type: 'bar',
            data: {
                labels: ['Seyahatler', 'Etkinlikler/Fuarlar'],
                datasets: [{
                    label: 'Harcama Tutarı',
                    data: [
                        {{ $data['byModule']->where('expensable_type', 'App\Models\Travel')->sum('total') }},
                        {{ $data['byModule']->where('expensable_type', 'App\Models\Event')->sum('total') }}
                    ],
                    backgroundColor: '#667EEA'
                }]
            }
        });
    </script>
@endsection
