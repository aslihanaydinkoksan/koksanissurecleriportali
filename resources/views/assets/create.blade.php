@extends('layouts.master')

@section('title', 'Yeni Demirbaş Ekle')

@section('content')

    {{-- 1. BREADCRUMB --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.index') }}" class="text-decoration-none text-muted">Mekanlar</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('locations.show', $location->id) }}"
                        class="text-decoration-none text-muted">{{ $location->name }}</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">Demirbaş Ekle</li>
            </ol>
        </nav>
    </div>

    {{-- 2. EKLEME KARTI --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">

                {{-- Kart Başlığı --}}
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-info bg-opacity-10 d-flex align-items-center justify-content-center text-info"
                            style="width: 48px; height: 48px;">
                            <i class="fa fa-box-open fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Yeni Demirbaş Tanımla</h5>
                            <p class="text-secondary small mb-0">
                                <span class="fw-bold text-primary">{{ $location->name }}</span> konumuna ürün ekleniyor
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('assets.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="location_id" value="{{ $location->id }}">

                        {{-- BÖLÜM 1: ÜRÜN BİLGİLERİ --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">Ürün Bilgileri</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Ürün Adı / Tipi <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-box"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0"
                                        placeholder="Örn: Klima, Yatak" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Marka / Model</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-tag"></i></span>
                                    <input type="text" name="brand" class="form-control border-start-0 ps-0"
                                        placeholder="Örn: Arçelik 12000 BTU">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-medium text-secondary small">Seri Numarası</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-barcode"></i></span>
                                    <input type="text" name="serial_number"
                                        class="form-control border-start-0 ps-0 font-monospace"
                                        placeholder="Cihaz üzerindeki barkod no">
                                </div>
                            </div>
                        </div>

                        {{-- BÖLÜM 2: DURUM VE TARİHLER --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2 mt-4">Durum ve Tarihçe
                        </h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary small">Mevcut Durumu</label>
                                <select name="status" class="form-select text-secondary">
                                    <option value="active">Sağlam / Kullanımda</option>
                                    <option value="broken">Arızalı</option>
                                    <option value="repair">Tamirde</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary small">Satın Alma Tarihi</label>
                                <input type="date" name="purchase_date" class="form-control text-secondary">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium text-secondary small">Garanti Bitiş</label>
                                <input type="date" name="warranty_expiration" class="form-control text-secondary">
                            </div>
                        </div>

                        {{-- FOOTER: BUTONLAR --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('locations.show', $location->id) }}" class="btn btn-light px-4">İptal</a>
                            <button type="submit" class="btn btn-info text-white px-5 shadow-sm">
                                <i class="fa fa-save me-2"></i>Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
