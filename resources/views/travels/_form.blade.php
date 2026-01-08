{{-- Hata Mesajları --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="create-event-card">
    {{-- BAŞLIK ALANI (Controller 'name' beklediği için 'name' kullanıyoruz) --}}
    <div class="mb-4">
        <label for="name" class="form-label">
            <i class="fa-solid fa-flag me-1 text-primary"></i> Seyahat Başlığı / Tanımı
        </label>
        <input type="text" name="name" id="name" class="form-control form-control-lg"
            value="{{ old('name', $travel->name ?? '') }}" placeholder="Örn: Ankara Müşteri Ziyareti" required>
    </div>

    {{-- TARİHLER VE SAATLER (Controller ayrı ayrı beklediği için ayırdık) --}}
    <div class="row mb-4">
        {{-- BAŞLANGIÇ --}}
        <div class="col-md-6">
            <label class="form-label fw-bold text-success">
                <i class="fa-regular fa-calendar me-1"></i> Başlangıç Zamanı
            </label>
            <div class="input-group">
                {{-- Başlangıç Tarihi --}}
                <input type="date" name="start_date" class="form-control"
                    value="{{ old('start_date', isset($travel->start_date) ? \Carbon\Carbon::parse($travel->start_date)->format('Y-m-d') : '') }}"
                    required>
                {{-- Başlangıç Saati --}}
                <input type="time" name="start_time" class="form-control"
                    value="{{ old('start_time', isset($travel->start_time) ? \Carbon\Carbon::parse($travel->start_time)->format('H:i') : '') }}"
                    required>
            </div>
            <small class="text-muted">Tarih ve Saat</small>
        </div>

        {{-- BİTİŞ --}}
        <div class="col-md-6">
            <label class="form-label fw-bold text-danger">
                <i class="fa-regular fa-calendar-check me-1"></i> Bitiş Zamanı
            </label>
            <div class="input-group">
                {{-- Bitiş Tarihi --}}
                <input type="date" name="end_date" class="form-control"
                    value="{{ old('end_date', isset($travel->end_date) ? \Carbon\Carbon::parse($travel->end_date)->format('Y-m-d') : '') }}"
                    required>
                {{-- Bitiş Saati --}}
                <input type="time" name="end_time" class="form-control"
                    value="{{ old('end_time', isset($travel->end_time) ? \Carbon\Carbon::parse($travel->end_time)->format('H:i') : '') }}"
                    required>
            </div>
            <small class="text-muted">Tarih ve Saat</small>
        </div>
    </div>

    {{-- DURUM VE ÖNEM --}}
    <div class="row align-items-center">
        {{-- Durum Seçimi --}}
        <div class="col-md-6">
            <label for="status" class="form-label">
                <i class="fa-solid fa-list-check me-1 text-info"></i> Durum
            </label>
            <select name="status" id="status" class="form-select">
                <option value="planned" {{ old('status', $travel->status ?? '') == 'planned' ? 'selected' : '' }}>
                    Planlandı</option>
                <option value="completed" {{ old('status', $travel->status ?? '') == 'completed' ? 'selected' : '' }}>
                    Tamamlandı</option>
                {{-- Controller validasyonunda sadece 'planned' ve 'completed' vardı, diğerlerini kaldırdım hata vermesin diye --}}
            </select>
        </div>

        {{-- Önemli Switch --}}
        <div class="col-md-6">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" role="switch" id="is_important" name="is_important"
                    value="1" {{ old('is_important', $travel->is_important ?? false) ? 'checked' : '' }}>
                <label class="form-check-label fw-bold text-danger ms-2" for="is_important">
                    <i class="fa-solid fa-star me-1"></i> Önemli / Acil
                </label>
            </div>
            <small class="text-muted d-block mt-1">İşaretlenirse listelerde öne çıkarılır.</small>
        </div>
    </div>
</div>
