@if (session('success') || session('error') || $errors->any())
    <div class="px-4 mt-3">
        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center mb-0"><i class="fa-solid fa-circle-check me-3 fs-4"></i>
                <div>{{ session('success') }}</div>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-0"><i class="fa-solid fa-circle-xmark me-3 fs-4"></i>
                <div>{{ session('error') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-0">
                <strong><i class="fa-solid fa-triangle-exclamation me-2"></i>Kayıt eklenirken bir hata oluştu:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif