@props([
    // normalized
    'images' => [
        '/storage/images/Blueprint.jpg',
        '/storage/images/color-banner.jpg',
        '/storage/images/playbutton.jpeg',
    ],
    'columns' => 3,
    // legacy
    'imagee' => null,
])

@php
    $imgs = is_array($images ?? null) ? $images : (is_array($imagee ?? null) ? $imagee : []);
@endphp

<div class="m-4 grid gap-4 max-w-6xl mx-auto grid-cols-1 sm:grid-cols-2 md:grid-cols-{{ $columns }}">
    @foreach($imgs as $img)
        <img src="{{ $img }}" class="rounded shadow" alt="Gallery image">
    @endforeach
</div>