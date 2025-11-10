

<?php $__env->startSection('title', 'Seyahat Detayı'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="customer-card shadow-sm border">
                    <div class="card-header bg-white border-0 px-4 pt-4 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1"><?php echo e($travel->name); ?></h4>
                            <span class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($travel->start_date)->format('d/m/Y')); ?> -
                                <?php echo e(\Carbon\Carbon::parse($travel->end_date)->format('d/m/Y')); ?>

                            </span>
                        </div>
                        <div>
                            <?php if(Auth::id() == $travel->user_id || Auth::user()->can('is-global-manager')): ?>
                                <a href="<?php echo e(route('travels.edit', $travel)); ?>"
                                    class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fa-solid fa-pen me-1"></i> Düzenle
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-body px-4">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success" role="alert"><?php echo e(session('success')); ?></div>
                        <?php endif; ?>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <strong>Kayıt eklenirken bir hata oluştu:</strong>
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        
                        <div class="quick-add-form mb-4"
                            style="background-color: #f8f9fa; border-radius: 0.5rem; padding: 1.5rem; border: 1px solid #e9ecef;">
                            <h5><i class="fa-solid fa-ticket me-2"></i> Bu Seyahate Yeni Rezervasyon Ekle</h5>
                            <form action="<?php echo e(route('travels.bookings.store', $travel)); ?>" method="POST"
                                enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="type" class="form-label">Tip (*)</label>
                                        <select name="type" class="form-select" required>
                                            <option value="flight">✈️ Uçuş</option>
                                            <option value="hotel">🏨 Otel</option>
                                            <option value="car_rental">🚗 Araç Kiralama</option>
                                            <option value="train">🚆 Tren</option>
                                            <option value="other">Diğer</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="provider_name" class="form-label">Sağlayıcı (*)</label>
                                        <input type="text" name="provider_name" class="form-control"
                                            placeholder="Örn: Türk Hava Yolları, Hilton..." required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="confirmation_code" class="form-label">Rezervasyon Kodu (PNR vb.)</label>
                                        <input type="text" name="confirmation_code" class="form-control"
                                            placeholder="Örn: ABC123">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="start_datetime" class="form-label">Başlangıç / Kalkış (*)</label>
                                        <input type="datetime-local" name="start_datetime" class="form-control" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="end_datetime" class="form-label">Bitiş / Varış</label>
                                        <input type="datetime-local" name="end_datetime" class="form-control">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="cost" class="form-label">Masraf (TL)</label>
                                        <input type="number" step="0.01" name="cost" class="form-control"
                                            placeholder="0.00">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="booking_files" class="form-label">Bilet / Voucher (PDF, JPG...)</label>
                                        <input type="file" name="booking_files[]" class="form-control" multiple>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notlar</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="Örn: 1 adet kabin bagajı dahil..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary-gradient px-4"
                                    style="background: linear-gradient(to right, #667EEA, #5a6ed0); color: white;">
                                    Rezervasyonu Ekle
                                </button>
                            </form>
                        </div>

                        <hr class="my-4">

                        
                        <h5><i class="fa-solid fa-list-check me-2"></i> Kayıtlı Rezervasyonlar
                            (<?php echo e($travel->bookings->count()); ?>)</h5>

                        <?php if($travel->bookings->isEmpty()): ?>
                            <div class="alert alert-secondary">Bu seyahat planına ait bir rezervasyon (uçuş, otel vb.) kaydı
                                bulunamadı.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Tip</th>
                                            <th>Sağlayıcı / Detay</th>
                                            <th>Kod</th>
                                            <th>Tarih</th>
                                            <th>Dosyalar</th>
                                            <th>Eylem</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $travel->bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <?php if($booking->type == 'flight'): ?>
                                                        ✈️ Uçuş
                                                    <?php endif; ?>
                                                    <?php if($booking->type == 'hotel'): ?>
                                                        🏨 Otel
                                                    <?php endif; ?>
                                                    <?php if($booking->type == 'car_rental'): ?>
                                                        🚗 Araç
                                                    <?php endif; ?>
                                                    <?php if($booking->type == 'train'): ?>
                                                        🚆 Tren
                                                    <?php endif; ?>
                                                    <?php if($booking->type == 'other'): ?>
                                                        Diğer
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?php echo e($booking->provider_name); ?></strong></td>
                                                <td><?php echo e($booking->confirmation_code); ?></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($booking->start_datetime)->format('d/m/Y H:i')); ?>

                                                </td>
                                                <td>
                                                    
                                                    <?php $__currentLoopData = $booking->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="file-list-item"
                                                            style="display: flex; align-items: center; justify-content: space-between; padding: 0.2rem 0.5rem; background-color: #f1f3f5; border-radius: 0.25rem; margin-bottom: 0.2rem;">
                                                            <span><i
                                                                    class="fa-solid fa-paperclip me-2"></i><?php echo e($media->file_name); ?></span>
                                                            <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                                                                class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td>
                                                    
                                                    <?php if(Auth::id() == $booking->user_id || Auth::user()->can('is-global-manager')): ?>
                                                        <form action="<?php echo e(route('bookings.destroy', $booking)); ?>"
                                                            method="POST"
                                                            onsubmit="return confirm('Bu rezervasyon kaydını silmek istediğinizden emin misiniz?');">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                style="border: none; background: transparent;">
                                                                <i class="fa-solid fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                        <hr class="my-4">
                        <h5>Bu Seyahate Bağlı Ziyaretler</h5>

                        <?php if($travel->customerVisits->isEmpty()): ?>
                            <div class="alert alert-secondary">Bu seyahat planına bağlı bir ziyaret (etkinlik) bulunamadı.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Müşteri</th>
                                            <th>Ziyaret Başlığı (Etkinlik)</th>
                                            <th>Ziyaret Tarihi</th>
                                            <th>Ziyaret Amacı</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $travel->customerVisits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $visit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo e(route('customers.show', $visit->customer)); ?>">
                                                        <?php echo e($visit->customer->name ?? 'Bilinmiyor'); ?>

                                                    </a>
                                                </td>
                                                <td><?php echo e($visit->event->title ?? 'N/A'); ?></td>
                                                <td><?php echo e($visit->event ? \Carbon\Carbon::parse($visit->event->start_datetime)->format('d/m/Y H:i') : '-'); ?>

                                                </td>
                                                <td><?php echo e($visit->visit_purpose); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali\resources\views/travels/show.blade.php ENDPATH**/ ?>