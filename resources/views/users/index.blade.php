@extends('layouts.app')

@section('title', 'Kullanıcı Listesi')

@push('styles')
    {{-- Google Fonts: Inter (Modern UI Font) --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            /* Indigo */
            --primary-hover: #4338ca;
            --secondary-color: #64748b;
            --bg-color: #f3f4f6;
            --card-bg: #ffffff;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
        }

        /* --- ANA KAPLAYICI --- */
        #app>main.py-4 {
            padding: 2rem 0 !important;
            min-height: 100vh;
        }

        /* --- KART TASARIMI --- */
        .modern-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        /* --- HEADER ALANI --- */
        .card-header-custom {
            padding: 1.5rem 2rem;
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header-title h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            letter-spacing: -0.025em;
        }

        .header-title small {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* --- BUTONLAR --- */
        .btn-modern {
            background: var(--primary-color);
            color: white;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);
            text-decoration: none;
        }

        .btn-modern:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.3);
            color: white;
        }

        /* --- TABLO TASARIMI --- */
        .table-responsive {
            overflow-x: auto;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }

        .table-custom th {
            background-color: #f9fafb;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
        }

        .table-custom td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            background: #fff;
            color: var(--text-main);
            transition: background 0.2s;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .table-custom tbody tr:hover td {
            background-color: #f8fafc;
        }

        /* --- AVATAR VE İSİM --- */
        .user-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar-initials {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            color: #4338ca;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .user-info h6 {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
        }

        .user-info span {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        /* --- ROZETLER (BADGES) --- */
        .badge-pill {
            padding: 0.35em 0.8em;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-right: 4px;
            margin-bottom: 2px;
        }

        .badge-role-admin {
            background-color: #ede9fe;
            color: #6d28d9;
        }

        .badge-role-user {
            background-color: #e0f2fe;
            color: #0369a1;
        }

        .badge-dept {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid transparent;
        }

        /* --- AKSİYON BUTONLARI --- */
        .action-group {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            color: var(--secondary-color);
            transition: all 0.2s;
            background: transparent;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-icon:hover {
            background-color: #f3f4f6;
            color: var(--primary-color);
        }

        .btn-icon.delete:hover {
            background-color: #fef2f2;
            color: #dc2626;
        }

        /* --- BOŞ DURUM --- */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        /* --- PAGINATION --- */
        .pagination-container {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            background-color: #f9fafb;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">

                {{-- Başarı Mesajı (Modern Alert) --}}
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm d-flex align-items-center mb-4" role="alert"
                        style="background: #d1fae5; color: #065f46; border-radius: 8px;">
                        <span class="me-2 fs-5">✅</span>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <div class="modern-card">
                    {{-- Header --}}
                    <div class="card-header-custom">
                        <div class="header-title">
                            <h4>Kullanıcı Yönetimi</h4>
                            <small>Tüm kayıtlı kullanıcıları görüntüleyin ve yönetin.</small>
                        </div>
                        <a href="{{ route('users.create') }}" class="btn-modern">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Yeni Kullanıcı Ekle
                        </a>
                    </div>

                    {{-- Tablo --}}
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>Kullanıcı</th>
                                    <th>Sistem Rolü</th>
                                    <th>Bağlı Departmanlar</th> {{-- EKLENDİ --}}
                                    <th>Yetkili Birimler</th>
                                    <th>Kayıt Tarihi</th>
                                    <th class="text-end">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="user-meta">
                                                <div class="avatar-initials">{{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div class="user-info">
                                                    <h6>{{ $user->name }}</h6>
                                                    <span>{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @foreach ($user->roles as $role)
                                                <span
                                                    class="badge-pill {{ $role->name == 'admin' ? 'badge-role-admin' : 'badge-role-user' }}">
                                                    {{ __('roles.' . $role->name) }}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td> {{-- DEPARTMANLAR KOLONU (YENİ) --}}
                                            @forelse($user->departments as $dept)
                                                <span class="badge bg-light text-dark border">{{ $dept->name }}</span>
                                            @empty
                                                <span class="text-muted small">Belirtilmemiş</span>
                                            @endforelse
                                        </td>
                                        <td>
                                            @forelse($user->businessUnits as $unit)
                                                <span class="badge bg-secondary opacity-75">{{ $unit->name }}</span>
                                            @empty
                                                <span class="text-muted small">-</span>
                                            @endforelse
                                        </td>
                                        <td><span
                                                class="text-secondary small">{{ $user->created_at->format('d.m.Y') }}</span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('users.edit', $user) }}" class="btn-icon">✏️</a>
                                                @if (Auth::id() !== $user->id)
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Emin misiniz?');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn-icon delete">🗑️</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-icon">📂</div>
                                                <h6 class="text-muted">Kayıt bulunamadı.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Sayfalama --}}
                    @if ($users->hasPages())
                        <div class="pagination-container">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
