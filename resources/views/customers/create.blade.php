@extends('layouts.app')

@section('title', 'Yeni Müşteri Ekle')

@section('content')
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg,
                    #dbe4ff,
                    #fde2ff,
                    #d9fcf7,
                    #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        /* Arka plan dalgalanma animasyonu */
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

        /* === GÜNCELLENDİ (create-event-card) === */
        .create-event-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .create-event-card .card-header,
        .create-event-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .create-event-card .card-header {
            color: #000;
        }

        .create-event-card .form-control,
        .create-event-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* === Dinamik satır CSS'leri (plan-detail-row) kaldırıldı === */

        /* Animasyonlu buton (Değişiklik yok) */
        .btn-animated-gradient {
            background: linear-gradient(-45deg,
                    #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="customer-card">
                    <div class="card-header bg-transparent border-0 px-4 pt-4">
                        <h4 class="mb-0">Yeni Müşteri Bilgileri</h4>
                    </div>
                    <div class="card-body px-4">

                        <form action="{{ route('customers.store') }}" method="POST">
                            @csrf
                            @include('customers._form', ['customer' => null])

                            <div class="text-end mt-3">
                                <a href="{{ route('customers.index') }}"
                                    class="btn btn-secondary rounded-pill px-4 me-2 btn-modern">
                                    İptal
                                </a>
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">
                                    <i class="fa-solid fa-save me-1"></i> Müşteriyi Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
