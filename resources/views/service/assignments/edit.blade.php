@extends('layouts.app')

@section('title', 'Görev Atamasını Düzenle')

@push('styles')
    <style>
        /* --- STİLLER (AYNEN KORUNDU) --- */
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
        $user = Auth::user();

        // --- YETKİ KONTROLLERİ ---

        // 1. Araç Yönetme: Ulaştırma Müdürü veya Admin
        $canManageVehicle =
            $user->role === 'admin' ||
            ($user->role === 'müdür' && $user->department && $user->department->slug === 'ulastirma');

        // 2. Durum Değiştirme: Atayan, Atanan veya Yönetici
        $isAssignee = false;
        if ($assignment->responsible_type === 'App\Models\User' && $assignment->responsible_id === $user->id) {
            $isAssignee = true;
        } elseif ($assignment->responsible_type === 'App\Models\Team') {
            $isAssignee = $user->teams->contains($assignment->responsible_id);
        }

        $canUpdateStatus = $user->id === $assignment->created_by || $isAssignee || $canManageVehicle;

        // 3. Genel Düzenleme: Sadece Atayan veya Admin
        $canEditDetails = $user->id === $assignment->created_by || $user->role === 'admin';

        // Eğer düzenleyemiyorsa disabled yap
        $disableInput = $canEditDetails ? '' : 'disabled';
    @endphp

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card edit-assignment-card" x-data="{
                    vehicleType: '{{ old('vehicle_type', $assignment->isLogistics() ? 'logistics' : 'company') }}',
                    responsibleType: '{{ old('responsible_type', $assignment->responsible_type === App\Models\User::class ? 'user' : 'team') }}',
                    status: '{{ old('status', $assignment->status) }}',
                    isLogistics() { return this.vehicleType === 'logistics'; }
                }" x-cloak>

                    <div class="card-header bg-transparent border-0 pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-1">✏️ Görev Düzenleme</h4>
                                <p class="text-muted mb-0">Görev detaylarını ve durumunu güncelleyin</p>
                            </div>
                            @if ($canEditDetails)
                                <form method="POST" action="{{ route('service.assignments.destroy', $assignment->id) }}"
                                    onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
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
                            {{-- Araç tipi gizli olarak tutuluyor --}}
                            <input type="hidden" name="vehicle_type" :value="vehicleType">

                            {{-- GÖREV DURUMU --}}
                            <div class="section-header">
                                <div class="icon">🔄</div>
                                <h5>Görev Durumu</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="status" class="form-label">Güncel Durum</label>
                                    @if ($canUpdateStatus)
                                        <select name="status" id="status"
                                            class="form-select form-select-lg @error('status') is-invalid @enderror"
                                            required>
                                            <option value="waiting_assignment"
                                                {{ $assignment->status == 'waiting_assignment' ? 'selected' : '' }}>⏳ Atama
                                                Bekliyor</option>
                                            <option value="pending"
                                                {{ $assignment->status == 'pending' ? 'selected' : '' }}>🕒 Bekliyor /
                                                Planlandı</option>
                                            <option value="in_progress"
                                                {{ $assignment->status == 'in_progress' ? 'selected' : '' }}>🔄 Başladım /
                                                Devam Ediyor</option>
                                            <option value="completed"
                                                {{ $assignment->status == 'completed' ? 'selected' : '' }}>✅ Tamamlandı
                                            </option>
                                            <option value="cancelled"
                                                {{ $assignment->status == 'cancelled' ? 'selected' : '' }}>❌ İptal Edildi
                                            </option>
                                        </select>
                                        <small class="text-muted">Görevi başlattığınızda veya bitirdiğinizde buradan durumu
                                            güncelleyiniz.</small>
                                    @else
                                        <div class="p-3 border rounded bg-light">
                                            <strong>{{ ucfirst($assignment->status) }}</strong>
                                            <input type="hidden" name="status" value="{{ $assignment->status }}">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- SORUMLU ATAMA --}}
                            <div class="section-header">
                                <div class="icon">👥</div>
                                <h5>Sorumlu Atama</h5>
                                @if (!$canEditDetails)
                                    <span class="ms-3 text-muted small">(Sadece Görevi Atayan Değiştirebilir)</span>
                                @endif
                            </div>

                            {{-- DÜZELTME 1: Eğer yetki yoksa HIDDEN INPUTS ekle --}}
                            @if (!$canEditDetails)
                                <input type="hidden" name="responsible_type"
                                    value="{{ $assignment->responsible_type === App\Models\User::class ? 'user' : 'team' }}">
                                <input type="hidden" name="responsible_user_id" value="{{ $assignment->responsible_id }}">
                                <input type="hidden" name="responsible_team_id" value="{{ $assignment->responsible_id }}">
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Sorumlu Tipi</label>
                                <div class="d-flex gap-2">
                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" name="responsible_type" x-model="responsibleType"
                                            value="user" {{ $disableInput }}>
                                        <div class="card-content">
                                            <div class="card-icon">👤</div>
                                            <div class="card-text">
                                                <h6>Tek Kişi</h6>
                                                <p>Bireysel atama</p>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="selection-card flex-fill mb-0">
                                        <input type="radio" name="responsible_type" x-model="responsibleType"
                                            value="team" {{ $disableInput }}>
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

                            <div x-show="responsibleType === 'user'" class="mb-4 fade-in">
                                <label class="form-label">👤 Sorumlu Kişi *</label>
                                <select name="responsible_user_id" class="form-select" {{ $disableInput }}>
                                    <option value="">Kişi seçiniz...</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('responsible_id', $assignment->responsible_id) == $user->id && $assignment->responsible_type === App\Models\User::class ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="responsibleType === 'team'" class="mb-4 fade-in">
                                <label class="form-label">👥 Sorumlu Takım *</label>
                                <select name="responsible_team_id" class="form-select" {{ $disableInput }}>
                                    <option value="">Takım seçiniz...</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            {{ old('responsible_id', $assignment->responsible_id) == $team->id && $assignment->responsible_type === App\Models\Team::class ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- GÖREV BİLGİLERİ --}}
                            <div class="section-header">
                                <div class="icon">📝</div>
                                <h5>Görev Detayları</h5>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 mb-3">
                                    <label for="title" class="form-label">📢 Görev Başlığı</label>
                                    {{-- Eğer düzenleyemiyorsa HIDDEN olarak gönder --}}
                                    <input type="text" class="form-control" name="title"
                                        value="{{ old('title', $assignment->title) }}" {{ $disableInput }} required>
                                    @if (!$canEditDetails)
                                        <input type="hidden" name="title" value="{{ $assignment->title }}">
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Açıklama</label>
                                    <textarea name="task_description" class="form-control" rows="3" {{ $disableInput }}>{{ old('task_description', $assignment->task_description) }}</textarea>
                                    @if (!$canEditDetails)
                                        <input type="hidden" name="task_description"
                                            value="{{ $assignment->task_description }}">
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label">Notlar</label>
                                    {{-- Notlar her zaman düzenlenebilir kalsın istiyorsan disable'ı kaldır, aksi halde buraya da hidden koy --}}
                                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $assignment->notes) }}</textarea>
                                </div>
                            </div>
                            {{-- ARAÇ BİLGİLERİ --}}
                            <div class="section-header mt-4">
                                <div class="icon">🚗</div>
                                <h5>Araç Bilgileri</h5>
                            </div>

                            <div class="card-body border rounded p-3 mb-4 bg-light">
                                @if ($canManageVehicle)
                                    {{-- YÖNETİCİ GÖRÜNÜMÜ: ARAÇ SEÇİM KUTUSU --}}
                                    <div class="mb-3">
                                        <label for="vehicle_selection" class="form-label fw-bold">Araç Ataması /
                                            Değişimi</label>

                                        {{-- ÖNEMLİ: name="vehicle_selection" Controller ile eşleşmeli --}}
                                        <select name="vehicle_selection" id="vehicle_selection"
                                            class="form-select select2">
                                            <option value="">-- Araç Yok / Atamayı Kaldır --</option>

                                            {{-- GRUP 1: ŞİRKET ARAÇLARI --}}
                                            <optgroup label="Şirket Araçları">
                                                @foreach ($companyVehicles as $vehicle)
                                                    @php
                                                        // Bu araç seçili mi?
                                                        $isSelected =
                                                            $assignment->vehicle_type === 'App\Models\Vehicle' &&
                                                            $assignment->vehicle_id == $vehicle->id;
                                                    @endphp
                                                    <option value="company_{{ $vehicle->id }}"
                                                        {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $vehicle->plate_number }} -
                                                        {{ $vehicle->brand_model ?? $vehicle->model }}
                                                    </option>
                                                @endforeach
                                            </optgroup>

                                            {{-- GRUP 2: LOJİSTİK ARAÇLARI --}}
                                            <optgroup label="Lojistik (Nakliye) Araçları">
                                                @foreach ($logisticsVehicles as $lVehicle)
                                                    @php
                                                        // Bu araç seçili mi?
                                                        $isSelected =
                                                            $assignment->vehicle_type ===
                                                                'App\Models\LogisticsVehicle' &&
                                                            $assignment->vehicle_id == $lVehicle->id;
                                                    @endphp
                                                    <option value="logistics_{{ $lVehicle->id }}"
                                                        {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $lVehicle->plate_number }} (Lojistik)
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        <div class="form-text text-muted">
                                            Listeden bir araç seçtiğinizde sistem otomatik olarak türünü (Şirket/Lojistik)
                                            algılar.
                                        </div>
                                    </div>
                                @else
                                    {{-- PERSONEL GÖRÜNÜMÜ: SADECE BİLGİ --}}
                                    @if ($assignment->vehicle)
                                        <div class="alert alert-success d-flex align-items-center">
                                            <div class="h2 me-3 mb-0">✅</div>
                                            <div>
                                                <h6 class="alert-heading fw-bold mb-0">Atanan Araç</h6>
                                                <p class="mb-0">{{ $assignment->vehicle->plate_number }}</p>
                                                @if ($assignment->vehicle_type == 'App\Models\LogisticsVehicle')
                                                    <small class="text-muted">(Lojistik Aracı)</small>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- Mevcut aracı korumak için gizli input --}}
                                        @php
                                            $prefix =
                                                $assignment->vehicle_type == 'App\Models\LogisticsVehicle'
                                                    ? 'logistics'
                                                    : 'company';
                                            $hiddenValue = $prefix . '_' . $assignment->vehicle_id;
                                        @endphp
                                        <input type="hidden" name="vehicle_selection" value="{{ $hiddenValue }}">
                                    @else
                                        <div class="alert alert-warning d-flex align-items-center">
                                            <div class="h2 me-3 mb-0">⏳</div>
                                            <div>
                                                <h6 class="alert-heading fw-bold mb-0">Araç Bekleniyor</h6>
                                                <p class="mb-0 small">Ulaştırma birimi henüz araç ataması yapmadı.</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                {{-- KM TAKİBİ (HERKES GÖREBİLİR) --}}
                                <div class="mt-3 pt-3 border-top">
                                    <h6 class="text-primary mb-3">⛽ Yakıt ve Kilometre Takibi</h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Başlangıç KM</label>
                                            <input type="number" step="0.1" name="start_km" class="form-control"
                                                value="{{ old('start_km', $assignment->start_km) }}"
                                                {{ $canManageVehicle ? '' : 'readonly' }}>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Bitiş KM</label>
                                            <input type="number" step="0.1" name="final_km" class="form-control"
                                                value="{{ old('final_km', $assignment->end_km) }}"
                                                placeholder="Görevi bitirirken giriniz">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- AKSİYON --}}
                            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                <a href="{{ route('service.general-tasks.index') }}"
                                    class="btn btn-outline-secondary btn-lg">
                                    ← Listeye Dön
                                </a>
                                <button type="submit" class="btn btn-animated-gradient btn-lg">
                                    💾 Kaydet ve Güncelle
                                </button>
                            </div>
                        </form>
                        {{-- DOSYA YÜKLEME ALANI --}}
                        <div class="section-header mt-5">
                            <div class="icon">📎</div>
                            <h5>Dosya ve Belgeler</h5>
                        </div>

                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                {{-- Yüklenmiş Dosyalar Listesi --}}
                                @if ($assignment->files && $assignment->files->count() > 0)
                                    <div class="list-group mb-3">
                                        @foreach ($assignment->files as $file)
                                            <div
                                                class="list-group-item d-flex justify-content-between align-items-center bg-white border rounded mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3 text-primary fs-4">
                                                        @if (Str::contains($file->mime_type, 'image'))
                                                            <i class="fa-regular fa-image"></i>
                                                        @elseif(Str::contains($file->mime_type, 'pdf'))
                                                            <i class="fa-regular fa-file-pdf text-danger"></i>
                                                        @else
                                                            <i class="fa-regular fa-file"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('files.download', $file->id) }}"
                                                            class="fw-bold text-dark text-decoration-none"
                                                            target="_blank">
                                                            {{ $file->original_name }}
                                                        </a>
                                                        <div class="small text-muted">
                                                            {{ $file->created_at->format('d.m.Y H:i') }} -
                                                            {{ $file->uploader->name ?? '?' }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('files.download', $file->id) }}"
                                                        class="btn btn-sm btn-outline-primary" title="İndir">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>

                                                    {{-- Sadece yükleyen veya admin silebilir --}}
                                                    @if (Auth::id() === $file->uploaded_by || Auth::user()->role === 'admin')
                                                        <form action="{{ route('files.destroy', $file->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Silmek istediğine emin misin?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Sil">
                                                                <i class="fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center mb-3 small">Henüz dosya yüklenmemiş.</p>
                                @endif

                                {{-- Yeni Dosya Yükleme Formu --}}
                                <div class="border-top pt-3">
                                    <form action="{{ route('files.store') }}" method="POST"
                                        enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        <input type="hidden" name="model_type" value="App\Models\VehicleAssignment">
                                        <input type="hidden" name="model_id" value="{{ $assignment->id }}">

                                        <input type="file" name="file" class="form-control form-control-sm"
                                            required>

                                        <button type="submit"
                                            class="btn btn-dark btn-sm d-flex align-items-center gap-2">
                                            <i class="fa-solid fa-upload"></i> Yükle
                                        </button>
                                    </form>
                                    <small class="text-muted ms-1" style="font-size: 0.75rem">Fatura, fiş, ruhsat vb.
                                        (Max: 10MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
