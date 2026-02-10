@extends('layouts.app')

@section('title', 'Kullanıcı Düzenle')

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
        .user-edit-card {
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* --- 2. INPUT VE İKON --- */
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

        /* --- 3. ROL SEÇİMİ (RADIO BUTTONS) --- */
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

        /* --- 4. LISTE & CHECKBOX TASARIMLARI --- */
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

        .list-wrapper {
            border-radius: 1.125rem;
            padding: 1.125rem;
            max-height: 300px;
            overflow-y: auto;
            border: 2px solid rgba(102, 126, 234, 0.15);
            background: rgba(247, 250, 252, 0.8);
        }

        .department-wrapper {
            border-color: rgba(102, 126, 234, 0.25);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(247, 250, 252, 0.95));
        }

        .factory-wrapper {
            border-color: rgba(118, 75, 162, 0.25);
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(250, 245, 255, 0.95));
        }

        .modern-checkbox-item {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            border-radius: 0.875rem;
            cursor: pointer;
            background: white;
            border: 2px solid #E2E8F0;
            transition: all 0.25s ease;
        }

        .modern-checkbox-item:hover {
            background: #F7FAFC;
            border-color: #667EEA;
            transform: translateX(4px);
        }

        .factory-item:hover {
            border-color: #764BA2;
            background: #FAF5FF;
        }

        .modern-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 0.875rem;
            accent-color: #667EEA;
            cursor: pointer;
        }

        .factory-checkbox {
            accent-color: #764BA2;
        }

        .checkbox-label {
            font-weight: 500;
            color: #4A5568;
            font-size: 0.95rem;
        }

        /* --- BUTONLAR --- */
        .btn-magic {
            background: linear-gradient(-45deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-magic:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }

        .btn-cancel {
            background: transparent;
            border: 2px solid #CBD5E0;
            color: #718096;
            padding: 0.9rem;
            border-radius: 1rem;
            font-weight: 600;
            width: 100%;
            display: block;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            background: #EDF2F7;
            color: #4A5568;
            border-color: #A0AEC0;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card user-edit-card">
                    <div class="card-header-custom">
                        <div>
                            <i class="fas fa-user-edit me-2"></i> Kullanıcıyı Düzenle
                        </div>
                        <small class="text-muted fs-6 fw-normal bg-white px-3 py-1 rounded-pill shadow-sm">
                            {{ $user->name }}
                        </small>
                    </div>

                    <div class="card-body p-5">

                        {{-- Hatalar --}}
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm mb-4" style="border-radius: 1rem;">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.update', $user->id) }}" autocomplete="off">
                            @csrf
                            @method('PUT')

                            {{-- Temel Bilgiler --}}
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Ad Soyad</label>
                                    <div class="custom-input-group">
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $user->name) }}" required>
                                        <span class="input-icon">👤</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">E-posta Adresi</label>
                                    <div class="custom-input-group">
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email) }}" required>
                                        <span class="input-icon">✉️</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.1">

                            {{-- Şifre Değiştirme Alanı --}}
                            <div class="alert alert-light border-0 shadow-sm mb-4 d-flex align-items-center" role="alert"
                                style="border-radius: 1rem; background-color: rgba(237, 242, 247, 0.5);">
                                <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                                <div class="text-muted small">
                                    Şifreyi değiştirmek <strong>istemiyorsanız</strong> aşağıdaki alanları boş bırakın.
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Yeni Şifre</label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="******">
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password')">👁️</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Yeni Şifre Tekrar</label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                            class="form-control" placeholder="******">
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password-confirm')">🔑</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.1">

                            {{-- 1. DEPARTMAN SEÇİMİ --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-building section-icon"></i> Bağlı Olduğu Departmanlar
                                </label>
                                <div class="list-wrapper department-wrapper">
                                    <div class="row g-3">
                                        @foreach ($departments as $dept)
                                            <div class="col-md-6">
                                                <label class="modern-checkbox-item" for="dept_{{ $dept->id }}">
                                                    {{-- Controller'dan gelen $userDepartments dizisi ile kontrol --}}
                                                    <input type="checkbox" name="departments[]"
                                                        id="dept_{{ $dept->id }}" value="{{ $dept->id }}"
                                                        class="modern-checkbox"
                                                        {{ in_array($dept->id, old('departments', $userDepartments)) ? 'checked' : '' }}>
                                                    <span class="checkbox-label">{{ $dept->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-text ms-2 mt-1"><small>Kullanıcının erişebileceği modülleri
                                        belirler.</small></div>
                            </div>

                            {{-- 2. FABRİKA (BUSINESS UNIT) SEÇİMİ --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-industry section-icon factory-icon"></i> Yetkili Olduğu Fabrikalar
                                </label>
                                <div class="list-wrapper factory-wrapper">
                                    <div class="row g-3">
                                        @foreach ($businessUnits as $unit)
                                            <div class="col-md-6">
                                                <label class="modern-checkbox-item factory-item"
                                                    for="unit_{{ $unit->id }}">
                                                    {{-- Controller'dan gelen $userUnits dizisi ile kontrol --}}
                                                    <input type="checkbox" name="units[]" id="unit_{{ $unit->id }}"
                                                        value="{{ $unit->id }}"
                                                        class="modern-checkbox factory-checkbox"
                                                        {{ in_array($unit->id, old('units', $userUnits)) ? 'checked' : '' }}>
                                                    <span class="checkbox-label">{{ $unit->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-text ms-2 mt-1"><small>Kullanıcının hangi fabrikalara ait verileri
                                        görebileceğini belirler (Data Scope).</small></div>
                            </div>

                            {{-- 3. ROL SEÇİMİ --}}
                            <div class="mb-5">
                                <label class="form-label text-muted fw-bold ms-1 d-block">
                                    <i class="fas fa-shield-alt text-primary me-1"></i> Yetki Seviyesi
                                </label>
                                <div class="d-flex flex-wrap bg-light p-3 rounded-4 border">
                                    @php
                                        $mainRoles = ['admin', 'yonetici', 'user'];
                                    @endphp

                                    @foreach ($roles->whereIn('name', $mainRoles) as $role)
                                        <div class="me-2 mb-2">
                                            <input type="radio" name="role" id="role_{{ $role->id }}"
                                                value="{{ $role->name }}" class="role-radio"
                                                {{ old('role', $user->roles->first()->name ?? 'user') == $role->name ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="role-label">
                                                {{ __('roles.' . $role->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- BUTONLAR --}}
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('users.index') }}" class="btn-cancel">
                                        ← İptal Et
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn-magic shadow">
                                        💾 Değişiklikleri Kaydet
                                    </button>
                                </div>
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
