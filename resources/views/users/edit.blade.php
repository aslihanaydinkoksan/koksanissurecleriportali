@extends('layouts.master')

@section('title', 'Kullanıcı Düzenle')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-warning">
                        <h5 class="mb-0 text-dark">Kullanıcı Düzenle: {{ $user->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Adı Soyadı</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">E-Posta</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rolü</label>
                                <select name="role" class="form-select">
                                    <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Personel</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Yönetici</option>
                                </select>
                            </div>

                            <hr>
                            <div class="alert alert-light border">
                                <small><i class="fa fa-info-circle"></i> Şifreyi değiştirmek istemiyorsanız aşağıdaki
                                    alanları boş bırakın.</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Yeni Şifre (Opsiyonel)</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Yeni Şifre Tekrar</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-warning px-4">Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
