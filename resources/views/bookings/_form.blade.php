{{-- Bu dosya: resources/views/bookings/_form.blade.php --}}

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-3 mb-3">
        <label for="type" class="form-label">Tip (*)</label>
        <select name="type" id="type" class="form-select" required>
            {{-- $booking değişkeni 'edit' sayfasından, 'null' ise 'create' sayfasından gelir --}}
            <option value="flight" @if (old('type', $booking->type ?? '') == 'flight') selected @endif>✈️ Uçuş</option>
            <option value="hotel" @if (old('type', $booking->type ?? '') == 'hotel') selected @endif>🏨 Otel</option>
            <option value="car_rental" @if (old('type', $booking->type ?? '') == 'car_rental') selected @endif>🚗 Araç Kiralama</option>
            <option value="train" @if (old('type', $booking->type ?? '') == 'train') selected @endif>🚆 Tren</option>
            <option value="other" @if (old('type', $booking->type ?? '') == 'other') selected @endif>Diğer</option>
        </select>
    </div>
    <div class="col-md-5 mb-3">
        <label for="provider_name" class="form-label">Sağlayıcı (*)</label>
        <input type="text" name="provider_name" id="provider_name" class="form-control"
            value="{{ old('provider_name', $booking->provider_name ?? '') }}"
            placeholder="Örn: Türk Hava Yolları, Hilton..." required>
    </div>
    <div class="col-md-4 mb-3">
        <label for="confirmation_code" class="form-label">Rezervasyon Kodu (PNR vb.)</label>
        <input type="text" name="confirmation_code" id="confirmation_code" class="form-control"
            value="{{ old('confirmation_code', $booking->confirmation_code ?? '') }}" placeholder="Örn: ABC123">
    </div>
</div>
<div class="row">
    <div class="col-md-3 mb-3">
        <label for="start_datetime" class="form-label">Başlangıç / Kalkış (*)</label>
        {{-- Tarih formatlamasını 'datetime-local' için düzelt --}}
        <input type="datetime-local" ...
            value="{{ old('start_datetime', isset($booking) && $booking->start_datetime ? \Carbon\Carbon::parse($booking->start_datetime)->format('Y-m-d\TH:i') : '') }}"
            required>
    </div>
    <div class="col-md-3 mb-3">
        <label for="end_datetime" class="form-label">Bitiş / Varış</label>
        <input type="datetime-local" ...
            value="{{ old('end_datetime', isset($booking) && $booking->end_datetime ? \Carbon\Carbon::parse($booking->end_datetime)->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-2 mb-3">
        <label for="cost" class="form-label">Masraf (TL)</label>
        <input type="number" step="0.01" name="cost" id="cost" class="form-control"
            value="{{ old('cost', $booking->cost ?? '') }}" placeholder="0.00">
    </div>
    <div class="col-md-4 mb-3">
        <label for="booking_files" class="form-label">Bilet / Voucher (PDF, JPG...)</label>
        <input type="file" name="booking_files[]" id="booking_files" class="form-control" multiple>
        <small class="form-text text-muted">Yeni dosya seçmek, eskilerin üzerine eklenir (eskiler silinmez).</small>
    </div>
</div>
<div class="mb-3">
    <label for="notes" class="form-label">Notlar</label>
    <textarea name="notes" id="notes" class="form-control" rows="2"
        placeholder="Örn: 1 adet kabin bagajı dahil...">{{ old('notes', $booking->notes ?? '') }}</textarea>
</div>

{{-- EĞER DÜZENLEME MODUNDAYSAK (Edit), MEVCUT DOSYALARI GÖSTER --}}
@if (isset($booking) && $booking->exists)
    <div class="mb-3">
        <h6><i class="fa-solid fa-paperclip me-2"></i> Mevcut Dosyalar</h6>
        @forelse($booking->getMedia('attachments') as $media)
            <div class="file-list-item"
                style="display: flex; align-items: center; justify-content: space-between; padding: 0.2rem 0.5rem; background-color: #f1f3f5; border-radius: 0.25rem; margin-bottom: 0.2rem;">
                <span>
                    <i class="fa-solid fa-file me-2"></i>{{ $media->file_name }} ({{ $media->human_readable_size }})
                </span>
                <a href="{{ $media->getUrl() }}" target="_blank"
                    class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0">Görüntüle</a>
                {{-- İsteğe bağlı: Dosya silme butonu buraya eklenebilir --}}
            </div>
        @empty
            <p class="text-muted small">Bu rezervasyona ait dosya bulunmuyor.</p>
        @endforelse
    </div>
@endif
