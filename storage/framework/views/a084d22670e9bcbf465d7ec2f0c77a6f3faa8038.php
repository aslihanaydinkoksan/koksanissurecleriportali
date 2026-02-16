<?php $__env->startSection('title', 'Yeni Sevkiyat Kaydı'); ?>

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

        .create-shipment-card {
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
            padding: 0.6rem 1rem;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: 0 0 0 0.25 margin-bottom: rgba(102, 126, 234, 0.25);
            border-color: #667EEA;
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

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card create-shipment-card">
                    <div
                        class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h3 class="fw-bold text-dark mb-0"><i class="fas fa-shipping-fast me-2"></i>Yeni Sevkiyat Planla</h3>

                        
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_important" id="is_important"
                                form="mainShipmentForm" value="1" <?php echo e(old('is_important') ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold text-danger ms-2" for="is_important">
                                <i class="fas fa-exclamation-triangle me-1"></i> KRİTİK SEVKİYAT
                            </label>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('shipments.store')); ?>" id="mainShipmentForm"
                            enctype="multipart/form-data" autocomplete="off">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                
                                <div class="col-md-6 border-end">
                                    <div class="section-header">1. Operasyon & Araç Bilgileri</div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Sevkiyat Türü (*)</label>
                                        <div class="d-flex gap-4 mt-1">
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_import" value="import"
                                                    <?php echo e(old('shipment_type', 'import') == 'import' ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="type_import">İthalat</label>
                                            </div>
                                            <div class="form-check custom-radio">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_export" value="export"
                                                    <?php echo e(old('shipment_type') == 'export' ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="type_export">İhracat</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="arac_tipi" class="form-label fw-bold">Araç Tipi (*)</label>
                                        <select name="arac_tipi" id="arac_tipi"
                                            class="form-select <?php $__errorArgs = ['arac_tipi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Seçiniz...</option>
                                            <option value="tır" <?php echo e(old('arac_tipi') == 'tır' ? 'selected' : ''); ?>>Tır
                                            </option>
                                            <option value="kamyon" <?php echo e(old('arac_tipi') == 'kamyon' ? 'selected' : ''); ?>>
                                                Kamyon</option>
                                            <option value="gemi" <?php echo e(old('arac_tipi') == 'gemi' ? 'selected' : ''); ?>>Gemi
                                            </option>
                                        </select>
                                        <?php $__errorArgs = ['arac_tipi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    
                                    <div id="karaAraciAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="plaka" class="form-label">Plaka (*)</label>
                                                <input type="text" class="form-control" id="plaka" name="plaka"
                                                    value="<?php echo e(old('plaka')); ?>" placeholder="34 ABC 123">
                                            </div>
                                            <div class="col-md-6" id="dorse_plakasi_div">
                                                <label for="dorse_plakasi" class="form-label">Dorse Plakası (*)</label>
                                                <input type="text" class="form-control" id="dorse_plakasi"
                                                    name="dorse_plakasi" value="<?php echo e(old('dorse_plakasi')); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="sofor_adi" class="form-label">Şoför Adı & Soyadı</label>
                                            <input type="text" class="form-control" id="sofor_adi" name="sofor_adi"
                                                value="<?php echo e(old('sofor_adi')); ?>">
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="kalkis_noktasi" class="form-label">Kalkış Noktası</label>
                                                <input type="text" class="form-control" name="kalkis_noktasi"
                                                    value="<?php echo e(old('kalkis_noktasi')); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="varis_noktasi" class="form-label">Varış Noktası</label>
                                                <input type="text" class="form-control" name="varis_noktasi"
                                                    value="<?php echo e(old('varis_noktasi')); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    
                                    <div id="gemiAlanlari" style="display: none;">
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="imo_numarasi" class="form-label">IMO Numarası (*)</label>
                                                <input type="text" class="form-control" id="imo_numarasi"
                                                    name="imo_numarasi" value="<?php echo e(old('imo_numarasi')); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="gemi_adi" class="form-label">Gemi Adı (*)</label>
                                                <input type="text" class="form-control" id="gemi_adi"
                                                    name="gemi_adi" value="<?php echo e(old('gemi_adi')); ?>">
                                            </div>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="kalkis_limani" class="form-label">Kalkış Limanı (*)</label>
                                                <input type="text" class="form-control" id="kalkis_limani"
                                                    name="kalkis_limani" value="<?php echo e(old('kalkis_limani')); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="varis_limani" class="form-label">Varış Limanı (*)</label>
                                                <input type="text" class="form-control" id="varis_limani"
                                                    name="varis_limani" value="<?php echo e(old('varis_limani')); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="section-header ms-md-3">2. Kargo & Zamanlama</div>
                                    <div class="ms-md-3">
                                        <div class="mb-3">
                                            <label for="kargo_icerigi" class="form-label fw-bold">Kargo İçeriği
                                                (*)</label>
                                            <input type="text" name="kargo_icerigi"
                                                class="form-control border-2 <?php $__errorArgs = ['kargo_icerigi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('kargo_icerigi')); ?>" required>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-7">
                                                <label for="kargo_miktari" class="form-label fw-bold">Miktar (*)</label>
                                                <input type="text" name="kargo_miktari"
                                                    class="form-control <?php $__errorArgs = ['kargo_miktari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    value="<?php echo e(old('kargo_miktari')); ?>" required>
                                            </div>
                                            <div class="col-md-5">
                                                <label for="kargo_tipi" class="form-label fw-bold">Birim (*)</label>
                                                <select name="kargo_tipi" class="form-select" required>
                                                    <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($birim->ad); ?>"
                                                            <?php echo e(old('kargo_tipi') == $birim->ad ? 'selected' : ''); ?>>
                                                            <?php echo e($birim->ad); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="cikis_tarihi" class="form-label fw-bold">Çıkış Tarihi
                                                    (*)</label>
                                                <input type="datetime-local"
                                                    class="form-control <?php $__errorArgs = ['cikis_tarihi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="cikis_tarihi" value="<?php echo e(old('cikis_tarihi')); ?>" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="tahmini_varis_tarihi" class="form-label fw-bold">Planlanan
                                                    Varış (*)</label>
                                                <input type="datetime-local"
                                                    class="form-control <?php $__errorArgs = ['tahmini_varis_tarihi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                    name="tahmini_varis_tarihi" value="<?php echo e(old('tahmini_varis_tarihi')); ?>"
                                                    required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="ek_dosya" class="form-label fw-bold">Ek Dosya
                                                (İrsaliye/Belge)</label>
                                            <input class="form-control" type="file" name="ek_dosya">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="section-header">3. Ekstra Veriler & Açıklamalar</div>
                                    <?php if (isset($component)) { $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00 = $component; } ?>
<?php $component = App\View\Components\DynamicFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dynamic-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\DynamicFields::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Shipment::class)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00)): ?>
<?php $component = $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00; ?>
<?php unset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00); ?>
<?php endif; ?>
                                </div>
                                <div class="col-12 mt-3">
                                    <label for="aciklamalar" class="form-label fw-bold">Detaylı Açıklama</label>
                                    <textarea class="form-control" id="aciklamalar" name="aciklamalar" rows="4"><?php echo e(old('aciklamalar')); ?></textarea>
                                </div>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient btn-lg px-5 py-3 rounded-pill shadow">
                                    <i class="fas fa-save me-2"></i>SEVKİYAT KAYDINI OLUŞTUR
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
            const aracTipiDropdown = document.getElementById('arac_tipi');
            const karaAraciAlanlari = document.getElementById('karaAraciAlanlari');
            const dorsePlakasiDiv = document.getElementById('dorse_plakasi_div');
            const gemiAlanlari = document.getElementById('gemiAlanlari');

            // Inputs
            const inputs = {
                plaka: document.getElementById('plaka'),
                dorse: document.getElementById('dorse_plakasi'),
                imo: document.getElementById('imo_numarasi'),
                gemi: document.getElementById('gemi_adi'),
                kalkisL: document.getElementById('kalkis_limani'),
                varisL: document.getElementById('varis_limani')
            };

            function updateVehicleFields() {
                const val = aracTipiDropdown.value;

                // Reset
                karaAraciAlanlari.style.display = 'none';
                gemiAlanlari.style.display = 'none';
                Object.values(inputs).forEach(i => i.required = false);

                if (val === 'tır' || val === 'kamyon') {
                    karaAraciAlanlari.style.display = 'block';
                    inputs.plaka.required = true;

                    if (val === 'tır') {
                        dorsePlakasiDiv.style.display = 'block';
                        inputs.dorse.required = true;
                    } else {
                        dorsePlakasiDiv.style.display = 'none';
                        inputs.dorse.required = false;
                    }
                } else if (val === 'gemi') {
                    gemiAlanlari.style.display = 'block';
                    inputs.imo.required = true;
                    inputs.gemi.required = true;
                    inputs.kalkisL.required = true;
                    inputs.varisL.required = true;
                }
            }

            aracTipiDropdown.addEventListener('change', updateVehicleFields);
            updateVehicleFields(); // Initial run
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/shipments/create.blade.php ENDPATH**/ ?>