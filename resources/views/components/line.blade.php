@props([
    'name' => 'line',
    'view_path' => 'components.line',
    'settings' => json_encode([
        'height' => '2px',
        'bg_color_class' => '#2e3132',
        'width' => '80%',
        'style-type' => 'solid',
    ]),
])

<div class="flex items-center justify-center m-16">
    <div
        class="border-0"
        style="
            width: {{ $settings['width'] }};
            height: {{ $settings['height'] }};
            background-color: {{ $settings['bg_color_class'] }};
            border-style: {{ $settings['style-type'] }};
        "
    ></div>
</div>