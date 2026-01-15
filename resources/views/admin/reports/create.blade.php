@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow border-0 report-card">
                    <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0 fw-bold"><i class="bi bi-gear-wide-connected me-2"></i>Otomatik Rapor Motoru</h3>
                            <small class="opacity-75">Sistem verilerini periyodik olarak Excel/PDF formatında iletin.</small>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm rounded-pill px-3">Geri Dön</a>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('report-settings.store') }}" method="POST">
                            @csrf
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Raporun Adı / Başlığı</label>
                                    <input type="text" name="report_name"
                                        class="form-control form-control-lg custom-input"
                                        placeholder="Örn: Pazartesi Lojistik Özeti" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-secondary">Hangi Modül Raporlanacak?</label>
                                    <select name="report_class" class="form-select form-control-lg custom-input" required>
                                        <option value="" disabled selected>Rapor Seçiniz...</option>
                                        @foreach ($reports as $class => $name)
                                            <option value="{{ $class }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-secondary">Gönderim Zamanı</label>
                                    <select name="frequency_preset" class="form-select form-control-lg custom-input"
                                        required>
                                        <option value="" disabled selected>Zamanlama Seçiniz...</option>
                                        @foreach ($presets as $key => $preset)
                                            <option value="{{ $key }}"
                                                class="{{ $key == 'minute' ? 'text-warning' : '' }}">
                                                {{ $preset['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Gönderim Saati</label>
                                    <input type="time" name="send_time" class="form-control custom-input" required>
                                </div> --}}

                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-secondary">Dosya Formatı</label>
                                    <div class="d-flex gap-3 pt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="file_format" value="excel"
                                                id="fmtExcel" checked>
                                            <label class="form-check-label" for="fmtExcel">Excel (.xlsx)</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="file_format" value="pdf"
                                                id="fmtPdf">
                                            <label class="form-check-label" for="fmtPdf">PDF (.pdf)</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-bold text-secondary">E-posta Alıcıları</label>
                                    <textarea name="recipients" class="form-control custom-input" rows="3"
                                        placeholder="Her mailin arasına virgül koyun (örn: mudur@koksan.com, sef@koksan.com)" required></textarea>
                                </div>
                            </div>

                            <div class="mt-5 d-grid">
                                <button type="submit" class="btn btn-primary btn-lg py-3 shadow">
                                    <i class="bi bi-clock-fill me-2"></i>Zamanlanmış Raporu Kaydet
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
