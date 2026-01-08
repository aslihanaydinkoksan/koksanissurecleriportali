<div class="modal-header">
    <h5 class="modal-title" id="cardModalLabel">
        {{-- Modele göre başlık --}}
        @if ($kanbanCard->model instanceof \App\Models\MaintenancePlan)
            <i class="fa fa-tools text-danger"></i> Bakım Talebi #{{ $kanbanCard->model->id }}
        @elseif($kanbanCard->model instanceof \App\Models\Shipment)
            <i class="fa fa-truck text-primary"></i> Sevkiyat #{{ $kanbanCard->model->id }}
        @elseif($kanbanCard->model instanceof \App\Models\Event)
            <i class="fa fa-calendar text-success"></i> İdari İş #{{ $kanbanCard->model->id }}
        @else
            İş Detayı
        @endif
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    {{-- ORTAK ALANLAR --}}
    <div class="mb-3">
        <label class="fw-bold text-muted small">BAŞLIK</label>
        <p class="h5">{{ $kanbanCard->model->title ?? ($kanbanCard->model->plaka ?? 'Başlıksız') }}</p>
    </div>

    {{-- MODÜLE ÖZEL ALANLAR (SWITCH MANTIĞI) --}}

    {{-- 1. BAKIM MODÜLÜ DETAYLARI --}}
    @if ($kanbanCard->model instanceof \App\Models\MaintenancePlan)
        <div class="row g-3">
            <div class="col-6">
                <label class="fw-bold text-muted small">ÖNCELİK</label><br>
                <span class="badge bg-{{ $kanbanCard->model->priority == 'high' ? 'danger' : 'info' }}">
                    {{ $kanbanCard->model->priority ?? 'Normal' }}
                </span>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">PLANLANAN TARİH</label>
                <p>{{ $kanbanCard->model->planned_start_date ?? '-' }}</p>
            </div>
            <div class="col-12">
                <label class="fw-bold text-muted small">AÇIKLAMA</label>
                <div class="p-2 bg-light rounded border">
                    {{ $kanbanCard->model->description ?? 'Açıklama yok.' }}
                </div>
            </div>
        </div>

        {{-- 2. LOJİSTİK MODÜLÜ DETAYLARI --}}
    @elseif($kanbanCard->model instanceof \App\Models\Shipment)
        <div class="row g-3">
            <div class="col-6">
                <label class="fw-bold text-muted small">ARAÇ TİPİ</label>
                <p>{{ $kanbanCard->model->arao_tipi ?? '-' }}</p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">ŞOFÖR</label>
                <p>{{ $kanbanCard->model->sofor_adi ?? '-' }}</p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">KALKIŞ NOKTASI</label>
                <p>{{ $kanbanCard->model->kalkis_noktasi ?? '-' }}</p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">VARIŞ NOKTASI</label>
                <p>{{ $kanbanCard->model->varis_noktasi ?? '-' }}</p>
            </div>
        </div>

        {{-- 3. İDARİ İŞLER DETAYLARI --}}
    @elseif($kanbanCard->model instanceof \App\Models\Event)
        <div class="row g-3">
            <div class="col-12">
                <label class="fw-bold text-muted small">LOKASYON</label>
                <p>{{ $kanbanCard->model->location ?? '-' }}</p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">BAŞLANGIÇ</label>
                <p>{{ $kanbanCard->model->start_datetime ?? '-' }}</p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">BİTİŞ</label>
                <p>{{ $kanbanCard->model->end_datetime ?? '-' }}</p>
            </div>
        </div>
    @endif

    <hr>
    <div class="d-flex justify-content-between text-muted small">
        <span>Oluşturulma: {{ $kanbanCard->created_at->format('d.m.Y H:i') }}</span>
        <span>Sütun: <strong>{{ $kanbanCard->column->title }}</strong></span>
    </div>
</div>
<div class="modal-footer">
    {{-- Buraya "Düzenlemeye Git" butonu koyabiliriz --}}
    @if ($kanbanCard->model instanceof \App\Models\MaintenancePlan)
        <a href="#" class="btn btn-primary btn-sm">Bakım Kaydına Git <i class="fa fa-arrow-right"></i></a>
    @endif
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Kapat</button>
</div>
