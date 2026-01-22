

<?php $__env->startSection('title', 'Etkinliği Düzenle'); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Ana içerik alanı animasyonu */
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

        .event-edit-card {
            border-radius: 1rem;
            box-shadow: none !important;
            border: 0;
            background-color: transparent;
        }

        .event-edit-card .card-header,
        .event-edit-card .form-label {
            color: #444;
            font-weight: bold;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.7);
        }

        .event-edit-card .form-control,
        .event-edit-card .form-select {
            border-radius: 0.5rem;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .btn-animated-gradient {
            background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D);
            background-size: 400% 400%;
            animation: gradientWave 18s ease infinite;
            border: none;
            color: white;
            font-weight: bold;
            transition: all 0.2s ease-out;
        }

        .btn-animated-gradient:hover {
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .crm-update-section {
            background-color: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* Önemli switch butonu rengi */
        #is_important:checked {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Dosya Yönetim Alanı CSS */
        .existing-attachment {
            transition: all 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .existing-attachment:hover {
            background-color: #fff !important;
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

                        <div class="d-flex align-items-center">
                            <?php
                                $statusMap = [
                                    'planlandi' => [
                                        'class' => 'bg-info text-dark',
                                        'icon' => '⏳',
                                        'label' => 'Planlandı',
                                    ],
                                    'gerceklesti' => [
                                        'class' => 'bg-success',
                                        'icon' => '✅',
                                        'label' => 'Gerçekleşti',
                                    ],
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
                            <span class="badge fs-6 rounded-pill <?php echo e($status['class']); ?> me-3">
                                <?php echo e($status['icon']); ?> <?php echo e($status['label']); ?>

                            </span>

                            <?php if(Auth::user()->role === 'admin' || Auth::user()->role === 'yonetici' || Auth::user()->role === 'yönetici'): ?>
                                <form method="POST" action="<?php echo e(route('service.events.destroy', $event->id)); ?>"
                                    onsubmit="return confirm('Bu etkinliği silmek istediğinizden emin misiniz?');">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">Etkinliği
                                        Sil</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        
                        <?php if(session('success')): ?>
                            <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <?php if(session('error')): ?>
                            <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
                        <?php endif; ?>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        
                        <form method="POST" action="<?php echo e(route('service.events.update', $event->id)); ?>"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Etkinlik Başlığı (*)</label>
                                        
                                        <div class="form-check form-switch float-end">
                                            <input class="form-check-input" type="checkbox" role="switch" id="is_important"
                                                name="is_important" value="1"
                                                <?php echo e(old('is_important', $event->is_important) ? 'checked' : ''); ?>>
                                            <label class="form-check-label text-danger fw-bold" for="is_important">
                                                <i class="bi bi-exclamation-circle-fill"></i> Önemli
                                            </label>
                                        </div>
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
                                    </div>

                                    <div class="mb-3">
                                        <label for="event_type" class="form-label">Etkinlik Tipi (*)</label>
                                        <select name="event_type" id="event_type" class="form-select" required
                                            <?php echo e($event->event_type == 'musteri_ziyareti' ? 'disabled' : ''); ?>>
                                            <?php $__currentLoopData = $eventTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(old('event_type', $event->event_type) == $key ? 'selected' : ''); ?>>
                                                    <?php echo e($value); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($event->event_type == 'musteri_ziyareti'): ?>
                                            <input type="hidden" name="event_type" value="<?php echo e($event->event_type); ?>">
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="location" class="form-label">Konum / Yer</label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="<?php echo e(old('location', $event->location)); ?>">
                                    </div>
                                </div>

                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç (*)</label>
                                        <input type="datetime-local" class="form-control" name="start_datetime"
                                            value="<?php echo e(old('start_datetime', $event->start_datetime->format('Y-m-d\TH:i'))); ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="end_datetime" class="form-label">Bitiş (*)</label>
                                        <input type="datetime-local" class="form-control" name="end_datetime"
                                            value="<?php echo e(old('end_datetime', $event->end_datetime->format('Y-m-d\TH:i'))); ?>"
                                            required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Açıklama</label>
                                        <textarea class="form-control" name="description" rows="3"><?php echo e(old('description', $event->description)); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="card border-0 bg-white bg-opacity-50 rounded-3 p-3">
                                        <label class="form-label fw-bold mb-3"><i class="bi bi-paperclip"></i> Ek
                                            Dosyalar</label>

                                        
                                        <?php $attachments = $event->getMedia('event_attachments'); ?>
                                        <?php if($attachments->count() > 0): ?>
                                            <div class="row g-2 mb-3">
                                                <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-4">
                                                        <div
                                                            class="d-flex align-items-center p-2 bg-light rounded existing-attachment">
                                                            <i class="bi bi-file-earmark-text me-2 fs-5"></i>
                                                            <span class="text-truncate small flex-grow-1"
                                                                title="<?php echo e($media->file_name); ?>"><?php echo e($media->file_name); ?></span>
                                                            
                                                            <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                                class="btn btn-sm btn-link text-primary p-0 mx-2"><i
                                                                    class="bi bi-eye"></i></a>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>

                                        
                                        <input type="file" name="attachments[]" class="form-control" multiple
                                            accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx">
                                        <div class="form-text small">Yeni dosyalar eklemek için seçiniz. (Max 20MB)</div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="crm-update-section mt-4 p-3 rounded-3">
                                <h5 class="text-primary mb-3"><i class="bi bi-check-circle-fill"></i> Durum Bilgileri</h5>
                                <div class="row">
                                    <div
                                        class="<?php echo e($event->event_type == 'musteri_ziyareti' ? 'col-md-6' : 'col-12'); ?> mb-3">
                                        <label for="visit_status" class="form-label fw-bold">Durum (*)</label>
                                        <select name="visit_status" id="visit_status" class="form-select">
                                            <option value="planlandi"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'planlandi' ? 'selected' : ''); ?>>
                                                ⏳ Planlandı</option>
                                            <option value="gerceklesti"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'gerceklesti' ? 'selected' : ''); ?>>
                                                ✅ Gerçekleşti</option>
                                            <option value="ertelendi"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'ertelendi' ? 'selected' : ''); ?>>
                                                📅 Ertelendi</option>
                                            <option value="iptal"
                                                <?php echo e(old('visit_status', $event->visit_status) == 'iptal' ? 'selected' : ''); ?>>
                                                ❌ İptal Edildi</option>
                                        </select>
                                    </div>
                                    <div class="col-12" id="reason-row" style="display: none;">
                                        <label for="cancellation_reason" class="form-label" id="reason-label">Açıklama /
                                            Not</label>
                                        <textarea name="cancellation_reason" id="cancellation_reason" class="form-control" rows="3"><?php echo e(old('cancellation_reason', $event->cancellation_reason)); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-12 mt-4">
                                <?php if (isset($component)) { $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00 = $component; } ?>
<?php $component = App\View\Components\DynamicFields::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('dynamic-fields'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\DynamicFields::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['model' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\App\Models\Event::class),'entity' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00)): ?>
<?php $component = $__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00; ?>
<?php unset($__componentOriginal560f029fe080d8d8e90f45a1a078f632c53e6b00); ?>
<?php endif; ?>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit"
                                    class="btn btn-animated-gradient rounded-pill px-5 py-2">Değişiklikleri Kaydet</button>
                                <a href="<?php echo e(route('service.events.index')); ?>"
                                    class="btn btn-outline-secondary rounded-pill px-4 ms-2">İptal</a>
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
            const visitStatusDropdown = document.getElementById('visit_status');
            const reasonRow = document.getElementById('reason-row');
            const reasonLabel = document.getElementById('reason-label');
            const reasonInput = document.getElementById('cancellation_reason');

            if (!visitStatusDropdown || !reasonRow) return;

            function toggleReasonField() {
                const status = visitStatusDropdown.value;
                if (status === 'planlandi') {
                    reasonRow.style.display = 'none';
                    reasonInput.required = false;
                } else {
                    reasonRow.style.display = 'block';
                    if (status === 'iptal') {
                        reasonLabel.textContent = 'İptal Sebebi (Zorunlu)';
                        reasonInput.required = true;
                    } else if (status === 'ertelendi') {
                        reasonLabel.textContent = 'Erteleme Sebebi (Zorunlu)';
                        reasonInput.required = true;
                    } else {
                        reasonLabel.textContent = 'Sonuç Notları / Rapor (Opsiyonel)';
                        reasonInput.required = false;
                    }
                }
            }

            toggleReasonField();
            visitStatusDropdown.addEventListener('change', toggleReasonField);
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/service/events/edit.blade.php ENDPATH**/ ?>