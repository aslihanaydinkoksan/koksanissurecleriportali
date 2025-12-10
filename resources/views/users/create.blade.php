@extends('layouts.master')

@section('title', 'Yeni Kullanıcı Ekle')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Yeni Sistem Kullanıcısı</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Adı Soyadı</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">E-Posta Adresi (Giriş için)</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rolü</label>
                                <select name="role" class="form-select">
                                    <option value="staff">Personel (İdari İşler)</option>
                                    <option value="admin">Yönetici (Tam Yetki)</option>
                                </select>
                                <div class="form-text">Yönetici, kullanıcı ekleyip silebilir. Personel sadece sistemi
                                    kullanır.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Şifre</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Şifre Tekrar</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-dark px-4">Kaydet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
