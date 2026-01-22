<?php if($errors->any()): ?>
    <div class="alert alert-danger mb-3 border-0 shadow-sm">
        <ul class="mb-0">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><i class="fa-solid fa-circle-exclamation me-2"></i><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    
    <div class="col-md-3 mb-3">
        <label for="type" class="form-label fw-bold text-dark">Tip (*)</label>
        <select name="type" id="type" class="form-select border-primary" required onchange="toggleBookingFields()">
            <option value="flight" <?php echo e(old('type', $booking->type ?? '') == 'flight' ? 'selected' : ''); ?>>✈️ Uçak Bileti
            </option>
            <option value="hotel" <?php echo e(old('type', $booking->type ?? '') == 'hotel' ? 'selected' : ''); ?>>🏨 Otel
                Konaklama</option>
            <option value="bus" <?php echo e(old('type', $booking->type ?? '') == 'bus' ? 'selected' : ''); ?>>🚌 Otobüs /
                Transfer</option>
            <option value="train" <?php echo e(old('type', $booking->type ?? '') == 'train' ? 'selected' : ''); ?>>🚆 Tren</option>
            <option value="car_rental" <?php echo e(old('type', $booking->type ?? '') == 'car_rental' ? 'selected' : ''); ?>>🚗 Araç
                Kiralama</option>
            <option value="other" <?php echo e(old('type', $booking->type ?? '') == 'other' ? 'selected' : ''); ?>>📋 Diğer
            </option>
        </select>
    </div>

    
    <div class="col-md-5 mb-3">
        <label for="provider_name" class="form-label fw-bold text-dark">Sağlayıcı (*)</label>
        <input type="text" name="provider_name" id="provider_name" class="form-control"
            value="<?php echo e(old('provider_name', $booking->provider_name ?? '')); ?>"
            placeholder="Örn: Türk Hava Yolları, Hilton, Metro Turizm..." required>
    </div>

    
    <div class="col-md-4 mb-3">
        <label for="confirmation_code" class="form-label fw-bold text-dark">Onay Kodu (PNR / Rez. No)</label>
        <input type="text" name="confirmation_code" id="confirmation_code" class="form-control"
            value="<?php echo e(old('confirmation_code', $booking->confirmation_code ?? '')); ?>" placeholder="Örn: ABC123">
    </div>
</div>


<div class="row d-none" id="transport-fields">
    <div class="col-md-6 mb-3">
        <label for="origin" class="form-label fw-bold text-primary">Nereden (Kalkış)</label>
        <div class="input-group">
            <span class="input-group-text bg-primary text-white border-0"><i
                    class="fa-solid fa-plane-departure"></i></span>
            <input type="text" name="origin" id="origin" class="form-control border-primary"
                value="<?php echo e(old('origin', $booking->origin ?? '')); ?>" placeholder="Şehir veya Havalimanı">
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="destination" class="form-label fw-bold text-success">Nereye (Varış)</label>
        <div class="input-group">
            <span class="input-group-text bg-success text-white border-0"><i
                    class="fa-solid fa-plane-arrival"></i></span>
            <input type="text" name="destination" id="destination" class="form-control border-success"
                value="<?php echo e(old('destination', $booking->destination ?? '')); ?>" placeholder="Hedef Şehir">
        </div>
    </div>
</div>


<div class="row d-none" id="location-fields">
    <div class="col-md-12 mb-3">
        <label for="location" class="form-label fw-bold text-info">Lokasyon / Adres</label>
        <div class="input-group">
            <span class="input-group-text bg-info text-white border-0"><i class="fa-solid fa-location-dot"></i></span>
            <input type="text" name="location" id="location_input" class="form-control border-info"
                value="<?php echo e(old('location', $booking->location ?? '')); ?>"
                placeholder="Örn: Taksim, İstanbul veya Otel Tam Adresi">
        </div>
    </div>
</div>

<?php
    $startValue = old(
        'start_datetime',
        isset($booking) && $booking->start_datetime
            ? \Carbon\Carbon::parse($booking->start_datetime)->format('Y-m-d\TH:i')
            : '',
    );
    $endValue = old(
        'end_datetime',
        isset($booking) && $booking->end_datetime
            ? \Carbon\Carbon::parse($booking->end_datetime)->format('Y-m-d\TH:i')
            : '',
    );
?>

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="start_datetime" class="form-label fw-bold text-dark">Başlangıç / Kalkış (*)</label>
        <input type="datetime-local" name="start_datetime" class="form-control" value="<?php echo e($startValue); ?>" required>
    </div>

    <div class="col-md-3 mb-3">
        <label for="end_datetime" class="form-label fw-bold text-dark">Bitiş / Varış</label>
        <input type="datetime-local" name="end_datetime" class="form-control" value="<?php echo e($endValue); ?>">
    </div>

    
    <div class="col-md-4 mb-3">
        <label class="form-label fw-bold text-dark">Tutar</label>
        <div class="input-group">
            <input type="number" step="0.01" name="cost" class="form-control"
                value="<?php echo e(old('cost', $booking->cost ?? '')); ?>" placeholder="0.00">
            <select name="currency" class="form-select" style="max-width: 100px;" required>
                <option value="TRY" <?php echo e(old('currency', $booking->currency ?? 'TRY') == 'TRY' ? 'selected' : ''); ?>>₺
                    TRY</option>
                <option value="USD" <?php echo e(old('currency', $booking->currency ?? '') == 'USD' ? 'selected' : ''); ?>>$
                    USD</option>
                <option value="EUR" <?php echo e(old('currency', $booking->currency ?? '') == 'EUR' ? 'selected' : ''); ?>>€
                    EUR</option>
                <option value="GBP" <?php echo e(old('currency', $booking->currency ?? '') == 'GBP' ? 'selected' : ''); ?>>£
                    GBP</option>
            </select>
        </div>
    </div>

    <div class="col-md-2 mb-3">
        <label for="booking_files" class="form-label fw-bold text-dark">Bilet / Voucher</label>
        <input type="file" name="booking_files[]" id="booking_files" class="form-control" multiple>
    </div>
</div>

<div class="row align-items-end">
    <div class="col-md-8 mb-3">
        <label for="notes" class="form-label fw-bold text-dark">Notlar</label>
        <textarea name="notes" id="notes" class="form-control" rows="2"
            placeholder="Koltuk numarası, yemek tercihi vb."><?php echo e(old('notes', $booking->notes ?? '')); ?></textarea>
    </div>

    <?php if(isset($booking) && $booking->exists): ?>
        <div class="col-md-4 mb-3">
            <label for="status" class="form-label fw-bold text-dark">
                <i class="fa-solid fa-flag me-1 text-primary"></i> Durum
            </label>
            <select name="status" id="status" class="form-select">
                <option value="planned" <?php echo e(old('status', $booking->status) == 'planned' ? 'selected' : ''); ?>>⏳
                    Planlandı</option>
                <option value="completed" <?php echo e(old('status', $booking->status) == 'completed' ? 'selected' : ''); ?>>✅
                    Gerçekleşti</option>
                <option value="cancelled" <?php echo e(old('status', $booking->status) == 'cancelled' ? 'selected' : ''); ?>>❌
                    İptal</option>
                <option value="postponed" <?php echo e(old('status', $booking->status) == 'postponed' ? 'selected' : ''); ?>>📅
                    Ertelendi</option>
            </select>
        </div>
    <?php endif; ?>
</div>


<?php if(isset($booking) && $booking->exists): ?>
    <div class="mb-3">
        <h6 class="fw-bold"><i class="fa-solid fa-paperclip me-2"></i> Mevcut Dosyalar</h6>
        <div class="d-flex flex-wrap gap-2">
            <?php $__empty_1 = true; $__currentLoopData = $booking->getMedia('attachments'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-2 border rounded bg-light d-flex align-items-center shadow-sm">
                    <span class="small me-2 text-truncate" style="max-width: 150px;"><?php echo e($media->file_name); ?></span>
                    <a href="<?php echo e($media->getUrl()); ?>" target="_blank"
                        class="btn btn-sm btn-primary py-0 px-2 rounded-pill">Aç</a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted small">Dosya bulunmuyor.</p>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
    function toggleBookingFields() {
        const typeSelector = document.getElementById('type');
        if (!typeSelector) return;

        const type = typeSelector.value;
        const transportSection = document.getElementById('transport-fields');
        const locationSection = document.getElementById('location-fields');
        const locationInput = document.getElementById('location_input');

        // Ulaşım Türleri: Uçak, Otobüs, Tren
        if (['flight', 'bus', 'train'].includes(type)) {
            transportSection.classList.remove('d-none');
            locationSection.classList.add('d-none');
            if (locationInput) locationInput.removeAttribute('required');
        }
        // Konaklama ve Diğerleri: Otel, Araç Kiralama, Diğer
        else {
            transportSection.classList.add('d-none');
            locationSection.classList.remove('d-none');
            // Otel ise konumu zorunlu yap (opsiyonel, validasyona yardımcı olur)
            if (type === 'hotel' && locationInput) {
                locationInput.setAttribute('required', 'required');
            } else if (locationInput) {
                locationInput.removeAttribute('required');
            }
        }
    }

    // Hem yüklemede hem de tip değiştiğinde çalıştır
    document.addEventListener('DOMContentLoaded', toggleBookingFields);
</script>
<?php /**PATH C:\xampp82\htdocs\koksanissurecleriportali\resources\views/bookings/_form.blade.php ENDPATH**/ ?>