{{-- filepath: /home/stevo/Development/web-builder/resources/views/components/image-edit-tile.blade.php --}}
@props(['image'])

<div class="h-full bg-white rounded-lg shadow-lg p-5 flex flex-col items-center gap-4 border border-gray-200">
    <img
        src="{{ $image->url }}"
        alt="{{ $image->alt_text ?? $image->name }}"
        class="w-full h-40 object-cover rounded shadow-lg"
    >
    <div class="flex gap-4 justify-between w-full">
        <h3 class="text-lg font-semibold text-center break-words text-black my-auto">{{ $image->name }}</h3>
        <a href="{{ route('images.edit', $image->id) }}"
           onclick="openImageEditModal(event, this.href)"
           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">
            Edit
        </a>
    </div>
</div>