{{-- filepath: /home/stevo/Development/web-builder/resources/views/video/create.blade.php --}}
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

{{-- Display success message --}}
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('videos.store') }}">
    @csrf
    <div class="mb-4">
        <label for="youtube_url" class="block font-bold mb-1 text-black">YouTube URL</label>
        <input
            id="youtube_url"
            type="url"
            name="youtube_url"
            value="{{ old('youtube_url') }}"
            class="w-full border rounded px-2 py-1 text-black"
            required
        >
    </div>
    <div class="mb-4">
        <label for="video_name" class="block font-bold mb-1 text-black">Video Name</label>
        <input
            id="video_name"
            type="text"
            name="video_name"
            value="{{ old('video_name') }}"
            class="w-full border rounded px-2 py-1 text-black"
            required
        >
    </div>
    <div class="mb-4">
        <label for="video_description" class="block font-bold mb-1 text-black">Description</label>
        <textarea
            id="video_description"
            name="video_description"
            class="w-full border rounded px-2 py-1 text-black max-h-60"
            maxlength="500"
        >{{ old('video_description') }}</textarea>
        <div class="text-xs text-gray-500 mt-1">Max 500 characters</div>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
    <button type="button" class="mt-4 mb-2 mx-2 px-2 py-1 bg-gray-200 text-black rounded hover:bg-gray-300"
        onclick="document.getElementById('how-to-section').classList.toggle('hidden')">
        How to get YouTube URL?
    </button>
</form>

{{-- Button to show/hide the how-to section --}}

{{-- add button to show this segment, should hidden by default --}}
<div class="mt-6 hidden text-black" id="how-to-section">
    {{-- ordered list --}}
    <ol class="list-decimal pl-5">
        <li>Navigate to your youtube video</li>
        <li>Right click on the video</li>
        <li>Select "Copy video URL" to copy the video URL</li>
        <img style="max-width: 350px;" src="{{ asset('/storage/images/video-link.png') }}" alt="video link example">
        <li>Paste the URL in the "YouTube URL" field above</li>
    </ol>
</div>
