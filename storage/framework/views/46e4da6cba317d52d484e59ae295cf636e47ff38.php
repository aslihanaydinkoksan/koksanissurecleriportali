

<?php $__env->startSection('title', 'Etkinliği Düzenle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanına (main) animasyonlu arka planı uygula */
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

        /* Kart Stili */
        .event-edit-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
            backdrop-filter: none;
        }

        .event-edit-card .card-header,
        .event-edit-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .event-edit-card .card-header {
            color: #000;
        }

        .event-edit-card .form-control,
        .event-edit-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        /* Buton Stili */
        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
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

        /* CRM Bölümü Stili */
        .crm-update-section {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card event-edit-card">

                    
                    <div
                        class="card-header d-flex justify-content-between align-items-center h4 bg-transparent border-0 pt-4">
                        <span><?php echo e(__('Etkinliği Düzenle')); ?></span>

                        
                        <?php
                            $statusMap = [
                                'planlandi' => ['class' => 'bg-info text-dark', 'icon' => '⏳', 'label' => 'Planlandı'],
                                'gerceklesti' => ['class' => 'bg-success', 'icon' => '✅', 'label' => 'Gerçekleşti'],
                                'ertelendi' => [
                                    'class' => 'bg-warning text-dark',
                                    'icon' => '📅',
                                    'label' => 'Ertelendi',
                                ],
                                'iptal' => ['class' => 'bg-danger', 'icon' => '❌', 'label' => 'İptal'],
                            ];
                            $status = $statusMap[$event->visit_status] ?? [
                                'class' => 'bg-secondary',
                                'icon' => '?',
                                'label' => 'Bilinmiyor',
                            ];
                        ?>
                        <span class="badge fs-6 rounded-pill <?php echo e($status['class']); ?> ms-2">
                            <?php echo e($status['icon']); ?> <?php echo e($status['label']); ?>

                        </span>

                        <?php if(Auth::user()->role === 'admin'): ?>
                            <form method="POST" action="<?php echo e(route('service.events.destroy', $event->id)); ?>"
                                onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');"
                                class="ms-auto">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger btn-sm">Etkinliği Sil</button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <div class="card-body p-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger" role="alert"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('service.events.update', $event->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Etkinlik Başlığı (*)</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="title" name="title" value="<?php echo e(old('title', $event->title)); ?>"
                                            required>
                                        <?php $__errorArgs = ['title'];
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
                                        <label for="event_type" class="form-label">Etkinlik Tipi (*)</label>
                                        
                                        <select name="event_type" id="event_type"
                                            class="form-select <?php $__errorArgs = ['event_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                                            <?php echo e($event->event_type == 'musteri_ziyareti' ? 'disabled' : ''); ?>>
                                            <option value="">Seçiniz...</option>
                                            <?php $__currentLoopData = $eventTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php if(old('event_type', $event->event_type) == $key): ?> selected <?php endif; ?>><?php echo e($value); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($event->event_type == 'musteri_ziyareti'): ?>
                                            <input type="hidden" name="event_type" value="<?php echo e($event->event_type); ?>">
                                        <?php endif; ?>
                                        <?php $__errorArgs = ['event_type'];
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
                                        <label for="location" class="form-label">Konum / Yer</label>
                                        <input type="text" class="form-control <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="location" name="location" value="<?php echo e(old('location', $event->location)); ?>">
                                        <?php $__errorArgs = ['location'];
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
                                        <label for="start_datetime" class="form-label">Başlangıç (*)</label>
                                        <input type="datetime-local"
                                            class="form-control <?php $__errorArgs = ['start_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="start_datetime" name="start_datetime"
                                            value="<?php echo e(old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i'))); ?>"
                                            required>
                                        <?php $__errorArgs = ['start_datetime'];
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
                                        <label for="end_datetime" class="form-label">Bitiş (*)</label>
                                        <input type="datetime-local"
                                            class="form-control <?php $__errorArgs = ['end_datetime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="end_datetime" name="end_datetime"
                                            value="<?php echo e(old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i'))); ?>"
                                            required>
                                        <?php $__errorArgs = ['end_datetime'];
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
                                        <label for="description" class="form-label">Açıklama</label>
                                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description"
                                            rows="3"><?php echo e(old('description', $event->description)); ?></textarea>
                                        <?php $__errorArgs = ['description'];
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

                            
                            <div class="crm-update-section mt-4 p-3 rounded-3">
                                <h5 class="text-primary mb-3">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <?php echo e($event->event_type == 'musteri_ziyareti' ? 'Ziyaret Durumu ve Sonuçları' : 'Etkinlik Durumu'); ?>

                                </h5>

                                <div class="row">
                                    
                                    <?php if($event->event_type == 'musteri_ziyareti'): ?>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small">İlgili Müşteri</label>
                                            <input type="text" class="form-control bg-white"
                                                value="<?php echo e($event->customer?->name ?? 'Müşteri Bilgisi Yok'); ?>" disabled>
                                            <input type="hidden" name="customer_id" value="<?php echo e($event->customer_id); ?>">
                                        </div>
                                    <?php endif; ?>

                                    
                                    <div
                                        class="<?php echo e($event->event_type == 'musteri_ziyareti' ? 'col-md-6' : 'col-12'); ?> mb-3">
                                        <label for="visit_status" class="form-label fw-bold">Durum (*)</label>
                                        <select name="visit_status" id="visit_status"
                                            class="form-select <?php $__errorArgs = ['visit_status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                            <option value="planlandi"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'planlandi' ? 'selected' : ''); ?>>
                                                ⏳ Planlandı</option>
                                            <option value="gerceklesti"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'gerceklesti' ? 'selected' : ''); ?>>
                                                ✅ Gerçekleşti / Tamamlandı</option>
                                            <option value="ertelendi"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'ertelendi' ? 'selected' : ''); ?>>
                                                📅 Ertelendi</option>
                                            <option value="iptal"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'iptal' ? 'selected' : ''); ?>>
                                                ❌ İptal Edildi</option>
                                        </select>
                                        <?php $__errorArgs = ['visit_status'];
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

                                
                                <div class="row" id="reason-row" style="display: none;">
                                    <div class="col-12">
                                        <label for="cancellation_reason" class="form-label" id="reason-label">Açıklama /
                                            Not</label>
                                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3"
                                            placeholder="Durum ile ilgili notunuzu giriniz..."><?php echo e(old('cancellation_reason', $event->cancellation_reason)); ?></textarea>
                                        <?php $__errorArgs = ['cancellation_reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient rounded-3 px-4 py-2">Değişiklikleri Kaydet</button>
                                <a href="<?php echo e(route('service.events.index')); ?>"
                                    class="btn btn-outline-secondary rounded-3">İptal</a>
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
            // Elemanları seç
            const visitStatusDropdown = document.getElementById('visit_status');
            const reasonRow = document.getElementById('reason-row');
            const reasonLabel = document.getElementById('reason-label');
            const reasonInput = document.getElementById('cancellation_reason');

            // Elemanlar yoksa çalışmayı durdur (Hata önleme)
            if (!visitStatusDropdown || !reasonRow) return;

            function toggleReasonField() {
                const status = visitStatusDropdown.value;

                // 1. Görünürlük Mantığı
                if (status === 'planlandi') {
                    reasonRow.style.display = 'none';
                    reasonInput.required = false;
                } else {
                    reasonRow.style.display = 'block';

                    // 2. Metin ve Zorunluluk Mantığı
                    if (status === 'iptal') {
                        reasonLabel.textContent = 'İptal Sebebi (Zorunlu)';
                        reasonInput.placeholder = 'Neden iptal edildiğini kısaca belirtiniz...';
                        reasonInput.required = true;

                    } else if (status === 'ertelendi') {
                        reasonLabel.textContent = 'Erteleme Sebebi (Zorunlu)';
                        reasonInput.placeholder = 'Neden ertelendiğini ve yeni planı belirtiniz...';
                        reasonInput.required = true;

                    } else if (status === 'gerceklesti') {
                        reasonLabel.textContent = 'Sonuç Notları / Rapor (Opsiyonel)';
                        reasonInput.placeholder = 'Toplantı notları, alınan kararlar, ziyaret sonucu vb...';
                        reasonInput.required = false; // Gerçekleştiyse not girmek opsiyonel
                    }
                }
            }

            // Sayfa ilk yüklendiğinde çalıştır
            toggleReasonField();

            // Değişiklik olduğunda çalıştır
            visitStatusDropdown.addEventListener('change', toggleReasonField);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/events/edit.blade.php ENDPATH**/ ?>