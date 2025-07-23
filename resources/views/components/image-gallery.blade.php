@props([
    'imagee' => [
        '/storage/images/Blueprint.jpg',
        '/storage/images/color-banner.jpg',
        '/storage/images/playbutton.jpeg',
    ],
    'columns' => 3,
])

<div class="m-4 grid gap-4 max-w-6xl mx-auto grid-cols-1 sm:grid-cols-2 md:grid-cols-{{ $columns }}">
    @foreach($imagee as $img)
        <img src="{{ $img }}" class="rounded shadow" alt="Gallery image">
    @endforeach
</div>