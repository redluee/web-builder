@props([
    'heading' => 'Default Heading',
    'body' => 'Default body text goes here.',
    // normalized
    'text_color' => null,  // hex
    'bg_color' => null,    // hex
    // legacy fallback
    'text_color_class' => '#ffffff',
    'bg_color_class' => '#181811',
])

@php
    $resolvedText = $text_color ?? $text_color_class ?? '#ffffff';
    $resolvedBg = $bg_color ?? $bg_color_class ?? '#181811';

    // Sanitize helper (uses Mews Purifier if available)
    $purifier = app()->bound('purifier') ? app('purifier') : null;
    $cleanHtml = function ($html) use ($purifier) {
        $html = $html ?? '';
        return $purifier ? $purifier->clean($html) : preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
    };
@endphp

<div class="p-6 rounded-lg m-4 max-w-4xl mx-auto" style="background-color: {{ $resolvedBg }};">
    <h2 class="text-2xl font-bold mb-2" style="color: {{ $resolvedText }};">{!! $cleanHtml($heading) !!}</h2>
    <div class="text-base" style="color: {{ $resolvedText }};">{!! $cleanHtml($body) !!}</div>
</div>