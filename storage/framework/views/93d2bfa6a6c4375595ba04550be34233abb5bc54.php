

<?php $__env->startSection('title', 'Fabrika ve Tesis Yönetimi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row">
            
            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="fas fa-industry me-2"></i> İşletme Birimleri (Fabrikalar)</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Fabrika Adı</th>
                                        <th>Kısa Kod</th>
                                        <th>Durum</th>
                                        <th class="text-end pe-4">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><?php echo e($unit->name); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo e($unit->code); ?></span></td>
                                            <td>
                                                <?php if($unit->is_active): ?>
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success px-2 py-1">Aktif</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Pasif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <form action="<?php echo e(route('business-units.destroy', $unit->id)); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Bu fabrikayı silmek istediğinize emin misiniz?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                Henüz fabrika tanımlanmamış.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="col-md-4">
                <div class="card shadow-sm border-0 sticky-top"
                    style="top: 100px; background: linear-gradient(145deg, #ffffff, #f8f9fa);">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                        <h5 class="mb-0 fw-bold text-dark">✨ Yeni Fabrika Ekle</h5>
                        <p class="text-muted small mt-1">Coped, Preform, Levha vb.</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="<?php echo e(route('business-units.store')); ?>" method="POST">
                            <?php echo csrf_field(); ?>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small">FABRİKA ADI</label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Örn: Levha Fabrikası" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-secondary small">KISA KOD</label>
                                <input type="text" name="code" class="form-control" placeholder="Örn: LVH">
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i> Kaydet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/business_units/index.blade.php ENDPATH**/ ?>