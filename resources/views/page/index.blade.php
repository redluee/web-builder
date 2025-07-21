@include('layouts.app')

@php
    $pages = $pages ?? [];
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
    style="background-image: url('{{ asset('/storage/images/pages.png') }}');">
    <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-60">
        <h1 class="text-5xl font-bold ">
            Pages
        </h1>
        <p class="mt-4 text-lg italic max-w-lg text-center ">
            Manage your pages here.
        </p>
    </div>
</div>

{{-- add page --}}
<div class="max-w-4xl mx-auto mt-10 mb-6">
    <a href="{{ route('pages.create') }}" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
        Add New Page
    </a>
</div>

<div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 items-stretch">
    @foreach($pages as $page)
        <div id="page-tile-{{ $page->id }}">
            <x-page-edit-tile :page="$page" />
        </div>
    @endforeach
</div>

{{-- Edit Modal Overlay --}}
<div id="page-edit-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div id="page-edit-modal-content" class="bg-white rounded-lg shadow-lg p-6 max-w-lg w-full relative">
        <button onclick="closePageEditModal()" class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl">&times;</button>
        {{-- The edit form will be loaded here --}}
    </div>
</div>

<script>
function openPageEditModal(event, url) {
    event.preventDefault();
    fetch(url)
        .then(response => response.text())
        .then(html => {
            document.getElementById('page-edit-modal-content').innerHTML =
                '<button onclick="closePageEditModal()" class="absolute top-2 right-4 text-gray-500 hover:text-black text-2xl">&times;</button>' +
                html;
            document.getElementById('page-edit-modal').classList.remove('hidden');
        });
}

function closePageEditModal() {
    document.getElementById('page-edit-modal').classList.add('hidden');
    document.getElementById('page-edit-modal-content').innerHTML = '';
}
</script>

