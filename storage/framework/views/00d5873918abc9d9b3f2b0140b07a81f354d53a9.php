
<div class="section-title mt-5 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-2">
        <i class="fa-solid fa-file-invoice-dollar text-warning"></i>
        <h5 class="mb-0 fw-bold">Harcamalar ve Masraflar</h5>
    </div>
    <?php if(!isset($readonly) || !$readonly): ?>
        <button type="button" class="btn btn-warning btn-sm text-white px-3 shadow-sm" data-bs-toggle="modal"
            data-bs-target="#addExpenseModal">
            <i class="fa-solid fa-plus me-1"></i> Masraf Ekle
        </button>
    <?php endif; ?>
</div>

<?php if($model->expenses->isEmpty()): ?>
    <div class="empty-state border bg-white py-5 rounded-3 text-center shadow-sm">
        <i class="fa-solid fa-receipt mb-3 d-block fs-1 text-muted opacity-50"></i>
        <h6 class="text-muted">Henüz masraf kaydı girilmedi.</h6>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white border rounded shadow-sm">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Kategori</th>
                    <th>Açıklama</th>
                    <th>Tarih</th>
                    <th class="text-end">Tutar</th>
                    <?php if(!isset($readonly) || !$readonly): ?>
                        <th class="text-end pe-4">İşlem</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $model->expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="ps-4"><span class="badge bg-light text-dark border"><?php echo e($expense->category); ?></span>
                        </td>
                        <td><small class="text-muted"><?php echo e($expense->description ?? '-'); ?></small></td>
                        <td><?php echo e($expense->receipt_date ? $expense->receipt_date->format('d.m.Y') : '-'); ?></td>
                        <td class="text-end fw-bold text-dark"><?php echo e(number_format($expense->amount, 2)); ?>

                            <?php echo e($expense->currency); ?></td>
                        <?php if(!isset($readonly) || !$readonly): ?>
                            <td class="text-end pe-4">
                                <form action="<?php echo e(route('expenses.destroy', $expense->id)); ?>" method="POST"
                                    onsubmit="return confirm('Silinsin mi?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-link text-danger p-0"><i
                                            class="fa-solid fa-trash-can"></i></button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <tr class="bg-light fw-bold border-top border-2">
                    <td colspan="3" class="text-end">GENEL TOPLAM:</td>
                    <td class="text-end">
                        <?php $__currentLoopData = $model->expenses->groupBy('currency'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $curr => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-primary"><?php echo e(number_format($items->sum('amount'), 2)); ?> <?php echo e($curr); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <?php if(!isset($readonly) || !$readonly): ?>
                        <td></td>
                    <?php endif; ?>
                </tr>
            </tbody>
        </table>
    </div>
<?php endif; ?>


<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title fw-bold"><i class="fa-solid fa-receipt me-2"></i>Yeni Masraf Kaydı</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('expenses.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                
                <input type="hidden" name="expensable_id" value="<?php echo e($model->id); ?>">
                <input type="hidden" name="expensable_type" value="<?php echo e(get_class($model)); ?>">

                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kategori</label>
                        <select name="category" class="form-select expense-category-select" required>
                            <option value="" selected disabled>Seçiniz...</option>
                            <option value="Ulaşım">✈️ Ulaşım</option>
                            <option value="Konaklama">🏨 Konaklama</option>
                            <option value="Yemek">🍽️ Yemek</option>
                            <option value="Temsil">🤝 Temsil & Ağırlama</option>
                            <option value="Diğer">Diğer</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none other-desc-container">
                        <label class="form-label fw-bold text-danger">Detay Belirtin *</label>
                        <input type="text" name="description" class="form-control" placeholder="Masraf türü nedir?">
                    </div>
                    <div class="row g-3">
                        <div class="col-8">
                            <label class="form-label fw-bold">Tutar</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00"
                                required>
                        </div>
                        <div class="col-4">
                            <label class="form-label fw-bold">Döviz</label>
                            <select name="currency" class="form-select fw-bold">
                                <option value="TRY">TRY ₺</option>
                                <option value="USD">USD $</option>
                                <option value="EUR">EUR €</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label fw-bold">Fiş/Fatura Tarihi</label>
                        <input type="date" name="receipt_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-warning text-white px-4 fw-bold">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/partials/_expense_section.blade.php ENDPATH**/ ?>