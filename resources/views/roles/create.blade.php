@extends('layouts.app')

@section('title', 'Yeni Rol Ekle')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem;">
                    <div class="card-header bg-transparent border-0 text-center pt-4 pb-2">
                        <h4 class="fw-bold text-secondary">Yeni Rol Tanımla</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('roles.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold">Rol Adı</label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Örn: Muhasebe Müdürü" required>
                                <small class="text-muted">Slug (kod) otomatik oluşturulacaktır.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">Kaydet</button>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('roles.index') }}" class="text-decoration-none text-muted">İptal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
