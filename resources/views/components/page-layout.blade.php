@props([
    'title', // Sayfa Başlığı (Örn: Odalar)
    'createRoute' => null, // Yeni Ekle butonunun gideceği link
    'createLabel' => 'Yeni Ekle', // Buton yazısı
    'count' => 0, // Toplam kayıt sayısı (Badge için)
])

<div class="mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

        <div class="d-flex align-items-center gap-3">
            <h2 class="h4 fw-bold text-dark mb-0">{{ $title }}</h2>
            @if ($count > 0)
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                    {{ $count }} Kayıt
                </span>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            {{ $filters ?? '' }}

            @if ($createRoute)
                <x-action-button type="create" href="{{ $createRoute }}" text="{{ $createLabel }}" />
            @endif
        </div>
    </div>

    <div class="row row-cols-1 g-4">
        {{ $slot }}
    </div>

    @if (isset($pagination))
        <div class="mt-4">
            {{ $pagination }}
        </div>
    @endif
</div>
