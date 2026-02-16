<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ziyaret Formu #{{ $visit->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.5; color: #333; max-width: 210mm; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .info-table th, .info-table td { border: 1px solid #ccc; padding: 8px; text-align: left; vertical-align: top; }
        .info-table th { background-color: #f5f5f5; font-weight: bold; width: 150px; }
        .section-title { background-color: #333; color: white; padding: 5px 10px; font-weight: bold; margin-bottom: 10px; border-radius: 4px; }
        .content-box { border: 1px solid #ccc; padding: 10px; min-height: 100px; margin-bottom: 20px; background-color: #fff; }
        .signature-section { display: flex; justify-content: space-between; margin-top: 50px; }
        .signature-box { width: 40%; text-align: center; border-top: 1px solid #333; padding-top: 10px; }
        @media print {
            body { padding: 0; margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; font-size: 16px;">🖨️ Yazdır / PDF İndir</button>
    </div>

    <div class="header">
        <div>
            {{-- Logo buraya gelebilir --}}
            <h1>MÜŞTERİ ZİYARET FORMU</h1>
            <small>Form No: #{{ $visit->id }} | Tarih: {{ $visit->created_at->format('d.m.Y') }}</small>
        </div>
        <div style="text-align: right;">
            <h3>KÖKSAN PET PLASTİK AMBALAJ SAN. ve TİC. A.Ş.</h3><br>
            {{-- Servis Veren: {{ $visit->user->name }} --}}
        </div>
    </div>

    <div class="section-title">1. MÜŞTERİ VE ZİYARET BİLGİLERİ</div>
    <table class="info-table">
        <tr>
            <th>Firma</th>
            <td>{{ $visit->customer->name }}</td>
            <th>Ziyaret Tarihi</th>
            <td>{{ $visit->visit_date->format('d.m.Y H:i') }}</td>
        </tr>
        <tr>
            <th>Adres</th>
            <td>{{ $visit->customer->address }}</td>
            <th>Ziyaret Sebebi</th>
            <td>{{ $visit->visit_reason }}</td>
        </tr>
        <tr>
            <th>Görüşülenler</th>
            <td colspan="3">
                @if($visit->contact_persons)
                    {{ implode(', ', $visit->contact_persons) }}
                @else - @endif
            </td>
        </tr>
    </table>

    <div class="section-title">2. ÜRÜN VE TEKNİK DETAYLAR</div>
    <table class="info-table">
        <tr>
            <th>Ürün Tanımı</th>
            <td>{{ $visit->product->name ?? '-' }}</td>
            <th>Şikayet No</th>
            <td>{{ $visit->complaint_id ? '#'.$visit->complaint_id : '-' }}</td>
        </tr>
        <tr>
            <th>Barkod No</th>
            <td>{{ $visit->barcode ?? '-' }}</td>
            <th>Lot No</th>
            <td>{{ $visit->lot_no ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">3. TESPİTLER / YAPILAN İŞLEMLER</div>
    <div class="content-box">
        {!! nl2br(e($visit->findings)) !!}
    </div>

    <div class="section-title">4. SONUÇ / KARAR</div>
    <div class="content-box">
        {!! nl2br(e($visit->result)) !!}
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <strong>Müşteri Yetkilisi</strong><br>
            (Müşteri Kaşe / İmza)
        </div>
        <div class="signature-box">
            <strong>Servis Veren / Yetkili</strong><br>
            {{-- {{ $visit->user->name }}<br> --}}
            (Ad Soyad / İmza)
        </div>
    </div>
</body>
</html>