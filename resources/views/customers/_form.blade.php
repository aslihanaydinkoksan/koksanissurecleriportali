@if ($errors->any())
    <div class="alert alert-danger d-flex align-items-start" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-3 fs-4 mt-1"></i>
        <div class="flex-grow-1">
            <strong>Lütfen aşağıdaki hataları düzeltin:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Müşteri Ana Bilgileri --}}
<div class="row g-3">
    <div class="col-md-12">
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $customer->name ?? '') }}" placeholder="Müşteri Unvanı" autocomplete="off"
                required>
            <label for="name">
                <i class="fa-solid fa-building me-2 text-primary"></i>Müşteri Unvanı <span class="text-danger">*</span>
            </label>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email', $customer->email ?? '') }}" placeholder="Genel Email Adresi"
                autocomplete="off">
            <label for="email">
                <i class="fa-solid fa-envelope me-2 text-primary"></i>Genel Email Adresi
            </label>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="tel" class="form-control" id="phone" name="phone"
                value="{{ old('phone', $customer->phone ?? '') }}" placeholder="Genel Telefon Numarası"
                autocomplete="off">
            <label for="phone">
                <i class="fa-solid fa-phone me-2 text-primary"></i>Genel Telefon Numarası
            </label>
        </div>
    </div>
</div>

<div class="form-floating mb-4">
    <textarea class="form-control" id="address" name="address" placeholder="Adres" style="height: 120px"
        autocomplete="off">{{ old('address', $customer->address ?? '') }}</textarea>
    <label for="address">
        <i class="fa-solid fa-location-dot me-2 text-primary"></i>Adres
    </label>
</div>

{{-- DİNAMİK İLETİŞİM KİŞİLERİ ALANI --}}
<div class="card border-0 shadow-sm mb-4 bg-light">
    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-users me-2"></i>İletişim Kişileri</h6>
        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill bg-white" onclick="addContactRow()">
            <i class="fa-solid fa-plus me-1"></i> Kişi Ekle
        </button>
    </div>
    <div class="card-body p-3">
        <div id="contacts-container">
            @if (isset($customer) && $customer->contacts->count() > 0)
                {{-- Düzenleme Modu: Mevcut Kişileri Listele --}}
                @foreach ($customer->contacts as $index => $contact)
                    <div class="contact-row row g-2 mb-3 align-items-center" id="contact-row-{{ $index }}">
                        <input type="hidden" name="contacts[{{ $index }}][id]" value="{{ $contact->id }}">

                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm"
                                    name="contacts[{{ $index }}][name]" value="{{ $contact->name }}"
                                    placeholder="Ad Soyad" required>
                                <label class="fs-7">Ad Soyad</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="text" class="form-control form-control-sm"
                                    name="contacts[{{ $index }}][title]" value="{{ $contact->title }}"
                                    placeholder="Ünvan">
                                <label class="fs-7">Ünvan</label>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="email" class="form-control form-control-sm"
                                    name="contacts[{{ $index }}][email]" value="{{ $contact->email }}"
                                    placeholder="Email">
                                <label class="fs-7">Email</label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-floating">
                                <input type="tel" class="form-control form-control-sm"
                                    name="contacts[{{ $index }}][phone]" value="{{ $contact->phone }}"
                                    placeholder="Telefon">
                                <label class="fs-7">Telefon</label>
                            </div>
                        </div>

                        <div class="col-md-1 text-center">
                            <button type="button" class="btn btn-sm btn-outline-danger border-0"
                                onclick="removeContactRow(this)" title="Sil">
                                <i class="fa-solid fa-trash fa-lg"></i>
                                <input type="hidden" name="contacts[{{ $index }}][delete]" value="0"
                                    class="delete-flag">
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Yeni Kayıt Modu veya Kişi Yoksa: Boş Bir Satır Göster --}}
                <div class="contact-row row g-2 mb-3 align-items-center">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" name="contacts[0][name]"
                                placeholder="Ad Soyad" required>
                            <label class="fs-7">Ad Soyad</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="text" class="form-control form-control-sm" name="contacts[0][title]"
                                placeholder="Ünvan">
                            <label class="fs-7">Ünvan</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="email" class="form-control form-control-sm" name="contacts[0][email]"
                                placeholder="Email">
                            <label class="fs-7">Email</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <input type="tel" class="form-control form-control-sm" name="contacts[0][phone]"
                                placeholder="Telefon">
                            <label class="fs-7">Telefon</label>
                        </div>
                    </div>
                    <div class="col-md-1 text-center">
                        {{-- İlk satırın silinmesini istemiyorsan butonu kaldırabilirsin, ama esneklik için kalsın --}}
                        <button type="button" class="btn btn-sm btn-outline-danger border-0"
                            onclick="this.closest('.contact-row').remove()">
                            <i class="fa-solid fa-trash fa-lg"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="alert alert-info border-0 d-flex align-items-center" role="alert">
    <i class="fa-solid fa-info-circle me-3 fs-4"></i>
    <div>
        <strong>Not:</strong> (<span class="text-danger">*</span>) işareti olan alanlar zorunludur.
    </div>
</div>

<style>
    .form-floating>.form-control,
    .form-floating>.form-select {
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 0.75rem;
        padding: 1rem 0.75rem;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.9);
    }

    .form-floating>.form-control:focus,
    .form-floating>.form-select:focus {
        border-color: #667EEA;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        background-color: white;
    }

    .form-floating>label {
        padding: 1rem 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Kişi listesi içindeki inputlar için label ayarı */
    .contact-row .form-floating>label.fs-7 {
        padding: 0.6rem 0.75rem;
        font-size: 0.85rem;
    }

    .contact-row .form-floating>.form-control {
        height: 3.5rem;
        padding-top: 1.25rem;
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .alert-info {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .alert-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border-radius: 0.75rem;
        padding: 1rem;
        border: none;
    }

    .alert-danger ul {
        padding-left: 1.5rem;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .form-control.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>

<script>
    // Başlangıç indeksini belirle (Mevcut sayısına göre)
    let contactIndex = {{ isset($customer) ? $customer->contacts->count() : 1 }};
    // Eğer hiç kayıt yoksa ve ilk satır (0) elle yazıldıysa, index 1'den başlasın.
    if (contactIndex === 0) contactIndex = 1;

    function addContactRow() {
        const container = document.getElementById('contacts-container');
        const html = `
        <div class="contact-row row g-2 mb-3 align-items-center">
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][name]" placeholder="Ad Soyad" required>
                    <label class="fs-7">Ad Soyad</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="text" class="form-control form-control-sm" name="contacts[${contactIndex}][title]" placeholder="Ünvan">
                    <label class="fs-7">Ünvan</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-floating">
                    <input type="email" class="form-control form-control-sm" name="contacts[${contactIndex}][email]" placeholder="Email">
                    <label class="fs-7">Email</label>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-floating">
                    <input type="tel" class="form-control form-control-sm" name="contacts[${contactIndex}][phone]" placeholder="Telefon">
                    <label class="fs-7">Telefon</label>
                </div>
            </div>
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="this.closest('.contact-row').remove()">
                    <i class="fa-solid fa-trash fa-lg"></i>
                </button>
            </div>
        </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        contactIndex++;
    }

    function removeContactRow(btn) {
        const row = btn.closest('.contact-row');
        // Eğer veritabanından gelen bir kayıt ise hidden input ile delete flag set et ve gizle
        const deleteInput = row.querySelector('.delete-flag');
        if (deleteInput) {
            deleteInput.value = 1;
            row.style.display = 'none';
        } else {
            row.remove();
        }
    }
</script>
