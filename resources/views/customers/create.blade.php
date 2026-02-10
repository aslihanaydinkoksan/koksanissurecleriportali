@extends('layouts.app')

@section('title', 'Yeni Müşteri Ekle')

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
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Form bölümü için özel stil */
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

        @media (max-width: 576px) {
            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
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
                            <i class="fa-solid fa-user-plus"></i>
                            Yeni Müşteri Bilgileri
                        </h4>
                    </div>

                    <div class="card-body">
                        {{-- Form Başlangıcı --}}
                        <form action="{{ route('customers.store') }}" method="POST" autocomplete="off"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="form-section">
                                {{-- Partial Form Çağırılıyor --}}
                                @include('customers._form', ['customer' => null])
                            </div>

                            <div class="action-buttons">
                                <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                                    <i class="fa-solid fa-times me-2"></i>
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-animated-gradient">
                                    <i class="fa-solid fa-save me-2"></i>
                                    Müşteriyi Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 p-4 rounded-3"
                    style="background: rgba(255, 255, 255, 0.7); border: 2px dashed rgba(102, 126, 234, 0.3);">
                    <div class="d-flex align-items-start">
                        <i class="fa-solid fa-lightbulb me-3 mt-1" style="color: #F59E0B; font-size: 1.5rem;"></i>
                        <div>
                            <h6 class="fw-bold mb-2" style="color: #2d3748;">İpucu</h6>
                            <p class="mb-0 text-muted small">
                                Müşteri kaydını tamamladıktan sonra müşteri detay sayfasından makine, test sonucu ve şikayet
                                bilgileri ekleyebilirsiniz. Ayrıca sınırsız sayıda iletişim kişisi tanımlayabilirsiniz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
