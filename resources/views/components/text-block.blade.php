@props([
    'heading' => 'Default Heading',
    'body' => 'Default body text goes here.',
    'text_color_class' => '#ffffff',
    'bg_color_class' => '#18181a',
])

<div class="p-6 rounded-lg m-4 max-w-4xl mx-auto" style="background-color: {{ $bg_color_class }};">
    <h2 class="text-2xl font-bold mb-2" style="color: {{ $text_color_class }};">{{ $heading }}</h2>
    <p class="text-base" style="color: {{ $text_color_class }};">{{ $body }}</p>
</div>