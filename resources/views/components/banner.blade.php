@props([
    'image_url'        => asset('storage/images/Sao-Paulo-Leaf.jpg'),
    'heading'          => 'Welcome to Our Website',
    'subheading'       => 'Explore our features and services.',
    // normalized
    'overlay_color'    => '#000000',   // hex only
    'overlay_opacity'  => 50,          // 0–100
    'height_vh'        => null,        // preferred: numeric vh (e.g., 50)
    'text_color'       => '#ffffff',   // hex only
    // legacy (fallbacks)
    'height_class'     => 'h-[50vh]',
    'text_color_class' => null,
])

@php
    // Overlay color (hex only in UI, but still tolerate legacy tailwind tokens)
    $opacityInt = is_numeric($overlay_opacity) ? max(0, min(100, (int)$overlay_opacity)) : 50;
    $isOverlayHex = is_string($overlay_color) && preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $overlay_color);
    $overlayClass = '';
    $overlayStyle = '';
    if ($isOverlayHex) {
        $hex = ltrim($overlay_color, '#');
        if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $a = $opacityInt / 100;
        $overlayStyle = "background-color: rgba({$r}, {$g}, {$b}, {$a})";
    } else {
        // legacy tailwind name support
        $overlayClass = "bg-{$overlay_color} bg-opacity-{$opacityInt}";
    }

    // Text color (prefer normalized text_color hex, fallback to legacy text_color_class)
    $resolvedText = $text_color ?? $text_color_class ?? '#ffffff';
    $textIsHex = is_string($resolvedText) && preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $resolvedText);
    $textClass = $textIsHex ? '' : $resolvedText;
    $textStyle = $textIsHex ? "color: {$resolvedText}" : '';

    // Height: prefer height_vh (slider), fallback to parsing h-[NNvh] from height_class
    $heightVh = null;
    if (is_numeric($height_vh)) {
        $heightVh = max(0, min(100, (int)$height_vh));
    } elseif (is_string($height_class) && preg_match('/^h-\[(\d+)vh\]$/', $height_class, $m)) {
        $heightVh = max(0, min(100, (int)$m[1]));
    }

    $containerClass = "bg-cover bg-center shadow-xl" . ($heightVh === null ? " {$height_class}" : "");
    $containerStyle = "background-image: url('{$image_url}');" . ($heightVh !== null ? " height: {$heightVh}vh;" : "");

    // Sanitize helper (uses Mews Purifier if available)
    $purifier = app()->bound('purifier') ? app('purifier') : null;
    $cleanHtml = function ($html) use ($purifier) {
        $html = $html ?? '';
        return $purifier ? $purifier->clean($html) : preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    };
@endphp

<div class="{{ $containerClass }}" style="{{ $containerStyle }}">
    <div class="flex flex-col items-center justify-center h-full {{ $overlayClass }}" style="{{ $overlayStyle }}">
        <h1 class="text-5xl font-bold m-4 {{ $textClass }}" style="{{ $textStyle }}">
            {!! $cleanHtml($heading) !!}
        </h1>
        <div class="mt-4 text-lg italic max-w-md text-center {{ $textClass }}" style="{{ $textStyle }}">
            {!! $cleanHtml($subheading) !!}
        </div>
    </div>
</div>
