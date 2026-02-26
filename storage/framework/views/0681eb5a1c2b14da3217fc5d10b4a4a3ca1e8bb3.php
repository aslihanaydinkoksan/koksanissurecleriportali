
<?php $__currentLoopData = $customer->visits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editVisitModal<?php echo e($visit->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-edit me-2"></i>Ziyaret Formunu
                        Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customers.visits.update', [$customer->id, $visit->id])); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Ziyaret
                                    Tarihi</label><input type="datetime-local" name="visit_date" class="form-control"
                                    value="<?php echo e($visit->visit_date ? $visit->visit_date->format('Y-m-d\TH:i') : ''); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Ziyaret Sebebi</label>
                                <select name="visit_reason" class="form-select">
                                    <option value="Şikayet" <?php echo e($visit->visit_reason == 'Şikayet' ? 'selected' : ''); ?>>
                                        Şikayet</option>
                                    <option value="Periyodik Ziyaret"
                                        <?php echo e($visit->visit_reason == 'Periyodik Ziyaret' ? 'selected' : ''); ?>>Periyodik
                                        Ziyaret</option>
                                    <option value="Ürün Denemesi"
                                        <?php echo e($visit->visit_reason == 'Ürün Denemesi' ? 'selected' : ''); ?>>Ürün Denemesi
                                    </option>
                                    <option value="Diğer" <?php echo e($visit->visit_reason == 'Diğer' ? 'selected' : ''); ?>>Diğer
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Barkod</label><input
                                    type="text" name="barcode" class="form-control" value="<?php echo e($visit->barcode); ?>">
                            </div>
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Lot No</label><input
                                    type="text" name="lot_no" class="form-control" value="<?php echo e($visit->lot_no); ?>">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Tespitler</label>
                                <div class="input-group">
                                    <textarea name="findings" id="edit_findings_<?php echo e($visit->id); ?>" class="form-control" rows="3"><?php echo e($visit->findings); ?></textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="toggleVoiceInput('edit_findings_<?php echo e($visit->id); ?>', this)"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Sonuç & Karar</label>
                                <div class="input-group">
                                    <textarea name="result" id="edit_result_<?php echo e($visit->id); ?>" class="form-control" rows="3"><?php echo e($visit->result); ?></textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="toggleVoiceInput('edit_result_<?php echo e($visit->id); ?>', this)"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sample): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="feedbackModal<?php echo e($sample->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-comment-dots me-2"></i>Geri
                        Bildirim Ekle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customer-samples.update-status', $sample->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <input type="hidden" name="status" value="<?php echo e($sample->status); ?>">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold text-muted small">Numune Konusu</label>
                            <p class="mb-2 fw-semibold"><?php echo e($sample->subject); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small">Müşteri Geri Bildirimi</label>
                            <div class="input-group">
                                <textarea name="feedback" id="feedback_input_<?php echo e($sample->id); ?>" class="form-control" rows="4"><?php echo e($sample->feedback); ?></textarea>
                                <button class="btn btn-outline-secondary" type="button"
                                    id="btn_feedback_<?php echo e($sample->id); ?>"
                                    onclick="toggleVoiceInput('feedback_input_<?php echo e($sample->id); ?>', 'btn_feedback_<?php echo e($sample->id); ?>')"><i
                                        class="fa-solid fa-microphone"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Kapat</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editSampleModal<?php echo e($sample->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-flask me-2"></i>Numuneyi
                        Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customer-samples.update', $sample->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Konu (*)</label><input
                                    type="text" name="subject" class="form-control"
                                    value="<?php echo e($sample->subject); ?>" required></div>
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">İlgili
                                    Ürün/Proje</label><input type="text" name="product_name" list="productList"
                                    class="form-control" value="<?php echo e($sample->product->name ?? ''); ?>"></div>
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Miktar</label><input
                                    type="number" name="quantity" class="form-control"
                                    value="<?php echo e($sample->quantity); ?>" step="0.01" required></div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Birim</label>
                                <select name="unit" class="form-select" required>
                                    <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($birim->ad); ?>"
                                            <?php echo e($sample->unit == $birim->ad ? 'selected' : ''); ?>><?php echo e($birim->ad); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Gönderim
                                    Tarihi</label><input type="date" name="sent_date" class="form-control"
                                    value="<?php echo e($sample->sent_date ? $sample->sent_date->format('Y-m-d') : ''); ?>"></div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Kargo
                                    Firması</label><input type="text" name="cargo_company" class="form-control"
                                    value="<?php echo e($sample->cargo_company); ?>"></div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Takip No</label><input
                                    type="text" name="tracking_number" class="form-control"
                                    value="<?php echo e($sample->tracking_number); ?>"></div>
                            <div class="col-12 mb-3"><label class="form-label fw-bold small">Ekstra Ürün
                                    Bilgisi</label><input type="text" name="product_info" class="form-control"
                                    value="<?php echo e($sample->product_info); ?>"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editReturnModal<?php echo e($return->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-rotate-left me-2"></i>İadeyi
                        Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customer-returns.update', $return->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3"><label class="form-label fw-bold small">Ürün Adı
                                    (*)</label><input type="text" name="product_name" list="productList"
                                    class="form-control" value="<?php echo e($return->product_name); ?>" required></div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-primary">Gönderilen Miktar & Birim
                                    (*)</label>
                                <div class="input-group">
                                    <input type="number" name="shipped_quantity" class="form-control border-primary"
                                        value="<?php echo e($return->shipped_quantity); ?>" step="0.01" required>
                                    <select name="shipped_unit" class="form-select border-primary" required>
                                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($birim->ad); ?>"
                                                <?php echo e($return->shipped_unit == $birim->ad ? 'selected' : ''); ?>>
                                                <?php echo e($birim->ad); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small text-danger">İade Gelen Miktar & Birim
                                    (*)</label>
                                <div class="input-group">
                                    <input type="number" name="quantity" class="form-control border-danger"
                                        value="<?php echo e($return->quantity); ?>" step="0.01" required>
                                    <select name="unit" class="form-select border-danger" required>
                                        <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($birim->ad); ?>"
                                                <?php echo e($return->unit == $birim->ad ? 'selected' : ''); ?>>
                                                <?php echo e($birim->ad); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Bağlı Şikayet</label>
                                <select name="complaint_id" class="form-select">
                                    <option value="">Seçilmedi</option>
                                    <?php $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($c->id); ?>"
                                            <?php echo e($return->complaint_id == $c->id ? 'selected' : ''); ?>>
                                            #<?php echo e($c->id); ?> - <?php echo e(Str::limit($c->title, 20)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Bağlı Numune</label>
                                <select name="customer_sample_id" class="form-select">
                                    <option value="">Seçilmedi</option>
                                    <?php $__currentLoopData = $customer->samples; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->id); ?>"
                                            <?php echo e($return->customer_sample_id == $s->id ? 'selected' : ''); ?>>
                                            #<?php echo e($s->id); ?> - <?php echo e(Str::limit($s->subject, 20)); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Tarih (*)</label><input
                                    type="date" name="return_date" class="form-control"
                                    value="<?php echo e($return->return_date->format('Y-m-d')); ?>" required></div>
                            <div class="col-12 mb-3"><label class="form-label fw-bold small">İade Nedeni (*)</label>
                                <textarea name="reason" class="form-control" rows="2" required><?php echo e($return->reason); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->complaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editComplaintModal<?php echo e($complaint->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i
                            class="fa-solid fa-exclamation-triangle me-2"></i>Şikayeti Düzenle</h5><button
                        type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('complaints.update', $complaint->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3"><label class="form-label fw-bold small">Şikayet Başlığı
                                    (*)
                                </label><input type="text" name="title" class="form-control"
                                    value="<?php echo e($complaint->title); ?>" required></div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Durum (*)</label>
                                <select name="status" class="form-select" required>
                                    <option value="open" <?php echo e($complaint->status == 'open' ? 'selected' : ''); ?>>Açık
                                    </option>
                                    <option value="in_progress"
                                        <?php echo e($complaint->status == 'in_progress' ? 'selected' : ''); ?>>İşlemde</option>
                                    <option value="resolved" <?php echo e($complaint->status == 'resolved' ? 'selected' : ''); ?>>
                                        Çözüldü</option>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Detaylı Açıklama (*)</label>
                                <div class="input-group">
                                    <textarea name="description" id="comp_desc_edit_<?php echo e($complaint->id); ?>" class="form-control" rows="4" required><?php echo e($complaint->description); ?></textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btn_comp_desc_<?php echo e($complaint->id); ?>"
                                        onclick="toggleVoiceInput('comp_desc_edit_<?php echo e($complaint->id); ?>', 'btn_comp_desc_<?php echo e($complaint->id); ?>')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->testResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editTestModal<?php echo e($result->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-vial me-2"></i>Test Sonucunu
                        Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('test-results.update', $result->id)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label fw-bold small">Test Adı (*)</label><input
                                type="text" name="test_name" class="form-control"
                                value="<?php echo e($result->test_name); ?>" required></div>
                        <div class="mb-3"><label class="form-label fw-bold small">İlgili Ürün</label><input
                                type="text" name="product_name" list="productList" class="form-control"
                                value="<?php echo e($result->product->name ?? ''); ?>"></div>
                        <div class="mb-3"><label class="form-label fw-bold small">Test Tarihi (*)</label><input
                                type="date" name="test_date" class="form-control"
                                value="<?php echo e(\Carbon\Carbon::parse($result->test_date)->format('Y-m-d')); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Özet</label>
                            <div class="input-group">
                                <textarea name="summary" id="test_sum_edit_<?php echo e($result->id); ?>" class="form-control" rows="3"><?php echo e($result->summary); ?></textarea>
                                <button class="btn btn-outline-secondary" type="button"
                                    id="btn_test_sum_<?php echo e($result->id); ?>"
                                    onclick="toggleVoiceInput('test_sum_edit_<?php echo e($result->id); ?>', 'btn_test_sum_<?php echo e($result->id); ?>')"><i
                                        class="fa-solid fa-microphone"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->machines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $machine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editMachineModal<?php echo e($machine->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-industry me-2"></i>Makineyi
                        Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('machines.update', $machine->id)); ?>" method="POST"><?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Kime Ait? (Kurulum Türü) (*)</label>
                                <select name="ownership_type" class="form-select" required>
                                    <option value="koksan"
                                        <?php echo e($machine->ownership_type == 'koksan' ? 'selected' : ''); ?>>KÖKSAN'ın Kurduğu
                                        Makine</option>
                                    <option value="customer"
                                        <?php echo e($machine->ownership_type == 'customer' ? 'selected' : ''); ?>>Müşterinin Kendi
                                        Makinesi</option>
                                    <option value="competitor"
                                        <?php echo e($machine->ownership_type == 'competitor' ? 'selected' : ''); ?>>Rakibin
                                        Kurduğu Makine</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Marka</label>
                                <input type="text" name="brand" class="form-control"
                                    value="<?php echo e($machine->brand); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Model (*)</label>
                                <input type="text" name="model" class="form-control"
                                    value="<?php echo e($machine->model); ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Çalıştığı Ürün</label>
                                <div class="input-group">
                                    <input type="text" name="product_name" list="productList"
                                        class="form-control border-end-0"
                                        placeholder="Listeden seçin veya yeni yazın..." autocomplete="off"
                                        value="<?php echo e($machine->product->name ?? ''); ?>">
                                    <span class="input-group-text bg-white border-start-0 text-muted"><i
                                            class="fa-solid fa-chevron-down" style="font-size: 0.8em;"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small text-muted">Seri
                                    No</label><input type="text" name="serial_number" class="form-control"
                                    value="<?php echo e($machine->serial_number); ?>"></div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small text-muted">Kurulum
                                    Tarihi</label><input type="date" name="installation_date" class="form-control"
                                    value="<?php echo e($machine->installation_date ? \Carbon\Carbon::parse($machine->installation_date)->format('Y-m-d') : ''); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editProductModal<?php echo e($prod->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-box-open me-2"></i>Ürünü /
                        Analizi Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customer-products.update', $prod->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small text-muted">Tedarikçi Tipi (*)</label>
                                <div class="d-flex gap-3 mt-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="supplier_type"
                                            id="edit_sup_koksan_<?php echo e($prod->id); ?>" value="koksan"
                                            <?php echo e($prod->supplier_type == 'koksan' ? 'checked' : ''); ?>

                                            onchange="toggleCompetitorFields('edit_sup_koksan_<?php echo e($prod->id); ?>', 'edit_prod_<?php echo e($prod->id); ?>_')">
                                        <label class="form-check-label text-primary small fw-bold"
                                            for="edit_sup_koksan_<?php echo e($prod->id); ?>"><i
                                                class="fa-solid fa-industry me-1"></i> KÖKSAN</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="supplier_type"
                                            id="edit_sup_competitor_<?php echo e($prod->id); ?>" value="competitor"
                                            <?php echo e($prod->supplier_type == 'competitor' ? 'checked' : ''); ?>

                                            onchange="toggleCompetitorFields('edit_sup_competitor_<?php echo e($prod->id); ?>', 'edit_prod_<?php echo e($prod->id); ?>_')">
                                        <label class="form-check-label text-danger small fw-bold"
                                            for="edit_sup_competitor_<?php echo e($prod->id); ?>"><i
                                                class="fa-solid fa-user-ninja me-1"></i> Rakip</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small text-muted">Ürün Adı
                                    (*)</label><input type="text" name="name" class="form-control"
                                    value="<?php echo e($prod->name); ?>" required></div>
                            <div class="col-md-4 mb-3"><label
                                    class="form-label fw-bold small text-muted">Kategori</label><input type="text"
                                    name="category" class="form-control" value="<?php echo e($prod->category); ?>"></div>
                        </div>

                        
                        <div id="edit_prod_<?php echo e($prod->id); ?>_competitor_panel" class="p-3 mb-3 rounded shadow-sm"
                            style="display: <?php echo e($prod->supplier_type == 'competitor' ? 'block' : 'none'); ?>; background: rgba(220, 53, 69, 0.05); border: 1px solid rgba(220, 53, 69, 0.2);">
                            <h6 class="text-danger fw-bold mb-3 small"><i
                                    class="fa-solid fa-magnifying-glass-chart me-2"></i>Rakip Analiz Detayları</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Rakip Firma (*)</label>

                                    
                                    <div class="input-group">
                                        <select name="competitor_id"
                                            id="edit_prod_<?php echo e($prod->id); ?>_competitor_id"
                                            class="form-select border-danger"
                                            <?php echo e($prod->supplier_type == 'competitor' ? 'required' : ''); ?>>
                                            <option value="">Rakip Seçin...</option>
                                            <?php $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($comp->id); ?>"
                                                    <?php echo e($prod->competitor_id == $comp->id ? 'selected' : ''); ?>>
                                                    <?php echo e($comp->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                            data-bs-target="#addCompetitorModal" title="Listede yoksa yeni ekle">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    

                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Müşterideki İlgili</label>
                                    <select name="customer_contact_id" class="form-select border-danger">
                                        <option value="">İlgili Seçin...</option>
                                        <?php $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($contact->id); ?>"
                                                <?php echo e($prod->customer_contact_id == $contact->id ? 'selected' : ''); ?>>
                                                <?php echo e($contact->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Performans / Şikayet</label>
                                    <textarea name="performance_notes" class="form-control border-danger" rows="1"><?php echo e($prod->performance_notes); ?></textarea>
                                </div>
                            </div>
                            <hr class="border-danger opacity-25">
                            <label class="form-label fw-bold text-dark small"><i
                                    class="fa-solid fa-list-check me-1"></i> Teknik Özellikler ve Reçete</label>
                            <div id="edit_prod_<?php echo e($prod->id); ?>_specs_container">
                                <?php if($prod->technical_specs && count($prod->technical_specs) > 0): ?>
                                    <?php $__currentLoopData = $prod->technical_specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row mb-2 spec-row">
                                            <div class="col-5"><input type="text" name="spec_keys[]"
                                                    class="form-control form-control-sm border-secondary"
                                                    value="<?php echo e($key); ?>" placeholder="Özellik Adı"></div>
                                            <div class="col-6"><input type="text" name="spec_values[]"
                                                    class="form-control form-control-sm border-secondary"
                                                    value="<?php echo e($val); ?>" placeholder="Değer"></div>
                                            <div class="col-1"><button type="button"
                                                    class="btn btn-sm btn-outline-danger w-100"
                                                    onclick="removeSpecRow(this)"><i
                                                        class="fa-solid fa-times"></i></button></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="row mb-2 spec-row">
                                        <div class="col-5"><input type="text" name="spec_keys[]"
                                                class="form-control form-control-sm border-secondary"
                                                placeholder="Özellik Adı (Örn: Mikron)"></div>
                                        <div class="col-6"><input type="text" name="spec_values[]"
                                                class="form-control form-control-sm border-secondary"
                                                placeholder="Değer (Örn: 140)"></div>
                                        <div class="col-1"><button type="button"
                                                class="btn btn-sm btn-outline-danger w-100"
                                                onclick="removeSpecRow(this)"><i
                                                    class="fa-solid fa-times"></i></button></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-sm btn-light border-danger text-danger mt-1"
                                onclick="addSpecRow('edit_prod_<?php echo e($prod->id); ?>_specs_container')">
                                <i class="fa-solid fa-plus me-1"></i> Yeni Özellik Ekle
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small text-muted">Yıllık
                                    Hacim</label><input type="text" name="annual_volume" class="form-control"
                                    value="<?php echo e($prod->annual_volume); ?>"></div>
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold small text-muted">Genel Notlar</label>
                                <div class="input-group">
                                    <textarea name="notes" id="prod_notes_edit_<?php echo e($prod->id); ?>" class="form-control" rows="2"><?php echo e($prod->notes); ?></textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btn_prod_notes_<?php echo e($prod->id); ?>"
                                        onclick="toggleVoiceInput('prod_notes_edit_<?php echo e($prod->id); ?>', 'btn_prod_notes_<?php echo e($prod->id); ?>')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editOppModal<?php echo e($opp->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen text-primary me-2"></i>Fırsatı
                        Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('opportunities.update', $opp->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="modal-body bg-light pt-0">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Başlık (*)</label>
                                <input type="text" name="title" class="form-control"
                                    value="<?php echo e($opp->title); ?>" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold small">Tutar</label>
                                <input type="number" name="amount" class="form-control" step="0.01"
                                    value="<?php echo e($opp->amount); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold small">Para Birimi</label>
                                <select name="currency" class="form-select">
                                    <option value="TRY" <?php echo e($opp->currency == 'TRY' ? 'selected' : ''); ?>>₺ TRY
                                    </option>
                                    <option value="USD" <?php echo e($opp->currency == 'USD' ? 'selected' : ''); ?>>$ USD
                                    </option>
                                    <option value="EUR" <?php echo e($opp->currency == 'EUR' ? 'selected' : ''); ?>>€ EUR
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Aşama (*)</label>
                                <select name="stage" id="edit_opp_<?php echo e($opp->id); ?>_stage" class="form-select"
                                    required
                                    onchange="toggleLossReason(this.id, 'edit_opp_<?php echo e($opp->id); ?>_loss_wrapper')">
                                    <option value="duyum" <?php echo e($opp->stage == 'duyum' ? 'selected' : ''); ?>>Duyum
                                    </option>
                                    <option value="teklif" <?php echo e($opp->stage == 'teklif' ? 'selected' : ''); ?>>Teklif
                                        Verildi</option>
                                    <option value="gorusme" <?php echo e($opp->stage == 'gorusme' ? 'selected' : ''); ?>>Görüşme
                                    </option>
                                    <option value="kazanildi" <?php echo e($opp->stage == 'kazanildi' ? 'selected' : ''); ?>>
                                        Kazanıldı</option>
                                    <option value="kaybedildi" <?php echo e($opp->stage == 'kaybedildi' ? 'selected' : ''); ?>>
                                        Kaybedildi</option>
                                </select>
                            </div>

                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label text-danger fw-bold small">Rekabet Edilen Rakip</label>
                                <div class="input-group">
                                    <select name="competitor_id" class="form-select border-danger">
                                        <option value="">Rakip Seçin</option>
                                        <?php $__currentLoopData = $competitors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($comp->id); ?>"
                                                <?php echo e($opp->competitor_id == $comp->id ? 'selected' : ''); ?>>
                                                <?php echo e($comp->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#addCompetitorModal"><i class="fa-solid fa-plus"></i></button>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3" id="edit_opp_<?php echo e($opp->id); ?>_loss_wrapper"
                                style="display: <?php echo e($opp->stage == 'kaybedildi' ? 'block' : 'none'); ?>;">
                                <label class="form-label text-danger fw-bold small">Kaybetme Nedeni</label>
                                <input type="text" name="loss_reason"
                                    id="edit_opp_<?php echo e($opp->id); ?>_loss_reason" class="form-control border-danger"
                                    value="<?php echo e($opp->loss_reason); ?>">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Beklenen Karar</label>
                                <input type="date" name="expected_close_date" class="form-control"
                                    value="<?php echo e($opp->expected_close_date ? \Carbon\Carbon::parse($opp->expected_close_date)->format('Y-m-d') : ''); ?>">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Açıklama</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo e($opp->description); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">İptal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Güncelle</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->opportunities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="historyOppModal<?php echo e($opp->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-clock-rotate-left text-info me-2"></i>Fırsat Tarihçesi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light pt-0">
                    <p class="small text-muted fw-bold mb-4 border-bottom pb-2"><?php echo e($opp->title); ?></p>

                    <div class="timeline ps-2 border-start border-2 border-info ms-3">
                        <?php $__empty_1 = true; $__currentLoopData = $opp->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="position-relative mb-4 ps-4">
                                <span
                                    class="position-absolute top-0 start-0 translate-middle p-2 bg-info border border-light rounded-circle"
                                    style="margin-left: -1px;"></span>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <strong class="text-dark">
                                                <?php if($history->old_stage): ?>
                                                    <span
                                                        class="text-muted text-decoration-line-through"><?php echo e(ucfirst($history->old_stage)); ?></span>
                                                    <i class="fa-solid fa-arrow-right mx-1 text-muted small"></i>
                                                <?php endif; ?>
                                                <span class="text-primary"><?php echo e(ucfirst($history->new_stage)); ?></span>
                                            </strong>
                                            <small
                                                class="text-muted"><?php echo e($history->created_at->format('d.m.Y H:i')); ?></small>
                                        </div>
                                        <div class="small text-muted mb-0">
                                            <i
                                                class="fa-solid fa-user-circle me-1"></i><?php echo e($history->user->name ?? 'Sistem'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted small">Henüz bir tarihçe kaydı bulunmuyor.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $registeredContactNames = $customer->contacts->pluck('name')->toArray();
        $activityContacts = is_array($activity->contact_persons) ? $activity->contact_persons : [];
        $otherContactsArr = array_diff($activityContacts, $registeredContactNames);
        $otherContactsStr = implode(', ', $otherContactsArr);
    ?>
    <div class="modal fade" id="editActivityModal<?php echo e($activity->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-primary"><i class="fa-solid fa-history me-2"></i>İşlemi
                        Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('customer-activities.update', $activity->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">İşlem Tipi</label>
                            <select name="type" class="form-select">
                                <option value="phone" <?php echo e($activity->type == 'phone' ? 'selected' : ''); ?>>📞 Telefon
                                    Görüşmesi</option>
                                <option value="meeting" <?php echo e($activity->type == 'meeting' ? 'selected' : ''); ?>>🤝 Yüz
                                    Yüze Toplantı</option>
                                <option value="email" <?php echo e($activity->type == 'email' ? 'selected' : ''); ?>>✉️ E-Posta
                                </option>
                                <option value="visit" <?php echo e($activity->type == 'visit' ? 'selected' : ''); ?>>🏢 Müşteri
                                    Ziyareti</option>
                                <option value="note" <?php echo e($activity->type == 'note' ? 'selected' : ''); ?>>📝 Genel Not
                                </option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Görüşülen Kişiler</label>
                            <div class="dropdown mb-2">
                                <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start bg-white"
                                    type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside">Müşteri
                                    yetkililerini seçin...</button>
                                <ul class="dropdown-menu w-100 p-2 shadow-sm"
                                    style="max-height: 200px; overflow-y: auto;">
                                    <?php $__empty_1 = true; $__currentLoopData = $customer->contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <li>
                                            <div class="form-check m-1">
                                                <input class="form-check-input" type="checkbox"
                                                    name="contact_persons[]" value="<?php echo e($contact->name); ?>"
                                                    id="edit_<?php echo e($activity->id); ?>_contact_<?php echo e($contact->id); ?>"
                                                    style="cursor: pointer;"
                                                    <?php echo e(in_array($contact->name, $activityContacts) ? 'checked' : ''); ?>>
                                                <label class="form-check-label"
                                                    for="edit_<?php echo e($activity->id); ?>_contact_<?php echo e($contact->id); ?>"
                                                    style="cursor: pointer;"><?php echo e($contact->name); ?> <small
                                                        class="text-muted">(<?php echo e($contact->title ?? 'Ünvan Yok'); ?>)</small></label>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <li class="text-muted small text-center p-2">Kayıtlı kişi bulunamadı.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <input type="text" name="other_contact_persons" class="form-control form-control-sm"
                                value="<?php echo e($otherContactsStr); ?>"
                                placeholder="Listede olmayanlar için yazın (virgülle ayırın)...">
                        </div>
                        <div class="mb-3"><label class="form-label fw-bold small text-muted">Tarih &
                                Saat</label><input type="datetime-local" name="activity_date" class="form-control"
                                value="<?php echo e(\Carbon\Carbon::parse($activity->activity_date)->format('Y-m-d\TH:i')); ?>"
                                required></div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Detaylar</label>
                            <div class="input-group">
                                <textarea name="description" id="act_desc_edit_<?php echo e($activity->id); ?>" class="form-control" rows="4" required><?php echo e($activity->description); ?></textarea>
                                <button class="btn btn-outline-secondary" type="button"
                                    id="btn_act_desc_<?php echo e($activity->id); ?>"
                                    onclick="toggleVoiceInput('act_desc_edit_<?php echo e($activity->id); ?>', 'btn_act_desc_<?php echo e($activity->id); ?>')"><i
                                        class="fa-solid fa-microphone"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->vehicleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editLogisticsModal<?php echo e($assignment->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-info"><i class="fa-solid fa-truck-fast me-2"></i>Görevi
                        Düzenle</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('service.assignments.update', $assignment->id)); ?>" method="POST"><?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="type" value="logistics">
                    <input type="hidden" name="customer_id" value="<?php echo e($customer->id); ?>">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3"><label class="form-label fw-bold small">Görev
                                    Başlığı</label><input type="text" name="title" class="form-control"
                                    value="<?php echo e($assignment->title); ?>" required></div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Durum</label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?php echo e($assignment->status == 'pending' ? 'selected' : ''); ?>>
                                        Beklemede</option>
                                    <option value="on_road" <?php echo e($assignment->status == 'on_road' ? 'selected' : ''); ?>>
                                        Yolda</option>
                                    <option value="completed"
                                        <?php echo e($assignment->status == 'completed' ? 'selected' : ''); ?>>Tamamlandı</option>
                                    <option value="cancelled"
                                        <?php echo e($assignment->status == 'cancelled' ? 'selected' : ''); ?>>İptal</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Araç</label>
                                <select name="vehicle_id" class="form-select">
                                    <option value="">Araçsız</option>
                                    <?php $__currentLoopData = \App\Models\Vehicle::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($v->id); ?>"
                                            <?php echo e($assignment->vehicle_id == $v->id ? 'selected' : ''); ?>>
                                            <?php echo e($v->plate_number); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Tarih</label><input
                                    type="datetime-local" name="start_time" class="form-control"
                                    value="<?php echo e($assignment->start_time ? $assignment->start_time->format('Y-m-d\TH:i') : ''); ?>"
                                    required></div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Sorumlu</label>
                                <select name="user_id" class="form-select">
                                    <?php $__currentLoopData = \App\Models\User::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($u->id); ?>"
                                            <?php echo e($assignment->responsible_id == $u->id ? 'selected' : ''); ?>>
                                            <?php echo e($u->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Ürün</label>
                                <select name="customer_product_id" class="form-select">
                                    <option value="">Seçiniz...</option>
                                    <?php $__currentLoopData = $customer->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($prod->id); ?>"
                                            <?php echo e($assignment->customer_product_id == $prod->id ? 'selected' : ''); ?>>
                                            <?php echo e($prod->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3"><label class="form-label fw-bold small">Miktar</label><input
                                    type="number" name="quantity" class="form-control"
                                    value="<?php echo e($assignment->quantity); ?>" step="0.01"></div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">Birim</label>
                                <select name="unit" class="form-select">
                                    <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($birim->ad); ?>"
                                            <?php echo e($assignment->unit == $birim->ad ? 'selected' : ''); ?>>
                                            <?php echo e($birim->ad); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold small">Açıklama</label>
                                <div class="input-group">
                                    <textarea name="description" id="log_desc_edit_<?php echo e($assignment->id); ?>" class="form-control" rows="2"><?php echo e($assignment->task_description); ?></textarea>
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="btn_log_desc_<?php echo e($assignment->id); ?>"
                                        onclick="toggleVoiceInput('log_desc_edit_<?php echo e($assignment->id); ?>', 'btn_log_desc_<?php echo e($assignment->id); ?>')"><i
                                            class="fa-solid fa-microphone"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light"><button type="button"
                            class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">İptal</button><button
                            type="submit" class="btn btn-animated-gradient rounded-pill px-4">Kaydet</button></div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php $__currentLoopData = $customer->vehicleAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="historyLogisticsModal<?php echo e($assignment->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-0">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="fa-solid fa-truck-fast text-info me-2"></i>Lojistik Görev Tarihçesi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light pt-0">
                    <p class="small text-muted fw-bold mb-4 border-bottom pb-2"><?php echo e($assignment->title); ?></p>

                    <?php
                        // Durumları Türkçeleştirme Dizisi
                        $statusLabels = [
                            'pending' => 'Beklemede',
                            'waiting_assignment' => 'Atama Bekliyor',
                            'on_road' => 'Yolda',
                            'in_progress' => 'İşlemde',
                            'completed' => 'Tamamlandı',
                            'cancelled' => 'İptal Edildi',
                        ];
                    ?>

                    <div class="timeline ps-2 border-start border-2 border-info ms-3">
                        <?php $__empty_1 = true; $__currentLoopData = $assignment->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="position-relative mb-4 ps-4">
                                <span
                                    class="position-absolute top-0 start-0 translate-middle p-2 bg-info border border-light rounded-circle"
                                    style="margin-left: -1px;"></span>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <strong class="text-dark">
                                                <?php if($history->old_status): ?>
                                                    <span
                                                        class="text-muted text-decoration-line-through"><?php echo e($statusLabels[$history->old_status] ?? ucfirst($history->old_status)); ?></span>
                                                    <i class="fa-solid fa-arrow-right mx-1 text-muted small"></i>
                                                <?php endif; ?>
                                                <span
                                                    class="text-primary"><?php echo e($statusLabels[$history->new_status] ?? ucfirst($history->new_status)); ?></span>
                                            </strong>
                                            <small
                                                class="text-muted"><?php echo e($history->created_at->format('d.m.Y H:i')); ?></small>
                                        </div>
                                        <div class="small text-muted mb-0">
                                            <i
                                                class="fa-solid fa-user-circle me-1"></i><?php echo e($history->user->name ?? 'Sistem'); ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted small">Henüz bir tarihçe kaydı bulunmuyor.</div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<div class="modal fade" id="addCompetitorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-user-ninja me-2"></i>Yeni Rakip Firma Ekle
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Kapat"></button>
            </div>
            <form action="<?php echo e(route('competitors.store')); ?>" method="POST" autocomplete="off">
                <?php echo csrf_field(); ?>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Rakip Firma Adı (*)</label>
                        <input type="text" name="name" class="form-control border-danger" required
                            placeholder="Örn: X Plastik A.Ş.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">Notlar (Opsiyonel)</label>
                        <textarea name="notes" class="form-control border-danger" rows="2"
                            placeholder="Rakip hakkında genel bilgi, pazar payı vs..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="fa-solid fa-save me-2"></i>Kaydet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/partials/modals.blade.php ENDPATH**/ ?>