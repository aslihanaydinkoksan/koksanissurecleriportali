@extends('layouts.app')

@section('title', 'Raporları Listele')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow border-0 report-card">
                    <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold"><i class="bi bi-list-stars me-2"></i>Rapor Planları</h3>
                            <small class="opacity-75">Aktif otomasyon süreçlerini buradan yönetebilirsiniz.</small>
                        </div>
                        <a href="{{ route('report-settings.create') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-lg me-1"></i> Yeni Plan Oluştur
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary small uppercase">
                                    <tr>
                                        <th class="ps-4">Rapor / Modül</th>
                                        <th>Gönderim Sıklığı</th>
                                        <th>Veri Kapsamı</th>
                                        <th>Alıcılar</th>
                                        <th>Son Gönderim</th>
                                        <th>Durum</th>
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
                                                        class="bi bi-box me-1"></i>{{ basename(str_replace('\\', '/', $report->report_class)) }}
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-soft-info text-info border border-info rounded-pill px-3">
                                                    {{ ucfirst($report->frequency) }}
                                                </span>
                                                <div class="small mt-1 text-secondary">
                                                    <i class="bi bi-alarm me-1"></i>{{ $report->send_time }}
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $filterLabel = match ($report->filter_frequency) {
                                                        'daily' => ['label' => 'Günlük', 'color' => 'bg-secondary'],
                                                        'weekly' => ['label' => 'Haftalık', 'color' => 'bg-secondary'],
                                                        'monthly' => ['label' => 'Aylık', 'color' => 'bg-primary'],
                                                        'last_3_months' => [
                                                            'label' => 'Son 3 Ay',
                                                            'color' => 'bg-warning text-dark',
                                                        ],
                                                        'last_6_months' => [
                                                            'label' => 'Son 6 Ay',
                                                            'color' => 'bg-orange text-white',
                                                        ],
                                                        'yearly' => ['label' => 'Yıllık', 'color' => 'bg-danger'],
                                                        'minute' => [
                                                            'label' => '2 Dakika (Test)',
                                                            'color' => 'bg-dark',
                                                        ],
                                                        default => [
                                                            'label' => 'Belirtilmedi',
                                                            'color' => 'bg-light text-muted',
                                                        ],
                                                    };
                                                @endphp
                                                <span class="badge {{ $filterLabel['color'] }} rounded-pill px-3">
                                                    <i class="bi bi-calendar3 me-1"></i> {{ $filterLabel['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @php
                                                        $recipients = is_array($report->recipients)
                                                            ? $report->recipients
                                                            : explode(',', $report->recipients);
                                                    @endphp
                                                    @foreach (array_slice($recipients, 0, 2) as $email)
                                                        <span
                                                            class="badge border text-dark fw-normal small bg-light">{{ $email }}</span>
                                                    @endforeach
                                                    @if (count($recipients) > 2)
                                                        <span
                                                            class="badge border text-primary fw-normal small bg-white">+{{ count($recipients) - 2 }}
                                                            kişi</span>
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
                                                            onchange="this.form.submit()">
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end align-items-center gap-2">
                                                    <a href="{{ route('report-settings.edit', $report) }}"
                                                        class="btn btn-sm btn-outline-primary border-0" title="Düzenle">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form action="{{ route('report-settings.destroy', $report) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bu planı silmek istediğinize emin misiniz?')"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-danger border-0" title="Sil">
                                                            <i class="bi bi-trash3"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted small">
                                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                                Henüz planlanmış bir rapor bulunmuyor.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @if ($reports->hasPages())
                        <div class="card-footer bg-white p-3">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            border-radius: 1.5rem;
            overflow: hidden;
        }

        .bg-soft-info {
            background-color: rgba(13, 202, 240, 0.1);
        }

        .bg-orange {
            background-color: #fd7e14;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: 0;
        }

        .table tbody td {
            border-bottom: 1px solid #f2f2f2;
            padding-top: 1.2rem;
            padding-bottom: 1.2rem;
        }

        .badge {
            font-weight: 500;
        }
    </style>
@endsection
