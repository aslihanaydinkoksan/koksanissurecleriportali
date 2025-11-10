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
    <label for="name" class="form-label">Seyahat Adı (*)</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
        value="{{ old('name', $travel->name ?? '') }}" required>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="start_date" class="form-label">Başlangıç Tarihi (*)</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                name="start_date" value="{{ old('start_date', $travel->start_date ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="end_date" class="form-label">Bitiş Tarihi (*)</label>
            <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                name="end_date" value="{{ old('end_date', $travel->end_date ?? '') }}" required>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Durum (*)</label>
    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
        <option value="planned" @if (old('status', $travel->status ?? '') == 'planned') selected @endif>
            Planlandı
        </option>
        <option value="completed" @if (old('status', $travel->status ?? '') == 'completed') selected @endif>
            Tamamlandı
        </option>
    </select>
</div>
