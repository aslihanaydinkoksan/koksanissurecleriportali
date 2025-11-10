@extends('layouts.app')

@section('title', 'Müşteri Yönetimi')

<style>
    /* Daha modern ve temiz bir kart görünümü */
    .customer-card {
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease;
    }

    .customer-card:hover {
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        transform: translateY(-2px);
    }

    /* DÜZELTME: '.table-hover' kuralı, yeni 'tbody td' kuralından
      daha spesifik (öncelikli) olmalı, bu yüzden onu koruyoruz.
    */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa !important;
        /* Hover'ın her zaman çalışması için !important eklendi */
    }

    .btn-outline-primary {
        border-color: #667EEA;
        color: #667EEA;
    }

    .btn-outline-primary:hover {
        background-color: #667EEA;
        color: #fff;
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #667EEA, #5a6ed0);
        color: white;
        border: none;
        font-weight: 500;
    }

    /* MODERN BUTON STİLLERİ */
    .btn.btn-modern {
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.07);
    }

    .btn.btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.1);
    }

    /* ARAMA KUTUSU STİLİ */
    .search-group .form-control {
        border-end-start-radius: 0.5rem;
        border-start-end-radius: 0.5rem;
    }

    .search-group .btn {
        border-end-start-radius: 0.5rem;
        border-start-end-radius: 0.5rem;
    }

    .table {
        background-color: transparent;
    }

    .table thead th {
        background-color: transparent;
        border-bottom: 2px solid #dee2e6;
    }

    .table>tbody>tr>td {
        background-color: transparent;
    }
</style>

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div
                    class="card-header bg-transparent border-0 px-4 pt-4 pb-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Müşteri Listesi</h4>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary-gradient rounded-pill px-4 btn-modern">
                        <i class="fa-solid fa-plus me-1"></i> Yeni Müşteri Ekle
                    </a>
                </div>
                <div class="card-body px-4">

                    <form method="GET" action="{{ route('customers.index') }}" class="mb-3">
                        <div class="input-group search-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="Müşteri adı, email veya telefona göre ara..." value="{{ $search ?? '' }}">

                            <button class="btn btn-outline-primary btn-modern" type="submit"
                                style="border-left: 1px solid #667EEA;">
                                <i class="fa-solid fa-search me-1"></i> Ara
                            </button>
                        </div>
                    </form>


                    <table class="table table-hover align-middle">
                        <thead class="">
                            <tr>
                                <th scope="col">Müşteri Adı</th>
                                <th scope="col">Email</th>
                                <th scope="col">Telefon</th>
                                <th scope="col">İlgili Kişi</th>
                                <th scope="col" class="text-end">Eylemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td><strong>{{ $customer->name }}</strong></td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td>{{ $customer->phone ?? '-' }}</td>
                                    <td>{{ $customer->contact_person ?? '-' }}</td>
                                    <td class="text-end">

                                        {{-- GÜNCELLENDİ: 'btn-modern' eklendi --}}
                                        <a href="{{ route('customers.show', $customer) }}"
                                            class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-modern">
                                            <i class="fa-solid fa-eye me-1"></i> Görüntüle
                                        </a>

                                        {{-- GÜNCELLENDİ: 'btn-modern' eklendi --}}
                                        <a href="{{ route('customers.edit', $customer) }}"
                                            class="btn btn-sm btn-outline-secondary rounded-pill px-3 btn-modern">
                                            <i class="fa-solid fa-pen me-1"></i> Düzenle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Kayıtlı müşteri bulunamadı.
                                    </td>
                                </tr>
                            @endforelse {{-- <-- HATALI OLAN @endSect DİREKTİFİ BURADA @endforelse OLARAK DÜZELTİLDİ --}}
                        </tbody>
                    </table>


                    <div class="mt-3 d-flex justify-content-center">
                        {{ $customers->appends(['search' => $search])->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
