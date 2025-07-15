{{-- filepath: /home/stevo/Development/web-builder/resources/views/components/video-edit-tile.blade.php --}}
@props(['video'])

<div class="h-full bg-white rounded-lg shadow-lg p-5 flex flex-col items-center gap-4 border border-gray-200">
    <iframe
        width="100%"
        height="200"
        src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($video->youtube_url, 'v=') }}"
        title="{{ $video->video_name }}"
        frameborder="0"
        allowfullscreen
        class="rounded shadow-lg"
    ></iframe>
    <div class="flex gap-4 justify-between w-full">
        <h3 class="text-lg font-semibold text-center break-words text-black my-auto">{{ $video->video_name }}</h3>
        <a href="{{ route('videos.edit', $video->id) }}"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
            Edit
        </a>
    </div>
    @if($video->video_description)
        <p class="text-gray-700 text-sm mt-2">{{ $video->video_description }}</p>
    @endif
</div>