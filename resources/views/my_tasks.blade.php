@extends('layouts.app')

@section('title', 'Görevlerim')

@push('styles')
    <style>
        /* teams.index.blade.php dosyasından kopyalandı */
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

        /* Yeni Kart Stili */
        .task-card {
            border-radius: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
            border: 0;
            background-color: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .task-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .task-card .card-header {
            font-weight: bold;
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .task-card .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.7em;
            border-radius: 0.5rem;
        }

        /* Görev türüne göre etiket renkleri */
        .badge-company_vehicle {
            background-color: #0d6efd;
            color: white;
        }

        .badge-logistics {
            background-color: #fd7e14;
            color: white;
        }

        .badge-personal {
            background-color: #198754;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="mb-4" style="color: #444;">Bana Atanan Görevler</h2>

                @if ($tasks->isEmpty())
                    <div class="alert alert-info" role="alert">
                        Size atanmış veya takımlarınıza atanmış bir görev bulunmamaktadır.
                    </div>
                @else
                    @foreach ($tasks as $task)
                        <div class="card task-card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    {{ $task->task_description }}

                                    {{-- Görev Türü Etiketi --}}
                                    <span class="badge ms-2 badge-{{ $task->assignment_type }}">
                                        @if ($task->assignment_type == 'company_vehicle')
                                            Şirket Aracı
                                        @elseif ($task->assignment_type == 'logistics')
                                            Nakliye
                                        @elseif ($task->assignment_type == 'personal')
                                            Kişisel
                                        @endif
                                    </span>
                                </div>

                                {{-- Sorumlu (Eğer bir takımsa) --}}
                                @if ($task->responsible_type == 'App\Models\Team')
                                    <span class="badge bg-secondary">
                                        {{-- <i class="fas fa-users me-1"></i> --}}
                                        Takım: {{ $task->responsible->name }}
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- Görevi yaratan (user_id ilişkisi) --}}
                                        <p class="mb-2"><strong>Oluşturan:</strong> {{ $task->user->name }}</p>
                                        <p class="mb-2"><strong>Gidilecek Yer:</strong> {{ $task->destination ?? '-' }}
                                        </p>
                                        <p class="mb-0"><strong>Notlar:</strong> {{ $task->notes ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        {{-- Kaynak (Araç) --}}
                                        {{-- resource ilişkisi (Vehicle) doluysa --}}
                                        @if ($task->resource_type == 'App\Models\Vehicle' && $task->resource)
                                            <p class="mb-2"><strong>Araç:</strong> {{ $task->resource->plate_number }}
                                                ({{ $task->resource->type }})
                                            </p>
                                        @endif

                                        {{-- Nakliye Detayları --}}
                                        @if ($task->assignment_type == 'logistics')
                                            <p class="mb-2"><strong>Başlangıç KM:</strong> {{ $task->start_km }}</p>
                                            <p class="mb-2"><strong>Yakıt Durumu:</strong> {{ $task->start_fuel_level }}
                                            </p>

                                            {{-- Yakıt Maliyeti (Girilmişse) --}}
                                            @if ($task->fuel_cost)
                                                <p class="mb-2"><strong>Yakıt Maliyeti:</strong> {{ $task->fuel_cost }}
                                                    TL</p>
                                            @else
                                                <p class="mb-2 text-muted"><em>(İş bitiminde yakıt maliyeti girilecek)</em>
                                                </p>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-light text-muted">
                                <strong>Görev Zamanı:</strong>
                                {{ $task->start_time ? $task->start_time->translatedFormat('d F Y, H:i') : 'Belirtilmemiş' }}
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-4">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
