@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="name" class="form-label">Müşteri Unvanı (*)</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                value="{{ old('name', $customer->name ?? '') }}" autocomplete="off" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="contact_person" class="form-label">İlgili Kişi</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person"
                value="{{ old('contact_person', $customer->contact_person ?? '') }} " autocomplete="off">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="email" class="form-label">Email Adresi</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email', $customer->email ?? '') }}" autocomplete="off">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="phone" class="form-label">Telefon Numarası</label>
            <input type="tel" class="form-control" id="phone" name="phone"
                value="{{ old('phone', $customer->phone ?? '') }}" autocomplete="off">
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="address" class="form-label">Adres</label>
    <textarea class="form-control" id="address" name="address" rows="3" autocomplete="off">{{ old('address', $customer->address ?? '') }}</textarea>
</div>
