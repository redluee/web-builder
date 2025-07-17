{{-- filepath: /home/stevo/Development/web-builder/resources/views/video/edit.blade.php --}}

{{-- Display validation errors --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('videos.update', $video->id) }}">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Current Video</label>
        <iframe
            width="100%"
            height="200"
            src="https://www.youtube.com/embed/{{ \Illuminate\Support\Str::afterLast($video->youtube_url, 'v=') }}"
            title="{{ $video->video_name }}"
            frameborder="0"
            allowfullscreen
            class="rounded shadow-lg mb-2"
        ></iframe>
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">YouTube URL</label>
        <input type="url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url) }}" class="w-full border rounded px-2 py-1 text-black" required>
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Video Name</label>
        <input type="text" name="video_name" value="{{ old('video_name', $video->video_name) }}" class="w-full border rounded px-2 py-1 text-black" required>
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Description</label>
        <textarea name="video_description" class="w-full border rounded px-2 py-1 text-black">{{ old('video_description', $video->video_description) }}</textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
</form>