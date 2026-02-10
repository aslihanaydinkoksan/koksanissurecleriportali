@extends('layouts.app')

@section('title', 'Lojistik Aracı Düzenle')

@push('styles')
    {{-- Create blade'indeki style bloğunun aynısı buraya da gelecek --}}
    <style>
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

        .create-vehicle-card {
            border-radius: 1rem;
            border: 0;
            background-color: transparent;
        }

        .create-vehicle-card .card-header {
            color: #000;
            font-weight: bold;
        }

        .create-vehicle-card .form-label {
            color: #444;
            font-weight: bold;
        }

        .create-vehicle-card .form-control,
        .create-vehicle-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .create-vehicle-card .form-control:focus {
            background-color: #fff;
            border-color: #667EEA;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card create-vehicle-card">
                    <div class="card-header h4 bg-transparent border-0 pt-4">
                        <i class="fas fa-edit me-2"></i>{{ __('Lojistik Aracı Düzenle') }}
                    </div>
                    <div class="card-body p-4">
                        {{-- Form Route: Update --}}
                        <form method="POST" action="{{ route('service.logistics-vehicles.update', $vehicle->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="plate_number" class="form-label">Plaka (*)</label>
                                    <input type="text" class="form-control" id="plate_number" name="plate_number"
                                        value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="status" class="form-label">Durum</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="active" {{ $vehicle->status == 'active' ? 'selected' : '' }}>🟢 Aktif
                                        </option>
                                        <option value="maintenance"
                                            {{ $vehicle->status == 'maintenance' ? 'selected' : '' }}>🟠 Bakımda</option>
                                        <option value="inactive" {{ $vehicle->status == 'inactive' ? 'selected' : '' }}>🔴
                                            Pasif</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="brand" class="form-label">Marka (*)</label>
                                    <input type="text" class="form-control" id="brand" name="brand"
                                        value="{{ old('brand', $vehicle->brand) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="model" class="form-label">Model (*)</label>
                                    <input type="text" class="form-control" id="model" name="model"
                                        value="{{ old('model', $vehicle->model) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="capacity" class="form-label">Yük Kapasitesi (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="capacity" name="capacity"
                                        value="{{ old('capacity', $vehicle->capacity) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="current_km" class="form-label">Güncel KM (*)</label>
                                    <input type="number" step="0.1" class="form-control" id="current_km"
                                        name="current_km" value="{{ old('current_km', $vehicle->current_km) }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="fuel_type" class="form-label">Yakıt Tipi</label>
                                    <select name="fuel_type" id="fuel_type" class="form-select">
                                        <option value="Diesel" {{ $vehicle->fuel_type == 'Diesel' ? 'selected' : '' }}>
                                            Dizel</option>
                                        <option value="Gasoline" {{ $vehicle->fuel_type == 'Gasoline' ? 'selected' : '' }}>
                                            Benzin</option>
                                        <option value="Electric" {{ $vehicle->fuel_type == 'Electric' ? 'selected' : '' }}>
                                            Elektrik</option>
                                    </select>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                    Değişiklikleri Kaydet
                                </button>
                                <a href="{{ route('service.logistics-vehicles.index') }}"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
