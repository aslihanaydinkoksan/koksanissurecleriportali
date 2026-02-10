@extends('layouts.app')

@section('title', 'Onay Bekleyen Bakım Planları')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">
                            <i class="fas fa-check-double text-warning me-2"></i>Onay Bekleyen İşler
                        </h2>
                        <p class="text-muted">Personel tarafından tamamlanan ve onayınıza sunulan bakım planları.</p>
                    </div>

                    {{-- Geri Dön Butonu --}}
                    <a href="{{ route('home') }}" class="btn btn-light border shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Ana Sayfa
                    </a>
                </div>

                @if ($plans->isEmpty())
                    {{-- LİSTE BOŞSA --}}
                    <div class="card border-0 shadow-sm text-center py-5">
                        <div class="card-body">
                            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3">
                                <i class="fas fa-check-circle fa-3x text-success opacity-50"></i>
                            </div>
                            <h4 class="fw-bold text-secondary">Harika!</h4>
                            <p class="text-muted mb-0">Onayınızı bekleyen herhangi bir işlem bulunmuyor.</p>
                        </div>
                    </div>
                @else
                    {{-- LİSTE DOLUYSA --}}
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Plan Başlığı</th>
                                        <th>Personel</th>
                                        <th>Varlık / Makine</th>
                                        <th>Tamamlanma Tarihi</th>
                                        <th class="text-end pe-4">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($plans as $plan)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-warning bg-opacity-10 p-2 rounded me-3 text-warning">
                                                        <i class="fas fa-file-alt"></i>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('maintenance.show', $plan->id) }}"
                                                            class="fw-bold text-dark text-decoration-none">
                                                            {{ $plan->title }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                        style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                        {{ substr($plan->user->name, 0, 1) }}
                                                    </div>
                                                    <span>{{ $plan->user->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $plan->asset->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                <i class="far fa-clock me-1 text-secondary"></i>
                                                {{ $plan->updated_at->diffForHumans() }}
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex justify-content-end gap-2">

                                                    {{-- İNCELE BUTONU --}}
                                                    <a href="{{ route('maintenance.show', $plan->id) }}"
                                                        class="btn btn-sm btn-info text-white" title="Detayları İncele">
                                                        <i class="fas fa-eye me-1"></i> İncele
                                                    </a>

                                                    {{-- HIZLI ONAY BUTONU --}}
                                                    <form action="{{ route('maintenance.update', $plan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bu planı onaylıyor musunuz?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit"
                                                            class="btn btn-sm btn-success text-white shadow-sm"
                                                            title="Hızlı Onayla">
                                                            <i class="fas fa-check"></i> Onayla
                                                        </button>
                                                    </form>

                                                    {{-- REDDET / GERİ GÖNDER BUTONU --}}
                                                    <form action="{{ route('maintenance.update', $plan->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Bu planı personele geri göndermek (reddetmek) istediğinize emin misiniz?');">
                                                        @csrf
                                                        @method('PUT')
                                                        {{-- Reddedilince tekrar işlemde (in_progress) olsun --}}
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Reddet / Geri Gönder">
                                                            <i class="fas fa-undo"></i>
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
                @endif

            </div>
        </div>
    </div>
@endsection
