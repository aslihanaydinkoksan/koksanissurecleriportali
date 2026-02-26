@extends('layouts.app')
@section('title', 'Rakip Firma Yönetimi')
@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-user-ninja text-danger me-2"></i>Rakip Firma Yönetimi
                </h4>
                <p class="text-muted small mb-0">Sistemde kayıtlı olan rakip firmaları buradan düzenleyebilir ve
                    silebilirsiniz.</p>
            </div>
            <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCompetitorModal">
                <i class="fa-solid fa-plus me-2"></i> Yeni Rakip Ekle
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Rakip Firma Adı</th>
                                <th>Notlar / Açıklama</th>
                                <th>Durum</th>
                                <th class="text-end pe-4">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($competitors as $comp)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $comp->name }}</td>
                                    <td>{{ Str::limit($comp->notes, 50) ?? '-' }}</td>
                                    <td>
                                        @if ($comp->is_active)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                                        @else
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Pasif</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal"
                                            data-bs-target="#editCompModal{{ $comp->id }}">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="{{ route('competitors.destroy', $comp->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-light border text-danger"><i
                                                    class="fa-solid fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                {{-- DÜZENLEME MODALI --}}
                                <div class="modal fade" id="editCompModal{{ $comp->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-light border-0">
                                                <h5 class="modal-title fw-bold text-dark"><i
                                                        class="fa-solid fa-pen text-primary me-2"></i>Rakibi Düzenle</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('competitors.update', $comp->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body bg-light pt-0">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Firma Adı</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="{{ $comp->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Notlar</label>
                                                        <textarea name="notes" class="form-control" rows="3">{{ $comp->notes }}</textarea>
                                                    </div>
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            id="active{{ $comp->id }}" value="1"
                                                            {{ $comp->is_active ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="active{{ $comp->id }}">Firma Aktif (Listelerde
                                                            Görünsün)</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                        data-bs-dismiss="modal">İptal</button>
                                                    <button type="submit"
                                                        class="btn btn-primary rounded-pill px-4">Güncelle</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-user-ninja fa-2x mb-3 opacity-25"></i>
                                        <p class="mb-0">Henüz kayıtlı bir rakip firma bulunmuyor.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- YENİ RAKİP EKLEME MODALI (Müşteri sayfasındakinin aynısı) --}}
    <div class="modal fade" id="addCompetitorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-ninja me-2"></i>Yeni Rakip Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('competitors.store') }}" method="POST">
                    @csrf
                    <div class="modal-body bg-light">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Rakip Firma Adı (*)</label>
                            <input type="text" name="name" class="form-control border-danger" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Notlar</label>
                            <textarea name="notes" class="form-control border-danger" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
