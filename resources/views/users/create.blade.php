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

        /* --- 2. INPUT VE İKONLAR --- */
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

        /* --- 3. ROL SEÇİMİ (RADIO) --- */
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

        /* --- 4. DEPARTMAN & FABRİKA TASARIMI --- */
        .section-label {
            display: flex;
            align-items: center;
            color: #2D3748;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.875rem;
        }

        .section-icon {
            margin-right: 0.625rem;
            font-size: 1.15rem;
            color: #667EEA;
        }

        .list-wrapper {
            border-radius: 1.125rem;
            padding: 1.125rem;
            max-height: 280px;
            overflow-y: auto;
            border: 2px solid rgba(102, 126, 234, 0.15);
            background: rgba(247, 250, 252, 0.8);
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
            margin-bottom: 8px;
        }

        .modern-checkbox-item:hover {
            border-color: #667EEA;
            transform: translateX(4px);
            background: #F7FAFC;
        }

        .modern-checkbox {
            width: 20px;
            height: 20px;
            margin-right: 0.875rem;
            accent-color: #667EEA;
        }

        .btn-magic {
            background: linear-gradient(-45deg, #667EEA, #764BA2);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-magic:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.3);
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card user-create-card">
                    <div class="card-header-custom">
                        <i class="fas fa-user-plus me-2"></i> Yeni Kullanıcı Tanımlama
                    </div>

                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                            @csrf

                            <div class="row">
                                {{-- AD SOYAD --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Ad Soyad <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}" required placeholder="Ad Soyad">
                                        <span class="input-icon">👤</span>
                                    </div>
                                </div>

                                {{-- EMAIL --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Kurumsal E-posta <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}" required placeholder="ornek@koksan.com">
                                        <span class="input-icon">✉️</span>
                                    </div>
                                </div>

                                {{-- ŞİFRE --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Şifre <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password')">👁️</span>
                                    </div>
                                </div>

                                {{-- ŞİFRE ONAY --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold">Şifre Tekrar <span
                                            class="text-danger">*</span></label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                            class="form-control" required>
                                        <span class="input-icon">🔑</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.1">

                            {{-- DEPARTMAN SEÇİMİ --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-building section-icon"></i> Çalıştığı Departmanlar
                                </label>
                                <div class="list-wrapper">
                                    <div class="row">
                                        @foreach ($departments as $dept)
                                            <div class="col-md-4">
                                                <label class="modern-checkbox-item" for="dept_{{ $dept->id }}">
                                                    <input type="checkbox" name="departments[]"
                                                        id="dept_{{ $dept->id }}" value="{{ $dept->id }}"
                                                        class="modern-checkbox"
                                                        {{ is_array(old('departments')) && in_array($dept->id, old('departments')) ? 'checked' : '' }}>
                                                    <span class="checkbox-label small fw-bold">{{ $dept->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-2 text-muted small">
                                    <i class="fas fa-info-circle text-primary"></i> <b>Önemli:</b> Kullanıcı sadece
                                    seçtiğiniz departmanlara ait verileri (Lojistik, Üretim vb.) görebilir.
                                </div>
                            </div>

                            {{-- FABRİKA SEÇİMİ --}}
                            <div class="mb-4">
                                <label class="section-label">
                                    <i class="fas fa-industry section-icon" style="color: #764BA2"></i> Yetkili Olduğu
                                    Fabrikalar
                                </label>
                                <div class="list-wrapper" style="border-color: rgba(118, 75, 162, 0.15)">
                                    <div class="row">
                                        @foreach ($businessUnits as $unit)
                                            <div class="col-md-4">
                                                <label class="modern-checkbox-item" for="unit_{{ $unit->id }}">
                                                    <input type="checkbox" name="units[]" id="unit_{{ $unit->id }}"
                                                        value="{{ $unit->id }}" class="modern-checkbox"
                                                        {{ is_array(old('units')) && in_array($unit->id, old('units')) ? 'checked' : '' }}>
                                                    <span class="checkbox-label small fw-bold">{{ $unit->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- SİSTEM ROLÜ --}}
                            <div class="mb-5">
                                <label class="form-label text-muted fw-bold ms-1 d-block">
                                    <i class="fas fa-shield-alt text-primary me-1"></i> Yetki Seviyesi
                                </label>
                                <div class="d-flex flex-wrap">
                                    @php
                                        // Sadece ana rollerimizi listeliyoruz
                                        $mainRoles = ['admin', 'yonetici', 'user'];
                                    @endphp

                                    @foreach ($roles->whereIn('name', $mainRoles) as $role)
                                        <div class="me-2 mb-2">
                                            <input type="radio" name="role" id="role_{{ $role->id }}"
                                                value="{{ $role->name }}" class="role-radio"
                                                {{ old('role', 'user') == $role->name ? 'checked' : '' }}>
                                            <label for="role_{{ $role->id }}" class="role-label">
                                                {{ __('roles.' . $role->name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn-magic py-3 shadow">
                            ✨ Kullanıcı Kaydını Tamamla
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
            input.type = (input.type === "password") ? "text" : "password";
        }
    </script>
@endsection
