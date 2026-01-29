<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
        }

        .header {
            background: #1D3557;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }

        table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 12px;
        }

        .footer {
            font-size: 11px;
            color: #777;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin:0;">{{ $reportName }}</h2>
        <div style="font-size: 14px; opacity: 0.9; margin-top:5px;">Köksan Otomatik Bilgilendirme Servisi</div>
    </div>

    <div class="content">
        <p>Sayın İlgili,</p>

        <p><strong>Köksan Misafirhane Yönetim Sistemi</strong> tarafından hazırlanan periyodik raporunuz oluşturulmuş ve
            ekte sunulmuştur.</p>

        <div
            style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #1D3557;">
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 13px; line-height: 1.6;">
                <li><strong>Rapor Adı:</strong> {{ $reportName }}</li>
                <li><strong>Oluşturulma:</strong> {{ date('d.m.Y H:i') }}</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="https://kys.koksan.com/koksan_misafirhane/"
                style="background-color: #1D3557; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px; display: inline-block;">
                <i class="fas fa-external-link-alt"></i> Uygulamaya Git
            </a>
        </div>
    </div>

</html>
