<div class="tab-pane fade" id="complaints" role="tabpanel">
    <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Şikayet Kaydı Ekle</h5>
    <form action="{{ route('customers.complaints.store', $customer) }}" method="POST" autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
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
            <div class="input-group">
                <textarea name="description" id="new_complaint_desc" class="form-control" rows="3" required></textarea>
                <button class="btn btn-outline-secondary" type="button" id="btn_new_comp_desc" onclick="toggleVoiceInput('new_complaint_desc', 'btn_new_comp_desc')"><i class="fa-solid fa-microphone"></i></button>
            </div>
        </div>
        <div class="mb-3">
            <label for="complaint_files" class="form-label">Kanıt Dosyaları</label>
            <input type="file" name="complaint_files[]" class="form-control" multiple>
        </div>
        <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i>Şikayeti Ekle</button>
    </form>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Şikayetler</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterCompDate" class="filter-input bg-white">
            <input type="text" id="filterCompSearch" class="filter-input bg-white" placeholder="Başlık veya içerik ara...">
            <select id="filterCompStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="open">Açık</option>
                <option value="in_progress">İşlemde</option>
                <option value="resolved">Çözüldü</option>
            </select>
        </div>
    </div>
    <div id="complaintsList">
        @forelse ($customer->complaints as $complaint)
            <div class="alert complaint-item {{ $complaint->status == 'resolved' ? 'alert-success' : 'alert-warning' }}" data-date="{{ $complaint->created_at->format('Y-m-d') }}" data-search="{{ mb_strtolower($complaint->title . ' ' . $complaint->description) }}" data-status="{{ $complaint->status }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-2"><i class="fa-solid fa-exclamation-circle me-2"></i><strong>{{ $complaint->title }}</strong></h6>
                        <div class="d-flex gap-3 mb-2 small">
                            <span><i class="fa-regular fa-calendar me-1"></i>{{ $complaint->created_at->format('d.m.Y') }}</span>
                            <span>Durum: <strong>{{ $complaint->status }}</strong></span>
                        </div>
                        <p class="mb-3">{{ $complaint->description }}</p>
                        @foreach ($complaint->getMedia('complaint_attachments') as $media)
                            <div class="file-list-item d-inline-flex me-2 mb-2" style="width: auto;">
                                <span><i class="fa-solid fa-paperclip me-2"></i>{{ Str::limit($media->file_name, 20) }}</span>
                                <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-link text-primary ms-2 p-0"><i class="fa-solid fa-eye"></i></a>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex gap-2 ms-3 flex-shrink-0">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal" data-bs-target="#editComplaintModal{{ $complaint->id }}" title="Düzenle"><i class="fa-solid fa-pen text-primary"></i></button>
                        <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-light border"><i class="fa-solid fa-trash-alt text-danger"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @empty 
            <div class="alert alert-secondary text-center empty-message-row">
                <i class="fa-solid fa-inbox fa-2x mb-3 d-block" style="opacity: 0.3;"></i>Bu müşteriye ait şikayet kaydı bulunamadı.
            </div>
        @endforelse
    </div>
    <x-history-timeline :activities="$historyService->getSupportHistory($customer)" />
</div>
