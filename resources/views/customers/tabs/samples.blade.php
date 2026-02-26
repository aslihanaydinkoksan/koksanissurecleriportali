<div class="tab-pane fade" id="samples" role="tabpanel">
    <h5><i class="fa-solid fa-flask me-2"></i>Hızlı Numune Kaydı Ekle</h5>
    <form action="{{ route('customers.samples.store', $customer) }}" method="POST" class="quick-add-form">
        @csrf
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Konu (*)</label>
                <input type="text" name="subject" class="form-control" required placeholder="Örn: Yeni Preform Denemesi">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">İlgili Ürün/Proje</label>
                <input type="text" name="product_name" list="productList" class="form-control" placeholder="Listeden seçin veya yeni yazın...">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Miktar & Birim (*)</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" required step="0.01" value="1">
                    <select name="unit" class="form-select" style="max-width: 90px;" required>
                        @foreach ($birimler as $birim)
                            <option value="{{ $birim->ad }}">{{ $birim->ad }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Gönderim Tarihi</label>
                <input type="date" name="sent_date" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kargo Firması ve Takip No</label>
                <div class="input-group">
                    <input type="text" name="cargo_company" class="form-control" placeholder="Kargo Firması">
                    <input type="text" name="tracking_number" class="form-control" placeholder="Takip No">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ekstra Ürün Bilgisi</label>
                <input type="text" name="product_info" class="form-control" placeholder="Gerekirse detay girin...">
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Kaydet</button>
        </div>
    </form>
    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Gönderilen Numuneler</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterSamDate" class="filter-input bg-white">
            <input type="text" id="filterSamSearch" class="filter-input bg-white" placeholder="Konu, ürün ara...">
            <select id="filterSamStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="preparing">Hazırlanıyor</option>
                <option value="sent">Gönderildi</option>
                <option value="delivered">Teslim Edildi</option>
                <option value="approved">Onaylandı</option>
                <option value="rejected">Reddedildi</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="samplesTable">
            <thead class="bg-light">
                <tr>
                    <th>Tarih</th>
                    <th>Konu</th>
                    <th>Bağlı Ürün</th>
                    <th>Miktar</th>
                    <th>Kargo</th>
                    <th>Durum</th>
                    <th>Geri Bildirim</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customer->samples as $sample)
                    <tr class="sample-item" data-date="{{ $sample->sent_date ? $sample->sent_date->format('Y-m-d') : '' }}" data-search="{{ mb_strtolower($sample->subject . ' ' . $sample->product_info . ' ' . ($sample->product->name ?? '')) }}" data-status="{{ $sample->status }}">
                        <td>{{ $sample->sent_date ? $sample->sent_date->format('d.m.Y') : '-' }}</td>
                        <td><span class="fw-bold">{{ $sample->subject }}</span><br><small class="text-muted">{{ $sample->product_info }}</small></td>
                        <td>
                            @if ($sample->product)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $sample->product->name }}</span>
                            @else <span class="text-muted small">-</span> @endif
                        </td>
                        <td>{{ $sample->quantity }} {{ $sample->unit }}</td>
                        <td>
                            @if ($sample->cargo_company)
                                {{ $sample->cargo_company }}<br><small class="text-muted">{{ $sample->tracking_number }}</small>
                            @else - @endif
                        </td>
                        <td>
                            <form action="{{ route('customer-samples.update-status', $sample->id) }}" method="POST" class="m-0">
                                @csrf @method('PATCH')
                                <input type="hidden" name="feedback" value="{{ $sample->feedback }}">
                                <select name="status" class="form-select status-select status-{{ $sample->status }}" onchange="this.form.submit()" style="min-width: 130px;">
                                    <option value="preparing" {{ $sample->status == 'preparing' ? 'selected' : '' }}>Hazırlanıyor</option>
                                    <option value="sent" {{ $sample->status == 'sent' ? 'selected' : '' }}>Gönderildi</option>
                                    <option value="delivered" {{ $sample->status == 'delivered' ? 'selected' : '' }}>Teslim Edildi</option>
                                    <option value="approved" {{ $sample->status == 'approved' ? 'selected' : '' }}>Onaylandı</option>
                                    <option value="rejected" {{ $sample->status == 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="text-muted small text-wrap text-break" style="width: 180px; hyphens: auto; cursor: pointer; line-height: 1.5; text-align: justify;" lang="tr" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $sample->id }}" title="Düzenle/Oku">
                                    {{ $sample->feedback ?: 'Henüz girilmedi' }}
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-primary p-0 ms-2 flex-shrink-0 mt-1" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $sample->id }}"><i class="fa-solid fa-pen-to-square fs-5"></i></button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSampleModal{{ $sample->id }}" title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('customer-samples.destroy', $sample->id) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty 
                    <tr class="empty-message-row">
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fa-solid fa-flask fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Henüz numune kaydı bulunamadı.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-history-timeline :activities="$historyService->getCommercialHistory($customer)" />
</div>