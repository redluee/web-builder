@props([
    // Your JSON keys with defaults:
    'image_url'        => asset('defaults/banner.jpg'),
    'heading'          => 'Welcome to Our Website',
    'subheading'       => 'Explore our features and services.',
    'overlay_color'    => 'black',
    'overlay_opacity'  => 50,          // 0–100
    'height_class'     => 'h-[50vh]',
    'text_color_class' => 'text-white',
])

<div class="bg-cover bg-center {{ $height_class }} shadow-xl"
     style="background-image: url('{{ $image_url }}');">
    <div class="flex flex-col items-center justify-center h-full
                bg-{{ $overlay_color }} bg-opacity-{{ $overlay_opacity }}">
        <h1 class="text-5xl font-bold {{ $text_color_class }}">
            {{ $heading }}
        </h1>
        <p class="mt-4 text-lg italic max-w-md text-center {{ $text_color_class }}">
            {{ $subheading }}
        </p>
    </div>
</div>
