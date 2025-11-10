@extends('layouts.app')
@section('title', 'Departman Yönetimi')

<style>
    .customer-card {
        /* Customer'daki stili kullanalım */
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #667EEA, #5a6ed0);
        color: white;
        border: none;
        font-weight: 500;
    }
</style>

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Departman Yönetimi</h4>
                    <a href="{{ route('departments.create') }}" class="btn btn-primary-gradient rounded-pill px-4">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Departman Ekle
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="customer-card shadow-sm">
                    <div class="card-body px-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Departman Adı</th>
                                        <th scope="col">Kısa Kod (slug)</th>
                                        <th scope="col">Kullanıcı Sayısı</th>
                                        <th scope="col" class="text-end">Eylemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($departments as $department)
                                        <tr>
                                            <td>{{ $department->id }}</td>
                                            <td><strong>{{ $department->name }}</strong></td>
                                            <td><code>{{ $department->slug }}</code></td>
                                            <td>{{ $department->users_count }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('departments.edit', $department) }}"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                                </a>

                                                <form action="{{ route('departments.destroy', $department) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bu departmanı silmek istediğinizden emin misiniz? Bu departmandaki kullanıcılar birimsiz kalacaktır (Eğer izin verilmişse).');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                        {{-- Güvenlik: Admin olmayan veya varsayılan 3 departmanı silmeyi engelle --}}
                                                        @if (in_array($department->slug, ['lojistik', 'uretim', 'hizmet'])) disabled 
                                                            title="Ana departmanlar sistemden silinemez." @endif>
                                                        <i class="fa-solid fa-trash-alt me-1"></i> Sil
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                Kayıtlı departman bulunamadı.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
