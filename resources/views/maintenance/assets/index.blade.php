@extends('layouts.app')
@section('title', 'Makine ve Varlık Yönetimi')

@push('styles')
    <style>
        /* --- ANA TASARIM (Diğer sayfalarla ortak) --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

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

        .modern-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Başlık Alanı */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .page-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Liste Kartı */
        .list-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .list-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 700;
            font-size: 1.3rem;
            border: none;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* Tablo Stilleri */
        .table {
            background-color: transparent;
            margin-bottom: 0;
        }

        .table thead {
            background: rgba(102, 126, 234, 0.05);
        }

        .table thead th {
            color: #495057;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            padding: 1.25rem 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.08) !important;
            transform: scale(1.005);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            z-index: 10;
            position: relative;
        }

        .table td {
            vertical-align: middle;
            padding: 1.25rem 1rem;
            color: #495057;
            font-size: 0.95rem;
        }

        /* Butonlar */
        .btn-modern-add {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-modern-add:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            font-size: 0.85rem;
            margin: 0 0.2rem;
        }

        .btn-action:hover {
            transform: translateY(-3px);
        }

        .btn-action.btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
        }

        /* Animasyon */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid modern-container">

        {{-- Başlık Alanı --}}
        <div class="page-header d-flex justify-content-between align-items-center">
            <div>
                <h1><i class="fas fa-industry"></i> Makine ve Varlıklar</h1>
                <p>İşletmenize ait makine parkuru, bölgeler ve tesisatların yönetimi.</p>
            </div>
            <div>
                <a href="{{ route('maintenance.assets.create') }}" class="btn btn-modern-add">
                    <i class="fas fa-plus me-2"></i> Yeni Makine/Varlık Ekle
                </a>
            </div>
        </div>

        {{-- Mesajlar --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4"
                style="background: rgba(40, 199, 111, 0.15); color: #1e7e34;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Liste Kartı --}}
        <div class="card list-card">
            <div class="card-header">
                <div>
                    <i class="fas fa-list me-2"></i> Varlık Listesi
                </div>
                <span class="badge bg-white text-primary rounded-pill fs-6 shadow-sm">{{ $assets->count() }} Kayıt</span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Kod</th>
                                <th>Kategori</th>
                                <th>Marka / Model</th>
                                <th>Varlık Adı</th>
                                <th>Lokasyon</th>
                                <th>Durum</th>
                                <th class="text-end pe-4">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assets as $asset)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold text-primary" style="font-family: monospace; font-size: 1rem;">
                                            {{ $asset->code ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($asset->category == 'machine')
                                            <span
                                                class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">
                                                <i class="fas fa-cogs me-1"></i> Makine
                                            </span>
                                        @elseif($asset->category == 'zone')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3">
                                                <i class="fas fa-map-marked-alt me-1"></i> Bölge
                                            </span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">
                                                <i class="fas fa-wrench me-1"></i> Tesisat
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($asset->brand)
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-dark">{{ $asset->brand }}</span>
                                                <small class="text-muted">{{ $asset->model }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark">{{ $asset->name }}</strong>
                                    </td>
                                    <td>
                                        @if ($asset->location)
                                            <i class="fas fa-map-pin text-danger me-1 opacity-50"></i>
                                            {{ $asset->location }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($asset->is_active)
                                            <span class="badge bg-success rounded-pill">
                                                <i class="fas fa-check me-1"></i> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger rounded-pill">
                                                <i class="fas fa-times me-1"></i> Pasif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('maintenance.assets.edit', $asset->id) }}"
                                            class="btn btn-action btn-primary" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('maintenance.assets.destroy', $asset->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bu varlığı silmek istediğinize emin misiniz?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-action btn-danger" title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center opacity-50">
                                            <i class="fas fa-box-open fa-3x mb-3 text-secondary"></i>
                                            <p class="mb-0 text-muted fw-bold">Kayıtlı makine veya varlık bulunamadı.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tablo animasyonu
            const tableRows = document.querySelectorAll('tbody tr');
            tableRows.forEach((row, index) => {
                row.style.animation = `slideDown 0.3s ease ${index * 0.05}s forwards`;
                row.style.opacity = '0';
            });

            // Alert otomatik kapanma
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
@endsection
