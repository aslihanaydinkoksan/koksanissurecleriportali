@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Header Bölümü -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h4 mb-0 fw-bold"><i class="fa-solid fa-clipboard-check me-2 text-primary"></i>Ziyaret Formu Detayı</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('customers.show', $visit->customer_id) }}">{{ $visit->customer->name }}</a></li>
                            <li class="breadcrumb-item active">Ziyaret #{{ $visit->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('visits.print', $visit->id) }}" target="_blank" class="btn btn-outline-dark shadow-sm">
                        <i class="fa-solid fa-print me-1"></i> Yazdır / PDF
                    </a>
                    <a href="{{ route('customers.show', $visit->customer_id . '?tab=visits') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> Geri Dön
                    </a>
                </div>
            </div>

            <div class="row g-4">
                <!-- Sol Sütun: Temel Bilgiler -->
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-primary bg-opacity-10 py-3 border-0">
                            <h5 class="card-title mb-0 fs-6 fw-bold text-primary"><i class="fa-solid fa-info-circle me-1"></i> Ziyaret Özet Bilgileri</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush small">
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Tarih:</span>
                                    <span class="fw-bold">{{ $visit->visit_date ? $visit->visit_date->format('d.m.Y H:i') : '-' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Sebep:</span>
                                    <span class="badge bg-light text-dark border">{{ $visit->visit_reason }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Ziyaretçi:</span>
                                    <span class="fw-bold text-primary">
                                        @if($visit->visitor_name)
                                            {{ $visit->visitor_name }}
                                        @else
                                            {{ $visit->visitor->name ?? 'Belirtilmedi' }}
                                        @endif
                                    </span>
                                </li>
                                @if($visit->complaint_id)
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Bağlı Şikayet:</span>
                                    <span class="badge bg-danger">#{{ $visit->complaint_id }}</span>
                                </li>
                                @endif
                                @if($visit->estimated_return_date)
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Tahmini Dönüş:</span>
                                    <span class="fw-bold text-success"><i class="fa-solid fa-calendar-check me-1"></i>{{ $visit->estimated_return_date->format('d.m.Y') }}</span>
                                </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between p-3">
                                    <span class="text-muted">Sisteme Kaydeden:</span>
                                    <span class="small">{{ $visit->user->name ?? 'Sistem' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-success bg-opacity-10 py-3 border-0">
                            <h5 class="card-title mb-0 fs-6 fw-bold text-success"><i class="fa-solid fa-box me-1"></i> Ürün & Teknik Detaylar</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">Ürün Tanımı:</label>
                                <p class="fw-bold mb-0">{{ $visit->product->name ?? 'Belirtilmedi' }}</p>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="text-muted small d-block mb-1">Barkod No:</label>
                                    <p class="mb-0">{{ $visit->barcode ?? '-' }}</p>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="text-muted small d-block mb-1">Lot No:</label>
                                    <p class="mb-0">{{ $visit->lot_no ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mt-2">
                                @php
                                    $mediaFiles = $visit->getMedia('visit_attachments');
                                    $iaaFiles = is_array($visit->visit_files) ? $visit->visit_files : [];
                                    $hasAnyFiles = $mediaFiles->count() > 0 || count($iaaFiles) > 0;

                                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                @endphp
                                @if($hasAnyFiles)
                                    <div class="row g-2">
                                        @foreach($mediaFiles as $media)
                                            @php
                                                $ext = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, $imageExts);
                                            @endphp
                                            <div class="col-4">
                                                <div class="file-preview-card rounded-3 overflow-hidden border position-relative" style="cursor:pointer;" onclick="openPreview('{{ $media->getUrl() }}', '{{ $media->file_name }}', {{ $isImage ? 'true' : 'false' }})">
                                                    @if($isImage)
                                                        <img src="{{ $media->getUrl() }}" alt="{{ $media->file_name }}" class="w-100" style="height:80px; object-fit:cover;">
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:80px;">
                                                            <i class="fa-regular fa-file-pdf fa-2x text-danger"></i>
                                                        </div>
                                                    @endif
                                                    <div class="px-1 py-1 bg-white text-center">
                                                        <span class="d-block text-truncate" style="font-size:0.65rem;">{{ $media->file_name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @foreach($iaaFiles as $file)
                                            @php
                                                $fileName = $file['name'] ?? 'Dosya';
                                                $fileUrl = $file['url'] ?? '#';
                                                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, $imageExts);
                                            @endphp
                                            <div class="col-4">
                                                <div class="file-preview-card rounded-3 overflow-hidden border position-relative" style="cursor:pointer;" onclick="openPreview('{{ $fileUrl }}', '{{ $fileName }}', {{ $isImage ? 'true' : 'false' }})">
                                                    @if($isImage)
                                                        <img src="{{ $fileUrl }}" alt="{{ $fileName }}" class="w-100" style="height:80px; object-fit:cover;" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                                                        <div class="align-items-center justify-content-center bg-light" style="height:80px; display:none;">
                                                            <i class="fa-regular fa-image fa-2x text-secondary"></i>
                                                        </div>
                                                    @else
                                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:80px;">
                                                            <i class="fa-regular fa-file fa-2x text-primary"></i>
                                                        </div>
                                                    @endif
                                                    <div class="px-1 py-1 bg-white text-center position-relative">
                                                        <span class="d-block text-truncate" style="font-size:0.65rem;">{{ $fileName }}</span>
                                                        <span class="badge bg-info bg-opacity-10 text-info position-absolute" style="font-size:0.5rem; top:2px; right:2px;">İAA</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted small fst-italic">Ekli dosya yok.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sağ Sütun: Notlar ve Görüşülenler -->
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-dark mb-4 border-bottom pb-2">Görüşme Detayları</h5>
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small"><i class="fa-solid fa-users me-1"></i> Görüşülen Kişiler:</label>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @php $contacts = is_array($visit->contact_persons) ? $visit->contact_persons : json_decode($visit->contact_persons ?? '[]', true); @endphp
                                    @forelse($contacts ?? [] as $p)
                                        <span class="badge bg-opacity-10 border px-3 py-2 rounded-pill font-md" style="background-color: rgba(102, 16, 242, 0.1); color: #6610f2; border-color: #d2b9fb !important;">{{ $p }}</span>
                                    @empty
                                        <span class="text-muted">Bilgi yok.</span>
                                    @endforelse
                                </div>
                            </div>

                            @if($visit->visit_notes)
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small"><i class="fa-solid fa-sticky-note me-1"></i> Ziyaret Notları:</label>
                                <div class="p-3 bg-light rounded-4 border-start border-4 border-warning">
                                    {!! nl2br(e($visit->visit_notes)) !!}
                                </div>
                            </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small"><i class="fa-solid fa-microscope me-1"></i> Tespitler / Yapılan İşlemler:</label>
                                <div class="p-4 bg-primary bg-opacity-5 rounded-4 border border-primary-subtle text-dark" style="font-size: 1.05rem; line-height: 1.7;">
                                    {!! nl2br(e($visit->findings)) !!}
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold text-muted small"><i class="fa-solid fa-flag-checkered me-1"></i> Sonuç / Karar:</label>
                                <div class="p-4 bg-success bg-opacity-5 rounded-4 border border-success-subtle text-dark" style="font-size: 1.05rem; line-height: 1.7;">
                                    {!! nl2br(e($visit->result)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($visit->remote_system === 'iaa')
                    <div class="alert alert-info border-0 rounded-4 shadow-sm d-flex align-items-center p-4">
                        <i class="fa-solid fa-circle-info fa-2x me-4 opacity-50"></i>
                        <div>
                            <h6 class="fw-bold mb-1">İAA Kaynaklı Ziyaret</h6>
                            <p class="mb-0 small">Bu ziyaret kaydı İAA (İç Akış Analizi) uygulaması üzerinden otomatik olarak oluşturulmuştur.</p>
                            <a href="{{ $visit->remote_url }}" target="_blank" class="btn btn-sm btn-info text-white mt-2 rounded-pill px-3">
                                <i class="fa-solid fa-external-link me-1"></i> İAA Projesini Görüntüle
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Lightbox Modal -->
<div id="filePreviewModal" onclick="closePreview(event)" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); cursor:pointer;">
    <button onclick="closePreview(event)" style="position:absolute; top:16px; right:24px; background:none; border:none; color:white; font-size:2rem; cursor:pointer; z-index:10001;">
        <i class="fa-solid fa-xmark"></i>
    </button>
    <a id="previewDownload" href="#" target="_blank" style="position:absolute; top:16px; right:72px; background:none; border:none; color:white; font-size:1.4rem; cursor:pointer; z-index:10001; text-decoration:none;" title="Yeni sekmede aç">
        <i class="fa-solid fa-up-right-from-square"></i>
    </a>
    <div style="display:flex; align-items:center; justify-content:center; height:100%; padding:40px;">
        <div id="previewContent" onclick="event.stopPropagation();" style="max-width:90vw; max-height:85vh; text-align:center;">
            <!-- Content injected by JS -->
        </div>
    </div>
    <div id="previewFileName" style="position:absolute; bottom:16px; left:50%; transform:translateX(-50%); color:white; font-size:0.85rem; background:rgba(0,0,0,0.5); padding:6px 18px; border-radius:20px;"></div>
</div>

<style>
    .bg-indigo { background-color: #6610f2; }
    .text-indigo { color: #6610f2; }
    .border-indigo-subtle { border-color: #d2b9fb !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .btn-animated-gradient { background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab); background-size: 400% 400%; animation: gradient 15s ease infinite; color: white; border: none; }

    .file-preview-card {
        transition: all 0.2s ease;
        border-color: #e9ecef !important;
    }
    .file-preview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        border-color: #0d6efd !important;
    }
    .file-preview-card img {
        transition: transform 0.3s ease;
    }
    .file-preview-card:hover img {
        transform: scale(1.05);
    }
</style>

<script>
function openPreview(url, name, isImage) {
    const modal = document.getElementById('filePreviewModal');
    const content = document.getElementById('previewContent');
    const fileName = document.getElementById('previewFileName');
    const download = document.getElementById('previewDownload');

    download.href = url;
    fileName.textContent = name;

    if (isImage) {
        content.innerHTML = '<img src="' + url + '" alt="' + name + '" style="max-width:90vw; max-height:80vh; border-radius:8px; box-shadow:0 8px 32px rgba(0,0,0,0.3);" onerror="this.outerHTML=\'<div style=padding:40px;color:white;><i class=fa-solid\\ fa-triangle-exclamation\\ fa-3x\\ style=margin-bottom:12px;></i><p>Görsel yüklenemedi</p></div>\'">';
    } else {
        content.innerHTML = '<div style="padding:60px; background:white; border-radius:16px; text-align:center;">' +
            '<i class="fa-regular fa-file-lines fa-4x text-primary" style="margin-bottom:16px;"></i>' +
            '<p class="fw-bold mb-2">' + name + '</p>' +
            '<a href="' + url + '" target="_blank" class="btn btn-primary btn-sm rounded-pill px-4"><i class="fa-solid fa-download me-1"></i> Dosyayı Aç</a>' +
            '</div>';
    }

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closePreview(e) {
    if (e) e.stopPropagation();
    document.getElementById('filePreviewModal').style.display = 'none';
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closePreview();
});
</script>
@endsection
