<div class="tab-pane fade" id="logistics" role="tabpanel">
    <h5><i class="fa-solid fa-truck-fast me-2 text-info"></i>Yeni Lojistik / Sevkiyat Görevi</h5>
    <form action="<?php echo e(route('service.vehicle-assignments.store')); ?>" method="POST" class="quick-add-form mb-5">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="customer_id" value="<?php echo e($customer->id); ?>">
        <input type="hidden" name="type" value="logistics">

        <div class="row">
            <div class="col-md-4 mb-3"><label class="form-label">Görev Başlığı (*)</label><input type="text"
                    name="title" class="form-control" required placeholder="Örn: İstanbul Sevkiyatı"></div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Araç Seçimi</label>
                <select name="vehicle_id" class="form-select">
                    <option value="">Araç Seçiniz...</option>
                    <?php $__currentLoopData = \App\Models\Vehicle::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($vehicle->id); ?>"><?php echo e($vehicle->plate_number); ?> - <?php echo e($vehicle->model); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4 mb-3"><label class="form-label">Planlanan Çıkış (*)</label><input type="datetime-local"
                    name="start_time" class="form-control" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required></div>
        </div>

        
        <div class="p-3 mb-3 rounded"
            style="background-color: rgba(102, 126, 234, 0.05); border: 1px solid rgba(102, 126, 234, 0.2);">
            <div class="mb-3">
                <label class="form-label fw-bold text-dark"><i class="fa-solid fa-layer-group me-1"></i> Gönderi Türü
                    (*)</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipment_type" id="type_product_new"
                            value="product" checked onchange="toggleShipmentType('product', 'new_')">
                        <label class="form-check-label fw-bold text-primary" for="type_product_new"><i
                                class="fa-solid fa-box me-1"></i> Standart Ürün Sevkiyatı</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="shipment_type" id="type_sample_new"
                            value="sample" onchange="toggleShipmentType('sample', 'new_')">
                        <label class="form-check-label fw-bold text-success" for="type_sample_new"><i
                                class="fa-solid fa-flask me-1"></i> Numune Sevkiyatı</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3" id="new_wrapper_product">
                    <label class="form-label text-primary">Taşınacak Ürün Seçimi</label>
                    <select name="customer_product_id" id="new_product_select" class="form-select border-primary">
                        <option value="">Ürün Seçiniz...</option>
                        <?php $__currentLoopData = $customer->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($prod->id); ?>"><?php echo e($prod->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3" id="new_wrapper_sample" style="display: none;">
                    <label class="form-label text-success">Gönderilecek Numune Seçimi</label>
                    <select name="customer_sample_id" id="new_sample_select" class="form-select border-success">
                        <option value="">Numune Seçiniz...</option>
                        <?php $__currentLoopData = $customer->samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($sample->id); ?>">#<?php echo e($sample->id); ?> -
                                <?php echo e(Str::limit($sample->subject, 25)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Miktar & Birim</label>
                    <div class="input-group">
                        <input type="number" name="quantity" class="form-control" placeholder="0.00" step="0.01">
                        <select name="unit" class="form-select" style="max-width: 90px;">
                            <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($birim->ad); ?>"><?php echo e($birim->ad); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Sorumlu Şoför</label>
                    <select name="user_id" class="form-select">
                        <option value="<?php echo e(Auth::id()); ?>"><?php echo e(Auth::user()->name); ?> (Ben)</option>
                        <?php $__currentLoopData = \App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($user->id !== Auth::id()): ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 mb-3">
                <label class="form-label">Açıklama / Adres Detayı</label>
                <div class="input-group">
                    <input type="text" name="description" id="new_logistics_desc" class="form-control"
                        placeholder="Sevkiyat hakkında notlar...">
                    <button class="btn btn-outline-secondary" type="button" id="btn_new_log_desc"
                        onclick="toggleVoiceInput('new_logistics_desc', 'btn_new_log_desc')"><i
                            class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
        </div>
        <div class="text-end"><button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                    class="fa-solid fa-calendar-check me-2"></i> Görevi Planla</button></div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-truck-ramp-box me-2 text-info"></i>Lojistik Hareketleri</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterLogDate" class="filter-input bg-white">
            <input type="text" id="filterLogSearch" class="filter-input bg-white"
                placeholder="Görev, araç, ürün ara...">
            <select id="filterLogStatus" class="form-select filter-input bg-white py-1" style="min-width: 130px;">
                <option value="">Tüm Durumlar</option>
                <option value="pending">Beklemede</option>
                <option value="on_road">Yolda</option>
                <option value="completed">Tamamlandı</option>
            </select>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="logisticsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Tarih</th>
                            <th>Görev & İçerik</th>
                            <th>Araç / Plaka</th>
                            <th>Sorumlu</th>
                            <th>Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $customer->vehicleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="logistic-item" data-date="<?php echo e($assignment->start_time->format('Y-m-d')); ?>"
                                data-search="<?php echo e(mb_strtolower($assignment->title . ' ' . ($assignment->vehicle->plate_number ?? '') . ' ' . ($assignment->product->name ?? '') . ' ' . ($assignment->sample->subject ?? ''))); ?>"
                                data-status="<?php echo e($assignment->status); ?>">
                                <td class="ps-4"><?php echo e($assignment->start_time->format('d.m.Y H:i')); ?></td>
                                <td>
                                    <span class="fw-bold d-block"><?php echo e($assignment->title); ?></span>

                                    
                                    <?php if($assignment->shipment_type == 'sample' && $assignment->sample): ?>
                                        <small class="text-success fw-bold"><i
                                                class="fa-solid fa-flask me-1"></i>Numune: <?php echo e($assignment->quantity); ?>

                                            <?php echo e($assignment->unit); ?> -
                                            <?php echo e(Str::limit($assignment->sample->subject, 20)); ?></small>
                                    <?php elseif($assignment->product): ?>
                                        <small class="text-primary fw-bold"><i class="fa-solid fa-box me-1"></i>Ürün:
                                            <?php echo e($assignment->quantity); ?> <?php echo e($assignment->unit); ?> -
                                            <?php echo e($assignment->product->name); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($assignment->vehicle): ?>
                                        <span class="badge bg-dark"><i
                                                class="fa-solid fa-truck me-1"></i><?php echo e($assignment->vehicle->plate_number); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted small">Araçsız</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($assignment->responsible->name ?? '-'); ?></td>
                                <td>
                                    <form action="<?php echo e(route('service.assignments.update-status', $assignment->id)); ?>"
                                        method="POST" class="m-0">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <select name="status"
                                            class="form-select status-select status-<?php echo e($assignment->status); ?>"
                                            onchange="this.form.submit()" style="min-width: 130px;">
                                            <option value="pending"
                                                <?php echo e($assignment->status == 'pending' ? 'selected' : ''); ?>>Beklemede
                                            </option>
                                            <option value="on_road"
                                                <?php echo e($assignment->status == 'on_road' ? 'selected' : ''); ?>>Yolda</option>
                                            <option value="completed"
                                                <?php echo e($assignment->status == 'completed' ? 'selected' : ''); ?>>Tamamlandı
                                            </option>
                                            <option value="cancelled"
                                                <?php echo e($assignment->status == 'cancelled' ? 'selected' : ''); ?>>İptal
                                            </option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light border"
                                            data-bs-toggle="modal"
                                            data-bs-target="#historyLogisticsModal<?php echo e($assignment->id); ?>"
                                            title="Tarihçeyi Gör">
                                            <i class="fa-solid fa-clock-rotate-left text-info"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editLogisticsModal<?php echo e($assignment->id); ?>" title="Düzenle"
                                            onclick="setTimeout(() => toggleShipmentType('<?php echo e($assignment->shipment_type ?? 'product'); ?>', 'edit_<?php echo e($assignment->id); ?>_'), 100);"><i
                                                class="fa-solid fa-pen"></i></button>
                                        <form
                                            action="<?php echo e(route('service.vehicle-assignments.destroy', $assignment->id)); ?>"
                                            method="POST"
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-truck-loading fa-2x mb-3 opacity-50"></i>
                                    <p class="mb-0">Bu müşteriye planlanmış bir lojistik görevi bulunamadı.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/logistics.blade.php ENDPATH**/ ?>