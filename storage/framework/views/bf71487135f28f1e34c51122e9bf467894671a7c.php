<div class="tab-pane fade" id="visits" role="tabpanel">
    <h5><i class="fa-solid fa-clipboard-list me-2 text-primary"></i>Yeni Müşteri Ziyaret Formu</h5>
    <form action="<?php echo e(route('customers.visits.store', $customer)); ?>" method="POST" class="quick-add-form mb-5" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Ziyaret Tarihi (*)</label>
                <input type="datetime-local" name="visit_date" class="form-control" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" required>
            </div>
            <div class="col-md-9">
                <label class="form-label d-block">Ziyaret Sebebi (*)</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" class="btn-check" name="visit_reason" id="reason1" value="Şikayet" autocomplete="off">
                    <label class="btn btn-outline-danger" for="reason1"><i class="fa-solid fa-triangle-exclamation me-1"></i> Şikayet</label>
                    
                    <input type="radio" class="btn-check" name="visit_reason" id="reason2" value="Periyodik Ziyaret" autocomplete="off">
                    <label class="btn btn-outline-primary" for="reason2"><i class="fa-solid fa-calendar-check me-1"></i> Ziyaret</label>
                    
                    <input type="radio" class="btn-check" name="visit_reason" id="reason3" value="Ürün Denemesi" autocomplete="off">
                    <label class="btn btn-outline-success" for="reason3"><i class="fa-solid fa-flask me-1"></i> Ürün Denemesi</label>
                    
                    <input type="radio" class="btn-check" name="visit_reason" id="reason4" value="Diğer" autocomplete="off" checked>
                    <label class="btn btn-outline-secondary" for="reason4">Diğer</label>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Görüşülen Kişiler</label>
                <div class="dropdown mb-2">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">Listeden Seçiniz...</button>
                    <ul class="dropdown-menu w-100 p-2 shadow-sm" style="max-height: 200px; overflow-y: auto;">
                        <?php $__empty_1 = true; $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <div class="form-check m-1">
                                    <input class="form-check-input" type="checkbox" name="contact_persons[]" value="<?php echo e($contact->name); ?>" id="vis_contact_<?php echo e($contact->id); ?>">
                                    <label class="form-check-label" for="vis_contact_<?php echo e($contact->id); ?>"><?php echo e($contact->name); ?> <small class="text-muted">(<?php echo e($contact->title ?? '-'); ?>)</small></label>
                                </div>
                            </li> 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                            <li class="text-muted small p-2">Kayıtlı kişi yok.</li>
                        <?php endif; ?>
                    </ul>
                </div>
                <input type="text" name="other_contact_persons" class="form-control form-control-sm" placeholder="Listede olmayanlar (virgülle ayırın)...">
            </div>
            <div class="col-md-6">
                <label class="form-label">Açıklama (Opsiyonel)</label>
                <div class="input-group">
                    <textarea name="visit_notes" id="new_visit_notes" class="form-control" rows="3" placeholder="Ziyaret sebebi hakkında kısa not..."></textarea>
                    <button class="btn btn-outline-secondary" type="button" id="btn_new_visit_notes" onclick="toggleVoiceInput('new_visit_notes', 'btn_new_visit_notes')"><i class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Ürün Tanımı</label>
                <select name="customer_product_id" class="form-select">
                    <option value="">Seçiniz...</option>
                    <?php $__currentLoopData = $customer->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($prod->id); ?>"><?php echo e($prod->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label">Barkod No</label><input type="text" name="barcode" class="form-control"></div>
            <div class="col-md-3"><label class="form-label">Lot No</label><input type="text" name="lot_no" class="form-control"></div>
            <div class="col-md-3">
                <label class="form-label">Şikayet Kayıt No</label>
                <select name="complaint_id" class="form-select">
                    <option value="">Yok / Bağımsız</option>
                    <?php $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($c->id); ?>">#<?php echo e($c->id); ?> - <?php echo e(Str::limit($c->title, 15)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-bold text-primary">Tespitler / Yapılan İşlemler (*)</label>
                <div class="input-group">
                    <textarea name="findings" id="new_visit_findings" class="form-control" rows="4" required placeholder="Sahada ne gördünüz? Ne işlem yaptınız?"></textarea>
                    <button class="btn btn-outline-primary" type="button" id="btn_new_visit_findings" onclick="toggleVoiceInput('new_visit_findings', 'btn_new_visit_findings')"><i class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold text-success">Sonuç / Karar (*)</label>
                <div class="input-group">
                    <textarea name="result" id="new_visit_result" class="form-control" rows="4" required placeholder="Sonuç ne oldu? Problem çözüldü mü?"></textarea>
                    <button class="btn btn-outline-success" type="button" id="btn_new_visit_result" onclick="toggleVoiceInput('new_visit_result', 'btn_new_visit_result')"><i class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
            <div class="col-12 mt-3">
                <label class="form-label fw-bold"><i class="fa-solid fa-paperclip me-1"></i> Dosya / Fotoğraf Ekle</label>
                <input type="file" name="visit_files[]" class="form-control" multiple>
                <small class="text-muted">Resim, PDF, Excel veya Word dosyaları ekleyebilirsiniz.</small>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-5 py-2"><i class="fa-solid fa-save me-2"></i> Formu Kaydet</button>
        </div>
    </form>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h5 class="mb-0"><i class="fa-solid fa-file-contract me-2 text-primary"></i>Kayıtlı Ziyaret Formları</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center flex-grow-1 mx-lg-3">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterVisDate" class="filter-input bg-white">
            <input type="text" id="filterVisSearch" class="filter-input bg-white flex-grow-1" placeholder="Ürün, kişi, sonuç ara...">
            <select id="filterVisStatus" class="form-select filter-input bg-white py-1" style="min-width: 140px;">
                <option value="">Tüm Sebepler</option>
                <option value="Şikayet">Şikayet</option>
                <option value="Periyodik Ziyaret"> Ziyaret</option>
                <option value="Ürün Denemesi">Ürün Denemesi</option>
                <option value="Diğer">Diğer</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="visitsTable">
            <thead class="bg-light">
                <tr>
                    <th>Tarih</th>
                    <th>Sebep</th>
                    <th>Ürün / Lot</th>
                    <th>Görüşülen</th>
                    <th>Sonuç</th>
                    <th>Dosyalar</th>
                    <th class="text-end">İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customer->visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="visit-item" data-date="<?php echo e($visit->visit_date ? $visit->visit_date->format('Y-m-d') : $visit->created_at->format('Y-m-d')); ?>" data-search="<?php echo e(mb_strtolower(($visit->product->name ?? '') . ' ' . implode(' ', $visit->contact_persons ?? []) . ' ' . $visit->result . ' ' . $visit->findings)); ?>" data-status="<?php echo e($visit->visit_reason); ?>">
                        <td><?php echo e($visit->visit_date ? $visit->visit_date->format('d.m.Y H:i') : $visit->created_at->format('d.m.Y H:i')); ?></td>
                        <td><span class="badge bg-light text-dark border"><?php echo e($visit->visit_reason ?? 'Belirtilmedi'); ?></span></td>
                        <td>
                            <?php if($visit->product): ?>
                                <strong class="text-primary"><?php echo e($visit->product->name); ?></strong>
                                <?php if($visit->lot_no): ?> <br><small class="text-muted">Lot: <?php echo e($visit->lot_no); ?></small> <?php endif; ?>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                        <td>
                            <?php if($visit->contact_persons): ?>
                                <?php $__currentLoopData = $visit->contact_persons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border"><?php echo e($p); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?> - <?php endif; ?>
                        </td>
                        <td><?php echo e(Str::limit($visit->result, 40)); ?></td>
                        <td>
                            <?php if($visit->getMedia('visit_attachments')->count() > 0): ?>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="fa-solid fa-paperclip"></i> <?php echo e($visit->getMedia('visit_attachments')->count()); ?></button>
                                    <ul class="dropdown-menu">
                                        <?php $__currentLoopData = $visit->getMedia('visit_attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><a class="dropdown-item small" href="<?php echo e($media->getUrl()); ?>" target="_blank"><i class="fa-regular fa-file me-1"></i> <?php echo e(Str::limit($media->file_name, 20)); ?></a></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="<?php echo e(route('visits.print', $visit->id)); ?>" target="_blank" class="btn btn-sm btn-outline-dark" title="Yazdır / PDF"><i class="fa-solid fa-print"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editVisitModal<?php echo e($visit->id); ?>" title="Düzenle"><i class="fa-solid fa-edit"></i></button>
                                <form action="<?php echo e(route('visits.destroy', $visit->id)); ?>" method="POST" onsubmit="return confirm('Bu formu silmek istediğinize emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                    <tr class="empty-message-row">
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="fa-solid fa-folder-open fa-2x mb-3 opacity-25"></i>
                            <p class="mb-0">Henüz kayıtlı bir ziyaret formu yok.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.history-timeline','data' => ['activities' => $historyService->getTechnicalHistory($customer)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('history-timeline'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['activities' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($historyService->getTechnicalHistory($customer))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/visits.blade.php ENDPATH**/ ?>