@extends('layouts.app')

@section('title', 'Rapor Düzenle')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 report-card">
                    <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Rapor Planını Düzenle</h3>
                            <small class="opacity-75">"{{ $report->report_name }}" yapılandırmasını güncelliyorsunuz.</small>
                        </div>
                        <a href="{{ route('report-settings.index') }}" class="btn btn-light btn-sm rounded-pill px-3">İptal
                            Et</a>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('report-settings.update', $report) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Raporun Adı / Başlığı</label>
                                    <input type="text" name="report_name"
                                        class="form-control form-control-lg custom-input"
                                        value="{{ old('report_name', $report->report_name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Hangi Modül Raporlanacak?</label>
                                    <select name="report_class" class="form-select form-control-lg custom-input" required>
                                        @foreach ($reports as $class => $name)
                                            <option value="{{ $class }}"
                                                {{ old('report_class', $report->report_class) == $class ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">E-Posta Gönderim Sıklığı</label>
                                    <select name="frequency_preset" class="form-select form-control-lg custom-input"
                                        required>
                                        @foreach ($presets as $key => $preset)
                                            @php
                                                // Modeldeki frequency ve send_time değerlerini preset anahtarıyla eşleştiriyoruz
                                                $isCurrent =
                                                    $report->frequency == $preset['frequency'] &&
                                                    $report->send_time == $preset['time'];
                                            @endphp
                                            <option value="{{ $key }}" {{ $isCurrent ? 'selected' : '' }}
                                                class="{{ $key == 'minute' ? 'text-warning' : '' }}">
                                                {{ $preset['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Gönderim periyodu.</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Rapor Veri Kapsamı</label>
                                    <select name="filter_frequency" class="form-select form-control-lg custom-input"
                                        required>
                                        @php
                                            $selectedFilter = old('filter_frequency', $report->filter_frequency);
                                        @endphp
                                        <option value="daily" {{ $selectedFilter == 'daily' ? 'selected' : '' }}>Günlük
                                            (Son 24 Saat)</option>
                                        <option value="weekly" {{ $selectedFilter == 'weekly' ? 'selected' : '' }}>Haftalık
                                            (Son 7 Gün)</option>
                                        <option value="monthly" {{ $selectedFilter == 'monthly' ? 'selected' : '' }}>Aylık
                                            (Son 30 Gün)</option>
                                        <option value="last_3_months"
                                            {{ $selectedFilter == 'last_3_months' ? 'selected' : '' }}>Son 3 Ay</option>
                                        <option value="last_6_months"
                                            {{ $selectedFilter == 'last_6_months' ? 'selected' : '' }}>Son 6 Ay</option>
                                        <option value="yearly" {{ $selectedFilter == 'yearly' ? 'selected' : '' }}>Yıllık
                                            (Son 1 Yıl)</option>
                                    </select>
                                    <small class="text-muted">Tablodaki verilerin geçmişi.</small>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Dosya Formatı</label>
                                    <div class="d-flex gap-3 pt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="file_format" value="excel"
                                                id="fmtExcel"
                                                {{ old('file_format', $report->file_format) == 'excel' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fmtExcel">Excel (.xlsx)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="file_format" value="pdf"
                                                id="fmtPdf"
                                                {{ old('file_format', $report->file_format) == 'pdf' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fmtPdf">PDF (.pdf)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary">E-posta Alıcıları</label>
                                    <textarea name="recipients" class="form-control custom-input" rows="3"
                                        placeholder="Her mailin arasına virgül koyun..." required>{{ old('recipients', is_array($report->recipients) ? implode(', ', $report->recipients) : $report->recipients) }}</textarea>
                                    <small class="text-muted">Mailleri virgül (,) ile ayırın.</small>
                                </div>
                            </div>

                            <div class="mt-5 d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3 shadow">
                                    <i class="bi bi-check-circle-fill me-2"></i>Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1.5rem;
        }

        .custom-input {
            border: 2px solid #eee;
            border-radius: 0.8rem;
            transition: all 0.3s;
        }

        .custom-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.1);
            background: #fff;
        }

        .form-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
@endsection
