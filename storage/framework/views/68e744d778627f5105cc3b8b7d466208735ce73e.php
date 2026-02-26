
<?php $__env->startSection('title', 'Rakip Firma Yönetimi'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-dark fw-bold"><i class="fa-solid fa-user-ninja text-danger me-2"></i>Rakip Firma Yönetimi
                </h4>
                <p class="text-muted small mb-0">Sistemde kayıtlı olan rakip firmaları buradan düzenleyebilir ve
                    silebilirsiniz.</p>
            </div>
            <button class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCompetitorModal">
                <i class="fa-solid fa-plus me-2"></i> Yeni Rakip Ekle
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Rakip Firma Adı</th>
                                <th>Notlar / Açıklama</th>
                                <th>Durum</th>
                                <th class="text-end pe-4">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?php echo e($comp->name); ?></td>
                                    <td><?php echo e(Str::limit($comp->notes, 50) ?? '-'); ?></td>
                                    <td>
                                        <?php if($comp->is_active): ?>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Aktif</span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Pasif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal"
                                            data-bs-target="#editCompModal<?php echo e($comp->id); ?>">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <form action="<?php echo e(route('competitors.destroy', $comp->id)); ?>" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-sm btn-light border text-danger"><i
                                                    class="fa-solid fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="editCompModal<?php echo e($comp->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-light border-0">
                                                <h5 class="modal-title fw-bold text-dark"><i
                                                        class="fa-solid fa-pen text-primary me-2"></i>Rakibi Düzenle</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="<?php echo e(route('competitors.update', $comp->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                                <div class="modal-body bg-light pt-0">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Firma Adı</label>
                                                        <input type="text" name="name" class="form-control"
                                                            value="<?php echo e($comp->name); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small">Notlar</label>
                                                        <textarea name="notes" class="form-control" rows="3"><?php echo e($comp->notes); ?></textarea>
                                                    </div>
                                                    <div class="form-check form-switch mt-3">
                                                        <input class="form-check-input" type="checkbox" name="is_active"
                                                            id="active<?php echo e($comp->id); ?>" value="1"
                                                            <?php echo e($comp->is_active ? 'checked' : ''); ?>>
                                                        <label class="form-check-label"
                                                            for="active<?php echo e($comp->id); ?>">Firma Aktif (Listelerde
                                                            Görünsün)</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                                                        data-bs-dismiss="modal">İptal</button>
                                                    <button type="submit"
                                                        class="btn btn-primary rounded-pill px-4">Güncelle</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-user-ninja fa-2x mb-3 opacity-25"></i>
                                        <p class="mb-0">Henüz kayıtlı bir rakip firma bulunmuyor.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="addCompetitorModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-user-ninja me-2"></i>Yeni Rakip Ekle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('competitors.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body bg-light">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Rakip Firma Adı (*)</label>
                            <input type="text" name="name" class="form-control border-danger" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Notlar</label>
                            <textarea name="notes" class="form-control border-danger" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/competitors/index.blade.php ENDPATH**/ ?>