@include('layouts.app')
@include('layouts.navigation')

@php
    $videos = $videos ?? [];
@endphp

{{-- Display success message --}}
@if (session('success'))
    <div class="max-w-4xl mx-auto mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

{{-- Display error message --}}
@if (session('error'))
    <div class="max-w-4xl mx-auto mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        {{ session('error') }}
    </div>
@endif

{{-- banner --}}
<div class="bg-cover bg-center h-[25vh] shadow-xl"
    style="background-image: url('{{ asset('/storage/images/video-banner.jpg') }}');">
    <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-60">
        <h1 class="text-5xl font-bold ">
            Videos
        </h1>
        <p class="mt-4 text-lg italic max-w-lg text-center ">
            Manage your embedded videos here.
        </p>
    </div>
</div>

{{-- add video --}}
<div class="max-w-4xl mx-auto mt-10 mb-6">
    <a href="{{ route('videos.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
        Add New Video
    </a>
</div>

<div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 items-stretch">
    @foreach($videos as $video)
        <div id="video-tile-{{ $video->id }}">
            <x-video-edit-tile :video="$video" />
        </div>
    @endforeach
</div>