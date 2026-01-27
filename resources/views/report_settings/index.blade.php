@extends('layouts.master')
@section('title', 'Rapor Ayarları')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mt-4">Rapor Yönetimi (Mail Ayarları)</h1>
            <a href="{{ route('report-settings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Yeni Rapor Planla
            </a>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Rapor Adı</th>
                            <th>Tür</th>
                            <th>Sıklık / Kapsam</th>
                            <th>Format</th>
                            <th>Alıcılar</th>
                            <th>Durum</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($settings as $setting)
                            <tr>
                                <td class="fw-bold">{{ $setting->report_name }}</td>
                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ str_contains($setting->report_type, 'Stay') ? 'Konaklama' : 'Demirbaş' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <i class="fas fa-clock text-muted me-1"></i>
                                        @switch($setting->frequency)
                                            @case('every_minute')
                                                Her Dakika (Test)
                                            @break

                                            @case('daily_morning')
                                                Her Sabah (09:00)
                                            @break

                                            @case('daily_evening')
                                                Her Akşam (18:00)
                                            @break

                                            @case('weekly_monday')
                                                Pazartesi Sabahı
                                            @break

                                            @case('monthly_first')
                                                Her Ayın 1'i
                                            @break

                                            @default
                                                {{ $setting->frequency }}
                                        @endswitch
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        @switch($setting->data_scope)
                                            @case('last_24h')
                                                Son 24 Saat
                                            @break

                                            @case('last_7d')
                                                Son 7 Gün
                                            @break

                                            @case('last_30d')
                                                Son 30 Gün
                                            @break

                                            @case('last_3m')
                                                Son 3 Ay
                                            @break

                                            @case('last_6m')
                                                Son 6 Ay
                                            @break

                                            @case('last_1y')
                                                Son 1 Yıl
                                            @break

                                            @default
                                                {{ $setting->data_scope }}
                                        @endswitch
                                    </div>
                                </td>
                                <td>
                                    @if ($setting->file_format == 'excel')
                                        <span class="text-success"><i class="fas fa-file-excel"></i> Excel</span>
                                    @else
                                        <span class="text-danger"><i class="fas fa-file-pdf"></i> PDF</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($setting->recipients as $email)
                                        <div class="small text-lowercase">{{ $email }}</div>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $setting->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $setting->is_active ? 'Aktif' : 'Pasif' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('report-settings.edit', $setting->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="Düzenle">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('report-settings.destroy', $setting->id) }}" method="POST"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger shadow-none"
                                                title="Sil">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.delete-form').on('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Emin misiniz?',
                text: "Bu rapor planı arşive kaldırılacak!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Evet, sil!',
                cancelButtonText: 'İptal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
@endpush
