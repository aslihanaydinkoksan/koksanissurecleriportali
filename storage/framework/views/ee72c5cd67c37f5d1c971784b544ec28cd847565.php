<div class="card-header bg-transparent border-bottom px-4 pt-4 pb-3">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2 class="mb-2 fw-bold" style="color: #2d3748;">
                <i class="fa-solid fa-building me-2" style="color: #667EEA;"></i><?php echo e($customer->name); ?>

                <?php if($customer->is_active ?? true): ?>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success fs-6 align-middle ms-2" style="border-radius: 8px;"><i class="fa-solid fa-circle-check me-1"></i> Aktif</span>
                <?php else: ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger fs-6 align-middle ms-2" style="border-radius: 8px;"><i class="fa-solid fa-ban me-1"></i> Pasif</span>
                <?php endif; ?>
            </h2>
            <p class="text-muted mb-0">Müşteri Detayları ve Süreç Yönetim Portalı</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="<?php echo e(route('customers.edit', $customer)); ?>" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-pen me-2"></i>Bilgileri Düzenle</a>
            <form action="<?php echo e(route('customers.destroy', $customer->id)); ?>" method="POST" class="d-inline">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-animated-gradient rounded-pill px-4" onclick="return confirm('Bu müşteriyi silmek istediğinizden emin misiniz?');"><i class="fa-solid fa-trash-alt me-2"></i>Kaydı Sil</button>
            </form>
        </div>
    </div>
</div><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/partials/header.blade.php ENDPATH**/ ?>