@props([
    // normalized
    'images' => [
        '/storage/images/Blueprint.jpg',
        '/storage/images/color-banner.jpg',
        '/storage/images/playbutton.jpeg',
    ],
    'height_vh' => null,     // numeric height via slider
    // legacy
    'image' => null,         // old key
    'height_class' => 'h-64' // legacy fallback
])

@php
    $imgs = is_array($images ?? null) ? $images : (is_array($image ?? null) ? $image : []);
    // height: prefer numeric height_vh, fallback to parse from class like h-64 (~16rem) or bracketed vh
    $heightStyle = '';
    if (is_numeric($height_vh)) {
        $vh = max(0, min(100, (int)$height_vh));
        $heightStyle = "height: {$vh}vh;";
    } elseif (is_string($height_class) && preg_match('/^h-\[(\d+)vh\]$/', $height_class, $m)) {
        $vh = max(0, min(100, (int)$m[1]));
        $heightStyle = "height: {$vh}vh;";
    } else {
        // minimal fallback: rely on provided class
        $heightStyle = '';
    }
@endphp

<div class="relative w-full overflow-hidden max-w-6xl mx-auto m-4 rounded-lg"
     id="carousel"
     style="{{ $heightStyle }}">
    @if(count($imgs))
        <div id="carousel-track" class="flex transition-transform duration-500 w-full h-full" style="will-change: transform;">
            @foreach($imgs as $img)
                <img src="{{ $img }}" class="w-full h-full object-cover flex-shrink-0" alt="Carousel image">
            @endforeach
        </div>
        <button id="prev-btn" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-50 px-2 py-2 rounded-full hover:bg-opacity-100 hover:text-black transition">&lt;</button>
        <button id="next-btn" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-50 px-2 py-2 rounded-full hover:bg-opacity-100 hover:text-black transition">&gt;</button>
    @else
        <div class="flex items-center justify-center h-full text-gray-500">No images</div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = @json($imgs);
        let current = 0;
        const track = document.getElementById('carousel-track');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        function showImage(idx) {
            track.style.transform = `translateX(-${idx * 100}%)`;
        }

        prevBtn?.addEventListener('click', function () {
            current = (current - 1 + images.length) % images.length;
            showImage(current);
        });

        nextBtn?.addEventListener('click', function () {
            current = (current + 1) % images.length;
            showImage(current);
        });

        showImage(current);
    });
</script>