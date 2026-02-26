@extends('layouts.app')

@section('title', 'Sistem Aktivite Logları')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 text-primary"><i class="bi bi-activity me-2"></i>Sistem Aktivite Logları</h5>
                            <small class="text-muted">Kullanıcı hareketleri ve veri değişiklikleri.</small>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 15%;">Tarih</th>
                                        <th style="width: 15%;">Kullanıcı</th>
                                        <th style="width: 10%;">İşlem Türü</th>
                                        <th style="width: 20%;">Modül / Kayıt</th>
                                        <th style="width: 25%;">Açıklama</th>
                                        <th style="width: 10%;">Detay</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>{{ $loop->iteration + $activities->firstItem() - 1 }}</td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span
                                                        class="fw-bold">{{ $activity->created_at->format('d.m.Y') }}</span>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($activity->causer)
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-xs me-2 bg-primary text-white rounded-circle d-flex justify-content-center align-items-center"
                                                            style="width:25px; height:25px;">
                                                            {{ substr($activity->causer->name, 0, 1) }}
                                                        </div>
                                                        <span>{{ $activity->causer->name }}</span>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary">Sistem</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $badgeColor = match ($activity->event) {
                                                        'created' => 'success',
                                                        'updated' => 'warning',
                                                        'deleted' => 'danger',
                                                        'restored' => 'info',
                                                        default => 'secondary',
                                                    };
                                                    $eventText = match ($activity->event) {
                                                        'created' => 'Oluşturma',
                                                        'updated' => 'Güncelleme',
                                                        'deleted' => 'Silme',
                                                        'restored' => 'Geri Yükleme',
                                                        default => 'Erişim/Diğer',
                                                    };
                                                @endphp
                                                <span class="badge bg-soft-{{ $badgeColor }} text-{{ $badgeColor }}">
                                                    {{ $eventText }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($activity->subject_type)
                                                    <span class="fw-bold text-dark">
                                                        {{ class_basename($activity->subject_type) }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">ID: {{ $activity->subject_id }}</small>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $activity->description }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-light border" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#logDetail{{ $activity->id }}" aria-expanded="false">
                                                    <i class="bi bi-eye"></i> İncele
                                                </button>
                                            </td>
                                        </tr>
                                        {{-- Detay Satırı --}}
                                        <tr>
                                            <td colspan="7" class="p-0 border-0">
                                                <div class="collapse bg-light" id="logDetail{{ $activity->id }}">
                                                    <div class="p-3">
                                                        @if (isset($activity->properties['old']) || isset($activity->properties['attributes']))
                                                            <h6 class="fw-bold text-primary mb-2">Değişiklik Detayları</h6>
                                                            <table class="table table-bordered table-sm bg-white mb-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 30%">Alan (Sütun)</th>
                                                                        <th style="width: 35%" class="text-danger">Eski
                                                                            Değer</th>
                                                                        <th style="width: 35%" class="text-success">Yeni
                                                                            Değer</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $attributes =
                                                                            $activity->properties['attributes'] ?? [];
                                                                        $old = $activity->properties['old'] ?? [];
                                                                        $keys = array_unique(
                                                                            array_merge(
                                                                                array_keys($attributes),
                                                                                array_keys($old),
                                                                            ),
                                                                        );
                                                                    @endphp

                                                                    @foreach ($keys as $key)
                                                                        <tr>
                                                                            <td class="fw-bold text-uppercase text-muted"
                                                                                style="font-size: 0.8rem;">
                                                                                {{ str_replace('_', ' ', $key) }}
                                                                            </td>

                                                                            {{-- ESKİ DEĞER HÜCRESİ --}}
                                                                            <td class="text-break bg-soft-danger">
                                                                                @php
                                                                                    $oldVal = $old[$key] ?? null;
                                                                                @endphp

                                                                                @if (is_array($oldVal) || is_object($oldVal))
                                                                                    <pre class="mb-0" style="font-size: 0.75rem;">{{ json_encode($oldVal, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                                                @else
                                                                                    {{ $oldVal ?? '-' }}
                                                                                @endif
                                                                            </td>

                                                                            {{-- YENİ DEĞER HÜCRESİ --}}
                                                                            <td class="text-break bg-soft-success">
                                                                                @php
                                                                                    $newVal = $attributes[$key] ?? null;
                                                                                @endphp

                                                                                @if (is_array($newVal) || is_object($newVal))
                                                                                    <pre class="mb-0" style="font-size: 0.75rem;">{{ json_encode($newVal, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                                                                @else
                                                                                    {{ $newVal ?? '-' }}
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @elseif(isset($activity->properties['ip']))
                                                            <div class="row g-2">
                                                                <div class="col-md-3"><strong>IP Adresi:</strong>
                                                                    {{ $activity->properties['ip'] }}</div>
                                                                <div class="col-md-3"><strong>Tarayıcı:</strong>
                                                                    {{ $activity->properties['agent'] }}</div>
                                                                <div class="col-md-3"><strong>URL:</strong>
                                                                    {{ $activity->properties['url'] }}</div>
                                                                <div class="col-md-3"><strong>Method:</strong>
                                                                    {{ $activity->properties['method'] }}</div>
                                                            </div>
                                                        @else
                                                            <p class="text-muted mb-0">Ek veri bulunamadı.</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5 text-muted">
                                                <i class="bi bi-clipboard-x fs-1"></i>
                                                <p class="mt-2">Henüz kayıtlı bir aktivite yok.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
