@extends('layouts.app')

@section('title', 'Rapor Planları')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow border-0 report-card">
                    <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold"><i class="bi bi-gear me-2"></i>Otomatik Rapor Planları</h3>
                            <small class="opacity-75">Sistem periyodik olarak bu yapılandırmalara göre rapor üretir.</small>
                        </div>
                        <a href="{{ route('report-settings.create') }}"
                            class="btn btn-light rounded-pill px-4 shadow-sm fw-bold">
                            <i class="bi bi-plus-circle me-2"></i>Yeni Plan Oluştur
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4">Rapor ve Modül</th>
                                        <th>Gönderim / Sıklık</th>
                                        <th>Veri Kapsamı</th>
                                        <th>Alıcılar</th>
                                        <th>Son Durum</th>
                                        <th>Aktif</th>
                                        <th class="text-end pe-4">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reports as $report)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $report->report_name }}</div>
                                                <div class="text-muted small">
                                                    <i
                                                        class="bi bi-collection me-1"></i>{{ basename(str_replace('\\', '/', $report->report_class)) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white rounded-pill px-3">
                                                    {{ ucfirst($report->frequency) }}
                                                </span>
                                                <div class="small mt-1 text-secondary">
                                                    <i class="bi bi-clock me-1"></i>{{ $report->send_time }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $filter = match ($report->filter_frequency) {
                                                        'daily' => ['l' => 'Günlük', 'c' => 'bg-secondary'],
                                                        'weekly' => ['l' => 'Haftalık', 'c' => 'bg-secondary'],
                                                        'monthly' => ['l' => 'Aylık', 'c' => 'bg-primary'],
                                                        'last_3_months' => [
                                                            'l' => 'Son 3 Ay',
                                                            'c' => 'bg-warning text-dark',
                                                        ],
                                                        'last_6_months' => [
                                                            'l' => 'Son 6 Ay',
                                                            'c' => 'bg-warning text-dark',
                                                        ],
                                                        'yearly' => ['l' => 'Yıllık', 'c' => 'bg-danger'],
                                                        'minute' => ['l' => 'Test (2dk)', 'c' => 'bg-dark'],
                                                        default => [
                                                            'l' => 'Belirtilmedi',
                                                            'c' => 'bg-light text-muted',
                                                        ],
                                                    };
                                                @endphp
                                                <span class="badge {{ $filter['c'] }} rounded-pill px-3">
                                                    <i class="bi bi-calendar3 me-1"></i> {{ $filter['l'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @php
                                                        $mails = is_array($report->recipients)
                                                            ? $report->recipients
                                                            : explode(',', $report->recipients);
                                                    @endphp
                                                    <span
                                                        class="badge border text-dark fw-normal bg-light">{{ Str::limit($mails[0], 15) }}</span>
                                                    @if (count($mails) > 1)
                                                        <span
                                                            class="badge border text-primary fw-normal bg-white">+{{ count($mails) - 1 }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="small text-muted">
                                                    {{ $report->last_sent_at ? $report->last_sent_at->format('d.m.Y H:i') : 'Henüz çalışmadı' }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('report-settings.toggle', $report) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                            {{ $report->is_active ? 'checked' : '' }}
                                                            onchange="this.form.submit()" style="cursor: pointer">
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('report-settings.edit', $report) }}"
                                                        class="btn btn-sm btn-primary" title="Düzenle">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('report-settings.destroy', $report) }}"
                                                        method="POST" onsubmit="return confirm('Emin misiniz?')"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Sil">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted small">
                                                Henüz planlanmış bir rapor bulunmuyor.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            border-radius: 1rem;
            overflow: hidden;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 0;
        }

        .table tbody td {
            border-bottom: 1px solid #eee;
            padding: 1rem 0.5rem;
        }
    </style>
@endsection
