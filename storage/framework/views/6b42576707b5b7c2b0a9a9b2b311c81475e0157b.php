

<?php $__env->startSection('title', 'Görev Detayları: ' . $assignment->title); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">

                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">
                        <i class="fas fa-file-invoice me-2"></i> Görev Detayları
                    </h1>
                    <a href="<?php echo e(route('my-assignments.index')); ?>" class="btn btn-outline-secondary btn-sm">
                        ← Görevlerime Geri Dön
                    </a>
                </div>

                
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white h5">
                        <?php echo e($assignment->title); ?>

                        <span class="badge bg-light text-dark ms-3"><?php echo e($assignment->getStatusNameAttribute()); ?></span>
                    </div>

                    <div class="card-body">

                        
                        <div class="row mb-4 border-bottom pb-3">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted fw-bold">Atanan Araç:</p>
                                <p class="lead mb-0"><?php echo e($assignment->vehicle->plate_number ?? 'Araç Yok'); ?>

                                    (<?php echo e($assignment->vehicle->type ?? 'Genel'); ?>)</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted fw-bold">Sorumlu:</p>
                                <?php
                                    // Polymorphic ilişkiyi kontrol et
                                    $responsibleName = $assignment->responsible->name ?? 'Bilinmiyor';
                                    $responsibleType =
                                        $assignment->responsible_type === App\Models\User::class ? 'Kişi' : 'Takım';
                                ?>
                                <p class="lead mb-0"><i
                                        class="fas fa-<?php echo e($responsibleType === 'Kişi' ? 'user' : 'users'); ?> me-1"></i>
                                    <?php echo e($responsibleName); ?> (<?php echo e($responsibleType); ?>)</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted fw-bold">Sefer Zamanı:</p>
                                <p class="lead mb-0"><?php echo e($assignment->start_time->format('d.m.Y H:i')); ?> -
                                    <?php echo e($assignment->end_time->format('H:i')); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted fw-bold">Yer/Hedef:</p>
                                <p class="lead mb-0"><?php echo e($assignment->destination ?? 'Belirtilmedi'); ?></p>
                            </div>
                        </div>

                        
                        <h6 class="fw-bold text-primary">Görev Açıklaması:</h6>
                        <p class="border p-3 rounded bg-light"><?php echo e($assignment->task_description); ?></p>

                        <h6 class="fw-bold text-primary">Ek Notlar:</h6>
                        <p class="border p-3 rounded bg-light"><?php echo e($assignment->notes ?? 'Yok'); ?></p>


                        
                        <?php if($assignment->isLogistics()): ?>
                            <h5 class="mt-4 mb-3 fw-bold text-danger">🚚 Nakliye / Lojistik Kayıtları</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="table-secondary">
                                            <th>Detay</th>
                                            <th>Başlangıç Değeri</th>
                                            <th>Bitiş Değeri</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Kilometre (KM)</td>
                                            <td><?php echo e($assignment->start_km ?? '-'); ?></td>
                                            <td><?php echo e($assignment->end_km ?? 'Bekleniyor'); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Yakıt Durumu</td>
                                            <td><?php echo e($assignment->start_fuel_level ?? '-'); ?></td>
                                            <td><?php echo e($assignment->end_fuel_level ?? 'Bekleniyor'); ?></td>
                                        </tr>
                                        <?php if($assignment->fuel_cost): ?>
                                            <tr>
                                                <td colspan="2">Yakıt Maliyeti</td>
                                                <td><?php echo e(number_format($assignment->fuel_cost, 2)); ?> TL</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if($assignment->status !== 'completed'): ?>
                                <div class="alert alert-warning mt-3">
                                    Görevi tamamlamak için **Bitiş KM** ve **Yakıt Maliyeti** alanlarını doldurmanız
                                    gerekebilir.
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <hr class="mt-4">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>Oluşturan: <?php echo e($assignment->createdBy->name ?? 'Bilinmiyor'); ?></span>
                            <span>Oluşturulma Tarihi: <?php echo e($assignment->created_at->format('d.m.Y H:i')); ?></span>
                        </div>
                    </div>

                    
                    <?php if(Gate::allows('manage-assignment', $assignment)): ?>
                        <div class="card-footer text-end">
                            <a href="<?php echo e(route('service.assignments.edit', $assignment->id)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Görevi Düzenle / Tamamla
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/service/assignments/show.blade.php ENDPATH**/ ?>