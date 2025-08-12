@props([
    'name' => 'line',
    'view_path' => 'components.line',
    'settings' => json_encode([
        'height' => '2px',
        'color_class' => '#2e3132',
        'width' => '80%',
        'style-type' => 'solid',
    ]),
])

{{-- Ensure $settings is an array and merge defaults --}}
@php
    $settings = is_array($settings ?? null) ? $settings : json_decode($settings ?? '{}', true);
    $settings = array_merge([
        'height' => '2px',
        'color_class' => '#2e3132',
        'width' => '80%',
        'style-type' => 'solid',
    ], $settings ?? []);
@endphp

<div class="flex items-center justify-center m-16">
    <div
        class="border-0"
        style="
            width: {{ $settings['width'] }};
            height: {{ $settings['height'] }};
            background-color: {{ $settings['color_class'] }};
            border-style: {{ $settings['style-type'] }};
        "
    ></div>
</div>