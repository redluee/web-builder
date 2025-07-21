@props([
    'imagee' => [
        '/storage/images/Blueprint.jpg',
        '/storage/images/color-banner.jpg',
        '/storage/images/playbutton.jpeg',
    ],
    'columns' => 3,
])

<div class="grid gap-4 max-w-6xl mx-auto" style="grid-template-columns: repeat({{ $columns }}, minmax(0, 1fr));">
    @foreach($imagee as $img)
        <img src="{{ $img }}" class="rounded shadow" alt="Gallery image">
    @endforeach
</div>