@props([
    'image' => [
        '/storage/images/Blueprint.jpg',
        '/storage/images/color-banner.jpg',
        '/storage/images/playbutton.jpeg',
    ],
    'height_class' => 'h-64',
])

<div class="relative w-full overflow-hidden max-w-6xl mx-auto m-4 {{ $height_class }} rounded-lg" id="carousel">
    @if(count($image))
        <div id="carousel-track" class="flex transition-transform duration-500 w-full h-full" style="will-change: transform;">
            @foreach($image as $img)
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
        const images = @json($image);
        let current = 0;
        const track = document.getElementById('carousel-track');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        function showImage(idx) {
            track.style.transform = `translateX(-${idx * 100}%)`;
        }

        prevBtn.addEventListener('click', function () {
            current = (current - 1 + images.length) % images.length;
            showImage(current);
        });

        nextBtn.addEventListener('click', function () {
            current = (current + 1) % images.length;
            showImage(current);
        });

        // Ensure correct initial position
        showImage(current);
    });
</script>