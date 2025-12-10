@extends('layouts.master')
@section('title', 'Sistem Logları')
@section('content')
    <div class="container mt-4">
        <h3><i class="fa fa-list-alt text-secondary"></i> İşlem Geçmişi (Loglar)</h3>
        <div class="card shadow-sm mt-3">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0" style="font-size: 0.9rem;">
                    <thead>
                        <tr>
                            <th>Kullanıcı</th>
                            <th>İşlem</th>
                            <th>Modül</th>
                            <th>Detay</th>
                            <th>IP</th>
                            <th>Zaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td class="fw-bold">{{ $log->user->name ?? 'Sistem' }}</td>
                                <td>
                                    @if ($log->action == 'create')
                                        <span class="badge bg-success">Oluşturma</span>
                                    @elseif($log->action == 'update')
                                        <span class="badge bg-warning text-dark">Güncelleme</span>
                                    @elseif($log->action == 'delete')
                                        <span class="badge bg-danger">Silme</span>
                                    @else
                                        {{ $log->action }}
                                    @endif
                                </td>
                                <td>
                                    {{ class_basename($log->loggable_type) }}
                                    <small class="text-muted">(ID: {{ $log->loggable_id }})</small>
                                </td>
                                <td>
                                    @if ($log->action == 'update')
                                        <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#log{{ $log->id }}">Değişikliği Gör</button>
                                        <div class="collapse" id="log{{ $log->id }}">
                                            <pre class="small bg-light p-2 border">{{ json_encode($log->new_values, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                        </div>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $log->ip_address }}</td>
                                <td class="small">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection
