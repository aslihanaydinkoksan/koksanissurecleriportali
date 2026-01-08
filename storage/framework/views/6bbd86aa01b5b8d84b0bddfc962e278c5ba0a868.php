<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="type" class="form-label">Tip (*)</label>
        <select name="type" id="type" class="form-select" required>
            
            <option value="flight" <?php if(old('type', $booking->type ?? '') == 'flight'): ?> selected <?php endif; ?>>✈️ Uçuş</option>
            <option value="bus" <?php echo e(old('type', $booking->type ?? '') == 'bus' ? 'selected' : ''); ?>>
                🚌 Otobüs
            </option>
            <option value="hotel" <?php if(old('type', $booking->type ?? '') == 'hotel'): ?> selected <?php endif; ?>>🏨 Otel</option>
            <option value="car_rental" <?php if(old('type', $booking->type ?? '') == 'car_rental'): ?> selected <?php endif; ?>>🚗 Araç Kiralama</option>
            <option value="train" <?php if(old('type', $booking->type ?? '') == 'train'): ?> selected <?php endif; ?>>🚆 Tren</option>
            <option value="other" <?php if(old('type', $booking->type ?? '') == 'other'): ?> selected <?php endif; ?>>Diğer</option>
        </select>
    </div>
    <div class="col-md-5 mb-3">
        <label for="provider_name" class="form-label">Sağlayıcı (*)</label>
        <input type="text" name="provider_name" id="provider_name" class="form-control"
            value="<?php echo e(old('provider_name', $booking->provider_name ?? '')); ?>"
            placeholder="Örn: Türk Hava Yolları, Hilton..." required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="confirmation_code" class="form-label">Rezervasyon Kodu (TK Numarası vb.)</label>
        <input type="text" name="confirmation_code" id="confirmation_code" class="form-control"
            value="<?php echo e(old('confirmation_code', $booking->confirmation_code ?? '')); ?>" placeholder="Örn: ABC123">
    </div>
</div>
<?php
    // Başlangıç tarihi için değer belirleme
    $startValue = '';
    if (old('start_datetime')) {
        // Doğrulama hatası varsa eski değeri koru
        $startValue = old('start_datetime');
    } elseif (isset($booking) && $booking->start_datetime) {
        // Booking varsa ve tarih doluysa, formatla
        $startValue = \Carbon\Carbon::parse($booking->start_datetime)->format('Y-m-d\TH:i');
    }

    // Bitiş tarihi için değer belirleme
    $endValue = '';
    if (old('end_datetime')) {
        $endValue = old('end_datetime');
    } elseif (isset($booking) && $booking->end_datetime) {
        $endValue = \Carbon\Carbon::parse($booking->end_datetime)->format('Y-m-d\TH:i');
    }
?>
<div class="row">
    <div class="col-md-3 mb-3">
        <label for="start_datetime" class="form-label">Başlangıç / Kalkış (*)</label>
        <input type="datetime-local" name="start_datetime" class="form-control" value="<?php echo e($startValue); ?>">
    </div>

    <div class="col-md-3 mb-3">
        <label for="end_datetime" class="form-label">Bitiş / Varış</label>
        <input type="datetime-local" name="end_datetime" class="form-control" value="<?php echo e($endValue); ?>">
    </div>
    <div class="col-md-4 mb-3">
        <label for="booking_files" class="form-label">Bilet / Voucher (PDF, JPG...)</label>
        <input type="file" name="booking_files[]" id="booking_files" class="form-control" multiple>
        <small class="form-text text-muted">Yeni dosya seçmek, eskilerin üzerine eklenir (eskiler silinmez).</small>
    </div>
    
    <?php if(isset($booking)): ?>
        <div class="col-md-6">
            <label for="status" class="form-label fw-bold text-dark">
                <i class="fa-solid fa-flag me-1 text-primary"></i> Rezervasyon Durumu
            </label>
            <select name="status" id="status" class="form-select">
                <option value="planned" <?php echo e(old('status', $booking->status) == 'planned' ? 'selected' : ''); ?>>⏳ Planlandı
                </option>
                <option value="completed" <?php echo e(old('status', $booking->status) == 'completed' ? 'selected' : ''); ?>>✅
                    Gerçekleşti</option>
                <option value="cancelled" <?php echo e(old('status', $booking->status) == 'cancelled' ? 'selected' : ''); ?>>❌ İptal
                    Edildi</option>
                <option value="postponed" <?php echo e(old('status', $booking->status) == 'postponed' ? 'selected' : ''); ?>>📅
                    Ertelendi</option>
            </select>
        </div>
    <?php endif; ?>
</div>
<div class="mb-3">
    <label for="notes" class="form-label">Notlar</label>
    <textarea name="notes" id="notes" class="form-control" rows="2"
        placeholder="Örn: 1 adet kabin bagajı dahil..."><?php echo e(old('notes', $booking->notes ?? '')); ?></textarea>
</div>



<?php if(isset($booking) && $booking->exists): ?>
    <div class="mb-3">
        <h6><i class="fa-solid fa-paperclip me-2"></i> Mevcut Dosyalar</h6>
        <?php $__empty_1 = true; $__currentLoopData = $booking->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="file-list-item"
                style="display: flex; align-items: center; justify-content: space-between; padding: 0.2rem 0.5rem; background-color: #f1f3f5; border-radius: 0.25rem; margin-bottom: 0.2rem;">
                <span>
                    <i class="fa-solid fa-file me-2"></i><?php echo e($media->file_name); ?> (<?php echo e($media->human_readable_size); ?>)
                </span>
                <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-muted small">Bu rezervasyona ait dosya bulunmuyor.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/bookings/_form.blade.php ENDPATH**/ ?>