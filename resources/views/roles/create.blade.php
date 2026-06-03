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
                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold">Bağlı Olduğu Departman</label>
                                <select name="department_id" class="form-select form-select-lg border-2 shadow-sm">
                                    <option value="">Sistem Rolü (Departman Bağımsız)</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }} Departmanı</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Örn: 'Satış Yöneticisi' için 'İdari İşler'i seçin. Admin/Kullanıcı
                                    için boş bırakın.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fw-bold border-bottom pb-2 w-100"><i class="fas fa-shield-alt text-primary me-2"></i> Role Ait Yetkiler</label>
                                <div class="row g-3 mt-1">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-check custom-control custom-checkbox p-3 border rounded shadow-sm bg-light h-100" style="transition: all 0.2s;">
                                                <input class="form-check-input ms-1 me-2" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}" style="transform: scale(1.2);">
                                                <label class="form-check-label fw-bold text-dark pt-1" for="perm_{{ $permission->id }}" style="cursor:pointer;">
                                                    {{ __('permissions.' . $permission->name) ?? $permission->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
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
