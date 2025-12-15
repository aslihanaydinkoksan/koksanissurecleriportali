@extends('layouts.app')

@section('title', 'Sevkiyat Detayı')
@section('content')

    <style>
        .shipment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .shipment-header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }

        .shipment-header .badge {
            font-size: 14px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
        }

        .action-buttons .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: 1px solid #e5e7eb;
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f3f4f6;
        }

        .info-card-header i {
            font-size: 20px;
            color: #667eea;
        }

        .info-card-header h6 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
        }

        .info-table {
            width: 100%;
        }

        .info-table tr {
            border-bottom: 1px solid #f3f4f6;
        }

        .info-table th {
            padding: 12px 0;
            font-weight: 500;
            color: #6b7280;
            font-size: 14px;
            width: 45%;
        }

        .info-table td {
            padding: 12px 0;
            color: #1f2937;
            font-size: 14px;
            font-weight: 500;
        }

        .current-load-box {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            color: white;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .current-load-box.empty {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .current-load-box .load-amount {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
        }

        .stops-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .stops-table thead th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            padding: 12px 16px;
            border-bottom: 2px solid #e5e7eb;
            text-align: left;
        }

        .stops-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
            vertical-align: middle;
        }

        .stops-table .factory-row {
            background: #fef3c7;
            font-weight: 600;
        }

        .badge-index {
            background: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
        }

        .amount-dropped {
            color: #dc2626;
            font-weight: 600;
        }

        .amount-remaining {
            color: #059669;
            font-weight: 600;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
        }

        .modal-header {
            border-bottom: none;
            padding: 24px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
        }

        /* Başlangıçta gizli olanlar */
        .print-header,
        .print-signature-area {
            display: none;
        }


        /* ==========================================
                               2. YAZDIRMA İÇİN KLASİK TASARIM (Burayı Düzenledim)
                               ========================================== */
        @media print {

            /* Gereksizleri Gizle */
            .btn,
            .sidebar,
            nav,
            header,
            footer,
            .alert,
            .breadcrumb,
            .no-print,
            .action-buttons,
            .modal {
                display: none !important;
            }

            /* İkonları Gizle (Resmi evrakta ikon olmaz) */
            i.fas,
            i.far {
                display: none !important;
            }

            /* Sayfa Ayarları */
            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 1.5cm !important;
                background-color: white !important;
                color: black !important;
                font-family: 'Times New Roman', Times, serif;
                /* Klasik Font */
                font-size: 11pt;
                -webkit-print-color-adjust: exact;
            }

            /* Layout Sıfırlama */
            .container-fluid,
            .row,
            .col-md-4,
            .col-md-8 {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                display: block !important;
            }

            /* Yan Yana Düzen (Flex ile kağıtta yer kazanma) */
            .row {
                display: flex !important;
                flex-wrap: wrap;
            }

            .col-md-8 {
                width: 65% !important;
            }

            .col-md-4 {
                width: 35% !important;
            }

            /* Kart Stillerini SIFIRLA (Düz Çizgili Yap) */
            .info-card {
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin-bottom: 20px !important;
            }

            .info-card-header {
                border-bottom: 2px solid #000 !important;
                margin-bottom: 10px !important;
                padding-bottom: 5px !important;
            }

            .info-card-header h6 {
                font-size: 14pt !important;
                font-weight: bold !important;
                text-transform: uppercase;
                color: black !important;
            }

            /* Tabloları Siyah-Beyaz ve Çizgili Yap */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                border: 1px solid #000 !important;
            }

            th,
            td {
                border: 1px solid #000 !important;
                padding: 6px !important;
                color: black !important;
                background: white !important;
                /* Arka plan renklerini sil */
            }

            /* Tablo Başlıkları Gri Olsun */
            thead th,
            .info-table th {
                background-color: #eee !important;
                font-weight: bold !important;
                color: black !important;
            }

            /* Güncel Yük Kutusunu Sadece Çerçeve Yap */
            .current-load-box,
            .current-load-box.empty {
                background: white !important;
                color: black !important;
                border: 2px solid black !important;
                border-radius: 0 !important;
                padding: 10px !important;
                margin-left: 10px;
                display: block !important;
                text-align: center;
            }

            .current-load-box small {
                font-size: 10pt;
                font-weight: bold;
                text-decoration: underline;
                color: black !important;
            }

            .current-load-box .load-amount {
                font-size: 24pt !important;
                color: black !important;
            }

            /* Renkli sayıları siyaha çevir */
            .amount-dropped,
            .amount-remaining,
            .badge-index {
                color: black !important;
                background: none !important;
                font-weight: normal !important;
                border: none !important;
                padding: 0 !important;
            }

            /* Fabrika satırının sarı rengini sil, kalın yap */
            .stops-table .factory-row {
                background-color: white !important;
                font-weight: bold !important;
            }

            /* Görünür Alanlar */
            .print-header {
                display: block !important;
                text-align: center;
                border-bottom: 2px solid black;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }

            .print-header h2 {
                margin: 0;
                font-size: 18pt;
                font-weight: bold;
            }

            .print-header p {
                margin: 5px 0;
                font-size: 14pt;
            }

            .print-signature-area {
                display: flex !important;
                justify-content: space-between;
                margin-top: 50px;
                page-break-inside: avoid;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="print-header">
            <h2>KÖKSAN PET ve PLASTİK AMBALAJ SAN. ve TİC. A.Ş.</h2>
            <p>SEVKİYAT VE DAĞITIM TAKİP FORMU</p>
            <small>Form Tarihi: {{ now()->format('d.m.Y') }} | Belge No: #{{ $shipment->id }}</small>
        </div>

        <div class="shipment-header no-print">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Sevkiyat Detayı <span class="badge">#{{ $shipment->id }}</span></h1>
                    <p class="mb-0 mt-2" style="opacity: 0.9;">
                        <i class="fas fa-calendar-alt"></i>
                        {{ \Carbon\Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y H:i') }}
                    </p>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('shipments.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                    <button onclick="window.print()" class="btn btn-warning">
                        <i class="fas fa-print"></i> Yazdır
                    </button>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success no-print">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger no-print">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-truck"></i>
                <h6>Araç ve Yük Bilgileri</h6>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <table class="info-table">
                        <tr>
                            <th><i class="fas fa-id-card text-muted"></i> Plaka / Araç</th>
                            <td>{{ $shipment->plaka ?? $shipment->gemi_adi }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-user text-muted"></i> Şoför / Kaptan</th>
                            <td>{{ $shipment->sofor_adi ?? $shipment->imo_numarasi }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-weight-hanging text-muted"></i> Başlangıç Yükü</th>
                            <td><strong>{{ number_format($shipment->kargo_miktari, 2) }} Ton</strong></td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-map-marker-alt text-muted"></i> Kalkış Noktası</th>
                            <td>{{ $shipment->kalkis_noktasi ?? $shipment->kalkis_limani }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-flag-checkered text-muted"></i> Varış Noktası</th>
                            <td>{{ $shipment->varis_noktasi ?? $shipment->varis_limani }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-calendar text-muted"></i> Çıkış Tarihi</th>
                            <td>{{ \Carbon\Carbon::parse($shipment->cikis_tarihi)->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <th><i class="fas fa-clock text-muted"></i> Çıkış Saati</th>
                            <td>{{ \Carbon\Carbon::parse($shipment->cikis_tarihi)->format('H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <div class="current-load-box {{ $shipment->latest_remaining_amount == 0 ? 'empty' : '' }}">
                        <small>Araçtaki Güncel Yük</small>
                        <div class="load-amount">{{ number_format($shipment->latest_remaining_amount, 2) }} Ton</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-header">
                <i class="fas fa-list-alt"></i>
                <h6>Dağıtım Durakları (Teslimat Listesi)</h6>
                <div class="ms-auto">
                    @if ($shipment->latest_remaining_amount > 0)
                        <button type="button" class="btn btn-primary btn-sm no-print" data-bs-toggle="modal"
                            data-bs-target="#addStopModal">
                            <i class="fas fa-plus"></i> Yeni Durak Ekle
                        </button>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="stops-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Lokasyon / Müşteri</th>
                            <th style="width: 140px;">Tarih</th>
                            <th style="width: 110px;">İndirilen</th>
                            <th style="width: 110px;">Kalan</th>
                            <th style="width: 200px;">Not</th>
                            <th class="no-print" style="width: 80px;">İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="factory-row">
                            <td>-</td>
                            <td><strong><i class="fas fa-industry"></i> FABRİKA ÇIKIŞ</strong></td>
                            <td>{{ \Carbon\Carbon::parse($shipment->created_at)->format('d.m.Y') }}</td>
                            <td>-</td>
                            <td class="amount-remaining"><strong>{{ (float) $shipment->kargo_miktari }}</strong></td>
                            <td><small>İlk Yükleme</small></td>
                            <td class="no-print"></td>
                        </tr>

                        @foreach ($shipment->stops as $index => $stop)
                            <tr>
                                <td><span class="badge-index">{{ $index + 1 }}</span></td>
                                <td><strong>{{ $stop->location_name }}</strong></td>
                                <td>
                                    @if ($stop->stop_date)
                                        {{ \Carbon\Carbon::parse($stop->stop_date)->format('d.m.Y') }}
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i>
                                            {{ \Carbon\Carbon::parse($stop->stop_date)->format('H:i') }}
                                        </small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="amount-dropped">
                                    <i class="fas fa-arrow-down"></i> {{ (float) $stop->dropped_amount }}
                                </td>
                                <td class="amount-remaining">
                                    <i class="fas fa-box"></i> {{ (float) $stop->remaining_amount }}
                                </td>
                                <td><small>{{ $stop->note }}</small></td>
                                <td class="no-print">
                                    <form action="{{ route('shipments.stops.destroy', $stop->id) }}" method="POST"
                                        onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Sil">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="print-signature-area">
            <div class="signature-box"
                style="text-align: center; width: 30%; border-top: 1px solid black; padding-top: 5px;">
                <strong>Şoför / Teslim Eden</strong><br>
                .........................<br>
                <br>İmza
            </div>
            <div class="signature-box"
                style="text-align: center; width: 30%; border-top: 1px solid black; padding-top: 5px;">
                <strong>Lojistik Sorumlusu</strong><br>
                .........................<br>
                <br>İmza
            </div>
            <div class="signature-box"
                style="text-align: center; width: 30%; border-top: 1px solid black; padding-top: 5px;">
                <strong>Müşteri / Teslim Alan</strong><br>
                .........................<br>
                <br>İmza
            </div>
        </div>
    </div>

    <div class="modal fade" id="addStopModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('shipments.stops.store', $shipment->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Yeni Teslimat Girişi</h5>
                        <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Güncel Yük:
                            <strong>{{ number_format($shipment->latest_remaining_amount, 2) }} Ton</strong>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-map-marker-alt"></i> Gidilen Lokasyon / Firma Adı <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="location_name" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-weight"></i> İndirilen Miktar (Ton) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0.01"
                                        max="{{ $shipment->latest_remaining_amount }}" class="form-control"
                                        name="dropped_amount" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-check"></i> Tarih/Saat <span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" class="form-control" name="stop_date"
                                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-user-check"></i> Teslim Alan Kişi</label>
                            <input type="text" class="form-control" name="receiver_name">
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-sticky-note"></i> Notlar</label>
                            <textarea class="form-control" name="note" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> İptal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
