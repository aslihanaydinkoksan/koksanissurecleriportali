@extends('layouts.app')
@section('title', 'Yeni Departman Ekle')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">Yeni Departman Oluştur</h4>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('departments.store') }}" method="POST">
                            @csrf
                            @include('departments._form', ['department' => null])
                            <div class="text-end mt-3">
                                <a href="{{ route('departments.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4 me-2">İptal</a>
                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    Departmanı Kaydet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
