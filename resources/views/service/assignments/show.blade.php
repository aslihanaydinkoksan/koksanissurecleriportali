@extends('layouts.app')

@section('title', 'Görev Detayları: ' . $assignment->title)

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">

                {{-- BAŞLIK VE GERİ DÖN BUTONU --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-file-invoice me-2"></i> Görev Detayları
                    </h1>
                    <a href="{{ route('my-assignments.index') }}" class="btn btn-outline-secondary btn-sm">
                        ← Görevlerime Geri Dön
                    </a>
                </div>

                {{-- ANA KART --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white h5">
                        {{ $assignment->title }}
                        <span class="badge bg-light text-dark ms-3">{{ $assignment->getStatusNameAttribute() }}</span>
                    </div>

                    <div class="card-body">

                        {{-- TEMEL GÖREV BİLGİLERİ --}}
                        <div class="row mb-4 border-bottom pb-3">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted fw-bold">Atanan Araç:</p>
                                <p class="lead mb-0">{{ $assignment->vehicle->plate_number ?? 'Araç Yok' }}
                                    ({{ $assignment->vehicle->type ?? 'Genel' }})</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted fw-bold">Sorumlu:</p>
                                @php
                                    // Polymorphic ilişkiyi kontrol et
                                    $responsibleName = $assignment->responsible->name ?? 'Bilinmiyor';
                                    $responsibleType =
                                        $assignment->responsible_type === App\Models\User::class ? 'Kişi' : 'Takım';
                                @endphp
                                <p class="lead mb-0"><i
                                        class="fas fa-{{ $responsibleType === 'Kişi' ? 'user' : 'users' }} me-1"></i>
                                    {{ $responsibleName }} ({{ $responsibleType }})</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted fw-bold">Sefer Zamanı:</p>
                                <p class="lead mb-0">{{ $assignment->start_time->format('d.m.Y H:i') }} -
                                    {{ $assignment->end_time->format('H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted fw-bold">Yer/Hedef:</p>
                                <p class="lead mb-0">{{ $assignment->destination ?? 'Belirtilmedi' }}</p>
                            </div>
                        </div>

                        {{-- AÇIKLAMA VE NOTLAR --}}
                        <h6 class="fw-bold text-primary">Görev Açıklaması:</h6>
                        <p class="border p-3 rounded bg-light">{{ $assignment->task_description }}</p>

                        <h6 class="fw-bold text-primary">Ek Notlar:</h6>
                        <p class="border p-3 rounded bg-light">{{ $assignment->notes ?? 'Yok' }}</p>


                        {{-- NAKLİYE (LOJİSTİK) DETAYLARI --}}
                        @if ($assignment->isLogistics())
                            <h5 class="mt-4 mb-3 fw-bold text-danger">🚚 Nakliye / Lojistik Kayıtları</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Detay</th>
                                            <th>Başlangıç Değeri</th>
                                            <th>Bitiş Değeri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Kilometre (KM)</td>
                                            <td>{{ $assignment->start_km ?? '-' }}</td>
                                            <td>{{ $assignment->end_km ?? 'Bekleniyor' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Yakıt Durumu</td>
                                            <td>{{ $assignment->start_fuel_level ?? '-' }}</td>
                                            <td>{{ $assignment->end_fuel_level ?? 'Bekleniyor' }}</td>
                                        </tr>
                                        @if ($assignment->fuel_cost)
                                            <tr>
                                                <td colspan="2">Yakıt Maliyeti</td>
                                                <td>{{ number_format($assignment->fuel_cost, 2) }} TL</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            @if ($assignment->status !== 'completed')
                                <div class="alert alert-warning mt-3">
                                    Görevi tamamlamak için **Bitiş KM** ve **Yakıt Maliyeti** alanlarını doldurmanız
                                    gerekebilir.
                                </div>
                            @endif
                        @endif

                        {{-- ALT BİLGİ --}}
                        <hr class="mt-4">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Oluşturan: {{ $assignment->createdBy->name ?? 'Bilinmiyor' }}</span>
                            <span>Oluşturulma Tarihi: {{ $assignment->created_at->format('d.m.Y H:i') }}</span>
                        </div>
                    </div>

                    {{-- İŞLEM BUTONLARI (Edit/Update) --}}
                    @if (Gate::allows('manage-assignment', $assignment))
                        <div class="card-footer text-end">
                            <a href="{{ route('service.assignments.edit', $assignment->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Görevi Düzenle / Tamamla
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
