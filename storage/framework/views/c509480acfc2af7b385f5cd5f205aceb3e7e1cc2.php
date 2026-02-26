<div class="tab-pane fade" id="opportunities" role="tabpanel">
    <h5><i class="fa-solid fa-plus-circle me-2 text-warning"></i>Yeni Fırsat / Duyum Ekle</h5>
    <form action="<?php echo e(route('customers.opportunities.store', $customer)); ?>" method="POST" class="quick-add-form mb-5">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Başlık / Konu (*)</label>
                <input type="text" name="title" class="form-control" required
                    placeholder="Örn: 2025 Yılı Preform İhalesi">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Tahmini Tutar & Para Birimi</label>
                <div class="input-group">
                    <input type="number" name="amount" class="form-control" step="0.01" placeholder="0.00">
                    <select name="currency" class="form-select" style="max-width: 80px;">
                        <option value="TRY">₺</option>
                        <option value="USD">$</option>
                        <option value="EUR">€</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Beklenen Karar Trh.</label>
                <input type="date" name="expected_close_date" class="form-control">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Aşama (*)</label>
                <select name="stage" id="new_opp_stage" class="form-select" required
                    onchange="toggleLossReason('new_opp_stage', 'new_opp_loss_wrapper')">
                    <option value="duyum">Söylenti / Duyum</option>
                    <option value="teklif">Teklif Verildi</option>
                    <option value="gorusme">Görüşme Aşamasında</option>
                    <option value="kazanildi">Kazanıldı (Won)</option>
                    <option value="kaybedildi">Kaybedildi (Lost)</option>
                </select>
            </div>

            
            <div class="col-md-4 mb-3">
                <label class="form-label text-danger"><i class="fa-solid fa-user-ninja me-1"></i>Rekabet Edilen
                    Rakip</label>
                <div class="input-group">
                    <select name="competitor_id" class="form-select border-danger">
                        <option value="">Rakip Seçin (Yoksa Boş Bırakın)</option>
                        <?php $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($comp->id); ?>"><?php echo e($comp->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#addCompetitorModal" title="Yeni Rakip Ekle">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>

            <div class="col-md-8 mb-3" id="new_opp_loss_wrapper" style="display: none;">
                <label class="form-label text-danger fw-bold"><i
                        class="fa-solid fa-circle-exclamation me-1"></i>Kaybetme Nedeni (*)</label>
                <input type="text" name="loss_reason" id="new_opp_loss_reason" class="form-control border-danger"
                    placeholder="Örn: Fiyatımız %10 yüksek kaldı">
            </div>
            

            <div class="col-12 mb-3">
                <label class="form-label">Detaylar / Kaynak Bilgisi</label>
                <div class="input-group">
                    <textarea name="description" id="new_opp_desc" class="form-control" rows="2"
                        placeholder="Fırsatın kaynağı nedir? Müşterinin mevcut durumu nedir?"></textarea>
                    <button class="btn btn-outline-secondary" type="button" id="btn_new_opp_desc"
                        onclick="toggleVoiceInput('new_opp_desc', 'btn_new_opp_desc')"><i
                            class="fa-solid fa-microphone"></i></button>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                    class="fa-solid fa-save me-2"></i> Fırsatı Kaydet</button>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-bullseye me-2 text-warning"></i>Açık Fırsatlar ve Duyumlar</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterOppDate" class="filter-input bg-white">
            <input type="text" id="filterOppSearch" class="filter-input bg-white" placeholder="Başlık ara...">
            <select id="filterOppStatus" class="form-select filter-input bg-white py-1" style="min-width: 140px;">
                <option value="">Tüm Aşamalar</option>
                <option value="duyum">Duyum</option>
                <option value="teklif">Teklif Verildi</option>
                <option value="gorusme">Görüşme</option>
                <option value="kazanildi">Kazanıldı</option>
                <option value="kaybedildi">Kaybedildi</option>
            </select>
        </div>
    </div>

    <div class="row" id="opportunitiesList">
        <?php $__empty_1 = true; $__currentLoopData = $customer->opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4 mb-4 opp-item" data-date="<?php echo e($opp->created_at->format('Y-m-d')); ?>"
                data-search="<?php echo e(mb_strtolower($opp->title . ' ' . ($opp->user->name ?? '') . ' ' . ($opp->competitor->name ?? ''))); ?>"
                data-status="<?php echo e($opp->stage); ?>">
                <div class="card h-100 shadow-sm opp-card opp-<?php echo e($opp->stage); ?>">
                    <div class="card-body position-relative">

                        <div class="position-absolute top-0 end-0 mt-3 me-3">
                            <form action="<?php echo e(route('opportunities.update-stage', $opp->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <select name="stage"
                                    class="form-select form-select-sm border-0 bg-light fw-bold text-muted shadow-none"
                                    onchange="if(this.value === 'kaybedildi') { 
                                            this.value = '<?php echo e($opp->stage); ?>'; 
                                            var modal = new bootstrap.Modal(document.getElementById('editOppModal<?php echo e($opp->id); ?>')); 
                                            modal.show(); 
                                            document.getElementById('edit_opp_<?php echo e($opp->id); ?>_stage').value = 'kaybedildi';
                                            toggleLossReason('edit_opp_<?php echo e($opp->id); ?>_stage', 'edit_opp_<?php echo e($opp->id); ?>_loss_wrapper');
                                            alert('Lütfen işi kime ve neden kaybettiğimizi detaylandırın.'); 
                                        } else { 
                                            this.form.submit(); 
                                        }"
                                    style="cursor: pointer;">
                                    <option value="duyum" <?php echo e($opp->stage == 'duyum' ? 'selected' : ''); ?>>Duyum
                                    </option>
                                    <option value="teklif" <?php echo e($opp->stage == 'teklif' ? 'selected' : ''); ?>>Teklif
                                        Verildi</option>
                                    <option value="gorusme" <?php echo e($opp->stage == 'gorusme' ? 'selected' : ''); ?>>Görüşme
                                    </option>
                                    <option value="kazanildi" <?php echo e($opp->stage == 'kazanildi' ? 'selected' : ''); ?>>🎉
                                        Kazanıldı</option>
                                    <option value="kaybedildi" <?php echo e($opp->stage == 'kaybedildi' ? 'selected' : ''); ?>>❌
                                        Kaybedildi</option>
                                </select>
                            </form>
                        </div>

                        <h6 class="fw-bold text-dark mb-1 pe-5"><?php echo e($opp->title); ?></h6>
                        <div class="text-muted small mb-3"><i
                                class="fa-solid fa-user-circle me-1"></i><?php echo e($opp->user ? $opp->user->name : 'Sistem'); ?>

                            &bull; <?php echo e($opp->created_at->format('d.m.Y')); ?></div>

                        <?php if($opp->amount): ?>
                            <h4 class="text-success mb-2 fw-bold"><?php echo e(number_format($opp->amount, 2, ',', '.')); ?>

                                <?php echo e($opp->currency); ?></h4>
                        <?php else: ?>
                            <h4 class="text-secondary mb-2 fs-6 fst-italic">Tutar Belirtilmemiş</h4>
                        <?php endif; ?>

                        
                        <div class="mb-3">
                            <?php if($opp->competitor): ?>
                                <span
                                    class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill mb-1">
                                    <i class="fa-solid fa-user-ninja me-1"></i> Rakip: <?php echo e($opp->competitor->name); ?>

                                </span>
                            <?php endif; ?>

                            <?php if($opp->stage == 'kaybedildi' && $opp->loss_reason): ?>
                                <br><small class="text-danger fw-bold"><i
                                        class="fa-solid fa-arrow-turn-down me-1"></i>Neden:
                                    <?php echo e($opp->loss_reason); ?></small>
                            <?php endif; ?>
                        </div>

                        <p class="small text-dark mb-3" style="min-height: 40px;">
                            <?php echo e(Str::limit($opp->description, 100)); ?></p>

                        <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-2">
                            <span class="small fw-semibold text-muted"><i
                                    class="fa-regular fa-calendar-check me-1"></i><?php echo e($opp->expected_close_date ? $opp->expected_close_date->format('d.m.Y') : 'Tarih Yok'); ?></span>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal"
                                    data-bs-target="#historyOppModal<?php echo e($opp->id); ?>" title="Tarihçeyi Gör">
                                    <i class="fa-solid fa-clock-rotate-left text-info"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="modal"
                                    data-bs-target="#editOppModal<?php echo e($opp->id); ?>" title="Düzenle">
                                    <i class="fa-solid fa-pen text-primary"></i>
                                </button>
                                <form action="<?php echo e(route('opportunities.destroy', $opp->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-light border"
                                        onclick="return confirm('Bu fırsatı silmek istediğinize emin misiniz?');">
                                        <i class="fa-solid fa-trash-alt text-danger"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12 empty-message-row">
                <div class="alert alert-light text-center py-5 border border-dashed text-muted">
                    <i class="fa-solid fa-ghost fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0">Bu müşteriye ait kayıtlı bir fırsat veya duyum bulunmuyor.</p>
                </div>
            </div>
        <?php endif; ?>
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
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/opportunities.blade.php ENDPATH**/ ?>