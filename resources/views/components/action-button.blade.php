@props([
    'href' => '#',
    'type' => 'edit', // edit, delete, view, create
    'text' => null,
])

@php
    $configs = [
        'edit' => [
            'class' => 'btn-outline-primary',
            'icon' => 'fa-pen-to-square',
            'label' => 'Düzenle',
        ],
        'delete' => [
            // Genellikle form içinde kullanılır ama link versiyonu da olsun
            'class' => 'btn-outline-danger',
            'icon' => 'fa-trash',
            'label' => 'Sil',
        ],
        'view' => [
            'class' => 'btn-outline-secondary',
            'icon' => 'fa-eye',
            'label' => 'Detay',
        ],
        'create' => [
            'class' => 'btn-primary text-white shadow-sm',
            'icon' => 'fa-plus',
            'label' => 'Yeni Ekle',
        ],
    ];

    $config = $configs[$type] ?? $configs['view'];
    $label = $text ?? $config['label'];
@endphp

<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'btn btn-sm fw-medium d-inline-flex align-items-center gap-2 ' . $config['class']]) }}>
    <i class="fa-solid {{ $config['icon'] }}"></i>
    @if ($type === 'create')
        <span>{{ $label }}</span>
    @else
        <span class="d-none d-md-inline">{{ $label }}</span>
    @endif
</a>
