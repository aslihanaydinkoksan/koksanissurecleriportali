@extends('layouts.app')

@section('title', 'Müşteri Bilgilerini Düzenle')

@push('styles')
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {
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

        /* Modern kart tasarımı */
        .customer-card {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 1.25rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: none;
            backdrop-filter: blur(10px);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
            border-bottom: 2px solid rgba(102, 126, 234, 0.1);
            padding: 1.5rem 2rem;
        }

        .card-header h4 {
            color: #2d3748;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .card-header h4 i {
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .card-body {
            padding: 2rem;
        }

        /* Müşteri adı badge */
        .customer-name-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%);
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 2rem;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        /* Animasyonlu buton */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* İptal butonu */
        .btn-outline-secondary {
            border: 2px solid #6c757d;
            background-color: transparent;
            color: #6c757d;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Form bölümü */
        .form-section {
            background-color: rgba(255, 255, 255, 0.6);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* Alt buton alanı */
        .action-buttons {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-top: 2rem;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            flex-wrap: wrap;
        }

        /* Uyarı kutusu */
        .edit-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.1) 100%);
            border-left: 4px solid #F59E0B;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .edit-warning i {
            color: #F59E0B;
            font-size: 1.25rem;
        }

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }

            .customer-name-badge {
                font-size: 0.9rem;
                padding: 0.4rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-9">
                <div class="customer-card">
                    <div class="card-header">
                        <h4>
                            <i class="fa-solid fa-pen-to-square"></i>
                            Müşteri Bilgilerini Düzenle
                        </h4>
                    </div>

                    <div class="card-body">
                        <div class="text-center">
                            <span class="customer-name-badge">
                                <i class="fa-solid fa-building me-2"></i>
                                {{ $customer->name }}
                            </span>
                        </div>

                        <div class="edit-warning d-flex align-items-start">
                            <i class="fa-solid fa-info-circle me-3 mt-1"></i>
                            <div>
                                <strong class="d-block mb-1">Düzenleme Modu</strong>
                                <small class="text-muted">
                                    Bu müşteriye ait mevcut bilgileri güncelliyorsunuz. Değişiklikleri kaydetmek için formu
                                    doldurup "Değişiklikleri Kaydet" butonuna tıklayın.
                                </small>
                            </div>
                        </div>

                        <form action="{{ route('customers.update', $customer) }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-section">
                                @include('customers._form', ['customer' => $customer])
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-times me-2"></i>
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-animated-gradient">
                                    <i class="fa-solid fa-check me-2"></i>
                                    Değişiklikleri Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-3"
                    style="background: rgba(255, 255, 255, 0.7); border: 2px solid rgba(102, 126, 234, 0.2);">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-2" style="color: #2d3748;">
                                <i class="fa-solid fa-rocket me-2" style="color: #667EEA;"></i>
                                Hızlı Erişim
                            </h6>
                            <p class="mb-0 text-muted small">
                                Müşteri detay sayfasından makine, test sonucu ve şikayet bilgilerini yönetebilirsiniz.
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('customers.show', $customer) }}"
                                class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                <i class="fa-solid fa-eye me-2"></i>
                                Detaylara Git
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
