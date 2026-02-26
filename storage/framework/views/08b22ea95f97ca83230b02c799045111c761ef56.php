<div class="tab-pane fade" id="returns" role="tabpanel">
    <h5><i class="fa-solid fa-plus-circle me-2"></i>Hızlı İade Kaydı Ekle</h5>
    <form action="<?php echo e(route('customers.returns.store', $customer)); ?>" method="POST" class="quick-add-form">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Ürün/Proje Adı (*)</label>
                <input type="text" name="product_name" list="productList" class="form-control" required
                    placeholder="Listeden seçin veya yazın...">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-primary">Gönderilen Miktar & Birim (*)</label>
                <div class="input-group">
                    <input type="number" name="shipped_quantity" class="form-control border-primary" required
                        step="0.01" placeholder="0.00">
                    <select name="shipped_unit" class="form-select border-primary" style="max-width: 100px;" required>
                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($birim->ad); ?>"><?php echo e($birim->ad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label text-danger">İade Gelen Miktar & Birim (*)</label>
                <div class="input-group">
                    <input type="number" name="quantity" class="form-control border-danger" required step="0.01"
                        placeholder="0.00">
                    <select name="unit" class="form-select border-danger" style="max-width: 100px;" required>
                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($birim->ad); ?>"><?php echo e($birim->ad); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Bağlı Şikayet</label>
                <select name="complaint_id" class="form-select">
                    <option value="">Seçilmedi</option>
                    <?php $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>">#<?php echo e($c->id); ?> - <?php echo e(Str::limit($c->title, 20)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Bağlı Numune</label>
                <select name="customer_sample_id" class="form-select">
                    <option value="">Seçilmedi</option>
                    <?php $__currentLoopData = $customer->samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>">#<?php echo e($s->id); ?> - <?php echo e(Str::limit($s->subject, 20)); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Tarih (*)</label>
                <input type="date" name="return_date" class="form-control" value="<?php echo e(date('Y-m-d')); ?>" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">İade Nedeni (*)</label>
                <input type="text" name="reason" class="form-control" required
                    placeholder="İade sebebini kısaca yazın...">
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                    class="fa-solid fa-save me-2"></i> Kaydet</button>
        </div>
    </form>
    <hr class="my-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı İadeler ve Oranlar</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterRetDate" class="filter-input bg-white">
            <input type="text" id="filterRetSearch" class="filter-input bg-white" placeholder="Ürün, Neden ara...">
            <select id="filterRetStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="pending">Beklemede</option>
                <option value="approved">Onaylandı</option>
                <option value="completed">Gerçekleşti</option>
                <option value="rejected">Reddedildi</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="returnsTable">
            <thead class="bg-light">
                <tr>
                    <th>Tarih</th>
                    <th>Ürün / Kaynak</th>
                    <th>Gönderilen</th>
                    <th>İade Gelen</th>
                    <th>İade Oranı</th>
                    <th>Durum</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customer->returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="return-item" data-date="<?php echo e($return->return_date->format('Y-m-d')); ?>"
                        data-search="<?php echo e(mb_strtolower($return->product_name . ' ' . $return->reason)); ?>"
                        data-status="<?php echo e($return->status); ?>">
                        <td><?php echo e($return->return_date->format('d.m.Y')); ?></td>
                        <td>
                            <span class="fw-bold"><?php echo e($return->product_name); ?></span><br>
                            <small class="text-muted"><?php echo e(Str::limit($return->reason, 30)); ?></small>
                            <?php if($return->complaint_id): ?>
                                <br><span class="badge bg-danger bg-opacity-10 text-danger border border-danger mt-1"
                                    style="font-size: 0.65rem;">Şikayet #<?php echo e($return->complaint_id); ?></span>
                            <?php endif; ?>
                            <?php if($return->customer_sample_id): ?>
                                <br><span
                                    class="badge bg-success bg-opacity-10 text-success border border-success mt-1"
                                    style="font-size: 0.65rem;">Numune #<?php echo e($return->customer_sample_id); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="text-primary fw-semibold"><?php echo e($return->shipped_quantity); ?>

                            <?php echo e($return->shipped_unit); ?></td>
                        <td class="text-danger fw-semibold"><?php echo e($return->quantity); ?> <?php echo e($return->unit); ?></td>
                        <td>
                            <?php
                                $rate = 0;
                                $showRate = false;
                                $shippedUnit = strtolower($return->shipped_unit);
                                $returnUnit = strtolower($return->unit);
                                if ($return->shipped_quantity > 0) {
                                    if ($shippedUnit === $returnUnit) {
                                        $rate = ($return->quantity / $return->shipped_quantity) * 100;
                                        $showRate = true;
                                    } elseif ($shippedUnit == 'ton' && $returnUnit == 'kg') {
                                        $rate = ($return->quantity / ($return->shipped_quantity * 1000)) * 100;
                                        $showRate = true;
                                    } elseif ($shippedUnit == 'kg' && $returnUnit == 'gr') {
                                        $rate = ($return->quantity / ($return->shipped_quantity * 1000)) * 100;
                                        $showRate = true;
                                    }
                                }
                                $badgeClass =
                                    $rate <= 2 ? 'bg-success' : ($rate <= 5 ? 'bg-warning text-dark' : 'bg-danger');
                            ?>
                            <?php if($showRate): ?>
                                <span
                                    class="badge <?php echo e($badgeClass); ?> rounded-pill px-3 py-2">%<?php echo e(number_format($rate, 2, ',', '.')); ?></span>
                            <?php else: ?>
                                <span
                                    class="badge bg-secondary bg-opacity-10 text-secondary border rounded-pill px-2 py-1"
                                    title="Birimler farklı olduğu için hesaplanamadı.">Birim Farkı</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="<?php echo e(route('customer-returns.update-status', $return->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <select name="status" class="form-select status-select status-<?php echo e($return->status); ?>"
                                    onchange="this.form.submit()">
                                    <option value="pending" <?php echo e($return->status == 'pending' ? 'selected' : ''); ?>>
                                        Beklemede</option>
                                    <option value="approved" <?php echo e($return->status == 'approved' ? 'selected' : ''); ?>>
                                        Onaylandı</option>
                                    <option value="completed" <?php echo e($return->status == 'completed' ? 'selected' : ''); ?>>
                                        İade Gerçekleşti</option>
                                    <option value="rejected" <?php echo e($return->status == 'rejected' ? 'selected' : ''); ?>>
                                        Reddedildi</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editReturnModal<?php echo e($return->id); ?>" title="Düzenle"><i
                                        class="fa-solid fa-pen"></i></button>
                                <form action="<?php echo e(route('customer-returns.destroy', $return->id)); ?>" method="POST"
                                    onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                            class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr class="empty-message-row">
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fa-solid fa-rotate-left fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Henüz iade kaydı bulunamadı.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history-timeline','data' => ['activities' => $historyService->getSupportHistory($customer)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('history-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($historyService->getSupportHistory($customer))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/returns.blade.php ENDPATH**/ ?>