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
        <h2>{{ $reportName }}</h2>
    </div>
    <div class="content">
        <p>Sistem tarafından otomatik oluşturulan periyodik rapor ektedir.</p>

    </div>
    <div class="footer">
        Köksan Misafirhane Yönetim Sistemi &copy; {{ date('Y') }}
    </div>
</body>

</html>
