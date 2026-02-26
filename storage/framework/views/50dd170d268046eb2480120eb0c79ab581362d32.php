<div class="tab-pane fade show active" id="details" role="tabpanel">
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="mb-0 border-0"><i class="fa-solid fa-building me-2"></i>Firma Bilgileri</h5>
            </div>
            <dl class="row detail-list mt-3">
                <dt class="col-sm-4 text-primary">Çalışma Başlangıcı</dt>
                <dd class="col-sm-8 fw-bold text-primary">
                    <?php echo e($customer->start_date ? \Carbon\Carbon::parse($customer->start_date)->format('d.m.Y') : 'Tarih Belirtilmedi'); ?>

                </dd>
                <?php if(!($customer->is_active ?? true) && $customer->end_date): ?>
                    <dt class="col-sm-4 text-danger">Çalışma Bitişi</dt>
                    <dd class="col-sm-8 fw-bold text-danger">
                        <?php echo e(\Carbon\Carbon::parse($customer->end_date)->format('d.m.Y')); ?>

                    </dd>
                <?php endif; ?>
                <dt class="col-sm-4 mt-2">Adres</dt>
                <dd class="col-sm-8 mt-2"><?php echo e($customer->address ?: '-'); ?></dd>
                <dt class="col-sm-4">Genel Tel</dt>
                <dd class="col-sm-8"><?php echo e($customer->phone ?: '-'); ?></dd>
                <dt class="col-sm-4">Genel Email</dt>
                <dd class="col-sm-8"><?php echo e($customer->email ?: '-'); ?></dd>
            </dl>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <h5 class="mb-0 border-0"><i class="fa-solid fa-users me-2"></i>İletişim Kişileri</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover">
                    <thead>
                        <tr>
                            <th>Ad Soyad</th>
                            <th>Ünvan</th>
                            <th>İletişim</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="align-middle"><span class="fw-bold text-dark"><?php echo e($contact->name); ?></span>
                                    <?php if($contact->is_primary): ?>
                                        <i class="fa-solid fa-star text-warning small ms-1" title="Ana İletişim"></i>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle text-muted small"><?php echo e($contact->title ?? '-'); ?></td>
                                <td class="small">
                                    <?php if($contact->email): ?>
                                        <div class="mb-1"><i class="fa-solid fa-envelope text-primary me-1"></i><?php echo e($contact->email); ?></div>
                                    <?php endif; ?>
                                    <?php if($contact->phone): ?>
                                        <div><i class="fa-solid fa-phone text-success me-1"></i><?php echo e($contact->phone); ?></div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                            <tr>
                                <td colspan="3" class="text-center text-muted">Kayıtlı kişi yok.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/details.blade.php ENDPATH**/ ?>