@extends('layouts.app')

@section('title', 'Sistem Aktivite Logları')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card shadow-sm">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">Sistem Aktivite Logları</h4>
                        <small class="text-muted">Projedeki aktiviteleri görüntüleyin.</small>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Kim (Causer)</th>
                                        <th style="width: 35%;">Ne Yaptı (Description)</th>
                                        <th style="width: 25%;">Neyi Etkiledi (Subject)</th>
                                        <th style="width: 20%;">Zaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $activity->causer->name ?? 'Sistem/Silinmiş Kullanıcı' }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $activity->description }}
                                            </td>
                                            <td>
                                                @if ($activity->subject)
                                                    <code class="text-dark">
                                                        {{ class_basename($activity->subject_type) }} (ID:
                                                        {{ $activity->subject_id }})
                                                    </code>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $activity->created_at->tz('Europe/Istanbul')->format('d/m/Y H:i:s') }}
                                                </small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1"></i>
                                                <p class="mb-0 mt-2">Görüntülenecek hiç log bulunamadı.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Geliştirilmiş Sayfalama --}}
                        @if ($activities->hasPages())
                            <div class="mt-4">
                                <nav aria-label="Sayfa navigasyonu">
                                    <ul class="pagination pagination-rounded justify-content-center mb-0">
                                        {{-- Önceki Sayfa --}}
                                        @if ($activities->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    <i class="bi bi-chevron-left"></i> Önceki
                                                </span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $activities->previousPageUrl() }}">
                                                    <i class="bi bi-chevron-left"></i> Önceki
                                                </a>
                                            </li>
                                        @endif

                                        {{-- Sayfa Numaraları --}}
                                        @foreach ($activities->getUrlRange(1, $activities->lastPage()) as $page => $url)
                                            @if ($page == $activities->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Sonraki Sayfa --}}
                                        @if ($activities->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $activities->nextPageUrl() }}">
                                                    Sonraki <i class="bi bi-chevron-right"></i>
                                                </a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">
                                                    Sonraki <i class="bi bi-chevron-right"></i>
                                                </span>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>

                                {{-- Sayfa Bilgisi --}}
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Toplam {{ $activities->total() }} kayıttan
                                        {{ $activities->firstItem() }}-{{ $activities->lastItem() }} arası gösteriliyor
                                    </small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .pagination-rounded .page-link {
            border-radius: 0.375rem;
            margin: 0 3px;
            border: 1px solid #dee2e6;
            color: #4a5568;
            padding: 0.5rem 0.75rem;
            transition: all 0.2s ease;
        }

        .pagination-rounded .page-link:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            transform: translateY(-1px);
        }

        .pagination-rounded .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            font-weight: 600;
        }

        .pagination-rounded .page-item.disabled .page-link {
            color: #adb5bd;
            background-color: white;
            cursor: not-allowed;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
        }
    </style>
@endpush
