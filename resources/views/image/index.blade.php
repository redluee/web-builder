@include('layouts.app')
@include('layouts.navigation')

@php
    $images = $images ?? [];
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
    style="background-image: url('{{ asset('/storage/images/Gallery-wall-layout.jpg') }}');">
    <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-60">
        <h1 class="text-5xl font-bold ">
            Images
        </h1>
        <p class="mt-4 text-lg italic max-w-lg text-center ">
            Manage your uploaded images here.
        </p>
    </div>
</div>

{{-- add image --}}

<div class="max-w-4xl mx-auto mt-10 mb-6">
    <a href="{{ route('images.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition" onclick="openImageCreateModal(event, this.href)">
        Add New Image
    </a>
</div>

<div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 items-stretch">
    @foreach($images as $image)
        <div id="image-tile-{{ $image->id }}">
            <x-image-edit-tile :image="$image" />
        </div>
    @endforeach
</div>


{{-- Edit Modal Overlay --}}
<div id="image-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="image-edit-modal-content" class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button onclick="closeImageEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
        {{-- The edit form will be loaded here --}}
    </div>
</div>

{{-- Create Modal Overlay --}}
<div id="image-create-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="image-create-modal-content" class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button onclick="closeImageCreateModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
        {{-- The create form will be loaded here --}}
    </div>
</div>

<script>
function openImageEditModal(event, url) {
    event.preventDefault();
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('image-edit-modal-content').innerHTML = 
                '<button onclick="closeImageEditModal()" class="absolute top-2 right-4 w-4 h-4 text-gray-500 hover:text-black text-2xl">&times;</button>' +
                html;
            document.getElementById('image-edit-modal').classList.remove('hidden');
        });
}

function closeImageEditModal() {
    document.getElementById('image-edit-modal').classList.add('hidden');
    document.getElementById('image-edit-modal-content').innerHTML = '';
}

function openImageCreateModal(event, url) {
    event.preventDefault();
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('image-create-modal-content').innerHTML = 
                '<button onclick="closeImageCreateModal()" class="absolute top-2 right-4 w-4 h-4 text-gray-500 hover:text-black text-2xl">&times;</button>' +
                html;
            document.getElementById('image-create-modal').classList.remove('hidden');
        });
}

function closeImageCreateModal() {
    document.getElementById('image-create-modal').classList.add('hidden');
    document.getElementById('image-create-modal-content').innerHTML = '';
}
</script>