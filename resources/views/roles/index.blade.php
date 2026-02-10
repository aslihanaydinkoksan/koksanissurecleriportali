@extends('layouts.app')

@section('title', 'Rol Yönetimi')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-secondary">🎭 Rol Listesi</h5>
                <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                    + Yeni Rol Ekle
                </a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Rol Adı</th>
                            <th>Slug (Kod)</th>
                            <th>Kullanıcı Sayısı</th>
                            <th class="text-end pe-4">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $role->name }}</td>
                                <td><span class="badge bg-secondary text-light">{{ $role->slug }}</span></td>
                                <td>{{ $role->users()->count() }} Kişi</td>
                                <td class="text-end pe-4">
                                    {{-- Admin rolü düzenlenemesin/silinemesin --}}
                                    @if ($role->slug !== 'admin')
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="btn btn-sm btn-outline-primary me-1">
                                            ✏️ Düzenle
                                        </a>

                                        {{-- Temel roller silinemesin --}}
                                        @if (!in_array($role->slug, ['admin', 'kullanici']))
                                            <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Bu rolü silmek istediğinize emin misiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑️
                                                    Sil</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-muted fst-italic text-small">Sistem Rolü</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
