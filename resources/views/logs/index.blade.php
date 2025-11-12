@extends('layouts.app')

@section('title', 'Sistem Aktivite Logları')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card shadow-sm">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">Sistem Aktivite Logları</h4>
                        <small>Projedeki son 50 aktivite gösteriliyor.</small>
                    </div>
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kim (Causer)</th>
                                        <th>Ne Yaptı (Description)</th>
                                        <th>Neyi Etkiledi (Subject)</th>
                                        <th>Zaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activities as $activity)
                                        <tr>
                                            <td>
                                                {{-- Logu tetikleyen kullanıcı (silinmiş olabilir, o yüzden '??') --}}
                                                {{ $activity->causer->name ?? 'Sistem/Silinmiş Kullanıcı' }}
                                            </td>
                                            <td>
                                                {{-- Bizim Trait'te yazdığımız mesaj (Örn: "yeni bir seyahat planı oluşturdu.") --}}
                                                <strong>{{ $activity->description }}</strong>
                                            </td>
                                            <td>
                                                {{-- Etkilenen model (Örn: "Travel") ve ID'si --}}
                                                @if ($activity->subject)
                                                    {{ $activity->subject_type }} (ID: {{ $activity->subject_id }})
                                                @else
                                                    {{-- Örn: "Kullanıcı girişi" logu bir modeli etkilemez --}}
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Ne zaman yapıldığı --}}
                                                {{ $activity->created_at->tz('Europe/Istanbul')->format('d/m/Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Görüntülenecek hiç log bulunamadı.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Sayfalama Linkleri --}}
                        <div class="mt-3 d-flex justify-content-center">
                            {{ $activities->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
