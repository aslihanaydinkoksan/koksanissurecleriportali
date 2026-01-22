

<?php $__env->startSection('title', 'Sevkiyat Kaydını Düzenle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına animasyonlu arka plan */
        #app>main.py-4 {
            padding: 2.5rem 0 !important;
            min-height: calc(100vh - 72px);
            background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
        }

        @keyframes gradientWave {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .shipment-edit-card {
            border-radius: 1.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.4);
            background-color: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(15px);
        }

        .section-header {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #667EEA;
            border-bottom: 2px solid rgba(102, 126, 234, 0.2);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            border: 1px solid rgba(0, 0, 0, 0.1);
            background-color: rgba(255, 255, 255, 0.9);
        }

        .status-badge-container {
            padding: 1rem;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px dashed #667EEA;
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 10s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn-animated-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            color: white;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card shipment-edit-card border-0">
                    <div
                        class="card-header d-flex justify-content-between align-items-center bg-transparent border-0 pt-4 px-4">
                        <div>
                            <h3 class="fw-bold mb-0">Sevkiyat Düzenle</h3>
                            <small class="text-muted">ID: #<?php echo e($shipment->id); ?> | Oluşturan:
                                <?php echo e($shipment->user->name); ?></small>
                        </div>

                        <div class="d-flex gap-2">
                            <div class="form-check form-switch me-3">
                                <input class="form-check-input" type="checkbox" name="is_important" id="is_important"
                                    form="editShipmentForm" value="1"
                                    <?php echo e(old('is_important', $shipment->is_important) ? 'checked' : ''); ?>>
                                <label class="form-check-label fw-bold text-danger" for="is_important">Önemli</label>
                            </div>
                            <?php if(Auth::user()->hasRole(['admin', 'yönetici'])): ?>
                                <form method="POST" action="<?php echo e(route('shipments.destroy', $shipment->id)); ?>"
                                    onsubmit="return confirm('Bu kaydı ve bağlı tüm durakları silmek istediğinizden emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="fas fa-trash me-1"></i> Kaydı Sil
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="<?php echo e(route('shipments.update', $shipment->id)); ?>" id="editShipmentForm"
                            enctype="multipart/form-data" autocomplete="off">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                
                                <div class="col-md-6 border-end">
                                    <div class="section-header">1. Durum ve Operasyon</div>

                                    <div class="status-badge-container mb-4 text-center">
                                        <label for="shipment_status" class="form-label fw-bold d-block">
                                            <i class="fas fa-route me-1"></i> Mevcut Sevkiyat Statüsü
                                        </label>
                                        <select name="shipment_status" id="shipment_status"
                                            class="form-select border-2 border-primary text-center fw-bold">
                                            <option value="pending"
                                                <?php echo e($shipment->shipment_status == 'pending' ? 'selected' : ''); ?>>📋 BEKLİYOR
                                                / HAZIRLANIYOR</option>
                                            <option value="on_road"
                                                <?php echo e($shipment->shipment_status == 'on_road' ? 'selected' : ''); ?>>🚚 YOLDA
                                                (TRANSIT)</option>
                                            <option value="delivered"
                                                <?php echo e($shipment->shipment_status == 'delivered' ? 'selected' : ''); ?>>✅ TESLİM
                                                EDİLDİ</option>
                                        </select>
                                        <p class="small text-muted mt-2 mb-0">Statü değişikliği Kanban kartını otomatik
                                            taşır.</p>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Sevkiyat Türü</label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="shipment_type" id="type_import"
                                                value="import"
                                                <?php echo e(old('shipment_type', $shipment->shipment_type) == 'import' ? 'checked' : ''); ?>>
                                            <label class="btn btn-outline-primary" for="type_import">İthalat</label>
                                            <input type="radio" class="btn-check" name="shipment_type" id="type_export"
                                                value="export"
                                                <?php echo e(old('shipment_type', $shipment->shipment_type) == 'export' ? 'checked' : ''); ?>>
                                            <label class="btn btn-outline-primary" for="type_export">İhracat</label>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="arac_tipi" class="form-label fw-bold">Araç Tipi (*)</label>
                                        <select name="arac_tipi" id="arac_tipi" class="form-select" required>
                                            <option value="tır"
                                                <?php echo e(old('arac_tipi', $shipment->arac_tipi) == 'tır' ? 'selected' : ''); ?>>Tır
                                            </option>
                                            <option value="kamyon"
                                                <?php echo e(old('arac_tipi', $shipment->arac_tipi) == 'kamyon' ? 'selected' : ''); ?>>
                                                Kamyon</option>
                                            <option value="gemi"
                                                <?php echo e(old('arac_tipi', $shipment->arac_tipi) == 'gemi' ? 'selected' : ''); ?>>
                                                Gemi</option>
                                        </select>
                                    </div>

                                    
                                    <div id="karaAraciAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="plaka" class="form-label">Plaka</label>
                                                <input type="text" class="form-control" name="plaka" id="plaka"
                                                    value="<?php echo e(old('plaka', $shipment->plaka)); ?>">
                                            </div>
                                            <div class="col-md-6" id="dorse_plakasi_div">
                                                <label for="dorse_plakasi" class="form-label">Dorse Plakası</label>
                                                <input type="text" class="form-control" name="dorse_plakasi"
                                                    id="dorse_plakasi"
                                                    value="<?php echo e(old('dorse_plakasi', $shipment->dorse_plakasi)); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Kalkış / Varış Noktası</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="kalkis_noktasi"
                                                    value="<?php echo e(old('kalkis_noktasi', $shipment->kalkis_noktasi)); ?>"
                                                    placeholder="Kalkış">
                                                <span class="input-group-text"><i class="fas fa-arrow-right"></i></span>
                                                <input type="text" class="form-control" name="varis_noktasi"
                                                    value="<?php echo e(old('varis_noktasi', $shipment->varis_noktasi)); ?>"
                                                    placeholder="Varış">
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div id="gemiAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Gemi Adı</label>
                                                <input type="text" class="form-control" name="gemi_adi"
                                                    id="gemi_adi" value="<?php echo e(old('gemi_adi', $shipment->gemi_adi)); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">IMO No</label>
                                                <input type="text" class="form-control" name="imo_numarasi"
                                                    id="imo_numarasi"
                                                    value="<?php echo e(old('imo_numarasi', $shipment->imo_numarasi)); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="section-header ms-md-3">2. Kargo Bilgileri & Ekler</div>
                                    <div class="ms-md-3">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Yük İçeriği</label>
                                            <input type="text" name="kargo_icerigi" class="form-control"
                                                value="<?php echo e(old('kargo_icerigi', $shipment->kargo_icerigi)); ?>" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-7">
                                                <label class="form-label fw-bold">Miktar</label>
                                                <input type="text" name="kargo_miktari" class="form-control"
                                                    value="<?php echo e(old('kargo_miktari', $shipment->kargo_miktari)); ?>" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label fw-bold">Birim</label>
                                                <select name="kargo_tipi" class="form-select">
                                                    <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($birim->ad); ?>"
                                                            <?php echo e($shipment->kargo_tipi == $birim->ad ? 'selected' : ''); ?>>
                                                            <?php echo e($birim->ad); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Çıkış Tarihi</label>
                                                <input type="datetime-local" class="form-control" name="cikis_tarihi"
                                                    value="<?php echo e(old('cikis_tarihi', $shipment->cikis_tarihi?->format('Y-m-d\TH:i'))); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Planlanan Varış</label>
                                                <input type="datetime-local" class="form-control"
                                                    name="tahmini_varis_tarihi"
                                                    value="<?php echo e(old('tahmini_varis_tarihi', $shipment->tahmini_varis_tarihi?->format('Y-m-d\TH:i'))); ?>">
                                            </div>
                                        </div>

                                        
                                        <div class="p-3 rounded bg-light border mb-3">
                                            <label class="form-label fw-bold">Dosya Yönetimi</label>
                                            <?php if($shipment->dosya_yolu): ?>
                                                <div class="d-flex align-items-center mb-2">
                                                    <i class="fas fa-file-pdf fa-2x text-danger me-2"></i>
                                                    <a href="<?php echo e(asset('storage/' . $shipment->dosya_yolu)); ?>"
                                                        target="_blank" class="text-decoration-none fw-bold">Mevcut
                                                        Dosyayı Gör</a>
                                                    <div class="form-check ms-auto">
                                                        <input class="form-check-input" type="checkbox" name="dosya_sil"
                                                            id="dosya_sil" value="1">
                                                        <label class="form-check-label text-danger small"
                                                            for="dosya_sil">Sil</label>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <input type="file" name="ek_dosya" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header">3. Ekstra Veriler & Detaylar</div>
                                    <?php if (isset($component)) { $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00 = $component; } ?>
<?php $component = App\View\Components\DynamicFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dynamic-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\DynamicFields::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Shipment::class),'entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($shipment)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00)): ?>
<?php $component = $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00; ?>
<?php unset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00); ?>
<?php endif; ?>
                                </div>
                                <div class="col-12 mt-3">
                                    <label class="form-label fw-bold text-muted">Açıklamalar</label>
                                    <textarea class="form-control" name="aciklamalar" rows="3"><?php echo e(old('aciklamalar', $shipment->aciklamalar)); ?></textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-animated-gradient px-5 py-3 rounded-pill shadow">
                                    <i class="fas fa-save me-2"></i>DEĞİŞİKLİKLERİ KAYDET
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('arac_tipi');
            const roadSection = document.getElementById('karaAraciAlanlari');
            const seaSection = document.getElementById('gemiAlanlari');
            const dorseDiv = document.getElementById('dorse_plakasi_div');

            const inputs = {
                plaka: document.getElementById('plaka'),
                dorse: document.getElementById('dorse_plakasi'),
                gemi: document.getElementById('gemi_adi'),
                imo: document.getElementById('imo_numarasi')
            };

            function toggleSections() {
                const val = typeSelect.value;
                roadSection.style.display = 'none';
                seaSection.style.display = 'none';
                Object.values(inputs).forEach(i => i.required = false);

                if (val === 'tır' || val === 'kamyon') {
                    roadSection.style.display = 'block';
                    inputs.plaka.required = true;
                    dorseDiv.style.display = (val === 'tır') ? 'block' : 'none';
                    if (val === 'tır') inputs.dorse.required = true;
                } else if (val === 'gemi') {
                    seaSection.style.display = 'block';
                    inputs.gemi.required = true;
                    inputs.imo.required = true;
                }
            }

            typeSelect.addEventListener('change', toggleSections);
            toggleSections();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/shipments/edit.blade.php ENDPATH**/ ?>