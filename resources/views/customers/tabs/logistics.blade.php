<div class="tab-pane fade" id="logistics" role="tabpanel">
    <h5><i class="fa-solid fa-truck-fast me-2 text-info"></i>Yeni Lojistik / Sevkiyat Görevi</h5>
    <form action="{{ route('service.vehicle-assignments.store') }}" method="POST" class="quick-add-form mb-5">
        @csrf
        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
        <input type="hidden" name="type" value="logistics">

        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Görev Başlığı (*)</label><input type="text"
                    name="title" class="form-control" required placeholder="Örn: İstanbul Sevkiyatı"></div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Araç Seçimi</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">Araç Seçiniz...</option>
                    @foreach (\App\Models\Vehicle::all() as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->plate_number }} - {{ $vehicle->model }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Planlanan Çıkış (*)</label><input type="datetime-local"
                    name="start_time" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required></div>
        </div>

        {{-- YENİ: DİNAMİK GÖNDERİ TÜRÜ ALANI (ŞIK VE ANLAŞILIR) --}}
        <div class="p-3 mb-3 rounded"
            style="background-color: rgba(102, 126, 234, 0.05); border: 1px solid rgba(102, 126, 234, 0.2);">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark"><i class="fa-solid fa-layer-group me-1"></i> Gönderi Türü
                    (*)</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipment_type" id="type_product_new"
                            value="product" checked onchange="toggleShipmentType('product', 'new_')">
                        <label class="form-check-label fw-bold text-primary" for="type_product_new"><i
                                class="fa-solid fa-box me-1"></i> Standart Ürün Sevkiyatı</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipment_type" id="type_sample_new"
                            value="sample" onchange="toggleShipmentType('sample', 'new_')">
                        <label class="form-check-label fw-bold text-success" for="type_sample_new"><i
                                class="fa-solid fa-flask me-1"></i> Numune Sevkiyatı</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3" id="new_wrapper_product">
                    <label class="form-label text-primary">Taşınacak Ürün Seçimi</label>
                    <select name="customer_product_id" id="new_product_select" class="form-select border-primary">
                        <option value="">Ürün Seçiniz...</option>
                        @foreach ($customer->products as $prod)
                            <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3" id="new_wrapper_sample" style="display: none;">
                    <label class="form-label text-success">Gönderilecek Numune Seçimi</label>
                    <select name="customer_sample_id" id="new_sample_select" class="form-select border-success">
                        <option value="">Numune Seçiniz...</option>
                        @foreach ($customer->samples as $sample)
                            <option value="{{ $sample->id }}">#{{ $sample->id }} -
                                {{ Str::limit($sample->subject, 25) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Miktar & Birim</label>
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control" placeholder="0.00" step="0.01">
                        <select name="unit" class="form-select" style="max-width: 90px;">
                            @foreach ($birimler as $birim)
                                <option value="{{ $birim->ad }}">{{ $birim->ad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Sorumlu Şoför</label>
                    <select name="user_id" class="form-select">
                        <option value="{{ Auth::id() }}">{{ Auth::user()->name }} (Ben)</option>
                        @foreach (\App\Models\User::all() as $user)
                            @if ($user->id !== Auth::id())
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Açıklama / Adres Detayı</label>
                <div class="input-group">
                    <input type="text" name="description" id="new_logistics_desc" class="form-control"
                        placeholder="Sevkiyat hakkında notlar...">
                    <button class="btn btn-outline-secondary" type="button" id="btn_new_log_desc"
                        onclick="toggleVoiceInput('new_logistics_desc', 'btn_new_log_desc')"><i
                            class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
        </div>
        <div class="text-end"><button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                    class="fa-solid fa-calendar-check me-2"></i> Görevi Planla</button></div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-truck-ramp-box me-2 text-info"></i>Lojistik Hareketleri</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterLogDate" class="filter-input bg-white">
            <input type="text" id="filterLogSearch" class="filter-input bg-white"
                placeholder="Görev, araç, ürün ara...">
            <select id="filterLogStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="pending">Beklemede</option>
                <option value="on_road">Yolda</option>
                <option value="completed">Tamamlandı</option>
            </select>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="logisticsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tarih</th>
                            <th>Görev & İçerik</th>
                            <th>Araç / Plaka</th>
                            <th>Sorumlu</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->vehicleAssignments as $assignment)
                            <tr class="logistic-item" data-date="{{ $assignment->start_time->format('Y-m-d') }}"
                                data-search="{{ mb_strtolower($assignment->title . ' ' . ($assignment->vehicle->plate_number ?? '') . ' ' . ($assignment->product->name ?? '') . ' ' . ($assignment->sample->subject ?? '')) }}"
                                data-status="{{ $assignment->status }}">
                                <td class="ps-4">{{ $assignment->start_time->format('d.m.Y H:i') }}</td>
                                <td>
                                    <span class="fw-bold d-block">{{ $assignment->title }}</span>

                                    {{-- DİNAMİK ROZET GÖSTERİMİ --}}
                                    @if ($assignment->shipment_type == 'sample' && $assignment->sample)
                                        <small class="text-success fw-bold"><i
                                                class="fa-solid fa-flask me-1"></i>Numune: {{ $assignment->quantity }}
                                            {{ $assignment->unit }} -
                                            {{ Str::limit($assignment->sample->subject, 20) }}</small>
                                    @elseif ($assignment->product)
                                        <small class="text-primary fw-bold"><i class="fa-solid fa-box me-1"></i>Ürün:
                                            {{ $assignment->quantity }} {{ $assignment->unit }} -
                                            {{ $assignment->product->name }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($assignment->vehicle)
                                        <span class="badge bg-dark"><i
                                                class="fa-solid fa-truck me-1"></i>{{ $assignment->vehicle->plate_number }}</span>
                                    @else
                                        <span class="text-muted small">Araçsız</span>
                                    @endif
                                </td>
                                <td>{{ $assignment->responsible->name ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('service.assignments.update-status', $assignment->id) }}"
                                        method="POST" class="m-0">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status"
                                            class="form-select status-select status-{{ $assignment->status }}"
                                            onchange="this.form.submit()" style="min-width: 130px;">
                                            <option value="pending"
                                                {{ $assignment->status == 'pending' ? 'selected' : '' }}>Beklemede
                                            </option>
                                            <option value="on_road"
                                                {{ $assignment->status == 'on_road' ? 'selected' : '' }}>Yolda</option>
                                            <option value="completed"
                                                {{ $assignment->status == 'completed' ? 'selected' : '' }}>Tamamlandı
                                            </option>
                                            <option value="cancelled"
                                                {{ $assignment->status == 'cancelled' ? 'selected' : '' }}>İptal
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light border"
                                            data-bs-toggle="modal"
                                            data-bs-target="#historyLogisticsModal{{ $assignment->id }}"
                                            title="Tarihçeyi Gör">
                                            <i class="fa-solid fa-clock-rotate-left text-info"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editLogisticsModal{{ $assignment->id }}" title="Düzenle"
                                            onclick="setTimeout(() => toggleShipmentType('{{ $assignment->shipment_type ?? 'product' }}', 'edit_{{ $assignment->id }}_'), 100);"><i
                                                class="fa-solid fa-pen"></i></button>
                                        <form
                                            action="{{ route('service.vehicle-assignments.destroy', $assignment->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                    class="fa-solid fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-message-row">
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-truck-loading fa-2x mb-3 opacity-50"></i>
                                    <p class="mb-0">Bu müşteriye planlanmış bir lojistik görevi bulunamadı.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <x-history-timeline :activities="$historyService->getSupportHistory($customer)" />
</div>
