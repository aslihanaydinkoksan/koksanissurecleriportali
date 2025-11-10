@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label for="name" class="form-label">Departman Adı (*)</label>
    <input type="text" class="form-control" id="name" name="name"
        value="{{ old('name', $department->name ?? '') }}" placeholder="Örn: Lojistik Departmanı" required>
</div>
<div class="mb-3">
    <label for="slug" class="form-label">Kısa Kod (slug) (*)</label>
    <input type="text" class="form-control" id="slug" name="slug"
        value="{{ old('slug', $department->slug ?? '') }}" placeholder="Örn: lojistik (Boşluksuz, küçük harf)"
        @if (isset($isCore) && $isCore) readonly 
               style="background-color: #e9ecef;"
               title="Ana sistem departmanlarının kısa kodu değiştirilemez." @endif
        required>
    <small class="form-text text-muted">Bu kod, sistemdeki yetkilendirmeler için kullanılır (örn: 'lojistik', 'uretim').
        Değiştirilmesi önerilmez.</small>
</div>
