@extends('layouts.app')

@section('title', 'Rol Düzenle')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0" style="border-radius: 1.5rem;">
                    <div class="card-header bg-transparent border-0 text-center pt-4 pb-2">
                        <h4 class="fw-bold text-secondary">Rolü Düzenle</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('roles.update', $role) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold">Rol Adı</label>
                                <input type="text" name="name" value="{{ old('name', $role->name) }}"
                                    class="form-control form-control-lg" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill">Güncelle</button>
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
