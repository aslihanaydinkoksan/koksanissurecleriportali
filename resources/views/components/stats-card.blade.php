{{-- Dashboard sayfasındaki ikonlu ve renkli kutucuklar. --}}
@props([
    'title',
    'value',
    'icon' => 'fa-chart-bar',
    'color' => 'primary', // primary, success, warning, danger, info
    'link' => null, // Tıklanınca gideceği yer (opsiyonel)
])

@php
    // Renk haritası (CSS değişkenlerine uygun)
    $colors = [
        'primary' => ['text' => 'text-primary', 'bg' => 'bg-primary', 'icon_bg' => '#dbeafe', 'icon_text' => '#2563eb'],
        'success' => ['text' => 'text-success', 'bg' => 'bg-success', 'icon_bg' => '#d1fae5', 'icon_text' => '#10b981'],
        'warning' => ['text' => 'text-warning', 'bg' => 'bg-warning', 'icon_bg' => '#fef3c7', 'icon_text' => '#d97706'],
        'danger' => ['text' => 'text-danger', 'bg' => 'bg-danger', 'icon_bg' => '#fee2e2', 'icon_text' => '#dc2626'],
        'info' => ['text' => 'text-info', 'bg' => 'bg-info', 'icon_bg' => '#e0f2fe', 'icon_text' => '#0891b2'],
    ];

    $theme = $colors[$color] ?? $colors['primary'];
@endphp

<div class="card h-100 border-0 shadow-sm hover-scale transition-base">
    <div class="card-body p-4 d-flex align-items-center justify-content-between">
        <div>
            <p class="text-uppercase text-muted fw-bold mb-1" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ $title }}
            </p>
            <h2 class="mb-0 fw-bold text-dark">{{ $value }}</h2>

            @if ($link)
                <a href="{{ $link }}"
                    class="stretched-link text-decoration-none small mt-2 d-inline-block {{ $theme['text'] }}">
                    Detaylar <i class="fa-solid fa-arrow-right ms-1 text-xs"></i>
                </a>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-center rounded-4"
            style="width: 56px; height: 56px; background-color: {{ $theme['icon_bg'] }}; color: {{ $theme['icon_text'] }};">
            <i class="fa-solid {{ $icon }} fa-xl"></i>
        </div>
    </div>
</div>

<style>
    .hover-scale:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md) !important;
    }

    .transition-base {
        transition: all 0.2s ease;
    }
</style>
