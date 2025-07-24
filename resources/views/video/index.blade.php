@include('layouts.app')

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
    style="background-image: url('{{ asset('/storage/images/playbutton.jpeg') }}');">
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
<div class="mx-auto max-w-6xl mt-10 m-2">
    <a href="{{ route('videos.create') }}" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
        onclick="openVideoCreateModal(event, this.href)">
        Add New Video
    </a>
</div>


<div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 items-stretch">
    @foreach ($videos as $video)
        <div id="video-tile-{{ $video->id }}">
            <x-video-edit-tile :video="$video" />
        </div>
    @endforeach
</div>

{{-- Edit Modal Overlay --}}
<div id="video-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="video-edit-modal-content" class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button onclick="closeVideoEditModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
        {{-- The edit form will be loaded here --}}
    </div>
</div>

{{-- Create Modal Overlay --}}
<div id="video-create-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="video-create-modal-content" class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button onclick="closeVideoCreateModal()"
            class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
        {{-- The create form will be loaded here --}}
    </div>
</div>

<script>
    function openVideoEditModal(event, url) {
        event.preventDefault();
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('video-edit-modal-content').innerHTML =
                    '<button onclick="closeVideoEditModal()" class="absolute top-2 right-4 w-4 h-4 text-gray-500 hover:text-black text-2xl">&times;</button>' +
                    html;
                document.getElementById('video-edit-modal').classList.remove('hidden');
            });
    }

    function closeVideoEditModal() {
        document.getElementById('video-edit-modal').classList.add('hidden');
        document.getElementById('video-edit-modal-content').innerHTML = '';
    }

    function openVideoCreateModal(event, url) {
        event.preventDefault();
        fetch(url)
            .then(response => response.text())
            .then(html => {
                document.getElementById('video-create-modal-content').innerHTML =
                    '<button onclick="closeVideoCreateModal()" class="absolute top-2 right-4 w-4 h-4 text-gray-500 hover:text-black text-2xl">&times;</button>' +
                    html;
                document.getElementById('video-create-modal').classList.remove('hidden');
            });
    }

    function closeVideoCreateModal() {
        document.getElementById('video-create-modal').classList.add('hidden');
        document.getElementById('video-create-modal-content').innerHTML = '';
    }

    // ESC key closes modals
    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            closeVideoEditModal();
            closeVideoCreateModal();
        }
    });
</script>
