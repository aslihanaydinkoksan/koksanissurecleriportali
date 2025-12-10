@extends('layouts.master')

@section('title', 'Telefon Rehberi / Ustalar')

@section('content')

    <x-page-layout title="Usta & Kurum Rehberi" :count="$contacts->total()" create-label="" {{-- Ekleme butonu modal açacağı için boş bıraktık --}}>
        {{-- FİLTRE SLOTU: Yeni Ekle Butonu --}}
        <x-slot:filters>
            <button class="btn btn-sm btn-success shadow-sm px-3" data-bs-toggle="modal" data-bs-target="#addContactModal">
                <i class="fa fa-plus me-2"></i> Yeni Kişi Ekle
            </button>
        </x-slot:filters>

        {{-- LİSTE (GRID) --}}
        @forelse($contacts as $contact)
            @php
                // Meslek Grubu Renkleri ve İkonları
                $pro = match ($contact->profession) {
                    'electric' => ['color' => 'warning', 'icon' => 'fa-bolt', 'text' => 'Elektrik'],
                    'water' => ['color' => 'info', 'icon' => 'fa-tint', 'text' => 'Su/Tesisat'],
                    'gas' => ['color' => 'danger', 'icon' => 'fa-fire', 'text' => 'Doğalgaz'],
                    'internet' => ['color' => 'secondary', 'icon' => 'fa-wifi', 'text' => 'İnternet'],
                    'locksmith' => ['color' => 'dark', 'icon' => 'fa-key', 'text' => 'Çilingir'],
                    default => [
                        'color' => 'light text-dark border',
                        'icon' => 'fa-briefcase',
                        'text' => 'Genel/Diğer',
                    ],
                };
            @endphp

            <div class="col">
                <x-info-card title="{{ $contact->name }}" subtitle="{{ $contact->company_name }}" badge="{{ $pro['text'] }}"
                    badgeColor="{{ $pro['color'] }}">
                    {{-- Kart İçeriği --}}
                    <div class="d-flex align-items-center mt-3 mb-2">
                        <div class="display-6 fw-bold text-dark" style="font-size: 1.25rem; letter-spacing: 0.5px;">
                            {{ $contact->phone }}
                        </div>
                    </div>

                    {{-- Notlar Varsa --}}
                    @if ($contact->notes)
                        <div class="small text-muted fst-italic border-top pt-2 mt-2">
                            "{{ Str::limit($contact->notes, 50) }}"
                        </div>
                    @endif

                    {{-- Aksiyon Butonları --}}
                    <x-slot:actions>
                        {{-- Sadece Sil Butonu Kaldı --}}
                        <x-delete-button action="{{ route('contacts.destroy', $contact->id) }}"
                            message="{{ $contact->name }} kişisini rehberden silmek istediğinize emin misiniz?" />
                    </x-slot:actions>

                </x-info-card>
            </div>

        @empty
            <div class="col-12">
                <div class="alert alert-light border-0 shadow-sm text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="fa fa-address-book fa-2x text-secondary opacity-50"></i>
                    </div>
                    <h5 class="text-muted">Rehber Boş</h5>
                    <p class="text-secondary small">Henüz kayıtlı bir usta veya kurum yok.</p>
                    <button class="btn btn-success px-4 rounded-pill" data-bs-toggle="modal"
                        data-bs-target="#addContactModal">
                        <i class="fa fa-plus me-2"></i> İlk Kişiyi Ekle
                    </button>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        <x-slot:pagination>
            {{ $contacts->links() }}
        </x-slot:pagination>

    </x-page-layout>

    {{-- YENİ EKLEME MODALI --}}
    <div class="modal fade" id="addContactModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center text-success"
                            style="width: 48px; height: 48px;">
                            <i class="fa fa-user-plus fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Yeni Usta/Kurum Ekle</h5>
                            <p class="text-secondary small mb-0">Rehbere yeni bir kişi ekliyorsunuz</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('contacts.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Adı Soyadı <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control bg-light border-0"
                                placeholder="Örn: Ahmet Usta" required>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Firma Adı</label>
                                <input type="text" name="company_name" class="form-control bg-light border-0"
                                    placeholder="Örn: Yıldız Tesisat">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Uzmanlık Alanı <span
                                        class="text-danger">*</span></label>
                                <select name="profession" class="form-select bg-light border-0" required>
                                    <option value="electric">Elektrik</option>
                                    <option value="water">Su / Tesisat</option>
                                    <option value="gas">Doğalgaz / Kombi</option>
                                    <option value="internet">İnternet / IT</option>
                                    <option value="locksmith">Çilingir</option>
                                    <option value="general">Genel / Diğer</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Telefon <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i
                                        class="fa fa-phone"></i></span>
                                <input type="text" name="phone" class="form-control bg-light border-0"
                                    placeholder="05XX XXX XX XX" required>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label small fw-bold text-secondary">Notlar</label>
                            <textarea name="notes" class="form-control bg-light border-0" rows="2" placeholder="Varsa ek notlar..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
