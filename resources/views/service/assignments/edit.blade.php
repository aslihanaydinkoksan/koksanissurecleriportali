@extends('layouts.app')

@section('title', 'Araç Atamasını Düzenle')

@push('styles')
    <style>
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .edit-assignment-card {
            border-radius: 1rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            border: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e5e7eb;
        }

        .section-header .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .section-header h5 {
            margin: 0;
            color: #1f2937;
            font-weight: 600;
        }

        .info-box {
            background: linear-gradient(135deg, rgba(219, 234, 254, 0.8), rgba(191, 219, 254, 0.8));
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
        }

        .info-box::before {
            content: "💡";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
        }

        .info-box-content {
            margin-left: 2.5rem;
            color: #1e40af;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .warning-box {
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.8), rgba(253, 230, 138, 0.8));
            border: 2px solid rgba(245, 158, 11, 0.3);
            border-radius: 1rem;
            padding: 1rem 1.25rem;
            margin: 1.5rem 0;
            position: relative;
        }

        .warning-box::before {
            content: "⚠️";
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.5rem;
        }

        .warning-box-content {
            margin-left: 2.5rem;
            color: #92400e;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .readonly-field {
            background: linear-gradient(135deg, rgba(243, 244, 246, 0.8), rgba(229, 231, 235, 0.8));
            border: 2px solid #d1d5db;
            cursor: not-allowed;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667EEA;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            transition: all 0.2s ease;
            border-radius: 0.75rem;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .selection-card {
            border: 2px solid #e5e7eb;
            border-radius: 1rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
        }

        .selection-card:hover {
            border-color: #667EEA;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .selection-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .selection-card input[type="radio"]:checked~.card-content {
            border-left: 4px solid #667EEA;
            padding-left: 1rem;
        }

        .selection-card input[type="radio"]:checked~.card-content .card-icon {
            background: linear-gradient(135deg, #667EEA, #764BA2);
            color: white;
        }

        .card-content {
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .card-text h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
        }

        .card-text p {
            margin: 0;
            font-size: 0.875rem;
            color: #6b7280;
        }

        [x-cloak] {
            display: none !important;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    @php
        $canChangeResponsible = Auth::user()->id === $assignment->user_id || Auth::user()->role === 'admin';
        $disableInput = $canChangeResponsible ? '' : 'disabled';

        // Kullanıcı yetkili mi? (Müdür, Admin, Yönetici)
        $isManager = in_array(Auth::user()->role, ['mudur', 'müdür', 'admin', 'yönetici']);
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card edit-assignment-card" x-data="{
                    vehicleType: '{{ old('vehicle_type', $assignment->isLogistics() ? 'logistics' : 'company') }}',
                    responsibleType: '{{ old('responsible_type', $assignment->responsible_type === App\Models\User::class ? 'user' : ($assignment->responsible_type === App\Models\Team::class ? 'team' : 'user')) }}',
                    status: '{{ old('status', $assignment->status) }}',
                    isLogistics() {
                        return this.vehicleType === 'logistics';
                    }
                }" x-cloak>

                    <div class="card-header bg-transparent border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">✏️ Görev Düzenleme</h4>
                                <p class="text-muted mb-0">Görev detaylarını güncelleyin</p>
                            </div>
                            @if (Auth::user()->role === 'admin')
                                <form method="POST" action="{{ route('service.assignments.destroy', $assignment->id) }}"
                                    onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Görevi Sil
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="card-body px-4 py-3">
                        @if ($errors->any())
                            <div class="alert alert-danger rounded-3">
                                <strong>⚠️ Hata!</strong> Lütfen aşağıdaki sorunları düzeltin:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('service.assignments.update', $assignment->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="vehicle_type" :value="vehicleType">
                            <input type="hidden" name="responsible_type" value="{{ $assignment->responsible_type }}">
                            <input type="hidden" name="responsible_id" value="{{ $assignment->responsible_id }}">

                            {{-- GÖREV BİLGİLERİ --}}
                            <div class="section-header">
                                <div class="icon">📋</div>
                                <h5>Görev Bilgileri</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8 mb-3">
                                    <label for="title" class="form-label">📢 Görev Başlığı *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $assignment->title) }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">🔄 Görev Durumu *</label>

                                    @if ($isManager)
                                        {{-- YÖNETİCİ İSE: Dropdown Açık --}}
                                        <select name="status" id="status" x-model="status"
                                            class="form-select @error('status') is-invalid @enderror" required>
                                            <option value="waiting_assignment">⏳ Atama Bekliyor</option>
                                            <option value="pending">🕒 Bekliyor (Onaylandı)</option>
                                            <option value="in_progress">🔄 Devam Ediyor</option>
                                            <option value="completed">✅ Tamamlandı</option>
                                            <option value="cancelled">❌ İptal Edildi</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    @else
                                        {{-- PERSONEL İSE: Sadece Bilgi (Değiştirilemez) --}}
                                        <div class="p-2 border rounded bg-light">
                                            @if ($assignment->status == 'waiting_assignment')
                                                <span class="badge bg-warning text-dark">⏳ Atama Bekliyor</span>
                                            @elseif($assignment->status == 'pending')
                                                <span class="badge bg-info text-dark">🕒 Onaylandı / Sırada</span>
                                            @elseif($assignment->status == 'in_progress')
                                                <span class="badge bg-primary">🔄 Yolda / Devam Ediyor</span>
                                            @elseif($assignment->status == 'completed')
                                                <span class="badge bg-success">✅ Tamamlandı</span>
                                            @else
                                                <span class="badge bg-secondary">❌ İptal</span>
                                            @endif
                                            <div class="small text-muted mt-1">
                                                <i class="fas fa-lock"></i> Durum yönetici tarafından güncellenir.
                                            </div>
                                        </div>
                                        <input type="hidden" name="status" value="{{ $assignment->status }}">
                                    @endif
                                </div>
                            </div>

                            <div class="info-box mb-4">
                                <div class="info-box-content">
                                    <strong>Sefer Zamanı:</strong>
                                    {{ $assignment->start_time ? $assignment->start_time->format('d.m.Y H:i') : 'Henüz Belirlenmedi' }}
                                    <br>
                                    <small>Görev zamanı araç ataması yapıldığında kesinleşecektir.</small>
                                </div>
                            </div>

                            {{-- ========================================================= --}}
                            {{-- ARAÇ BİLGİLERİ (GÜNCELLENMİŞ MANTIK) --}}
                            {{-- ========================================================= --}}

                            {{-- KURAL: Eğer bu görev bir "Araç Görevi" ise (Tipi varsa), bu bölüm hep açık olsun. --}}
                            @if ($assignment->vehicle_type)
                                <div class="section-header">
                                    <div class="icon">🚗</div>
                                    <h5>Araç Bilgileri</h5>
                                </div>

                                {{-- SENARYO A: YÖNETİCİ (MÜDÜR) --}}
                                {{-- Yönetici her durumda (atama bekliyor veya atanmış) araç seçimi yapabilir/değiştirebilir --}}
                                @if ($isManager)
                                    <div class="mb-4">
                                        <div class="alert alert-light border">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Şu anki durum:
                                            <strong>{{ $assignment->vehicle ? 'Araç Atanmış' : 'Araç Bekleniyor' }}</strong>.
                                            Gerekirse aşağıdan aracı değiştirebilirsiniz.
                                        </div>

                                        <label for="vehicle_id" class="form-label">
                                            <span x-show="vehicleType === 'company'">🚙</span>
                                            <span x-show="vehicleType === 'logistics'">🚚</span>
                                            Araç Seçimi / Değişimi *
                                        </label>

                                        {{-- ŞİRKET ARAÇLARI --}}
                                        <div x-show="vehicleType === 'company'">
                                            <select name="vehicle_id" class="form-select">
                                                <option value="">Araç Seçiniz...</option>
                                                @foreach ($companyVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}"
                                                        {{ $assignment->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                                        {{ $vehicle->plate_number }} -
                                                        {{ $vehicle->brand_model ?? $vehicle->model }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- NAKLİYE ARAÇLARI --}}
                                        <div x-show="vehicleType === 'logistics'">
                                            <select name="vehicle_id" class="form-select">
                                                <option value="">Nakliye Aracı Seçiniz...</option>
                                                @foreach ($logisticsVehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}"
                                                        {{ $assignment->vehicle_id == $vehicle->id ? 'selected' : '' }}>
                                                        {{ $vehicle->plate_number }} - {{ $vehicle->brand }}
                                                        {{ $vehicle->model }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- SENARYO B: STANDART PERSONEL --}}
                                    {{-- Personel sadece mevcut durumu görür, müdahale edemez --}}
                                @else
                                    <div class="mb-4">
                                        @if ($assignment->vehicle)
                                            {{-- Araç Atanmışsa Göster --}}
                                            <div class="alert alert-success d-flex align-items-center">
                                                <div class="h2 me-3 mb-0">✅</div>
                                                <div>
                                                    <h6 class="alert-heading fw-bold mb-0">Atanan Araç</h6>
                                                    <p class="mb-0">
                                                        {{ $assignment->vehicle->plate_number }}
                                                        <span
                                                            class="text-muted small">({{ $assignment->vehicle->brand_model ?? $assignment->vehicle->brand }})</span>
                                                    </p>
                                                </div>
                                            </div>
                                            {{-- Form hatası vermemesi için mevcut ID'yi gizli yolluyoruz --}}
                                            <input type="hidden" name="vehicle_id" value="{{ $assignment->vehicle_id }}">
                                        @else
                                            {{-- Araç Henüz Atanmamışsa Uyarı Göster --}}
                                            <div class="alert alert-warning d-flex align-items-center">
                                                <div class="h2 me-3 mb-0">⏳</div>
                                                <div>
                                                    <h6 class="alert-heading fw-bold mb-0">Araç Bekleniyor</h6>
                                                    <p class="mb-0 small">
                                                        Bu görev için araç ataması henüz yapılmamıştır.
                                                        Yönetici atama yaptığında burada görünecektir.
                                                    </p>
                                                </div>
                                            </div>
                                            <input type="hidden" name="vehicle_id" value="">
                                        @endif
                                    </div>
                                @endif

                                {{-- NAKLİYE DETAYLARI (KM & Yakıt) --}}
                                <div x-show="isLogistics()" class="fade-in mt-4">
                                    <div class="section-header">
                                        <div class="icon">📊</div>
                                        <h5>Nakliye Detayları (KM & Yakıt)</h5>
                                    </div>

                                    @if ($isManager)
                                        {{-- Yönetici: Değerleri Düzenleyebilir --}}
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">📍 Başlangıç KM</label>
                                                <input type="number" step="0.1" name="start_km"
                                                    class="form-control" value="{{ $assignment->start_km }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">⛽ Başlangıç Yakıt</label>
                                                <select name="start_fuel_level" class="form-select">
                                                    @foreach (['full', '3/4', '1/2', '1/4', 'empty'] as $lvl)
                                                        <option value="{{ $lvl }}"
                                                            {{ $assignment->start_fuel_level == $lvl ? 'selected' : '' }}>
                                                            {{ $lvl }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Personel: Sadece Okuyabilir --}}
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">📍 Başlangıç KM</label>
                                                <input type="text" class="form-control readonly-field"
                                                    value="{{ $assignment->start_km ? number_format($assignment->start_km, 2) . ' km' : '-' }}"
                                                    disabled readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">⛽ Başlangıç Yakıt Durumu</label>
                                                <input type="text" class="form-control readonly-field"
                                                    value="{{ $assignment->start_fuel_level ?? '-' }}" disabled readonly>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Bitiş Değerleri (Herkes Görebilir - Görev Tamamlanırken) --}}
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-3">
                                            <label for="final_km" class="form-label">🏁 Bitiş KM</label>
                                            <input type="number" step="0.01" name="final_km" id="final_km"
                                                class="form-control" value="{{ old('final_km', $assignment->end_km) }}"
                                                placeholder="Örn: 125250.75">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="final_fuel" class="form-label">⛽ Bitiş Yakıt Durumu</label>
                                            <select name="final_fuel" id="final_fuel" class="form-select">
                                                <option value="">Seçiniz...</option>
                                                @foreach (['full' => 'Dolu (Full)', '3/4' => '3/4', '1/2' => '1/2 (Yarım)', '1/4' => '1/4', 'empty' => 'Boş'] as $level => $label)
                                                    <option value="{{ $level }}"
                                                        {{ old('final_fuel', $assignment->end_fuel_level) == $level ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="fuel_cost" class="form-label">💰 Yakıt Maliyeti (TL)</label>
                                            <input type="number" step="0.01" name="fuel_cost" id="fuel_cost"
                                                class="form-control"
                                                value="{{ old('fuel_cost', $assignment->fuel_cost) }}"
                                                placeholder="Örn: 1250.50">
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Bu bir araç görevi değilse input gönderme --}}
                                <input type="hidden" name="vehicle_id" value="">
                            @endif

                            {{-- SORUMLULAR --}}
                            <div class="section-header">
                                <div class="icon">👥</div>
                                <h5>Sorumlu Atama</h5>
                                @if (!$canChangeResponsible)
                                    <span class="ms-3 text-muted small">(Sadece Görevi Atayan Değiştirebilir)</span>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sorumlu Tipi</label>
                                <div class="d-flex gap-2">
                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" x-model="responsibleType" value="user"
                                            {{ $disableInput }}>
                                        <div class="card-content">
                                            <div class="card-icon">👤</div>
                                            <div class="card-text">
                                                <h6>Tek Kişi</h6>
                                                <p>Bireysel atama</p>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" x-model="responsibleType" value="team"
                                            {{ $disableInput }}>
                                        <div class="card-content">
                                            <div class="card-icon">👥</div>
                                            <div class="card-text">
                                                <h6>Takım</h6>
                                                <p>Grup ataması</p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <input type="hidden" name="responsible_type_field" :value="responsibleType">

                            <div x-show="responsibleType === 'user'" class="mb-4 fade-in">
                                <label for="responsible_user_id" class="form-label">👤 Sorumlu Kişi *</label>
                                <select :name="responsibleType === 'user' ? 'responsible_user_id' : ''"
                                    id="responsible_user_id"
                                    class="form-select @error('responsible_user_id') is-invalid @enderror"
                                    :required="responsibleType === 'user'" {{ $disableInput }}>
                                    <option value="">Kişi seçiniz...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('responsible_id', $assignment->responsible_id) == $user->id && $assignment->responsible_type === App\Models\User::class ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsible_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div x-show="responsibleType === 'team'" class="mb-4 fade-in">
                                <label for="responsible_team_id" class="form-label">👥 Sorumlu Takım *</label>
                                <select :name="responsibleType === 'team' ? 'responsible_team_id' : ''"
                                    id="responsible_team_id"
                                    class="form-select @error('responsible_team_id') is-invalid @enderror"
                                    :required="responsibleType === 'team'" {{ $disableInput }}>
                                    <option value="">Takım seçiniz...</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            {{ old('responsible_id', $assignment->responsible_id) == $team->id && $assignment->responsible_type === App\Models\Team::class ? 'selected' : '' }}>
                                            {{ $team->name }} ({{ $team->users_count ?? 0 }} kişi)
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsible_team_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- GÖREV DETAYLARI --}}
                            <div class="section-header">
                                <div class="icon">📝</div>
                                <h5>Görev Detayları</h5>
                            </div>

                            <div class="mb-4">
                                <label for="task_description" class="form-label">📋 Görev Açıklaması *</label>
                                <input type="text" class="form-control @error('task_description') is-invalid @enderror"
                                    id="task_description" name="task_description"
                                    value="{{ old('task_description', $assignment->task_description) }}" required
                                    {{ $disableInput === 'disabled' ? 'readonly' : '' }}>

                                @if ($disableInput === 'disabled')
                                    <small class="form-text text-muted">Bu alanı yalnızca görevi atayan kişi
                                        düzenleyebilir.</small>
                                @endif

                                @error('task_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-4">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">🏢 İlgili Müşteri</label>
                                    <select name="customer_id" class="form-select">
                                        <option value="">İlişkili müşteri yok</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id', $assignment->customer_id) == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="destination" class="form-label">📍 Hedef Konum</label>
                                    <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                        id="destination" name="destination"
                                        value="{{ old('destination', $assignment->destination) }}"
                                        placeholder="Örn: Merkez Ofis, İstanbul Şubesi">
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="requester_name" class="form-label">🙋 Talep Eden Kişi / Departman</label>
                                    <input type="text"
                                        class="form-control @error('requester_name') is-invalid @enderror"
                                        id="requester_name" name="requester_name"
                                        value="{{ $assignment->createdBy->name ?? 'Bilinmiyor' }}" disabled readonly>
                                    <small class="form-text text-muted">Bu görev, görev atan kişi tarafından
                                        oluşturulmuştur.</small>
                                    @error('requester_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="notes" class="form-label">📌 Ek Notlar</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                    placeholder="Varsa ek bilgiler veya önemli notlar...">{{ old('notes', $assignment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                <a href="{{ route('service.assignments.index') }}"
                                    class="btn btn-outline-secondary btn-lg">
                                    ← İptal
                                </a>
                                <button type="submit" class="btn btn-animated-gradient btn-lg">
                                    💾 Değişiklikleri Kaydet
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
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
