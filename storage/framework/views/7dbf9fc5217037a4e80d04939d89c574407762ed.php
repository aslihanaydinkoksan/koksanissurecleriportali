<div class="modal-header">
    <h5 class="modal-title" id="cardModalLabel">
        
        <?php if($kanbanCard->model instanceof \App\Models\MaintenancePlan): ?>
            <i class="fa fa-tools text-danger"></i> Bakım Talebi #<?php echo e($kanbanCard->model->id); ?>

        <?php elseif($kanbanCard->model instanceof \App\Models\Shipment): ?>
            <i class="fa fa-truck text-primary"></i> Sevkiyat #<?php echo e($kanbanCard->model->id); ?>

        <?php elseif($kanbanCard->model instanceof \App\Models\Event): ?>
            <i class="fa fa-calendar text-success"></i> İdari İş #<?php echo e($kanbanCard->model->id); ?>

        <?php else: ?>
            İş Detayı
        <?php endif; ?>
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

    
    <div class="mb-3">
        <label class="fw-bold text-muted small">BAŞLIK</label>
        <p class="h5"><?php echo e($kanbanCard->model->title ?? ($kanbanCard->model->plaka ?? 'Başlıksız')); ?></p>
    </div>

    

    
    <?php if($kanbanCard->model instanceof \App\Models\MaintenancePlan): ?>
        <div class="row g-3">
            <div class="col-6">
                <label class="fw-bold text-muted small">ÖNCELİK</label><br>
                <span class="badge bg-<?php echo e($kanbanCard->model->priority == 'high' ? 'danger' : 'info'); ?>">
                    <?php echo e($kanbanCard->model->priority ?? 'Normal'); ?>

                </span>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">PLANLANAN TARİH</label>
                <p><?php echo e($kanbanCard->model->planned_start_date ?? '-'); ?></p>
            </div>
            <div class="col-12">
                <label class="fw-bold text-muted small">AÇIKLAMA</label>
                <div class="p-2 bg-light rounded border">
                    <?php echo e($kanbanCard->model->description ?? 'Açıklama yok.'); ?>

                </div>
            </div>
        </div>

        
    <?php elseif($kanbanCard->model instanceof \App\Models\Shipment): ?>
        <div class="row g-3">
            <div class="col-6">
                <label class="fw-bold text-muted small">ARAÇ TİPİ</label>
                <p><?php echo e($kanbanCard->model->arao_tipi ?? '-'); ?></p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">ŞOFÖR</label>
                <p><?php echo e($kanbanCard->model->sofor_adi ?? '-'); ?></p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">KALKIŞ NOKTASI</label>
                <p><?php echo e($kanbanCard->model->kalkis_noktasi ?? '-'); ?></p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">VARIŞ NOKTASI</label>
                <p><?php echo e($kanbanCard->model->varis_noktasi ?? '-'); ?></p>
            </div>
        </div>

        
    <?php elseif($kanbanCard->model instanceof \App\Models\Event): ?>
        <div class="row g-3">
            <div class="col-12">
                <label class="fw-bold text-muted small">LOKASYON</label>
                <p><?php echo e($kanbanCard->model->location ?? '-'); ?></p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">BAŞLANGIÇ</label>
                <p><?php echo e($kanbanCard->model->start_datetime ?? '-'); ?></p>
            </div>
            <div class="col-6">
                <label class="fw-bold text-muted small">BİTİŞ</label>
                <p><?php echo e($kanbanCard->model->end_datetime ?? '-'); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <hr>
    <div class="d-flex justify-content-between text-muted small">
        <span>Oluşturulma: <?php echo e($kanbanCard->created_at->format('d.m.Y H:i')); ?></span>
        <span>Sütun: <strong><?php echo e($kanbanCard->column->title); ?></strong></span>
    </div>
</div>
<div class="modal-footer">
    
    <?php if($kanbanCard->model instanceof \App\Models\MaintenancePlan): ?>
        <a href="#" class="btn btn-primary btn-sm">Bakım Kaydına Git <i class="fa fa-arrow-right"></i></a>
    <?php endif; ?>
    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Kapat</button>
</div>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/admin/kanban/partials/card-detail.blade.php ENDPATH**/ ?>