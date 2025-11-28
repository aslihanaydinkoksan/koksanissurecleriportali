@extends('layouts.app')

@section('title', 'Yeni Kullanıcı Kaydı')

@push('styles')
    <style>
        /* --- 1. ARKA PLAN VE ANİMASYONLAR (Eski Sevdiğiniz Tasarım) --- */
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
            background: rgba(255, 255, 255, 0.90);
            /* Biraz daha opak yaptım okunurluk için */
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
            /* İkon için yer açtık */
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

        /* --- 3. YENİ ROL SEÇİMİ (KUTUCUKLAR) --- */
        /* Checkbox'ı gizle, Label'ı buton gibi göster */
        .role-checkbox {
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

        /* Seçilince (Checked) ne olsun? */
        .role-checkbox:checked+.role-label {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.4);
            transform: scale(1.05);
        }

        /* --- 4. DEPARTMAN SEÇİMİ (LİSTE) --- */
        .department-list-wrapper {
            max-height: 200px;
            overflow-y: auto;
            border: 2px solid rgba(102, 126, 234, 0.15);
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.6);
            padding: 10px;
        }

        .dept-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 8px;
            transition: background 0.2s;
            cursor: pointer;
        }

        .dept-item:hover {
            background: rgba(102, 126, 234, 0.1);
        }

        .dept-checkbox {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #667EEA;
            /* Checkbox rengi */
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

        /* Scrollbar Güzelleştirme */
        .department-list-wrapper::-webkit-scrollbar {
            width: 6px;
        }

        .department-list-wrapper::-webkit-scrollbar-thumb {
            background-color: #CBD5E0;
            border-radius: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9"> {{-- Kart genişliğini biraz artırdım --}}
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
                                <label class="form-label text-muted fw-bold ms-1">Ad Soyad</label>
                                <div class="custom-input-group">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required placeholder="Ad Soyad giriniz">
                                    <span class="input-icon">👤</span>
                                </div>
                            </div>

                            {{-- EMAIL --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold ms-1">E-posta Adresi</label>
                                <div class="custom-input-group">
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required placeholder="ornek@koksan.com">
                                    <span class="input-icon">✉️</span>
                                </div>
                            </div>

                            <div class="row">
                                {{-- ŞİFRE --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Şifre</label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password" id="password" class="form-control" required>
                                        {{-- Şifre ikonu buton işlevi görecek --}}
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password')">👁️</span>
                                    </div>
                                </div>
                                {{-- ŞİFRE ONAY --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-muted fw-bold ms-1">Şifre Tekrar</label>
                                    <div class="custom-input-group">
                                        <input type="password" name="password_confirmation" id="password-confirm"
                                            class="form-control" required>
                                        <span class="input-icon" style="pointer-events: auto; cursor: pointer;"
                                            onclick="togglePwd('password-confirm')">👁️</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="opacity: 0.2">

                            {{-- YENİ ROLLER (BUTON GÖRÜNÜMLÜ) --}}
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold ms-1 d-block">Kullanıcı Rolleri <small
                                        class="fw-normal">(Birden fazla seçebilirsiniz)</small></label>

                                <div class="d-flex flex-wrap">
                                    @foreach ($roles as $role)
                                        <div>
                                            {{-- Checkbox gizli, Label tıklanınca checkbox'ı tetikler --}}
                                            <input type="checkbox" name="roles[]" id="role_{{ $role->id }}"
                                                value="{{ $role->id }}" class="role-checkbox" {{-- Eğer validation hatası dönerse eski seçilenler kalsın --}}
                                                @if (is_array(old('roles')) && in_array($role->id, old('roles'))) checked @endif>
                                            <label for="role_{{ $role->id }}" class="role-label">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('roles')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- YENİ DEPARTMANLAR (LİSTE GÖRÜNÜMLÜ) --}}
                            <div class="mb-5">
                                <label class="form-label text-muted fw-bold ms-1">Bağlı Olduğu Departmanlar</label>

                                <div class="department-list-wrapper">
                                    @if ($departments->count() > 0)
                                        @foreach ($departments as $dept)
                                            <label class="dept-item" for="dept_{{ $dept->id }}">
                                                <input type="checkbox" name="departments[]" id="dept_{{ $dept->id }}"
                                                    value="{{ $dept->id }}" class="dept-checkbox"
                                                    @if (is_array(old('departments')) && in_array($dept->id, old('departments'))) checked @endif>
                                                <span class="ms-2 text-dark">{{ $dept->name }}</span>
                                            </label>
                                        @endforeach
                                    @else
                                        <div class="p-3 text-center text-muted">Henüz departman eklenmemiş.</div>
                                    @endif
                                </div>
                                <div class="form-text ms-1">Kullanıcının sorumlu olduğu veya çalıştığı birimleri
                                    işaretleyiniz.</div>
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
        // Basit şifre göster/gizle fonksiyonu
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
