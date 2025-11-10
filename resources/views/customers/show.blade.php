@extends('layouts.app')

@section('title', $customer->name)

<style>
    .customer-card {
        background-color: #ffffff;
        border-radius: 0.75rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: none;
    }

    .btn-outline-primary {
        border-color: #667EEA;
        color: #667EEA;
    }

    .btn-outline-primary:hover {
        background-color: #667EEA;
        color: #fff;
    }

    .nav-tabs .nav-link {
        border-bottom-width: 3px;
        border-color: transparent;
        color: #555;
    }

    .nav-tabs .nav-link.active {
        border-color: #667EEA;
        color: #333;
        font-weight: 500;
    }

    .detail-list dt {
        font-weight: 500;
        color: #555;
    }

    .detail-list dd {
        color: #222;
        font-weight: 400;
    }

    /* Hızlı Ekleme Formu Stili */
    .quick-add-form {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
    }

    .file-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0.75rem;
        background-color: #f1f3f5;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }
</style>

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="customer-card">
                    <div class="card-header bg-transparent border-0 px-4 pt-4">
                        {{-- BAŞLIK VE BUTONLAR --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="mb-1">{{ $customer->name }}</h4>
                                <span class="text-muted">Müşteri Detayları </span>
                            </div>
                            <div class="row g-2 mt-2">
                                <div class="col">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2 w-100">
                                        <i class="fa-solid fa-pen me-1"></i> Müşteri Bilgilerini Düzenle
                                    </a>
                                </div>
                                <div class="col">
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-grid"
                                        {{-- Formun da tam genişlik kaplamasını sağlar --}}
                                        onsubmit="return confirm('Bu müşteriyi silmek (arşivlemek) istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-danger rounded-pill px-3 me-2 w-100">
                                            <i class="fa-solid fa-trash-alt me-1"></i> Sil
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-4">

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        {{-- Hata mesajları (Eğer Hızlı Ekleme formlarında hata olursa) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Kayıt eklenirken bir hata oluştu:</strong>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- SEKMELİ YAPI BAŞLANGICI --}}
                        <ul class="nav nav-tabs mt-3" id="customerTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="details-tab" data-bs-toggle="tab"
                                    data-bs-target="#details" type="button" role="tab" aria-controls="details"
                                    aria-selected="true">
                                    <i class="fa-solid fa-user me-1"></i> Detaylar
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="machines-tab" data-bs-toggle="tab" data-bs-target="#machines"
                                    type="button" role="tab" aria-controls="machines" aria-selected="false">
                                    <i class="fa-solid fa-industry me-1"></i> Makineler ({{ $customer->machines->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="tests-tab" data-bs-toggle="tab" data-bs-target="#tests"
                                    type="button" role="tab" aria-controls="tests" aria-selected="false">
                                    <i class="fa-solid fa-vial me-1"></i> Test Sonuçları
                                    ({{ $customer->testResults->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="complaints-tab" data-bs-toggle="tab"
                                    data-bs-target="#complaints" type="button" role="tab" aria-controls="complaints"
                                    aria-selected="false">
                                    <i class="fa-solid fa-exclamation-triangle me-1"></i> Şikayetler
                                    ({{ $customer->complaints->count() }})
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="visits-tab" data-bs-toggle="tab" data-bs-target="#visits"
                                    type="button" role="tab" aria-controls="visits" aria-selected="false">
                                    <i class="fa-solid fa-calendar-days me-1"></i> Ziyaret Geçmişi
                                    ({{ $customer->visits->count() }})
                                </button>
                            </li>
                        </ul>

                        {{-- SEKME İÇERİKLERİ --}}
                        <div class="tab-content pt-4" id="customerTabContent">

                            <div class="tab-pane fade show active" id="details" role="tabpanel"
                                aria-labelledby="details-tab">
                                <h5>Müşteri İletişim Bilgileri</h5>
                                <dl class="row detail-list mt-3">
                                    <dt class="col-sm-3">İlgili Kişi</dt>
                                    <dd class="col-sm-9">{{ $customer->contact_person ?? '-' }}</dd>
                                    <dt class="col-sm-3">Email</dt>
                                    <dd class="col-sm-9">{{ $customer->email ?? '-' }}</dd>
                                    <dt class="col-sm-3">Telefon</dt>
                                    <dd class="col-sm-9">{{ $customer->phone ?? '-' }}</dd>
                                    <dt class="col-sm-3">Adres</dt>
                                    <dd class="col-sm-9">{{ $customer->address ?? '-' }}</dd>
                                </dl>
                            </div>

                            <div class="tab-pane fade" id="machines" role="tabpanel" aria-labelledby="machines-tab">
                                <h5>Hızlı Makine Ekle</h5>
                                <form action="{{ route('customers.machines.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-4">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="model" class="form-label">Model (*)</label>
                                            <input type="text" name="model" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="serial_number" class="form-label">Seri No</label>
                                            <input type="text" name="serial_number" class="form-control">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="installation_date" class="form-label">Kurulum Tarihi</label>
                                            <input type="date" name="installation_date" class="form-control">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary-gradient px-3">Makineyi
                                        Ekle</button>
                                </form>

                                <h5>Kayıtlı Makineler</h5>
                                <table class="table table-sm table-striped">
                                    <tbody>
                                        @forelse ($customer->machines as $machine)
                                            <tr>
                                                <td>{{ $machine->model }}</td>
                                                <td>{{ $machine->serial_number }}</td>
                                                <td>{{ $machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Bu müşteriye ait makine kaydı
                                                    bulunamadı.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="tests" role="tabpanel" aria-labelledby="tests-tab">
                                <h5>Hızlı Test Sonucu Yükle</h5>
                                {{-- Dosya yükleme için 'enctype' şart! --}}
                                <form action="{{ route('customers.test-results.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-4" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="test_name" class="form-label">Test Adı (*)</label>
                                            <input type="text" name="test_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="test_date" class="form-label">Test Tarihi (*)</label>
                                            <input type="date" name="test_date" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="test_files" class="form-label">Dosya(lar) (PDF, JPG...)</label>
                                            <input type="file" name="test_files[]" class="form-control" multiple>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="summary" class="form-label">Özet</label>
                                        <textarea name="summary" class="form-control" rows="2"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary-gradient px-3">Test Sonucunu
                                        Ekle</button>
                                </form>

                                <h5>Kayıtlı Test Sonuçları</h5>
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Test Adı</th>
                                            <th>Tarih</th>
                                            <th>Özet</th>
                                            <th>Dosyalar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customer->testResults as $result)
                                            <tr>
                                                <td>{{ $result->test_name }}</td>
                                                <td>{{ \Carbon\Carbon::parse($result->test_date)->format('d/m/Y') }}</td>
                                                <td>{{ $result->summary ?? '-' }}</td>
                                                <td>
                                                    {{-- YÜKLENEN DOSYALARI GÖSTER --}}
                                                    @foreach ($result->getMedia('test_reports') as $media)
                                                        <div class="file-list-item">
                                                            <span>
                                                                <i class="fa-solid fa-file-arrow-down me-2"></i>
                                                                {{ $media->file_name }}
                                                            </span>
                                                            <a href="{{ $media->getUrl() }}" target="_blank"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Bu müşteriye ait test sonucu
                                                    bulunamadı.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="tab-pane fade" id="complaints" role="tabpanel" aria-labelledby="complaints-tab">
                                <h5>Hızlı Şikayet Kaydı Ekle</h5>
                                <form action="{{ route('customers.complaints.store', $customer) }}" method="POST"
                                    class="quick-add-form mb-4" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="title" class="form-label">Şikayet Başlığı (*)</label>
                                            <input type="text" name="title" class="form-control" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="status" class="form-label">Durum (*)</label>
                                            <select name="status" class="form-select" required>
                                                <option value="open">Açık</option>
                                                <option value="in_progress">İşlemde</option>
                                                <option value="resolved">Çözüldü</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Detaylı Açıklama (*)</label>
                                        <textarea name="description" class="form-control" rows="3" required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="complaint_files" class="form-label">Kanıt Dosyaları (Resim,
                                            Video...)</label>
                                        <input type="file" name="complaint_files[]" class="form-control" multiple>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-primary-gradient px-3">Şikayeti
                                        Ekle</button>
                                </form>

                                <h5>Kayıtlı Şikayetler</h5>
                                @forelse ($customer->complaints as $complaint)
                                    <div
                                        class="alert {{ $complaint->status == 'resolved' ? 'alert-success' : 'alert-warning' }}">
                                        <strong>{{ $complaint->title }}</strong> (Durum: {{ $complaint->status }})
                                        <p>{{ $complaint->description }}</p>
                                        {{-- YÜKLENEN DOSYALARI GÖSTER --}}
                                        @foreach ($complaint->getMedia('complaint_attachments') as $media)
                                            <div class="file-list-item">
                                                <span><i
                                                        class="fa-solid fa-paperclip me-2"></i>{{ $media->file_name }}</span>
                                                <a href="{{ $media->getUrl() }}" target="_blank"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                                            </div>
                                        @endforeach
                                    </div>
                                @empty
                                    <div class="alert alert-secondary">Bu müşteriye ait şikayet kaydı bulunamadı.</div>
                                @endforelse
                            </div>

                            <div class="tab-pane fade" id="visits" role="tabpanel" aria-labelledby="visits-tab">
                                <div class="alert alert-info">
                                    Müşteri ziyaretleri, "İdari İşler" menüsündeki <strong>"Yeni Etkinlik Oluştur"</strong>
                                    (Müşteri Ziyareti tipi) sayfası üzerinden eklenir ve buraya otomatik olarak düşer.
                                </div>
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Etkinlik/Ziyaret</th>
                                            <th>Tarih</th>
                                            <th>Ziyaret Amacı</th>
                                            <th>Seyahat Programı</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($customer->visits as $visit)
                                            <tr>
                                                <td>{{ $visit->event->title ?? 'N/A' }}</td>
                                                <td>{{ $visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td>{{ $visit->visit_purpose }}</td>
                                                <td>{{ $visit->travel->name ?? 'Bağımsız Ziyaret' }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Bu müşteriye ait ziyaret kaydı
                                                    bulunamadı.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        {{-- SEKMELİ YAPI BİTİŞİ --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
