<?php $__env->startSection('title', 'Dinamik Alan Yönetimi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid py-4">

        
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h2 class="h3 fw-bold text-dark mb-1">Dinamik Alan Yönetimi</h2>
                <p class="text-muted small mb-0">
                    Modüllere (Lojistik, Üretim vb.) ait özel veri alanlarını buradan yapılandırabilirsiniz.
                </p>
            </div>

            <a href="<?php echo e(route('admin.custom-fields.create')); ?>"
                class="btn btn-primary d-inline-flex align-items-center shadow-sm">
                <svg style="width: 20px; height: 20px;" class="me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Yeni Alan Ekle</span>
            </a>
        </div>

        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0">Modül</th>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0">Etiket & Anahtar
                                </th>
                                
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0">Geçerli Birim
                                </th>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0">Veri Tipi</th>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0 text-center">
                                    Zorunlu</th>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0 text-center">Durum
                                </th>
                                <th scope="col" class="px-4 py-3 small text-uppercase fw-bold border-0 text-end">İşlemler
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    // Modül İsimlendirmesi
                                    $moduleName = match ($field->model_scope) {
                                        'App\Models\Shipment' => 'Lojistik',
                                        'App\Models\Event' => 'Hizmet',
                                        'App\Models\MaintenancePlan' => 'Bakım',
                                        'App\Models\ProductionPlan' => 'Üretim',
                                        'App\Models\User' => 'Personel',
                                        default => class_basename($field->model_scope),
                                    };
                                    $initial = mb_substr($moduleName, 0, 1);

                                    // Renk Sınıfları
                                    $bsClass = match ($initial) {
                                        'L' => 'text-primary bg-light',
                                        'H' => 'text-info bg-light',
                                        'B' => 'text-warning bg-light',
                                        'Ü' => 'text-success bg-light',
                                        'P' => 'text-secondary bg-light',
                                        default => 'text-dark bg-light',
                                    };
                                ?>

                                <tr>
                                    
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center rounded border fw-bold <?php echo e($bsClass); ?>"
                                                style="width: 40px; height: 40px; font-size: 1.1em;">
                                                <?php echo e($initial); ?>

                                            </div>
                                            <div class="ms-3">
                                                <div class="fw-bold text-dark"><?php echo e($moduleName); ?></div>
                                                <div class="small text-muted"><?php echo e(class_basename($field->model_scope)); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    
                                    <td class="px-4 py-3">
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold text-dark"><?php echo e($field->label); ?></span>
                                            <span
                                                class="badge bg-light text-dark border font-monospace mt-1 align-self-start">
                                                <?php echo e($field->key); ?>

                                            </span>
                                        </div>
                                    </td>

                                    
                                    <td class="px-4 py-3">
                                        <?php if($field->businessUnit): ?>
                                            <span
                                                class="badge bg-white text-dark border border-dark border-opacity-25 shadow-sm">
                                                <i class="fa-solid fa-industry text-secondary me-1"></i>
                                                <?php echo e($field->businessUnit->name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10">
                                                <i class="fa-solid fa-globe me-1"></i>
                                                Genel (Tüm Fabrikalar)
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-4 py-3">
                                        <span
                                            class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10">
                                            <?php echo e(ucfirst($field->type)); ?>

                                        </span>
                                    </td>

                                    
                                    <td class="px-4 py-3 text-center">
                                        <?php if($field->is_required): ?>
                                            <div class="d-flex justify-content-center">
                                                <span
                                                    class="d-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10 text-danger"
                                                    style="width: 28px; height: 28px;" title="Zorunlu Alan">
                                                    <svg style="width: 16px; height: 16px;" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted fw-bold">-</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-4 py-3 text-center">
                                        <?php if($field->is_active): ?>
                                            <span class="badge rounded-pill bg-success text-white px-3 py-2">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-secondary text-white px-3 py-2">Pasif</span>
                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="px-4 py-3 text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="<?php echo e(route('admin.custom-fields.edit', $field->id)); ?>"
                                                class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="Düzenle">
                                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <form action="<?php echo e(route('admin.custom-fields.destroy', $field->id)); ?>"
                                                method="POST"
                                                onsubmit="return confirm('Bu alanı silmek istediğinize emin misiniz?');"
                                                class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-danger d-flex align-items-center justify-content-center"
                                                    style="width: 32px; height: 32px;" title="Sil">
                                                    <svg style="width: 16px; height: 16px;" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div
                                            class="d-flex flex-column align-items-center justify-content-center text-muted">
                                            <div class="bg-light rounded-circle p-3 mb-3">
                                                <svg style="width: 32px; height: 32px;" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h5 class="fw-bold text-dark">Kayıtlı Alan Bulunamadı</h5>
                                            <p class="mb-0">Henüz sisteme eklenmiş bir dinamik alan yok.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if(method_exists($fields, 'links') && $fields->hasPages()): ?>
                <div class="card-footer bg-white border-top-0 py-3">
                    <?php echo e($fields->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/admin/custom_fields/index.blade.php ENDPATH**/ ?>