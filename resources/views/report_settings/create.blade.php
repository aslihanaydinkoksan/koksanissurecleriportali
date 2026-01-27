@extends('layouts.master')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Yeni Rapor Planla</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('report-settings.index') }}">Rapor Ayarları</a></li>
            <li class="breadcrumb-item active">Yeni Plan</li>
        </ol>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-calendar-plus me-1"></i> Rapor Konfigürasyonu
            </div>
            <div class="card-body">
                <form action="{{ route('report-settings.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rapor Tanımı</label>
                            <input type="text" name="report_name"
                                class="form-control @error('report_name') is-invalid @enderror"
                                placeholder="Örn: Haftalık Doluluk Raporu" value="{{ old('report_name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rapor Türü (Veri Kaynağı)</label>
                            <select name="report_type" class="form-select @error('report_type') is-invalid @enderror"
                                required>
                                <option value="">Seçiniz...</option>
                                <option value="App\Reports\StayManagementReport">Konaklama Yönetim Raporu</option>
                                <option value="App\Reports\AssetManagementReport">Demirbaş Yönetim Raporu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Gönderim Sıklığı</label>
                            <select name="frequency" class="form-select shadow-sm border-info" required>
                                <option value="every_minute">Her Dakika (Test İçin)</option>
                                <option value="daily_morning">Her Sabah (09:00)</option>
                                <option value="daily_evening">Her Akşam (18:00)</option>
                                <option value="weekly_monday">Pazartesi Sabahı</option>
                                <option value="monthly_first">Her Ayın İlk Günü</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Veri Kapsamı (Zaman Aralığı)</label>
                            <select name="data_scope" class="form-select shadow-sm border-info" required>
                                <option value="last_24h">Günlük (Son 24 Saat)</option>
                                <option value="last_7d">Haftalık (Son 7 Gün)</option>
                                <option value="last_30d">Aylık (Son 30 Gün)</option>
                                <option value="last_3m">Son 3 Ay</option>
                                <option value="last_6m">Son 6 Ay</option>
                                <option value="last_1y">Yıllık (Son 1 Yıl)</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Dosya Formatı</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="format_excel"
                                        value="excel" checked>
                                    <label class="form-check-label" for="format_excel"><i
                                            class="fas fa-file-excel text-success"></i> Excel</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="format_pdf"
                                        value="pdf">
                                    <label class="form-check-label" for="format_pdf"><i
                                            class="fas fa-file-pdf text-danger"></i> PDF</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-Posta Alıcıları</label>
                        <textarea name="recipients" rows="2" class="form-control" placeholder="admin@koksan.com, mudur@koksan.com"
                            required>{{ old('recipients') }}</textarea>
                        <small class="text-muted">Birden fazla adresi virgülle ayırarak giriniz.</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                checked>
                            <label class="form-check-label" for="is_active">Raporu hemen aktifleştir</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('report-settings.index') }}" class="btn btn-light border">Vazgeç</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Planı Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
