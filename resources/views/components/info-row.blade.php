@props(['label', 'value', 'icon', 'color' => 'secondary'])

@if ($value)
    <div class="d-flex align-items-center p-2 rounded-3 border border-light bg-light bg-opacity-25">
        <div class="d-flex align-items-center text-muted small" style="min-width: 90px;">
            <i class="fa fa-{{ $icon }} me-2 text-{{ $color }} opacity-50"></i> {{ $label }}
        </div>
        <div class="fw-bold text-dark font-monospace small">
            : {{ $value }}
        </div>
    </div>
@endif
