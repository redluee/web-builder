@props([
    'name' => 'Video',
    'view_path' => 'components.video',
    'settings' => [
        'video_url' => 'https://youtu.be/YHS8AYSwW34',
        'autoplay' => false,
    ],
])

@php
    $url = $settings['video_url'];
    // Handle youtu.be and youtube.com links
    if (preg_match('/youtu\.be\/([^\?&]+)/', $url, $matches)) {
        $videoId = $matches[1];
        $embedUrl = "https://www.youtube.com/embed/$videoId";
    } elseif (preg_match('/youtube\.com.*[?&]v=([^\?&]+)/', $url, $matches)) {
        $videoId = $matches[1];
        $embedUrl = "https://www.youtube.com/embed/$videoId";
    } else {
        $embedUrl = $url; // fallback
    }
    // Add autoplay if needed
    if ($settings['autoplay']) {
        $embedUrl .= (str_contains($embedUrl, '?') ? '&' : '?') . 'autoplay=1&mute=1';
    }
@endphp

<div class="flex items-center justify-center max-w-4xl mx-auto h-96 rounded-lg overflow-hidden shadow-lg">
    <iframe
        class="w-full h-full rounded"
        src="{{ $embedUrl }}"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
    ></iframe>
</div>
