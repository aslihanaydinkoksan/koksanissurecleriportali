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
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card create-assignment-card">
                    {{-- Başlık ve Sil Butonu --}}
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span>{{ __('Araç Atamasını Düzenle') }}</span>
                        @if (Auth::user()->role === 'admin')
                            <form method="POST" action="{{ route('service.assignments.destroy', $assignment->id) }}"
                                onsubmit="return confirm('Bu atamayı silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Görevi Sil</button>
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

                            {{-- ATAMA ZAMANI (Düzenlenemez, sadece bilgi) --}}
                            <div class="mb-3">
                                <label class="form-label">Atanan Sefer Zamanı (Değiştirilemez)</label>
                                <input type="text" class="form-control"
                                    value="{{ $assignment->start_time->format('d.m.Y H:i') }} Seferi" disabled readonly>
                                <small class="form-text text-muted">Bir görevin zamanını değiştirmek için silip yeniden
                                    eklemeniz gerekmektedir.</small>
                            </div>
                            <hr>

                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Araç (*)</label>
                                <select name="vehicle_id" id="vehicle_id"
                                    class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                    <option value="">Araç Seçiniz...</option>
                                    @foreach ($vehicles as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ old('vehicle_id', $assignment->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->plate_number }} ({{ $vehicle->type }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehicle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="task_description" class="form-label">Görev Açıklaması (*)</label>
                                <input type="text" class="form-control @error('task_description') is-invalid @enderror"
                                    id="task_description" name="task_description"
                                    value="{{ old('task_description', $assignment->task_description) }}" required>
                                @error('task_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="destination" class="form-label">Yer / Gidilecek Nokta</label>
                                <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                    id="destination" name="destination"
                                    value="{{ old('destination', $assignment->destination) }}">
                                @error('destination')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="requester_name" class="form-label">Talep Eden Kişi / Departman</label>
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
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri
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
    {{-- Bu sayfa için özel JavaScript gerekmiyor --}}
@endsection
