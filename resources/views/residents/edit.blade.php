@extends('layouts.master')

@section('title', 'Personel Düzenle')

@section('content')

    {{-- 1. ÜST NAVİGASYON (BREADCRUMB) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 m-0 small">
                <li class="breadcrumb-item">
                    <a href="{{ route('residents.index') }}" class="text-decoration-none text-muted">Personel & Misafir</a>
                </li>
                <li class="breadcrumb-item active text-primary" aria-current="page">Bilgileri Düzenle</li>
            </ol>
        </nav>
    </div>

    {{-- 2. DÜZENLEME KARTI --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-4">

                {{-- Kart Başlığı --}}
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center text-primary"
                            style="width: 48px; height: 48px;">
                            <i class="fa fa-user-pen fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Personel Bilgilerini Güncelle</h5>
                            <p class="text-secondary small mb-0">{{ $resident->first_name }} {{ $resident->last_name }}
                                kaydını düzenliyorsunuz</p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('residents.update', $resident->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- BÖLÜM 1: KİMLİK BİLGİLERİ --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">Kimlik Bilgileri</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Adı <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                    value="{{ old('first_name', $resident->first_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Soyadı <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ old('last_name', $resident->last_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">TC Kimlik No</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-id-card"></i></span>
                                    <input type="text" name="tc_no" class="form-control border-start-0 ps-0"
                                        maxlength="11" value="{{ old('tc_no', $resident->tc_no) }}"
                                        placeholder="11 haneli TC No">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Cinsiyet (Opsiyonel)</label>
                                <select name="gender" class="form-select text-secondary">
                                    <option value="">Seçiniz...</option>
                                    <option value="male" {{ $resident->gender == 'male' ? 'selected' : '' }}>Erkek
                                    </option>
                                    <option value="female" {{ $resident->gender == 'female' ? 'selected' : '' }}>Kadın
                                    </option>
                                </select>
                            </div>
                        </div>

                        {{-- BÖLÜM 2: İLETİŞİM & KURUMSAL --}}
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2 mt-4">İletişim ve
                            Kurumsal</h6>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Telefon Numarası</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-phone"></i></span>
                                    <input type="text" name="phone" class="form-control border-start-0 ps-0"
                                        value="{{ old('phone', $resident->phone) }}" placeholder="05XX XXX XX XX">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Departman / Görev</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-briefcase"></i></span>
                                    <input type="text" name="department" class="form-control border-start-0 ps-0"
                                        value="{{ old('department', $resident->department) }}"
                                        placeholder="Örn: İdari İşler">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Şirket Sicil No</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="fa fa-hashtag"></i></span>
                                    <input type="text" name="employee_id" class="form-control border-start-0 ps-0"
                                        value="{{ old('employee_id', $resident->employee_id) }}"
                                        placeholder="Personel sicil no">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-medium text-secondary small">Çalışma Tipi</label>
                                <select name="employment_type" class="form-select">
                                    <option value="white_collar"
                                        {{ $resident->employment_type == 'white_collar' ? 'selected' : '' }}>Beyaz Yaka
                                    </option>
                                    <option value="blue_collar"
                                        {{ $resident->employment_type == 'blue_collar' ? 'selected' : '' }}>Mavi Yaka
                                    </option>
                                    <option value="contractor"
                                        {{ $resident->employment_type == 'contractor' ? 'selected' : '' }}>Taşeron /
                                        Yüklenici</option>
                                    <option value="guest" {{ $resident->employment_type == 'guest' ? 'selected' : '' }}>
                                        Misafir</option>
                                </select>
                            </div>
                        </div>

                        {{-- FOOTER: BUTONLAR --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('residents.index') }}" class="btn btn-light px-4">İptal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fa fa-save me-2"></i>Değişiklikleri Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
