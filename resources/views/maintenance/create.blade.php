@extends('layouts.app')

@section('title', 'Yeni Bakım Planı')

@push('styles')
    <style>
        /* --- ANA TASARIM (Diğer sayfalarla ortak) --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
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

        .modern-container {
            max-width: 1000px;
            /* Form için biraz daha dar container idealdir */
            margin: 0 auto;
        }

        /* Form Kartı */
        .form-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1.5rem 2rem;
            border-bottom: none;
        }

        .form-card .card-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }

        /* Form Elemanları */
        .form-label {
            color: #495057;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.8rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-text {
            font-size: 0.8rem;
            color: #8898aa;
            margin-top: 0.4rem;
        }

        /* Butonlar */
        .btn-save {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-cancel {
            padding: 1rem 2rem;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-cancel:hover {
            color: #343a40;
            background-color: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid modern-container">
        {{-- Üst Navigasyon (Show sayfasındaki modern stile güncellendi) --}}
        <div class="position-relative mb-4">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-4 shadow-sm"
                style="background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.98) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">

                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('maintenance.index') }}"
                        class="btn btn-white border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 48px; height: 48px; transition: all 0.3s ease; background: white;">
                        <i class="fas fa-arrow-left" style="color: #6366f1; font-size: 18px;"></i>
                    </a>

                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <div
                                style="width: 4px; height: 24px; background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 100%); border-radius: 2px;">
                            </div>
                            <h4 class="fw-bold mb-0" style="color: #1e293b; font-size: 24px; letter-spacing: -0.5px;">
                                Yeni Plan Oluştur
                            </h4>
                        </div>
                        <div class="d-flex align-items-center gap-2 ms-1">
                            <span style="color: #64748b; font-size: 13px; font-weight: 500;">
                                Yeni bir bakım veya arıza kaydı oluşturun
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card form-card">
                    <div class="card-header">
                        <h4><i class="fas fa-calendar-plus me-2"></i>Plan Bilgileri</h4>
                    </div>

                    <div class="card-body p-4 p-md-5">

                        {{-- Hata Mesajları --}}
                        @if ($errors->any())
                            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                                    <h6 class="mb-0 fw-bold">Lütfen aşağıdaki hataları düzeltin:</h6>
                                </div>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('maintenance.store') }}" autocomplete="off">
                            @csrf

                            {{-- Başlık --}}
                            <div class="mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-1 text-primary opacity-75"></i> Plan Başlığı / Arıza Tanımı
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="title" name="title"
                                    value="{{ old('title') }}" placeholder="Örn: CNC Lazer Yıllık Bakımı" required>
                                <div class="form-text">Yapılacak işi özetleyen kısa ve anlaşılır bir başlık giriniz.</div>
                            </div>

                            <div class="row g-4">
                                {{-- Sol Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="maintenance_type_id" class="form-label">
                                            <i class="fas fa-tags me-1 text-primary opacity-75"></i> Bakım Türü / Departman
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="maintenance_type_id" name="maintenance_type_id"
                                            required>
                                            <option value="">Seçiniz...</option>
                                            @foreach ($types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ old('maintenance_type_id') == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="planned_start_date" class="form-label">
                                            <i class="fas fa-calendar-alt me-1 text-primary opacity-75"></i> Planlanan
                                            Başlangıç <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" class="form-control" id="planned_start_date"
                                            name="planned_start_date" value="{{ old('planned_start_date') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="priority" class="form-label">
                                            <i class="fas fa-thermometer-half me-1 text-primary opacity-75"></i> Öncelik
                                            Durumu
                                        </label>
                                        <select class="form-select" id="priority" name="priority">
                                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                                🟢 Düşük (Planlı Rutin)
                                            </option>
                                            <option value="normal"
                                                {{ old('priority', 'normal') == 'normal' ? 'selected' : '' }}>
                                                🔵 Normal
                                            </option>
                                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                                🟠 Yüksek (Üretimi Etkiliyor)
                                            </option>
                                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>
                                                🔴 KRİTİK (Acil Müdahale)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Sağ Sütun --}}
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="maintenance_asset_id" class="form-label">
                                            <i class="fas fa-microchip me-1 text-primary opacity-75"></i> İlgili Varlık
                                            (Makine/Zone) <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select" id="maintenance_asset_id" name="maintenance_asset_id"
                                            required>
                                            <option value="">Seçiniz...</option>
                                            @foreach ($assets as $asset)
                                                <option value="{{ $asset->id }}"
                                                    {{ old('maintenance_asset_id') == $asset->id ? 'selected' : '' }}>
                                                    [{{ strtoupper($asset->category) }}] {{ $asset->name }}
                                                    ({{ $asset->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Bakım yapılacak makineyi veya bölgeyi seçiniz.</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="planned_end_date" class="form-label">
                                            <i class="fas fa-calendar-check me-1 text-primary opacity-75"></i> Tahmini Bitiş
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" class="form-control" id="planned_end_date"
                                            name="planned_end_date" value="{{ old('planned_end_date') }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Açıklama --}}
                            <div class="mt-4">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1 text-primary opacity-75"></i> Detaylı Açıklama / İş
                                    Emri Notları
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="5"
                                    placeholder="Yapılacak işlemlerin detaylarını, parça gereksinimlerini veya özel notları buraya giriniz...">{{ old('description') }}</textarea>
                            </div>
                            {{-- DİNAMİK ALANLAR --}}
                            <div class="mt-4">
                                <x-dynamic-fields :model="\App\Models\MaintenancePlan::class" />
                            </div>

                            {{-- Aksiyon Butonları --}}
                            <div class="d-flex justify-content-end align-items-center mt-5 pt-3 border-top">
                                <a href="{{ route('maintenance.index') }}"
                                    class="btn btn-cancel me-3 text-decoration-none">
                                    İptal Et
                                </a>
                                <button type="submit" class="btn btn-save">
                                    <i class="fas fa-save me-2"></i>Planı Oluştur
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
