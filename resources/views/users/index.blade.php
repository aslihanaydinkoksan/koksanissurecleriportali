@extends('layouts.master')

@section('title', 'Kullanıcı Yönetimi')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3><i class="fa fa-user-shield text-dark"></i> Sistem Kullanıcıları</h3>
            <a href="{{ route('users.create') }}" class="btn btn-dark">
                <i class="fa fa-plus"></i> Yeni Kullanıcı
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>E-Posta</th>
                            <th>Rol</th>
                            <th>Kayıt Tarihi</th>
                            <th class="text-end">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="fw-bold">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->role == 'admin')
                                        <span class="badge bg-danger">Yönetici</span>
                                    @else
                                        <span class="badge bg-info text-dark">Personel</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d.m.Y') }}</td>

                                {{-- DÜZELTİLEN KISIM BAŞLANGIÇ --}}
                                <td class="text-end">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    @if ($user->id != auth()->id())
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                {{-- DÜZELTİLEN KISIM BİTİŞ --}}

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
