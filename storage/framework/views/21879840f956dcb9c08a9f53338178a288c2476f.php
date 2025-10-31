

<?php $__env->startSection('title', 'Yeni Sevkiyat Kaydı'); ?>

<style>
    /* Ana içerik alanına (main) animasyonlu arka planı uygula */
    #app>main.py-4 {
        padding: 2.5rem 0 !important;
        min-height: calc(100vh - 72px);
        background: linear-gradient(-45deg,
                #dbe4ff,
                /* #667EEA (Canlı mavi-mor) tonu */
                #fde2ff,
                /* #F093FB (Yumuşak pembe) tonu */
                #d9fcf7,
                /* #4FD1C5 (Teal/turkuaz) tonu */
                #fff0d9
                /* #FBD38D (Sıcak sarı) tonu */
            );
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
    }

    /* Arka plan dalgalanma animasyonu */
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

    /* === GÜNCELLENDİ (TAM ŞEFFAF KART) === */
    .create-shipment-card {
        border-radius: 1rem;
        /* Gölgeyi ve blur'u kaldırıyoruz */
        box-shadow: none !important;
        border: 0;
        /* Arka planı tamamen şeffaf yapıyoruz */
        background-color: transparent;
        backdrop-filter: none;
    }

    .create-shipment-card .card-header,
    .create-shipment-card .form-label {
        color: #444;
        /* Koyu renk (veya #FFFFFF beyaz) metin */
        font-weight: bold;
        /* Kalın Metin */
        /* Hareketli arka plan üzerinde okunabilirlik için hafif gölge */
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
    }

    .create-shipment-card .card-header {
        color: #000;
        /* Başlık siyah kalsın */
    }

    /* Yumuşak köşeli form elemanları */
    .create-shipment-card .form-control,
    .create-shipment-card .form-select {
        border-radius: 0.5rem;
        /* Formların içini daha okunaklı yapmak için hafif opaklık */
        background-color: rgba(255, 255, 255, 0.8);
    }

    /* Animasyonlu buton (Değişiklik yok) */
    .btn-animated-gradient {
        background: linear-gradient(-45deg,
                #667EEA, #F093FB, #4FD1C5, #FBD38D);
        background-size: 400% 400%;
        animation: gradientWave 18s ease infinite;
        border: none;
        color: white;
        font-weight: bold;
        transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
    }

    .btn-animated-gradient:hover {
        color: white;
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card create-shipment-card">
                    <div class="card-header h4 bg-transparent border-0 pt-4"><?php echo e(__('Yeni Sevkiyat Kaydı Oluştur')); ?></div>
                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('shipments.store')); ?>" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="arac_tipi" class="form-label">Araç Tipi (*)</label>
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
                                            <option value="tır" <?php if(old('arac_tipi') == 'tır'): ?> selected <?php endif; ?>>Tır
                                            </option>
                                            <option value="gemi" <?php if(old('arac_tipi') == 'gemi'): ?> selected <?php endif; ?>>Gemi
                                            </option>
                                            <option value="kamyon" <?php if(old('arac_tipi') == 'kamyon'): ?> selected <?php endif; ?>>Kamyon
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
                                        <div class="mb-3">
                                            <label for="plaka" class="form-label">Plaka (*)</label>
                                            <input type="text" class="form-control <?php $__errorArgs = ['plaka'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="plaka" name="plaka" value="<?php echo e(old('plaka')); ?>">
                                            <?php $__errorArgs = ['plaka'];
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
                                        <div class="mb-3" id="dorse_plakasi_div">
                                            <label for="dorse_plakasi" class="form-label">Dorse Plakası (*)</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['dorse_plakasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="dorse_plakasi" name="dorse_plakasi" value="<?php echo e(old('dorse_plakasi')); ?>">
                                            <?php $__errorArgs = ['dorse_plakasi'];
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
                                        <div class="mb-3">
                                            <label for="sofor_adi" class="form-label">Şoför Adı</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['sofor_adi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sofor_adi"
                                                name="sofor_adi" value="<?php echo e(old('sofor_adi')); ?>">
                                            <?php $__errorArgs = ['sofor_adi'];
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
                                        <div class="mb-3">
                                            <label for="kalkis_noktasi" class="form-label">Kalkış Noktası
                                                (Gümrük/Tesis)</label>
                                            <input type="text" class="form-control" id="kalkis_noktasi"
                                                name="kalkis_noktasi"
                                                value="<?php echo e(old('kalkis_noktasi', $shipment->kalkis_noktasi ?? '')); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="varis_noktasi" class="form-label">Varış Noktası
                                                (Gümrük/Tesis)</label>
                                            <input type="text" class="form-control" id="varis_noktasi"
                                                name="varis_noktasi"
                                                value="<?php echo e(old('varis_noktasi', $shipment->varis_noktasi ?? '')); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Sevkiyat Türü</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_import" value="import" checked>
                                                <label class="form-check-label" for="type_import">
                                                    İthalat
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_export" value="export">
                                                <label class="form-check-label" for="type_export">
                                                    İhracat
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="gemiAlanlari" style="display: none;">
                                        <div class="mb-3">
                                            <label for="imo_numarasi" class="form-label">IMO Numarası (*)</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['imo_numarasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="imo_numarasi" name="imo_numarasi" value="<?php echo e(old('imo_numarasi')); ?>">
                                            <?php $__errorArgs = ['imo_numarasi'];
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
                                        <div class="mb-3">
                                            <label for="gemi_adi" class="form-label">Gemi Adı (*)</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['gemi_adi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="gemi_adi" name="gemi_adi" value="<?php echo e(old('gemi_adi')); ?>">
                                            <?php $__errorArgs = ['gemi_adi'];
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
                                        <div class="mb-3">
                                            <label for="kalkis_limani" class="form-label">Kalkış Limanı (*)</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['kalkis_limani'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="kalkis_limani" name="kalkis_limani"
                                                value="<?php echo e(old('kalkis_limani')); ?>">
                                            <?php $__errorArgs = ['kalkis_limani'];
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
                                        <div class="mb-3">
                                            <label for="varis_limani" class="form-label">Varış Limanı (*)</label>
                                            <input type="text"
                                                class="form-control <?php $__errorArgs = ['varis_limani'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                id="varis_limani" name="varis_limani" value="<?php echo e(old('varis_limani')); ?>">
                                            <?php $__errorArgs = ['varis_limani'];
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
                                        <div class="mb-3">
                                            <label class="form-label">Sevkiyat Türü</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_import" value="import" checked>
                                                <label class="form-check-label" for="type_import">
                                                    İthalat
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="shipment_type"
                                                    id="type_export" value="export">
                                                <label class="form-check-label" for="type_export">
                                                    İhracat
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cikis_tarihi" class="form-label">Gümrükten / Limandan Çıkış Tarihi
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
                                            id="cikis_tarihi" name="cikis_tarihi" value="<?php echo e(old('cikis_tarihi')); ?>"
                                            required>
                                        <?php $__errorArgs = ['cikis_tarihi'];
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
                                    <div class="mb-3">
                                        <label for="tahmini_varis_tarihi" class="form-label">Tesise / Limana Planlanan
                                            Varış Tarihi (*)</label>
                                        <input type="datetime-local"
                                            class="form-control <?php $__errorArgs = ['tahmini_varis_tarihi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="tahmini_varis_tarihi" name="tahmini_varis_tarihi"
                                            value="<?php echo e(old('tahmini_varis_tarihi')); ?>" required>
                                        <?php $__errorArgs = ['tahmini_varis_tarihi'];
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
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kargo_icerigi" class="form-label">Kargo Yükü / İçeriği (*)</label>
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['kargo_icerigi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="kargo_icerigi" name="kargo_icerigi" value="<?php echo e(old('kargo_icerigi')); ?>"
                                            required>
                                        <?php $__errorArgs = ['kargo_icerigi'];
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
                                    <div class="mb-3">
                                        <label for="kargo_tipi" class="form-label">Kargo Yük Tipi (*)</label>
                                        <select name="kargo_tipi" id="kargo_tipi"
                                            class="form-select <?php $__errorArgs = ['kargo_tipi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                            <option value="">Birim Seçiniz...</option>
                                            <?php if(isset($birimler)): ?>
                                                <?php $__currentLoopData = $birimler; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $birim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($birim->ad); ?>"
                                                        <?php if(old('kargo_tipi') == $birim->ad): ?> selected <?php endif; ?>>
                                                        <?php echo e($birim->ad); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </select>
                                        <?php $__errorArgs = ['kargo_tipi'];
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
                                    <div class="mb-3">
                                        <label for="kargo_miktari" class="form-label">Yük Miktarı (*)</label>
                                        <input type="text"
                                            class="form-control <?php $__errorArgs = ['kargo_miktari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="kargo_miktari" name="kargo_miktari" value="<?php echo e(old('kargo_miktari')); ?>"
                                            required>
                                        <?php $__errorArgs = ['kargo_miktari'];
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

                                    
                                    <div class="mb-3">
                                        <label for="ek_dosya" class="form-label">Ek Dosya (Opsiyonel)</label>
                                        <input class="form-control <?php $__errorArgs = ['ek_dosya'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            type="file" id="ek_dosya" name="ek_dosya">
                                        <?php $__errorArgs = ['ek_dosya'];
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

                                    <div class="mb-3">
                                        <label for="aciklamalar" class="form-label">Açıklamalar</label>
                                        <textarea class="form-control <?php $__errorArgs = ['aciklamalar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="aciklamalar" name="aciklamalar"
                                            rows="6"><?php echo e(old('aciklamalar')); ?></textarea>
                                        <?php $__errorArgs = ['aciklamalar'];
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
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-animated-gradient rounded-3 px-4 py-2">Kaydı
                                    Oluştur</button>
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
            const plakaInput = document.getElementById('plaka');
            const dorsePlakasiInput = document.getElementById('dorse_plakasi');
            const imoInput = document.getElementById('imo_numarasi');
            const gemiAdiInput = document.getElementById('gemi_adi');
            const kalkisLimaniInput = document.getElementById('kalkis_limani');
            const varisLimaniInput = document.getElementById('varis_limani');
            const kalkisNoktasiInput = document.getElementById('kalkis_noktasi');
            const varisNoktasiInput = document.getElementById('varis_noktasi');

            function updateVehicleFields() {
                const selectedValue = aracTipiDropdown.value;
                karaAraciAlanlari.style.display = 'none';
                dorsePlakasiDiv.style.display = 'none';
                gemiAlanlari.style.display = 'none';
                plakaInput.required = false;
                dorsePlakasiInput.required = false;
                imoInput.required = false;
                gemiAdiInput.required = false;
                kalkisLimaniInput.required = false;
                varisLimaniInput.required = false;
                kalkisNoktasiInput.required = false;
                varisNoktasiInput.required = false;

                if (selectedValue === 'tır') {
                    karaAraciAlanlari.style.display = 'block';
                    dorsePlakasiDiv.style.display = 'block';
                    plakaInput.required = true;
                    dorsePlakasiInput.required = true;
                    kalkisNoktasiInput.required = true;
                    varisNoktasiInput.required = true;
                } else if (selectedValue === 'kamyon') {
                    karaAraciAlanlari.style.display = 'block';
                    plakaInput.required = true;
                    kalkisNoktasiInput.required = true;
                    varisNoktasiInput.required = true;
                } else if (selectedValue === 'gemi') {
                    gemiAlanlari.style.display = 'block';
                    imoInput.required = true;
                    gemiAdiInput.required = true;
                    kalkisLimaniInput.required = true;
                    varisLimaniInput.required = true;
                }
            }
            aracTipiDropdown.addEventListener('change', updateVehicleFields);
            updateVehicleFields();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\aslihan.aydin\Desktop\tedarik-yonetimi\tedarik-yonetimi\resources\views/shipments/create.blade.php ENDPATH**/ ?>