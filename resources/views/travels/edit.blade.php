@extends('layouts.app')

@section('title', 'Seyahat Planını Düzenle')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">"{{ $travel->name }}" Planını Düzenle</h4>
                    </div>
                    <div class="card-body px-4">

                        <form action="{{ route('travels.update', $travel) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @include('travels._form', ['travel' => $travel])

                            <div class="d-flex justify-content-between mt-3">
                                {{-- Silme Butonu --}}
                                <form action="{{ route('travels.destroy', $travel) }}" method="POST"
                                    onsubmit="return confirm('Bu seyahat planını silmek istediğinizden emin misiniz? Bağlı ziyaretler silinmez, bağımsız hale gelir.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                        <i class="fa-solid fa-trash-alt me-1"></i> Sil
                                    </button>
                                </form>

                                {{-- Kaydetme Grubu --}}
                                <div>
                                    <a href="{{ route('travels.index') }}"
                                        class="btn btn-outline-secondary rounded-pill px-4 me-2">
                                        İptal
                                    </a>
                                    <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                        style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                        <i class="fa-solid fa-save me-1"></i> Değişiklikleri Kaydet
                                    </button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
