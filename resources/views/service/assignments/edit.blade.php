@extends('layouts.app')

@section('title', 'Araç Atamasını Düzenle')

<style>
    /* create.blade.php ile aynı stiller */
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

    .create-assignment-card {
        border-radius: 1rem;
        box-shadow: none !important;
        border: 0;
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-assignment-card .card-header,
    .create-assignment-card .form-label {
        color: #444;
        font-weight: bold;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-assignment-card .card-header {
        color: #000;
    }

    .create-assignment-card .form-control,
    .create-assignment-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .btn-animated-gradient {
        background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    [x-cloak] {
        display: none !important;
    }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{-- ALPINE.JS DATA TANIMI --}}
                <div class="card create-assignment-card" x-data="{
                    // Görev tipi: vehicle veya general
                    assignmentType: '{{ old('assignment_type', $assignment->assignment_type) }}',
                
                    // Araç tipi: company veya logistics (Controller'dan gelmeli veya modelden çekilmeli)
                    vehicleType: '{{ old('vehicle_type', $assignment->vehicle->type ?? '') }}',
                
                    // Sorumlu tipi: user veya team (Modelden çekilen Class ismine göre ayarlanır)
                    responsibleType: '{{ old('responsible_type', $assignment->responsible_type === App\Models\User::class ? 'user' : ($assignment->responsible_type === App\Models\Team::class ? 'team' : 'user')) }}',
                
                    // Sadece sorumlu seçimi için required mantığı
                    isRequired(type) {
                        return this.responsibleType === type;
                    }
                }" x-cloak>
                    {{-- Başlık ve Sil Butonu --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Araç Atamasını Düzenle') }}</span>
                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('service.assignments.destroy', $assignment->id) }}"
                                onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Görevi
                                    Sil</button>
                            </form>
                        @endif
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Form action ve method güncellendi --}}
                        <form method="POST" action="{{ route('service.assignments.update', $assignment->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- GÖREV BAŞLIĞI (Create'ten eklendi) --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">Görev Başlığı (*)</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    id="title" name="title" value="{{ old('title', $assignment->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- DURUM SEÇİMİ (Yeni eklendi) --}}
                            <div class="mb-3">
                                <label for="status" class="form-label">Görev Durumu (*)</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach (['pending' => 'Bekliyor', 'in_progress' => 'Devam Ediyor', 'completed' => 'Tamamlandı', 'cancelled' => 'İptal Edildi'] as $key => $value)
                                        <option value="{{ $key }}"
                                            {{ old('status', $assignment->status) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ATAMA ZAMANI (Değiştirilemez) --}}
                            <div class="mb-3">
                                <label class="form-label">Atanan Sefer Zamanı
                                    (Değiştirilemez)</label>
                                <input type="text" class="form-control"
                                    value="{{ $assignment->start_time->format('d.m.Y H:i') }} Seferi" disabled readonly>
                                <small class="form-text text-muted">Bir görevin zamanını
                                    değiştirmek için silip yeniden
                                    eklemeniz gerekmektedir.</small>
                            </div>

                            <hr>

                            {{-- ARAÇ SEÇİMİ --}}
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Araç (*)</label>
                                <select name="vehicle_id" id="vehicle_id"
                                    class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                    <option value="">Araç Seçiniz...</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id', $assignment->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->plate_number }}
                                            ({{ $vehicle->type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <hr class="mt-4 mb-4">
                            <h5 class="mb-3 fw-bold">Sorumlu Güncelleme</h5>

                            <div class="row mb-3">
                                {{-- Sorumlu Tipi Seçimi --}}
                                <div class="col-md-6 mb-3">
                                    <label for="responsibleTypeSelect" class="form-label">Sorumlu Tipi</label>
                                    <select id="responsibleTypeSelect" x-model="responsibleType" class="form-select">
                                        <option value="user">Tek Kişi</option>
                                        <option value="team">Takım</option>
                                    </select>
                                </div>

                                {{-- Sorumlu Alanı (Kişi/Takım) --}}
                                <div class="col-md-6 mb-3">
                                    <input type="hidden" name="responsible_type_field" :value="responsibleType">

                                    {{-- Sorumlu Kişi Seçimi --}}
                                    <div x-show="responsibleType === 'user'">
                                        <label for="responsible_user_id" class="form-label">👤 Sorumlu Kişi (*)</label>
                                        <select :name="responsibleType === 'user' ? 'responsible_user_id' : ''"
                                            id="responsible_user_id"
                                            class="form-select @error('responsible_user_id') is-invalid @enderror"
                                            :required="isRequired('user')">
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

                                    {{-- Sorumlu Takım Seçimi --}}
                                    <div x-show="responsibleType === 'team'">
                                        <label for="responsible_team_id" class="form-label">👥 Sorumlu Takım (*)</label>
                                        <select :name="responsibleType === 'team' ? 'responsible_team_id' : ''"
                                            id="responsible_team_id"
                                            class="form-select @error('responsible_team_id') is-invalid @enderror"
                                            :required="isRequired('team')">
                                            <option value="">Takım seçiniz...</option>
                                            @foreach ($teams as $team)
                                                <option value="{{ $team->id }}"
                                                    {{ old('responsible_id', $assignment->responsible_id) == $team->id && $assignment->responsible_type === App\Models\Team::class ? 'selected' : '' }}>
                                                    {{ $team->name }} ({{ $team->users_count }} kişi)
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('responsible_team_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            {{-- Nakliye Görevleri için EK ALANLAR --}}
                            @if ($assignment->isLogistics())
                                <hr class="mt-4 mb-4">
                                <h5 class="mb-3 fw-bold">Nakliye Detayları (KM & Yakıt)</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="initial_km" class="form-label">Başlangıç KM</label>
                                        <input type="number" step="0.01" name="initial_km" id="initial_km"
                                            class="form-control" value="{{ old('initial_km', $assignment->start_km) }}"
                                            disabled readonly>
                                        <small class="form-text text-muted">Başlangıç KM değiştirilemez.</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="initial_fuel" class="form-label">Başlangıç Yakıt Durumu</label>
                                        <input type="text" name="initial_fuel" id="initial_fuel" class="form-control"
                                            value="{{ old('initial_fuel', $assignment->start_fuel_level) }}" disabled
                                            readonly>
                                        <small class="form-text text-muted">Başlangıç Yakıt Durumu değiştirilemez.</small>
                                    </div>
                                </div>

                                <hr>
                                <h6 class="fw-bold">Görev Tamamlama Detayları</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="final_km" class="form-label">Bitiş KM</label>
                                        <input type="number" step="0.01" name="final_km" id="final_km"
                                            class="form-control" value="{{ old('final_km', $assignment->end_km) }}">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="final_fuel" class="form-label">Bitiş Yakıt Durumu</label>
                                        <select name="final_fuel" id="final_fuel" class="form-select">
                                            <option value="">Seçiniz...</option>
                                            @foreach (['full', '3/4', '1/2', '1/4', 'empty'] as $level)
                                                <option value="{{ $level }}"
                                                    {{ old('final_fuel', $assignment->end_fuel_level) == $level ? 'selected' : '' }}>
                                                    {{ $level }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="fuel_cost" class="form-label">Yakıt Maliyeti (TL)</label>
                                        <input type="number" step="0.01" name="fuel_cost" id="fuel_cost"
                                            class="form-control" value="{{ old('fuel_cost', $assignment->fuel_cost) }}">
                                    </div>
                                </div>
                            @endif
                            <hr class="mt-4 mb-4">

                            <div class="mb-3">
                                <label for="task_description" class="form-label">Görev
                                    Açıklaması (*)</label>
                                <input type="text" class="form-control @error('task_description') is-invalid @enderror"
                                    id="task_description" name="task_description"
                                    value="{{ old('task_description', $assignment->task_description) }}" required>
                                @error('task_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="destination" class="form-label">Yer /
                                    Gidilecek Nokta</label>
                                <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                    id="destination" name="destination"
                                    value="{{ old('destination', $assignment->destination) }}">
                                @error('destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="requester_name" class="form-label">Talep Eden
                                    Kişi / Departman</label>
                                <input type="text" class="form-control @error('requester_name') is-invalid @enderror"
                                    id="requester_name" name="requester_name"
                                    value="{{ old('requester_name', $assignment->requester_name) }}">
                                @error('requester_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Ek Notlar</label>

                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $assignment->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
                                    Kaydet</button>
                                <a href="{{ route('service.assignments.index') }}"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
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
