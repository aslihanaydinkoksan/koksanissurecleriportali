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
        <h5 class="mb-0">
            <i class="fa-solid fa-list me-2"></i>Kayıtlı Şikayetler 
            <span class="badge bg-primary rounded-pill ms-1" style="font-size: 0.8rem; vertical-align: middle;">{{ $customer->complaints->count() }}</span>
        </h5>
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
        @php $complaints = $customer->complaints->sortByDesc('created_at'); @endphp
        @forelse ($complaints as $complaint)
            @php
                $durumRenkleri = [
                    'blue'   => ['bg' => '#e3f2fd', 'text' => '#0d47a1', 'border' => '#007bff'],
                    'orange' => ['bg' => '#fff3e0', 'text' => '#e65100', 'border' => '#ff9800'],
                    'green'  => ['bg' => '#e8f5e9', 'text' => '#1b5e20', 'border' => '#28a745'],
                    'red'    => ['bg' => '#ffebee', 'text' => '#b71c1c', 'border' => '#dc3545'],
                    'purple' => ['bg' => '#f3e5f5', 'text' => '#4a148c', 'border' => '#6f42c1'],
                    'pink'   => ['bg' => '#fce4ec', 'text' => '#880e4f', 'border' => '#e83e8c'],
                    'indigo' => ['bg' => '#e8eaf6', 'text' => '#1a237e', 'border' => '#3f51b5'],
                    'gray'   => ['bg' => '#f5f5f5', 'text' => '#424242', 'border' => '#9e9e9e'],
                ];
                
                $stiller = $durumRenkleri[$complaint->durum_rengi] ?? $durumRenkleri['gray'];
                $isHidden = $loop->iteration > 5;
            @endphp

            <div class="card mb-4 border-0 shadow-sm complaint-item {{ $isHidden ? 'd-none load-more-item' : '' }}" 
                 id="complaint-{{ $complaint->id }}"
                 data-date="{{ $complaint->created_at->format('Y-m-d') }}" 
                 data-search="{{ mb_strtolower($complaint->title . ' ' . $complaint->description) }}" 
                 data-status="{{ $complaint->status }}"
                 style="overflow: hidden; border-radius: 12px; border-left: 6px solid {{ $stiller['border'] }} !important;">
                
                <div class="card-body p-4">
                    {{-- ÜST BİLGİ BANDI (ID ve KAYNAK) --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-light">
                        <div class="d-flex align-items-center gap-2">
                             <span class="badge bg-dark text-white rounded-1" style="font-size: 0.7rem;">ID: #{{ $complaint->id }}</span>
                             @if($complaint->remote_system === 'iaa')
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25" style="font-size: 0.65rem; font-weight: 700;">
                                    <i class="fas fa-sync-alt me-1"></i> IAA'DAN AKTARILDI
                                </span>
                             @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light border-0 rounded-circle shadow-none" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                @if($complaint->remote_system !== 'iaa')
                                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#editComplaintModal{{ $complaint->id }}"><i class="fas fa-edit me-2 text-primary"></i>Düzenle</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('complaints.destroy', $complaint->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item py-2 text-danger"><i class="fas fa-trash-alt me-2"></i>Sil</button>
                                        </form>
                                    </li>
                                @else
                                    @if($complaint->remote_url)
                                        <li><a class="dropdown-item py-2" href="{{ $complaint->remote_url }}" target="_blank"><i class="fas fa-external-link-alt me-2 text-info"></i>IAA'da Detaylar</a></li>
                                    @endif
                                    <li><span class="dropdown-item disabled text-muted italic"><i class="fas fa-lock me-2"></i>Düzenleme Kilitli (IAA)</span></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- DETAY TABLOSU (LABEL: VALUE Yapısı) --}}
                    <div class="complaint-details px-2">
                        
                        {{-- OLUŞTURAN --}}
                        <div class="row mb-3">
                            <div class="col-sm-3 col-md-2">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">OLUŞTURAN:</span>
                            </div>
                            <div class="col-sm-9 col-md-10 text-secondary">
                                {{ $complaint->remote_creator_name ?? 'Sistem' }}
                            </div>
                        </div>

                        {{-- BAŞLIK --}}
                        <div class="row mb-3">
                            <div class="col-sm-3 col-md-2">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">BAŞLIK:</span>
                            </div>
                            <div class="col-sm-9 col-md-10">
                                <h6 class="card-title fw-bold text-dark mb-1 d-flex align-items-center flex-wrap gap-2">
                                    @if($complaint->remote_system === 'iaa' && $complaint->remote_url)
                                        <a href="{{ $complaint->remote_url }}" target="_blank" class="text-dark text-decoration-none hover-primary" title="IAA'da Görüntüle">
                                            {{ $complaint->title }} <i class="fas fa-external-link-alt ms-1 text-info" style="font-size: 0.7rem;"></i>
                                        </a>
                                    @else
                                        {{ $complaint->title }}
                                    @endif

                                    @if($complaint->returns->count() > 0)
                                        <a href="#returns" onclick="event.preventDefault(); scrollToReturn({{ $complaint->id }})" 
                                           class="badge bg-success bg-opacity-10 text-success border border-success text-decoration-none ms-2"
                                           style="font-size: 0.7rem; cursor: pointer;"
                                           title="Bağlı İade Kaydına Git">
                                           <i class="fa-solid fa-rotate-left me-1"></i> İade Var
                                        </a>
                                    @endif
                                </h6>
                            </div>
                        </div>

                        {{-- TARİH --}}
                        <div class="row mb-3">
                            <div class="col-sm-3 col-md-2">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">TARİH:</span>
                            </div>
                            <div class="col-sm-9 col-md-10 font-monospace text-secondary">
                                {{ $complaint->created_at->format('d.m.Y') }} <small class="ms-1 text-muted">({{ $complaint->created_at->format('H:i') }})</small>
                            </div>
                        </div>

                        {{-- DURUM --}}
                        <div class="row mb-3 align-items-center">
                            <div class="col-sm-3 col-md-2">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">DURUM:</span>
                            </div>
                            <div class="col-sm-9 col-md-10">
                                <span class="px-3 py-1 rounded-pill fw-bold" 
                                      style="font-size: 0.8rem; background-color: {{ $stiller['bg'] }}; color: {{ $stiller['text'] }}; border: 1.5px solid {{ $stiller['border'] }}; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                    <i class="fas fa-circle me-1" style="font-size: 0.55rem; vertical-align: middle; opacity: 0.8;"></i>
                                    {{ $complaint->clean_status }}
                                </span>
                            </div>
                        </div>

                        {{-- KONU / DETAY --}}
                        <div class="row">
                            <div class="col-sm-3 col-md-2">
                                <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">KONU:</span>
                            </div>
                            <div class="col-sm-9 col-md-10">
                                <div class="p-3 rounded-3" style="background-color: #fcfcfc; border: 1px solid #f0f0f0; min-height: 60px;">
                                    <p class="mb-0 text-secondary" style="font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">{{ $complaint->description }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- DOSYALAR (EĞER VARSA) --}}
                        @if($complaint->getMedia('complaint_attachments')->count() > 0)
                            <div class="row mt-3 pt-3 border-top border-light">
                                <div class="col-sm-3 col-md-2">
                                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">EKLER:</span>
                                </div>
                                <div class="col-sm-9 col-md-10">
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($complaint->getMedia('complaint_attachments') as $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-white border px-3 py-2 d-inline-flex align-items-center hover-shadow transition-all" style="border-radius: 8px; font-size: 0.8rem;">
                                                <i class="far fa-file-alt me-2 text-primary" style="font-size: 1rem;"></i>
                                                <span class="text-dark">{{ Str::limit($media->file_name, 35) }}</span>
                                                <i class="fas fa-download ms-3 text-muted" style="font-size: 0.7rem;"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty 
            <div class="text-center py-5 bg-light rounded-4 border border-dashed border-2">
                <div class="mb-3">
                    <i class="fa-solid fa-inbox fa-3x text-muted opacity-25"></i>
                </div>
                <h6 class="text-muted fw-bold">Şikayet Kaydı Bulunamadı</h6>
                <p class="small text-muted mb-0">Bu müşteriye ait henüz bir şikayet veya geri bildirim kaydı girilmemiş.</p>
            </div>
        @endforelse
    </div>

    @if($customer->complaints->count() > 5)
        <div class="text-center mt-4 mb-5" id="loadMoreContainer">
            <button class="btn btn-outline-primary rounded-pill px-5 shadow-sm" id="btnLoadMoreComplaints">
                <i class="fas fa-chevron-down me-2"></i> Daha Fazla Göster
            </button>
        </div>

        <script>
            document.getElementById('btnLoadMoreComplaints').addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.load-more-item.d-none');
                const itemsToShow = 5;
                
                for(let i = 0; i < itemsToShow && i < hiddenItems.length; i++) {
                    hiddenItems[i].classList.remove('d-none');
                }
                
                if (document.querySelectorAll('.load-more-item.d-none').length === 0) {
                    document.getElementById('loadMoreContainer').classList.add('d-none');
                }
            });
        </script>
    @endif

    <x-history-timeline :activities="$historyService->getSupportHistory($customer)" />
</div>
