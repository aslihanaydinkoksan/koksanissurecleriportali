@extends('layouts.app')

@section('title', 'Yeni Kullanıcı Kaydı')

@push('styles')
    <style>
        /* --- 1. ARKA PLAN VE ANİMASYONLAR --- */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667EEA, #764BA2, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 15s ease infinite;
        }

        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Glassmorphism Card */
        .user-create-card {
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.5);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(240, 147, 251, 0.1));
            padding: 1.5rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #4A5568;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* --- 2. INPUT VE İKON DÜZELTMESİ --- */
        .custom-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .custom-input-group .form-control {
            border-radius: 1rem;
            padding: 0.8rem 1rem;
            padding-right: 2.5rem;
            border: 2px solid rgba(102, 126, 234, 0.15);
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .custom-input-group .form-control:focus {
            background: #fff;
            border-color: #667EEA;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .custom-input-group .input-icon {
            position: absolute;
            right: 15px;
            color: #A0AEC0;
            font-size: 1.1rem;
            pointer-events: none;
        }

        /* --- 3. ROL SEÇİMİ (RADIO BUTONLAR) --- */
        /* Roller tekil seçim olmalı (Controller'a göre) */
        .role-radio {
            display: none;
        }

        .role-label {
            display: inline-block;
            cursor: pointer;
            padding: 10px 20px;
            margin: 5px;
            border-radius: 50px;
            background: #fff;
            border: 2px solid #E2E8F0;
            color: #718096;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .role-label:hover {
            transform: translateY(-2px);
            border-color: #CBD5E0;
        }

        .role-radio:checked+.role-label {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
            transform: scale(1.05);
        }

        /* --- 4. FABRİKA SEÇİMİ (CHECKBOX LİSTESİ) --- */
        .unit-list-wrapper {
            border: 2px solid rgba(102, 126, 234, 0.15);
            border-radius: 1rem;
            background: rgba(247, 250, 252, 0.8);
            padding: 15px;
        }

        .unit-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
            cursor: pointer;
            background: white;
            margin-bottom: 8px;
            border: 1px solid #e2e8f0;
        }

        .unit-item:hover {
            background: #edf2f7;
            border-color: #cbd5e0;
        }

        .unit-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            accent-color: #667EEA;
            cursor: pointer;
        }

        /* --- BUTON --- */
        .btn-magic {
            background: linear-gradient(-45deg, #667EEA, #764BA2);
            background-size: 200% 200%;
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-magic:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }

        /* --- 5. SECTION LABELS (Departman & Fabrika Başlıkları) --- */
        .section-label {
            display: flex;
            align-items: center;
            color: #2D3748;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.875rem;
            margin-left: 0.25rem;
        }

        .section-icon {
            margin-right: 0.625rem;
            font-size: 1.15rem;
            color: #667EEA;
        }

        .factory-icon {
            color: #764BA2 !important;
        }

        /* --- 6. MODERN CHECKBOX WRAPPER --- */
        .department-wrapper {
            border: 2px solid rgba(102, 126, 234, 0.25);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(247, 250, 252, 0.95));
            box-shadow: 0 3px 12px rgba(102, 126, 234, 0.08);
        }

        .factory-wrapper {
            border: 2px solid rgba(118, 75, 162, 0.25);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(250, 245, 255, 0.95));
            box-shadow: 0 3px 12px rgba(118, 75, 162, 0.08);
        }

        .list-wrapper {
            border-radius: 1.125rem;
            padding: 1.125rem;
            max-height: 300px;
            overflow-y: auto;
            transition: all 0.3s ease;
        }

        /* --- 7. MODERN CHECKBOX ITEMS --- */
        .modern-checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border-radius: 0.875rem;
            cursor: pointer;
            background: white;
            margin-bottom: 0;
            border: 2px solid #E2E8F0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .modern-checkbox-item:hover {
            background: #F7FAFC;
            border-color: #CBD5E0;
            transform: translateX(4px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
        }

        .factory-item:hover {
            border-color: rgba(118, 75, 162, 0.3);
            background: rgba(250, 245, 255, 0.5);
        }

        /* --- 8. MODERN CHECKBOX STYLING --- */
        .modern-checkbox {
            width: 22px;
            height: 22px;
            margin-right: 0.875rem;
            cursor: pointer;
            border-radius: 0.375rem;
            accent-color: #667EEA;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .modern-checkbox:checked {
            transform: scale(1.1);
        }

        .factory-checkbox {
            accent-color: #764BA2;
        }

        .checkbox-label {
            color: #2D3748;
            font-weight: 500;
            font-size: 0.9375rem;
            line-height: 1.4;
        }

        .modern-checkbox-item:hover .checkbox-label {
            color: #1A202C;
        }

        /* --- 9. EMPTY STATE --- */
        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
            background: rgba(247, 250, 252, 0.6);
            border-radius: 0.875rem;
            border: 2px dashed #CBD5E0;
        }

        .empty-icon {
            font-size: 2.5rem;
            color: #CBD5E0;
            margin-bottom: 0.75rem;
            opacity: 0.6;
        }

        .empty-text {
            margin: 0;
            color: #A0AEC0;
            font-size: 0.9375rem;
            font-weight: 500;
        }

        /* --- 10. INFO TEXT --- */
        .info-text {
            margin-top: 0.75rem;
            margin-left: 0.5rem;
            color: #718096;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }

        .info-icon {
            margin-right: 0.5rem;
            color: #667EEA;
            font-size: 0.875rem;
        }

        /* --- 11. SCROLLBAR STYLING --- */
        .list-wrapper::-webkit-scrollbar {
            width: 8px;
        }

        .list-wrapper::-webkit-scrollbar-track {
            background: rgba(226, 232, 240, 0.3);
            border-radius: 10px;
        }

        .list-wrapper::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #CBD5E0, #A0AEC0);
            border-radius: 10px;
            transition: background 0.3s;
        }

        .list-wrapper::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #A0AEC0, #718096);
        }

        /* --- 12. RESPONSIVE ADJUSTMENTS --- */
        @media (max-width: 768px) {
            .section-label {
                font-size: 0.95rem;
            }

            .modern-checkbox-item {
                padding: 0.75rem 0.875rem;
            }

            .list-wrapper {
                max-height: 250px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card user-create-card">
                    <div class="card-header-custom">
                        👥 Yeni Kullanıcı Oluştur
                    </div>

                    <div class="card-body p-5">

                        {{-- Hatalar --}}
                        @if ($errors->any())
                            <div class="alert alert-danger" style="border-radius: 1rem;">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success" style="border-radius: 1rem;">
                                ✓ {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                            @csrf

                            {{-- AD SOYAD --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold ms-1">Ad Soyad <span
                                        class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required placeholder="Ad Soyad giriniz">
                                    <span class="input-icon">👤</span>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold ms-1">E-posta Adresi <span
                                        class="text-danger">*</span></label>
                                <div class="custom-input-group">
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required placeholder="ornek@koksan.com">
                                    <span class="input-icon">✉️</span>
                                </div>
                            </div>

                            {{-- ŞİFRELER --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Şifre <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password')">👁️</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Şifre Tekrar <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                            class="form-control" required>
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password-confirm')">👁️</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.2">

                            {{-- 1. DEPARTMAN SEÇİMİ (ÇOKLU SEÇİM / CHECKBOX) --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-building section-icon"></i>
                                    Bağlı Olduğu Departmanlar
                                </label>

                                <div class="list-wrapper department-wrapper">
                                    <div class="row g-3">
                                        @if ($departments->count() > 0)
                                            @foreach ($departments as $dept)
                                                <div class="col-md-6">
                                                    <label class="list-item modern-checkbox-item"
                                                        for="dept_{{ $dept->id }}">
                                                        <input type="checkbox" name="departments[]"
                                                            id="dept_{{ $dept->id }}" value="{{ $dept->id }}"
                                                            class="modern-checkbox"
                                                            {{ is_array(old('departments')) && in_array($dept->id, old('departments')) ? 'checked' : '' }}>
                                                        <span class="checkbox-label">{{ $dept->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="empty-state">
                                                    <i class="fas fa-inbox empty-icon"></i>
                                                    <p class="empty-text">Henüz departman eklenmemiş.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-text">
                                    <i class="fas fa-info-circle info-icon"></i> Kullanıcı birden fazla departmanın
                                    süreçlerini yönetiyorsa hepsini seçiniz.
                                </div>
                            </div>

                            {{-- 2. FABRİKA (BUSINESS UNIT) SEÇİMİ (CHECKBOX LIST) - YENİ --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-industry section-icon factory-icon"></i>
                                    Yetkili Olduğu Fabrikalar / Lokasyonlar
                                </label>
                                <div class="list-wrapper factory-wrapper">
                                    <div class="row g-3">
                                        @if ($businessUnits->count() > 0)
                                            @foreach ($businessUnits as $unit)
                                                <div class="col-md-6">
                                                    <label class="list-item modern-checkbox-item factory-item"
                                                        for="unit_{{ $unit->id }}">
                                                        <input type="checkbox" name="units[]" id="unit_{{ $unit->id }}"
                                                            value="{{ $unit->id }}"
                                                            class="modern-checkbox factory-checkbox"
                                                            {{ is_array(old('units')) && in_array($unit->id, old('units')) ? 'checked' : '' }}>
                                                        <span class="checkbox-label">{{ $unit->name }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="empty-state">
                                                    <i class="fas fa-industry empty-icon"></i>
                                                    <p class="empty-text">Henüz fabrika/lokasyon eklenmemiş.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="info-text">
                                    <i class="fas fa-shield-alt info-icon factory-icon"></i> Kullanıcı, panelde sadece
                                    burada seçtiğiniz fabrikaların verilerini görebilecektir.
                                </div>
                            </div>

                            {{-- 3. ROL SEÇİMİ (RADIO BUTTONS) --}}
                            <div class="mb-5">
                                <label class="form-label text-muted fw-bold ms-1 d-block">
                                    Sistem Rolü <span class="text-danger">*</span>
                                </label>

                                <div class="d-flex flex-wrap">
                                    @foreach ($roles as $role)
                                        <div>
                                            {{-- Radio Button: Bir kullanıcının tek bir ana rolü olması yönetimi kolaylaştırır --}}
                                            <input type="radio" name="role" id="role_{{ $role->id }}"
                                                value="{{ $role->name }}" class="role-radio"
                                                {{ old('role') == $role->name ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="role-label">
                                                {{ __('roles.' . $role->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('role')
                                    <small class="text-danger ms-2">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- SUBMIT --}}
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn-magic">
                                    ✨ Kullanıcıyı Oluştur
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script>
        function togglePwd(id) {
            var input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
            } else {
                input.type = "password";
            }
        }
    </script>
@endsection
