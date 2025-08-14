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
@endphp

<div class="p-6 rounded-lg m-4 max-w-4xl mx-auto" style="background-color: {{ $resolvedBg }};">
    <h2 class="text-2xl font-bold mb-2" style="color: {{ $resolvedText }};">{{ $heading }}</h2>
    <p class="text-base" style="color: {{ $resolvedText }};">{{ $body }}</p>
</div>