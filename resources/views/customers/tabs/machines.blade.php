<div class="tab-pane fade" id="machines" role="tabpanel">
    <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı Makine Ekle</h5>
    <form action="{{ route('customers.machines.store', $customer) }}" method="POST" autocomplete="off" class="quick-add-form">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Kime Ait? (Kurulum Türü) (*)</label>
                <select name="ownership_type" class="form-select" required>
                    <option value="koksan">KÖKSAN'ın Kurduğu Makine</option>
                    <option value="customer" selected>Müşterinin Kendi Makinesi</option>
                    <option value="competitor">Rakibin Kurduğu Makine</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="brand" class="form-label">Marka</label>
                <input type="text" name="brand" class="form-control" placeholder="Örn: Husky, Netstal...">
            </div>
            <div class="col-md-4 mb-3">
                <label for="model" class="form-label">Model (*)</label>
                <input type="text" name="model" class="form-control" required placeholder="Örn: HyPET 300">
            </div>
            
            <div class="col-md-4 mb-3">
                <label class="form-label">Çalıştığı Ürün</label>
                <div class="input-group">
                    <input type="text" name="product_name" list="productList" class="form-control border-end-0" placeholder="Listeden seçin veya yeni yazın..." autocomplete="off">
                    <span class="input-group-text bg-white border-start-0 text-muted"><i class="fa-solid fa-chevron-down" style="font-size: 0.8em;"></i></span>
                </div>
            </div>
            <div class="col-md-4 mb-3"><label for="serial_number" class="form-label">Seri No / Kod</label><input type="text" name="serial_number" class="form-control"></div>
            <div class="col-md-4 mb-3"><label for="installation_date" class="form-label">Kurulum Tarihi</label><input type="date" name="installation_date" class="form-control"></div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i>Makineyi Ekle</button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Makineler</h5>
    </div>
    
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover" id="machinesTable">
            <thead>
                <tr>
                    <th>Mülkiyet</th>
                    <th>Marka & Model</th>
                    <th>Bağlı Ürün</th>
                    <th>Seri No</th>
                    <th>Kurulum Tarihi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customer->machines as $machine)
                    <tr class="machine-item">
                        <td>
                            @if($machine->ownership_type == 'koksan') <span class="badge bg-primary">KÖKSAN</span>
                            @elseif($machine->ownership_type == 'competitor') <span class="badge bg-danger">Rakip Kurulumu</span>
                            @else <span class="badge bg-secondary">Müşteriye Ait</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $machine->brand ?? 'Marka Yok' }}</span><br>
                            <strong>{{ $machine->model }}</strong>
                        </td>
                        <td>
                            @if ($machine->product) <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">{{ $machine->product->name }}</span>
                            @else <span class="text-muted small">-</span> @endif
                        </td>
                        <td>{{ $machine->serial_number ?? '-' }}</td>
                        <td>{{ $machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('d.m.Y') : '-' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMachineModal{{ $machine->id }}" title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                <form action="{{ route('machines.destroy', $machine->id ?? 0) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-0 px-2"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty 
                    <tr class="empty-message-row">
                        <td colspan="6" class="text-center text-muted py-4">Bu müşteriye ait makine kaydı bulunamadı.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-history-timeline :activities="$historyService->getTechnicalHistory($customer)" />
</div>