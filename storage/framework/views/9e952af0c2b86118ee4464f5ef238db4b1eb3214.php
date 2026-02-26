<div class="tab-pane fade" id="tests" role="tabpanel">
    <h5><i class="fa-solid fa-upload me-2"></i>Hızlı Test Sonucu Yükle</h5>
    <form action="<?php echo e(route('customers.test-results.store', $customer)); ?>" method="POST" autocomplete="off" class="quick-add-form" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-md-3 mb-3"><label for="test_name" class="form-label">Test Adı (*)</label><input type="text" name="test_name" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label class="form-label">İlgili Ürün</label><input type="text" name="product_name" list="productList" class="form-control" placeholder="Listeden seçin veya yeni yazın..."></div>
            <div class="col-md-3 mb-3"><label for="test_date" class="form-label">Test Tarihi (*)</label><input type="date" name="test_date" class="form-control" required></div>
            <div class="col-md-3 mb-3"><label for="test_files" class="form-label">Dosya(lar)</label><input type="file" name="test_files[]" class="form-control" multiple></div>
        </div>
        <div class="mb-3">
            <label for="summary" class="form-label">Özet</label>
            <div class="input-group">
                <textarea name="summary" id="new_test_summary" class="form-control" rows="2"></textarea>
                <button class="btn btn-outline-secondary" type="button" id="btn_new_test_sum" onclick="toggleVoiceInput('new_test_summary', 'btn_new_test_sum')"><i class="fa-solid fa-microphone"></i></button>
            </div>
        </div>
        <button type="submit" class="btn btn-animated-gradient rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i>Test Sonucunu Ekle</button>
    </form>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="fa-solid fa-list me-2"></i>Kayıtlı Test Sonuçları</h5>
        <div class="filter-bar p-2 rounded d-flex gap-2 align-items-center">
            <i class="fa-solid fa-filter text-muted mx-1"></i>
            <input type="date" id="filterTestDate" class="filter-input bg-white">
            <input type="text" id="filterTestSearch" class="filter-input bg-white" placeholder="Test Adı, Ürün veya Özet ara...">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-striped table-hover" id="testsTable">
            <thead>
                <tr>
                    <th>Test Adı</th>
                    <th>Bağlı Ürün</th>
                    <th>Tarih</th>
                    <th>Özet</th>
                    <th>Dosyalar</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $customer->testResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="test-item" data-date="<?php echo e(\Carbon\Carbon::parse($result->test_date)->format('Y-m-d')); ?>" data-search="<?php echo e(mb_strtolower($result->test_name . ' ' . $result->summary . ' ' . ($result->product->name ?? ''))); ?>">
                        <td><strong><?php echo e($result->test_name); ?></strong></td>
                        <td>
                            <?php if($result->product): ?> <span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><?php echo e($result->product->name); ?></span>
                            <?php else: ?> <span class="text-muted small">-</span> <?php endif; ?>
                        </td>
                        <td><?php echo e(\Carbon\Carbon::parse($result->test_date)->format('d.m.Y')); ?></td>
                        <td><?php echo e($result->summary ?? '-'); ?></td>
                        <td>
                            <?php $__currentLoopData = $result->getMedia('test_reports'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="file-list-item d-inline-flex me-2 mb-2" style="width:auto; padding: 0.2rem 0.5rem;">
                                    <span><i class="fa-solid fa-file-pdf me-2"></i><?php echo e(Str::limit($media->file_name, 15)); ?></span>
                                    <a href="<?php echo e($media->getUrl()); ?>" target="_blank" class="btn btn-sm btn-link text-primary ms-2 p-0"><i class="fa-solid fa-eye"></i></a>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editTestModal<?php echo e($result->id); ?>" title="Düzenle"><i class="fa-solid fa-pen"></i></button>
                                <form action="<?php echo e(route('test-results.destroy', $result->id ?? 0)); ?>" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger p-0 px-2"><i class="fa-solid fa-trash-alt"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?> 
                    <tr class="empty-message-row">
                        <td colspan="6" class="text-center text-muted py-4">Bu müşteriye ait test sonucu bulunamadı.</td>
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
</div><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/tabs/tests.blade.php ENDPATH**/ ?>