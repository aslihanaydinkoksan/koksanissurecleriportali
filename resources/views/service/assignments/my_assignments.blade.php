@extends('layouts.app')

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, hsl(270, 37%, 46%), #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        /* Arka plan animasyonu */
        @keyframes gradientShift {
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

        /* Yüzen parçacık efekti */
        #app>main.py-4::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.06) 0%, transparent 50%);
            animation: floatingParticles 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floatingParticles {

            0%,
            100% {
                transform: translate(0, 0);
            }

            33% {
                transform: translate(30px, -30px);
            }

            66% {
                transform: translate(-20px, 20px);
            }
        }

        /* Modern Glassmorphism Kart */
        .list-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.4);
            overflow: hidden;
            animation: cardSlideIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Kart Başlığı */
        .list-card .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            color: #1a202c;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
            text-shadow: none;
        }

        /* Modern Butonlar (index'ten alındı) */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.5);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        /* Tablo Stilleri (index'ten alındı) */
        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead th {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            color: #1a202c;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
            padding: 1.25rem 1rem;
            text-shadow: none;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.85);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:nth-of-type(even) {
            background: rgba(255, 255, 255, 0.95);
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table td {
            color: #1a202c;
            font-weight: 500;
            padding: 1rem;
            vertical-align: middle;
            text-shadow: none;
        }

        .table td.fw-bold {
            font-weight: 700;
            color: #1a202c;
        }

        /* İşlem Butonları (index'ten alındı) */
        .btn-sm {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .table .btn-sm.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .table .btn-sm.btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
        }

        /* Alert Stilleri (index'ten alındı) */
        .alert {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            color: #1a202c;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: alertSlideIn 0.4s ease-out;
        }

        @keyframes alertSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(251, 200, 212, 0.3), rgba(255, 195, 113, 0.3));
            border-color: rgba(255, 195, 113, 0.5);
            color: #7c4a03;
        }

        /* Sayfalama (index'ten alındı) */
        .pagination {
            margin-top: 1.5rem;
        }

        .page-link {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: #fff;
            font-weight: 600;
            border-radius: 8px;
            margin: 0 4px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #fff;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        /* Responsive iyileştirmeler */
        @media (max-width: 768px) {
            .list-card .card-header {
                font-size: 1.25rem;
                padding: 1.25rem 1.5rem;
            }

            .table-responsive {
                border-radius: 0 0 24px 24px;
            }
        }
    </style>
@endpush

@section('title', 'Atanmış Görevlerim')

@section('content')
    {{-- Arka plan animasyonu için container-fluid kullanıyoruz --}}
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">

                {{-- Modern 'list-card' yapısını burada kullanıyoruz --}}
                <div class="card list-card">
                    <div class="card-header">
                        {{-- Başlık ve ikon --}}
                        <i class="fas fa-user-check me-2"></i> @yield('title')
                    </div>

                    <div class="card-body p-0">
                        @if ($assignments->isEmpty())
                            {{-- Modern alert stilini uyguluyoruz --}}
                            <div class="alert alert-warning m-3" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i> Size atanmış aktif görev bulunmamaktadır.
                            </div>
                        @else
                            {{-- Modern tablo stilini uyguluyoruz --}}
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            {{-- Başlıklara ikonlar ve modern stil için gerekli class'lar eklendi --}}
                                            <th scope="col" class="ps-3">
                                                <i class="fas fa-tasks me-2"></i>Görev Başlığı
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-car me-2"></i>Araç
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-users me-2"></i>Takımım
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-clock me-2"></i>Başlangıç Zamanı
                                            </th>
                                            <th scope="col">
                                                <i class="fas fa-info-circle me-2"></i>Durum
                                            </th>
                                            <th scope="col" class="text-end pe-3">
                                                <i class="fas fa-cog me-2"></i>Aksiyon
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                {{-- İlk hücreye padding eklendi --}}
                                                <td class="ps-3 fw-bold">{{ $assignment->title }}</td>
                                                <td>{{ $assignment->vehicle->plate_number ?? 'Yok' }}</td>
                                                <td>
                                                    @if ($assignment->responsible_type === App\Models\Team::class)
                                                        @php
                                                            // Takım üyelerinin isimlerini virgülle ayırarak göster
                                                            $members = $assignment->responsible->users
                                                                ->pluck('name')
                                                                ->implode(', ');
                                                        @endphp
                                                        {{ $members }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>{{ $assignment->start_time->format('d.m.Y H:i') }}</td>
                                                <td>{{ ucfirst($assignment->status) }}</td>
                                                {{-- Butona modern stil (btn-primary) ve ikon uygulandı --}}
                                                <td class="text-end pe-3">
                                                    <a href="{{ route('service.assignments.show', $assignment) }}"
                                                        class="btn btn-sm btn-primary" title="Detay">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    {{-- Görevi Tamamla veya Güncelleme Butonları eklenebilir --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Modern sayfalama stilini uyguluyoruz --}}
                                @if ($assignments->hasPages())
                                    <div class="card-footer bg-transparent border-top-0 pt-3 pb-2 px-3">
                                        {{ $assignments->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
