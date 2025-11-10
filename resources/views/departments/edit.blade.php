@extends('layouts.app')
@section('title', 'Departmanı Düzenle')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4">
                        <h4 class="mb-0">"{{ $department->name }}" Düzenle</h4>
                    </div>
                    <div class="card-body px-4">
                        <form action="{{ route('departments.update', $department) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @php
                                // Ana sistem departmanlarının slug'ları
$coreSlugs = ['lojistik', 'uretim', 'hizmet'];
                                $isCore = in_array($department->slug, $coreSlugs);
                            @endphp
                            @include('departments._form', [
                                'department' => $department,
                                'isCore' => $isCore,
                            ])
                            <div class="text-end mt-3">
                                <a href="{{ route('departments.index') }}"
                                    class="btn btn-outline-secondary rounded-pill px-4 me-2">İptal</a>
                                <button type="submit" class="btn btn-primary-gradient rounded-pill px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    Güncelle
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
