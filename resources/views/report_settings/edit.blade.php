@extends('layouts.master')
@section('title', 'Rapor Planını Düzenle')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Rapor Planını Düzenle</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('report-settings.index') }}">Rapor Ayarları</a></li>
            <li class="breadcrumb-item active">Düzenle</li>
        </ol>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-success text-white">
                <i class="fas fa-edit me-1"></i> Plan Detaylarını Güncelle
            </div>
            <div class="card-body">
                <form action="{{ route('report-settings.update', $reportSetting->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rapor Tanımı</label>
                            <input type="text" name="report_name"
                                class="form-control @error('report_name') is-invalid @enderror"
                                value="{{ old('report_name', $reportSetting->report_name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Rapor Türü (Veri Kaynağı)</label>
                            <select name="report_type" class="form-select @error('report_type') is-invalid @enderror"
                                required>
                                <option value="App\Reports\StayManagementReport"
                                    {{ $reportSetting->report_type == 'App\Reports\StayManagementReport' ? 'selected' : '' }}>
                                    Konaklama Yönetim Raporu</option>
                                <option value="App\Reports\AssetManagementReport"
                                    {{ $reportSetting->report_type == 'App\Reports\AssetManagementReport' ? 'selected' : '' }}>
                                    Demirbaş Yönetim Raporu</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Gönderim Sıklığı</label>
                            <select name="frequency" class="form-select shadow-sm border-info" required>
                                <option value="every_minute"
                                    {{ $reportSetting->frequency == 'every_minute' ? 'selected' : '' }}>Her Dakika (Test
                                    İçin)</option>
                                <option value="daily_morning"
                                    {{ $reportSetting->frequency == 'daily_morning' ? 'selected' : '' }}>Her Sabah (09:00)
                                </option>
                                <option value="daily_evening"
                                    {{ $reportSetting->frequency == 'daily_evening' ? 'selected' : '' }}>Her Akşam (18:00)
                                </option>
                                <option value="weekly_monday"
                                    {{ $reportSetting->frequency == 'weekly_monday' ? 'selected' : '' }}>Pazartesi Sabahı
                                </option>
                                <option value="monthly_first"
                                    {{ $reportSetting->frequency == 'monthly_first' ? 'selected' : '' }}>Her Ayın İlk Günü
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Veri Kapsamı (Zaman Aralığı)</label>
                            <select name="data_scope" class="form-select shadow-sm border-info" required>
                                <option value="last_24h" {{ $reportSetting->data_scope == 'last_24h' ? 'selected' : '' }}>
                                    Günlük (Son 24 Saat)</option>
                                <option value="last_7d" {{ $reportSetting->data_scope == 'last_7d' ? 'selected' : '' }}>
                                    Haftalık (Son 7 Gün)</option>
                                <option value="last_30d" {{ $reportSetting->data_scope == 'last_30d' ? 'selected' : '' }}>
                                    Aylık (Son 30 Gün)</option>
                                <option value="last_3m" {{ $reportSetting->data_scope == 'last_3m' ? 'selected' : '' }}>Son
                                    3 Ay</option>
                                <option value="last_6m" {{ $reportSetting->data_scope == 'last_6m' ? 'selected' : '' }}>Son
                                    6 Ay</option>
                                <option value="last_1y" {{ $reportSetting->data_scope == 'last_1y' ? 'selected' : '' }}>
                                    Yıllık (Son 1 Yıl)</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Dosya Formatı</label>
                            <div class="mt-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="format_excel"
                                        value="excel" {{ $reportSetting->file_format == 'excel' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="format_excel"><i
                                            class="fas fa-file-excel text-success"></i> Excel</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="file_format" id="format_pdf"
                                        value="pdf" {{ $reportSetting->file_format == 'pdf' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="format_pdf"><i
                                            class="fas fa-file-pdf text-danger"></i> PDF</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">E-Posta Alıcıları</label>
                        <textarea name="recipients" rows="2" class="form-control" placeholder="admin@koksan.com, mudur@koksan.com"
                            required>{{ implode(', ', $reportSetting->recipients) }}</textarea>
                        <small class="text-muted">Birden fazla adresi virgülle ayırarak giriniz.</small>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                {{ $reportSetting->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Rapor planı aktif</label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between border-top pt-3">
                        <a href="{{ route('report-settings.index') }}" class="btn btn-light border">Vazgeç</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fas fa-sync me-1"></i> Ayarları Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
