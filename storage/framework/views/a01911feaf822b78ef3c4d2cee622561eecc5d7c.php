<div class="tab-pane fade" id="products" role="tabpanel">
    <h5><i class="fa-solid fa-box-open me-2 text-primary"></i>Yeni Ürün / Rakip Analizi Ekle</h5>

    <form action="<?php echo e(route('customers.products.store', $customer)); ?>" method="POST" class="quick-add-form mb-5">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tedarikçi Tipi (*)</label>
                <div class="d-flex gap-3 mt-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supplier_type" id="sup_koksan"
                            value="koksan" checked onchange="toggleCompetitorFields('sup_koksan', 'new_prod_')">
                        <label class="form-check-label fw-bold text-primary" for="sup_koksan"><i
                                class="fa-solid fa-industry me-1"></i> KÖKSAN Ürünü</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="supplier_type" id="sup_competitor"
                            value="competitor" onchange="toggleCompetitorFields('sup_competitor', 'new_prod_')">
                        <label class="form-check-label fw-bold text-danger" for="sup_competitor"><i
                                class="fa-solid fa-user-ninja me-1"></i> Rakip Ürün</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Ürün Adı (*)</label>
                <input type="text" name="name" class="form-control" required placeholder="Örn: 19L Damacana">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control" placeholder="Örn: Su">
            </div>
        </div>

        
        <div id="new_prod_competitor_panel" class="p-3 mb-3 rounded shadow-sm"
            style="display: none; background: rgba(220, 53, 69, 0.05); border: 1px solid rgba(220, 53, 69, 0.2); backdrop-filter: blur(5px);">
            <h6 class="text-danger fw-bold mb-3"><i class="fa-solid fa-magnifying-glass-chart me-2"></i>Rakip Analiz
                Detayları</h6>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Rakip Firma (*)</label>
                    <select name="competitor_id" id="new_prod_competitor_id" class="form-select border-danger">
                        <option value="">Rakip Seçin...</option>
                        <?php $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($comp->id); ?>"><?php echo e($comp->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#addCompetitorModal" title="Listede yoksa yeni ekle">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Müşterideki İlgili Kişi</label>
                    <select name="customer_contact_id" class="form-select border-danger">
                        <option value="">İlgili Seçin...</option>
                        <?php $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($contact->id); ?>"><?php echo e($contact->name); ?> (<?php echo e($contact->title ?? '-'); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Ürün Performansı / Şikayetler</label>
                    <textarea name="performance_notes" class="form-control border-danger" rows="1"
                        placeholder="Rakibin ürününde patlama var mı? vb."></textarea>
                </div>
            </div>

            <hr class="border-danger opacity-25">

            <label class="form-label fw-bold text-dark"><i class="fa-solid fa-list-check me-1"></i> Teknik Özellikler ve
                Reçete</label>
            <div id="new_prod_specs_container">
                <div class="row mb-2 spec-row">
                    <div class="col-5">
                        <input type="text" name="spec_keys[]" class="form-control form-control-sm"
                            placeholder="Özellik Adı (Örn: Kalınlık, Mikron, Reçete)">
                    </div>
                    <div class="col-6">
                        <input type="text" name="spec_values[]" class="form-control form-control-sm"
                            placeholder="Değer (Örn: 140, %20 PET vb.)">
                    </div>
                    <div class="col-1">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100"
                            onclick="removeSpecRow(this)"><i class="fa-solid fa-times"></i></button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light border-danger text-danger mt-1"
                onclick="addSpecRow('new_prod_specs_container')">
                <i class="fa-solid fa-plus me-1"></i> Yeni Özellik Ekle
            </button>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Yıllık Tahmini Hacim</label>
                <input type="text" name="annual_volume" class="form-control" placeholder="Örn: 5 Milyon">
            </div>
            <div class="col-md-8 mb-3">
                <label class="form-label">Genel Notlar</label>
                <input type="text" name="notes" class="form-control" placeholder="Ekstra detaylar...">
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i
                    class="fa-solid fa-save me-2"></i> Ürünü Kaydet</button>
        </div>
    </form>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2 text-primary"></i>Müşterinin Aldığı Ürünler & Rakip
            Analizleri</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="text" id="filterProdSearch" class="filter-input bg-white"
                placeholder="Ürün veya kategori ara...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr>
                    <th>Tedarikçi</th>
                    <th>Ürün Adı</th>
                    <th>Kategori / Hacim</th>
                    <th>Rakip Firma / İlgili Kişi</th>
                    <th>Teknik Özellikler</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody id="productsList">
                <?php $__empty_1 = true; $__currentLoopData = $customer->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="product-item"
                        data-search="<?php echo e(mb_strtolower($prod->name . ' ' . $prod->category . ' ' . ($prod->competitor->name ?? ''))); ?>">
                        <td>
                            <?php if($prod->supplier_type == 'koksan'): ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><i
                                        class="fa-solid fa-industry me-1"></i> KÖKSAN</span>
                            <?php else: ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger"><i
                                        class="fa-solid fa-user-ninja me-1"></i> Rakip</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-dark"><?php echo e($prod->name); ?></td>
                        <td>
                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary border mb-1"><?php echo e($prod->category ?? 'Kategori Yok'); ?></span><br>
                            <small class="text-muted"><i
                                    class="fa-solid fa-cubes me-1"></i><?php echo e($prod->annual_volume ?? '-'); ?></small>
                        </td>
                        <td>
                            <?php if($prod->supplier_type == 'competitor'): ?>
                                <strong class="text-danger"><?php echo e($prod->competitor->name ?? 'Bilinmiyor'); ?></strong><br>
                                <small class="text-muted"><i
                                        class="fa-solid fa-user me-1"></i><?php echo e($prod->contact->name ?? 'İlgili Yok'); ?></small>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($prod->technical_specs && count($prod->technical_specs) > 0): ?>
                                <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="popover"
                                    data-bs-trigger="focus" title="Teknik Özellikler" data-bs-html="true"
                                    data-bs-content="
                                    <table class='table table-sm mb-0'>
                                        <?php $__currentLoopData = $prod->technical_specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<tr><th><?php echo e($key); ?></th><td><?php echo e($val); ?></td></tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </table>
                                "><i
                                        class="fa-solid fa-microscope text-info me-1"></i> Gör</button>
                            <?php else: ?>
                                <span class="text-muted small">Yok</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editProductModal<?php echo e($prod->id); ?>" title="Düzenle"><i
                                        class="fa-solid fa-pen"></i></button>
                                <form action="<?php echo e(route('customer-products.destroy', $prod->id)); ?>" method="POST"
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
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-solid fa-box-open fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Müşteriye ait kaydedilmiş bir ürün bulunamadı.</p>
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
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/products.blade.php ENDPATH**/ ?>