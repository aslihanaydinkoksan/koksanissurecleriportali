<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <title>Oda Bilgi Fişi - {{ $location->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
        }

        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #eee;
            padding: 5px;
            font-weight: bold;
            border: 1px solid #000;
            margin-top: 15px;
        }

        .info-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        /* YAZDIRMA AYARLARI */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
                margin: 0;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="p-4">

    <div class="container">
        <div class="text-end mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg">🖨️ Yazdır</button>
            <button onclick="window.close()" class="btn btn-secondary btn-lg">Kapat</button>
        </div>

        <div class="header text-center">
            <h2>KÖKSAN PET ve PLASTİK AMBALAJ SAN. ve TİC. A.Ş.</h2>
            <h4>MİSAFİRHANE & LOJMAN BİLGİ FİŞİ</h4>
            <small>{{ now()->format('d.m.Y') }} Tarihinde Oluşturuldu</small>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="info-box">
                    <strong>Sayın Misafirimiz:</strong><br>
                    @if ($activeStay)
                        <h3>{{ $activeStay->resident->first_name }} {{ $activeStay->resident->last_name }}</h3>
                        <small>Giriş Tarihi: {{ $activeStay->check_in_date->format('d.m.Y') }}</small>
                    @else
                        <span class="text-muted">-- Boş Oda --</span>
                    @endif
                </div>
            </div>

            <div class="col-6">
                <div class="info-box text-end">
                    Lokasyon: <strong>{{ $location->parent ? $location->parent->name : 'Merkez' }}</strong><br>
                    Oda/Daire: <strong>{{ $location->name }}</strong>
                </div>
            </div>
        </div>

        <div class="section-title">📶 İNTERNET ERİŞİMİ</div>
        <div class="p-2 border border-top-0">
            <div class="row">
                <div class="col-md-6">Ağ Adı (SSID): <strong></strong></div>
                <div class="col-md-6">Wi-Fi Şifresi:
                    @if ($activeStay)
                        <strong>{{ $location->wifi_password ?? 'Henüz Tanımlanmadı' }}</strong>
                    @else
                        <strong>{{ $location->wifi_password ?? '-' }}</strong>
                    @endif
                </div>
            </div>
        </div>

        <div class="section-title">☎️ ACİL DURUM & TEKNİK DESTEK</div>
        <div class="p-2 border border-top-0">
            <p class="small fst-italic mb-2">
                Dairenizde yaşayacağınız teknik sorunlar için lütfen aşağıdaki numaralarla doğrudan iletişime geçiniz.
                İdari işler departmanını sadece çözülemeyen durumlarda arayınız.
            </p>
            <table class="table table-bordered table-sm mb-0">
                <thead>
                    <tr>
                        <th width="30%">Hizmet</th>
                        <th>Sorumlu Kişi / Firma</th>
                        <th>Telefon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contacts as $service => $contact)
                        <tr>
                            <td>{{ $service }}</td>
                            @if ($contact)
                                <td>{{ $contact->name }}</td>
                                <td><strong>{{ $contact->phone }}</strong></td>
                            @else
                                <td colspan="2" class="text-muted text-center">Site Yönetimine Danışınız</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($location->assets->count() > 0)
            <div class="section-title">🛋️ ZİMMETLİ DEMİRBAŞ LİSTESİ</div>
            <div class="p-2 border border-top-0">
                <table class="table table-striped table-sm mb-0">
                    <thead>
                        <tr>
                            <th>Ürün</th>
                            <th>Marka/Model</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($location->assets as $asset)
                            <tr>
                                <td>{{ $asset->name }}</td>
                                <td>{{ $asset->brand }}</td>
                                <td>
                                    @if ($asset->status == 'active')
                                        Sağlam
                                    @elseif($asset->status == 'broken')
                                        Arızalı
                                    @else
                                        {{ $asset->status }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 small">
                    * Yukarıdaki eşyalar eksiksiz teslim edilmiştir. Çıkışta kontrol edilecektir.
                </div>
            </div>
        @endif

        <div class="row mt-5">
            <div class="col-6 text-center">
                <p>Teslim Eden<br>(İdari İşler)</p>
                <br><br>
                ____________________
            </div>
            <div class="col-6 text-center">
                <p>Teslim Alan<br>(Misafir)</p>
                <br><br>
                ____________________
            </div>
        </div>

    </div>

</body>

</html>
