@extends('layouts.app')

@section('title', $customer->name)

<style>
    /* === 1. Ana kartı transparan yapar === */
    .customer-card {
        background-color: transparent;
        border-radius: 0.75rem;
        box-shadow: none;
        border: none;
    }

    /* === 2. Sekme (Tab) Stilini Güçlendirme === */
    .nav-tabs {
        border-bottom: none !important;
    }

    .nav-tabs .nav-link {
        border: none !important;
        border-radius: 0.5rem;
        color: #555;
        font-weight: 500;
        margin-right: 0.25rem;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .nav-tabs .nav-link:not(.active):hover {
        background-color: #f1f3f5;
        color: #333;
    }

    .nav-tabs .nav-link.active {
        border-color: transparent !important;
        background-color: #667EEA !important;
        color: #ffffff !important;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }

    /* === 3. YENİ BÖLÜM: Sekme İÇERİKLERİNİ transparan yapma === */

    /* "Hızlı Ekle" formlarının arka planını kaldırır */
    .quick-add-form {
        background-color: transparent !important;
        border: none !important;
        padding: 1.5rem 0;
        /* İçeriğin gövdeye yapışmaması için dikey padding kalsın */
    }

    /* "Kayıtlı Şikayetler" gibi uyarı kutularını transparan yapar */
    .alert {
        background-color: transparent !important;
        border: none !important;
        padding-left: 0;
        /* Kenar boşluklarını sıfırla */
        padding-right: 0;
    }

    /* "Kayıtlı Makineler" gibi tabloları transparan yapar */
    .table,
    .table-striped>tbody>tr:nth-of-type(odd)>*,
    .table> :not(caption)>*>* {
        background-color: transparent !important;
    }

    /* Uyarılardaki "Dosya" listeleme kutularını transparan yapar */
    .file-list-item {
        /* Tamamen şeffaf olunca kayboluyor, hafif yarı-şeffaf bir beyaz daha iyi durur */
        background-color: rgba(255, 255, 255, 0.5) !important;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }


    /* --- Orijinal stilleriniz (dokunulmadı) --- */

    .btn-outline-primary {
        border-color: #667EEA;
        color: #667EEA;
    }

    .btn-outline-primary:hover {
        background-color: #667EEA;
        color: #fff;
    }

    .detail-list dt {
        font-weight: 500;
        color: #555;
    }

    .detail-list dd {
        color: #222;
        font-weight: 400;
    }

    .create-event-card .form-control,
    .create-event-card .form-select {
        border-radius: 0.5rem;
        background-color: rgba(255, 255, 255, 0.8);
    }

    .btn-animated-gradient {
        background: linear-gradient(-45deg,
                #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
                                <div class="col-md-6">
                                    <a href="{{ route('customers.edit', $customer) }}"
                                        class="btn btn-animated-gradient rounded-pill px-4 w-100 btn-modern">
                                        <i class="fa-solid fa-pen me-1"></i> Müşteri Bilgilerini Düzenle
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-grid"
                                        {{-- Formun da tam genişlik kaplamasını sağlar --}}
                                        onsubmit="return confirm('Bu müşteriyi silmek (arşivlemek) istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-animated-gradient rounded-pill px-4 btn-modern">
                                            <i class="fa-solid fa-trash-alt me-1"></i> Müşteri Kaydını Sil
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
                                    <button type="submit"
                                        class="btn btn-animated-gradient rounded-pill px-4 btn-modern">Makineyi
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
                                    <button type="submit"
                                        class="btn btn-animated-gradient rounded-pill px-4 btn-modern">Test Sonucunu
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
                                    <button type="submit"
                                        class="btn btn-animated-gradient rounded-pill px-4 btn-modern">Şikayeti
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
