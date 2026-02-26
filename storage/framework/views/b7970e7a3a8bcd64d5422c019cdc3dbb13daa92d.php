<div class="tab-pane fade" id="samples" role="tabpanel">
    <h5><i class="fa-solid fa-flask me-2"></i>Hızlı Numune Kaydı Ekle</h5>
    <form action="<?php echo e(route('customers.samples.store', $customer)); ?>" method="POST" class="quick-add-form">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Konu (*)</label>
                <input type="text" name="subject" class="form-control" required placeholder="Örn: Yeni Preform Denemesi">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">İlgili Ürün/Proje</label>
                <input type="text" name="product_name" list="productList" class="form-control" placeholder="Listeden seçin veya yeni yazın...">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Miktar & Birim (*)</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control" required step="0.01" value="1">
                    <select name="unit" class="form-select" style="max-width: 90px;" required>
                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($birim->ad); ?>"><?php echo e($birim->ad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Gönderim Tarihi</label>
                <input type="date" name="sent_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Kargo Firması ve Takip No</label>
                <div class="input-group">
                    <input type="text" name="cargo_company" class="form-control" placeholder="Kargo Firması">
                    <input type="text" name="tracking_number" class="form-control" placeholder="Takip No">
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Ekstra Ürün Bilgisi</label>
                <input type="text" name="product_info" class="form-control" placeholder="Gerekirse detay girin...">
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-save me-2"></i> Kaydet</button>
        </div>
    </form>
    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Gönderilen Numuneler</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterSamDate" class="filter-input bg-white">
            <input type="text" id="filterSamSearch" class="filter-input bg-white" placeholder="Konu, ürün ara...">
            <select id="filterSamStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="preparing">Hazırlanıyor</option>
                <option value="sent">Gönderildi</option>
                <option value="delivered">Teslim Edildi</option>
                <option value="approved">Onaylandı</option>
                <option value="rejected">Reddedildi</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="samplesTable">
            <thead class="bg-light">
                <tr>
                    <th>Tarih</th>
                    <th>Konu</th>
                    <th>Bağlı Ürün</th>
                    <th>Miktar</th>
                    <th>Kargo</th>
                    <th>Durum</th>
                    <th>Geri Bildirim</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customer->samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="sample-item" data-date="<?php echo e($sample->sent_date ? $sample->sent_date->format('Y-m-d') : ''); ?>" data-search="<?php echo e(mb_strtolower($sample->subject . ' ' . $sample->product_info . ' ' . ($sample->product->name ?? ''))); ?>" data-status="<?php echo e($sample->status); ?>">
                        <td><?php echo e($sample->sent_date ? $sample->sent_date->format('d.m.Y') : '-'); ?></td>
                        <td><span class="fw-bold"><?php echo e($sample->subject); ?></span><br><small class="text-muted"><?php echo e($sample->product_info); ?></small></td>
                        <td>
                            <?php if($sample->product): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><?php echo e($sample->product->name); ?></span>
                            <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                        </td>
                        <td><?php echo e($sample->quantity); ?> <?php echo e($sample->unit); ?></td>
                        <td>
                            <?php if($sample->cargo_company): ?>
                                <?php echo e($sample->cargo_company); ?><br><small class="text-muted"><?php echo e($sample->tracking_number); ?></small>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?php echo e(route('customer-samples.update-status', $sample->id)); ?>" method="POST" class="m-0">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="feedback" value="<?php echo e($sample->feedback); ?>">
                                <select name="status" class="form-select status-select status-<?php echo e($sample->status); ?>" onchange="this.form.submit()" style="min-width: 130px;">
                                    <option value="preparing" <?php echo e($sample->status == 'preparing' ? 'selected' : ''); ?>>Hazırlanıyor</option>
                                    <option value="sent" <?php echo e($sample->status == 'sent' ? 'selected' : ''); ?>>Gönderildi</option>
                                    <option value="delivered" <?php echo e($sample->status == 'delivered' ? 'selected' : ''); ?>>Teslim Edildi</option>
                                    <option value="approved" <?php echo e($sample->status == 'approved' ? 'selected' : ''); ?>>Onaylandı</option>
                                    <option value="rejected" <?php echo e($sample->status == 'rejected' ? 'selected' : ''); ?>>Reddedildi</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex align-items-start justify-content-between">
                                <div class="text-muted small text-wrap text-break" style="width: 180px; hyphens: auto; cursor: pointer; line-height: 1.5; text-align: justify;" lang="tr" data-bs-toggle="modal" data-bs-target="#feedbackModal<?php echo e($sample->id); ?>" title="Düzenle/Oku">
                                    <?php echo e($sample->feedback ?: 'Henüz girilmedi'); ?>

                                </div>
                                <button type="button" class="btn btn-sm btn-link text-primary p-0 ms-2 flex-shrink-0 mt-1" data-bs-toggle="modal" data-bs-target="#feedbackModal<?php echo e($sample->id); ?>"><i class="fa-solid fa-pen-to-square fs-5"></i></button>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSampleModal<?php echo e($sample->id); ?>" title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                <form action="<?php echo e(route('customer-samples.destroy', $sample->id)); ?>" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                    <tr class="empty-message-row">
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="fa-solid fa-flask fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Henüz numune kaydı bulunamadı.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history-timeline','data' => ['activities' => $historyService->getCommercialHistory($customer)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('history-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($historyService->getCommercialHistory($customer))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/samples.blade.php ENDPATH**/ ?>