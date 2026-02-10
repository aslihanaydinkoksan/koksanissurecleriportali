@extends('layouts.app')

@section('title', 'Takımı Düzenle')

@push('styles')
    <style>
        /* Arka plan animasyonu */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
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

        /* Modern Kart Tasarımı */
        .edit-team-card {
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .edit-team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.12);
        }

        /* Başlık Stili */
        .card-header-edit {
            background: linear-gradient(135deg, rgba(79, 209, 197, 0.1), rgba(102, 126, 234, 0.1));
            border: none;
            padding: 2rem;
            border-bottom: 2px solid rgba(79, 209, 197, 0.2);
        }

        .card-header-edit h4 {
            color: #2d3748;
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header-edit .icon-wrapper {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4FD1C5, #667EEA);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .card-header-edit .team-name-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, rgba(79, 209, 197, 0.15), rgba(102, 126, 234, 0.15));
            border-radius: 0.75rem;
            font-size: 1rem;
            color: #2d3748;
            margin-top: 0.5rem;
            border: 1px solid rgba(79, 209, 197, 0.3);
        }

        /* Form Stilleri */
        .form-label-edit {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label-edit i {
            color: #4FD1C5;
            font-size: 1rem;
        }

        .form-control-edit,
        .form-select-edit {
            border-radius: 0.75rem;
            border: 2px solid rgba(79, 209, 197, 0.2);
            background-color: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control-edit:focus,
        .form-select-edit:focus {
            border-color: #4FD1C5;
            box-shadow: 0 0 0 4px rgba(79, 209, 197, 0.1);
            background-color: white;
        }

        .form-select-edit {
            min-height: 250px;
        }

        .form-select-edit option {
            padding: 0.75rem;
            border-radius: 0.5rem;
            margin: 0.25rem;
        }

        .form-select-edit option:checked {
            background: linear-gradient(135deg, rgba(79, 209, 197, 0.2), rgba(102, 126, 234, 0.2));
            font-weight: 600;
        }

        /* Yardım Metni */
        .form-text-edit {
            color: #718096;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: rgba(79, 209, 197, 0.05);
            border-radius: 0.5rem;
            border-left: 3px solid #4FD1C5;
        }

        .form-text-edit i {
            color: #4FD1C5;
        }

        /* Alert Stilleri */
        .alert-edit {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger-edit {
            background: linear-gradient(135deg, rgba(245, 101, 101, 0.1), rgba(229, 62, 62, 0.1));
            color: #c53030;
            border-left: 4px solid #f56565;
        }

        .alert-danger-edit ul {
            margin-bottom: 0;
            padding-left: 1.25rem;
        }

        .alert-danger-edit li {
            margin-bottom: 0.25rem;
        }

        /* Buton Stilleri */
        .btn-update-gradient {
            background: linear-gradient(-45deg, #4FD1C5, #667EEA, #F093FB, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 209, 197, 0.3);
        }

        .btn-update-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(79, 209, 197, 0.4);
        }

        .btn-update-gradient:active {
            transform: translateY(0);
        }

        .btn-cancel-edit {
            background: white;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            font-weight: 600;
            padding: 0.875rem 2rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-cancel-edit:hover {
            background: #f7fafc;
            color: #2d3748;
            border-color: #cbd5e0;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-edit {
                padding: 1.5rem;
            }

            .card-header-edit h4 {
                font-size: 1.5rem;
            }

            .btn-update-gradient,
            .btn-cancel-edit {
                width: 100%;
                margin-bottom: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card edit-team-card">
                    <div class="card-header card-header-edit">
                        <h4>
                            <div class="icon-wrapper">
                                <i class="fas fa-edit"></i>
                            </div>
                            <div>
                                Takımı Düzenle
                                <div class="team-name-badge">
                                    <i class="fas fa-layer-group me-2"></i>{{ $team->name }}
                                </div>
                            </div>
                        </h4>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        @if ($errors->any())
                            <div class="alert alert-edit alert-danger-edit">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('teams.update', $team->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="name" class="form-label form-label-edit">
                                    <i class="fas fa-tag"></i>
                                    Takım Adı
                                    <span style="color: #f56565;">*</span>
                                </label>
                                <input type="text"
                                    class="form-control form-control-edit @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $team->name) }}"
                                    placeholder="Örn: Proje Alpha Takımı" required>
                            </div>

                            <div class="mb-4">
                                <label for="members" class="form-label form-label-modern">
                                    <i class="fas fa-user-friends"></i>
                                    Takım Üyeleri
                                    <span style="color: #f56565;">*</span>
                                </label>

                                {{-- KRİTİK ALAN: KAYDIRILABİLİR CHECKBOX LİSTESİ --}}
                                <div class="checkbox-list-container @error('members') is-invalid @enderror">

                                    {{-- Validasyon hatası gösterimi --}}
                                    @error('members')
                                        <div class="text-danger small mb-2">Takıma en az bir üye seçmelisiniz.</div>
                                    @enderror

                                    {{-- Checkbox'ları sarmalayan Fieldset --}}
                                    <fieldset class="p-0 border-0">
                                        @foreach ($users as $user)
                                            <div class="form-check py-1">
                                                <input class="form-check-input" type="checkbox" name="members[]"
                                                    value="{{ $user->id }}" id="user_{{ $user->id }}"
                                                    {{ in_array($user->id, old('members', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="user_{{ $user->id }}">
                                                    {{ $user->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </fieldset>
                                </div>
                                <div class="form-text form-text-modern">
                                    <i class="fas fa-info-circle"></i>
                                    Takıma dahil edilecek tüm üyeleri işaretleyin (Tek tık yeterlidir).
                                </div>
                            </div>


                            <div class="d-flex flex-column flex-md-row justify-content-end gap-2 mt-5">
                                <a href="{{ route('teams.index') }}" class="btn btn-cancel-edit">
                                    <i class="fas fa-times me-2"></i>İptal
                                </a>
                                <button type="submit" class="btn btn-update-gradient">
                                    <i class="fas fa-save me-2"></i>Takımı Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush
