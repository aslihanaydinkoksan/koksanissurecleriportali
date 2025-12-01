@extends('layouts.app')

@section('title', 'Görev Detayları: ' . $assignment->title)

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
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

        /* Yüzen parçacık efekti */
        #app>main.py-4::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.06) 0%, transparent 50%);
            animation: floatingParticles 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes floatingParticles {

            0%,
            100% {
                transform: translate(0, 0);
            }

            33% {
                transform: translate(30px, -30px);
            }

            66% {
                transform: translate(-20px, 20px);
            }
        }

        /* Başlık Bölümü */
        .page-header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 1.75rem 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: slideInFromTop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideInFromTop {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header h1 {
            color: white;
            font-weight: 700;
            font-size: 1.75rem;
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .page-header .btn-back {
            background: rgba(255, 255, 255, 0.95);
            color: #1a202c;
            border: none;
            font-weight: 600;
            padding: 0.625rem 1.25rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .page-header .btn-back:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: #1a202c;
        }

        /* Ana Detay Kartı */
        .detail-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px) saturate(180%);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: cardSlideIn 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Kart Başlığı */
        .detail-card .card-header {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            backdrop-filter: blur(10px);
            color: #1a202c;
            font-weight: 700;
            font-size: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1.75rem 2rem;
        }

        .detail-card .card-header .badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Kart İçeriği */
        .detail-card .card-body {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
        }

        /* Bilgi Kutuları */
        .info-box {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }

        .info-box .info-label {
            font-size: 0.875rem;
            font-weight: 700;
            color: #667eea;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .info-box .info-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        /* Açıklama Kutuları */
        .description-box {
            background: linear-gradient(135deg, rgba(67, 233, 123, 0.08), rgba(56, 249, 215, 0.08));
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #43e97b;
            transition: all 0.3s ease;
        }

        .description-box:hover {
            box-shadow: 0 6px 20px rgba(67, 233, 123, 0.15);
        }

        .description-box h6 {
            font-weight: 700;
            color: #43e97b;
            font-size: 1.125rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .description-box p {
            color: #1a202c;
            font-weight: 500;
            line-height: 1.6;
            margin: 0;
        }

        /* Lojistik Bölümü */
        .logistics-section {
            background: linear-gradient(135deg, rgba(240, 147, 251, 0.1), rgba(245, 87, 108, 0.1));
            border-radius: 20px;
            padding: 2rem;
            margin-top: 2rem;
            border: 2px solid rgba(245, 87, 108, 0.3);
        }

        .logistics-section h5 {
            color: #f5576c;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logistics-table {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .logistics-table thead th {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
            padding: 1rem;
            border: none;
        }

        .logistics-table tbody tr {
            transition: all 0.3s ease;
        }

        .logistics-table tbody tr:hover {
            background: rgba(240, 147, 251, 0.05);
        }

        .logistics-table tbody td {
            padding: 1rem;
            color: #1a202c;
            font-weight: 500;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .logistics-table tbody td:first-child {
            font-weight: 700;
            color: #667eea;
        }

        /* Uyarı Kutusu */
        .alert-custom {
            background: linear-gradient(135deg, rgba(255, 195, 113, 0.15), rgba(251, 200, 212, 0.15));
            border: 2px solid rgba(255, 195, 113, 0.4);
            border-radius: 12px;
            padding: 1.25rem;
            color: #7c4a03;
            font-weight: 600;
            margin-top: 1.5rem;
        }

        /* Alt Bilgi */
        .card-footer-info {
            background: rgba(102, 126, 234, 0.05);
            padding: 1.25rem 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .card-footer-info span {
            color: #667eea;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* İşlem Butonları */
        .action-footer {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.9));
            padding: 1.5rem 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(255, 167, 38, 0.4);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .btn-edit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-edit:hover::before {
            left: 100%;
        }

        .btn-edit:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 12px 30px rgba(255, 167, 38, 0.5);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.25rem 1.5rem;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .detail-card .card-body {
                padding: 1.5rem;
            }

            .info-box {
                padding: 1.25rem;
            }

            .logistics-section {
                padding: 1.5rem;
            }

            .card-footer-info {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        /* Animasyon gecikmeleri */
        .info-box:nth-child(1) {
            animation: fadeInUp 0.6s ease 0.1s both;
        }

        .info-box:nth-child(2) {
            animation: fadeInUp 0.6s ease 0.2s both;
        }

        .info-box:nth-child(3) {
            animation: fadeInUp 0.6s ease 0.3s both;
        }

        .info-box:nth-child(4) {
            animation: fadeInUp 0.6s ease 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">

                {{-- BAŞLIK VE GERİ DÖN BUTONU --}}
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <h1 style="color: #1a202c">
                            <i class="fas fa-file-invoice me-2"></i> Görev Detayları
                        </h1>
                        <a href="{{ route('my-assignments.index') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left me-2"></i> Görevlerime Geri Dön
                        </a>
                    </div>
                </div>

                {{-- ANA DETAY KARTI --}}
                <div class="card detail-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <span>{{ $assignment->title }}</span>
                            <span class="badge bg-primary">{{ $assignment->getStatusNameAttribute() }}</span>
                        </div>
                    </div>

                    <div class="card-body">

                        {{-- TEMEL GÖREV BİLGİLERİ --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">
                                        <i class="fas fa-car"></i> Atanan Araç
                                    </div>
                                    <p class="info-value">
                                        {{ $assignment->vehicle->plate_number ?? 'Araç Yok' }}
                                        <small class="text-muted">({{ $assignment->vehicle->type ?? 'Genel' }})</small>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">
                                        @php
                                            $responsibleName = $assignment->responsible->name ?? 'Bilinmiyor';
                                            $responsibleType =
                                                $assignment->responsible_type === App\Models\User::class
                                                    ? 'Kişi'
                                                    : 'Takım';
                                        @endphp
                                        <i class="fas fa-{{ $responsibleType === 'Kişi' ? 'user' : 'users' }}"></i>
                                        Sorumlu
                                    </div>
                                    <p class="info-value">
                                        {{ $responsibleName }}
                                        <small class="text-muted">({{ $responsibleType }})</small>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">
                                        <i class="fas fa-clock"></i> Sefer Zamanı
                                    </div>
                                    <p class="info-value">
                                        {{ $assignment->start_time->format('d.m.Y H:i') }} -
                                        {{ $assignment->end_time->format('H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-box">
                                    <div class="info-label">
                                        <i class="fas fa-map-marker-alt"></i> Yer / Hedef
                                    </div>
                                    <p class="info-value">{{ $assignment->destination ?? 'Belirtilmedi' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- AÇIKLAMA VE NOTLAR --}}
                        <div class="description-box">
                            <h6>
                                <i class="fas fa-clipboard-list"></i> Görev Açıklaması
                            </h6>
                            <p>{{ $assignment->task_description }}</p>
                        </div>

                        <div class="description-box">
                            <h6>
                                <i class="fas fa-sticky-note"></i> Ek Notlar
                            </h6>
                            <p>{{ $assignment->notes ?? 'Ek not bulunmuyor.' }}</p>
                        </div>
                    </div>
                </div>

                {{-- NAKLİYE (LOJİSTİK) DETAYLARI --}}ac
                @if ($assignment->isLogistics())
                    <div class="logistics-section">
                        <h5>
                            <i class="fas fa-truck"></i> Nakliye / Lojistik Kayıtları
                        </h5>

                        <div class="table-responsive">
                            <table class="table logistics-table mb-0">
                                <thead>
                                    <tr>
                                        <th>Detay</th>
                                        <th>Başlangıç Değeri</th>
                                        <th>Bitiş Değeri</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Kilometre (KM)</td>
                                        <td>{{ $assignment->start_km ?? '-' }}</td>
                                        <td>{{ $assignment->end_km ?? 'Bekleniyor' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Yakıt Durumu</td>
                                        <td>{{ $assignment->start_fuel_level ?? '-' }}</td>
                                        <td>{{ $assignment->end_fuel_level ?? 'Bekleniyor' }}</td>
                                    </tr>
                                    @if ($assignment->fuel_cost)
                                        <tr>
                                            <td colspan="2">Yakıt Maliyeti</td>
                                            <td class="fw-bold">{{ number_format($assignment->fuel_cost, 2) }} TL
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if ($assignment->status !== 'completed')
                            <div class="alert-custom">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Görevi tamamlamak için <strong>Bitiş KM</strong> ve <strong>Yakıt
                                    Maliyeti</strong> alanlarını doldurmanız gerekebilir.
                            </div>
                        @endif
                    </div>
                @endif

                {{-- DOSYALAR --}}
                @if ($assignment->files && $assignment->files->count() > 0)
                    <div class="mt-5 pt-4 border-top">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="fw-bold mb-0 me-3 text-secondary">
                                <i class="fa-solid fa-paperclip me-2"></i> Ekli Dosyalar
                            </h5>
                            <span class="badge bg-secondary rounded-pill">{{ $assignment->files->count() }}</span>
                        </div>

                        <div class="row g-3">
                            @foreach ($assignment->files as $file)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border"
                                        style="transition: transform 0.2s; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                                        <div class="card-body d-flex align-items-center p-3">
                                            {{-- Dosya Türü İkonu --}}
                                            <div class="me-3 fs-1 text-secondary">
                                                @if (Str::contains($file->mime_type, 'image'))
                                                    <i class="fa-regular fa-file-image text-primary"></i>
                                                @elseif(Str::contains($file->mime_type, 'pdf'))
                                                    <i class="fa-regular fa-file-pdf text-danger"></i>
                                                @elseif(Str::contains($file->mime_type, 'excel') || Str::contains($file->mime_type, 'spreadsheet'))
                                                    <i class="fa-regular fa-file-excel text-success"></i>
                                                @else
                                                    <i class="fa-regular fa-file"></i>
                                                @endif
                                            </div>

                                            <div class="flex-grow-1 overflow-hidden">
                                                <h6 class="mb-1 text-truncate" title="{{ $file->original_name }}"
                                                    style="font-size: 0.95rem; font-weight: 600;">
                                                    {{ $file->original_name }}
                                                </h6>
                                                <div class="small text-muted" style="font-size: 0.75rem;">
                                                    {{ $file->uploader->name ?? 'Sistem' }} •
                                                    {{ $file->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-light border-top-0 p-2 text-end">
                                            <a href="{{ route('files.download', $file->id) }}"
                                                class="btn btn-sm btn-dark w-100" target="_blank">
                                                <i class="fa-solid fa-download me-2"></i> İndir / Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ALT BİLGİ --}}
                <div class="card-footer-info">
                    <span>
                        <i class="fas fa-user-circle me-2"></i>
                        <strong>Oluşturan:</strong> {{ $assignment->createdBy->name ?? 'Bilinmiyor' }}
                    </span>
                    <span>
                        <i class="fas fa-calendar-alt me-2"></i>
                        <strong>Oluşturulma Tarihi:</strong> {{ $assignment->created_at->format('d.m.Y') }}
                        <strong>Saati:</strong> {{ $assignment->created_at->format('H:i') }}
                    </span>


                </div>
            </div>

            @php
                $user = Auth::user();
                $canEdit =
                    $user->id === $assignment->created_by ||
                    $user->role === 'admin' ||
                    $user->role === 'müdür' ||
                    ($assignment->responsible_type === 'App\Models\User' && $assignment->responsible_id === $user->id);
            @endphp

            {{-- İŞLEM BUTONLARI --}}
            @if ($canEdit)
                <div class="action-footer text-end">
                    <a href="{{ route('service.assignments.edit', $assignment->id) }}" class="btn btn-edit">
                        <i class="fas fa-edit me-2"></i> Görevi Düzenle / Tamamla
                    </a>
                </div>
            @endif
        </div>

    </div>
    </div>
    </div>
@endsection
