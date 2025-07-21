<div class=" bg-white rounded-lg shadow-lg p-5 flex items-start border border-gray-200 gap-2">

    <div class="flex flex-col items-start gap-2">
        <h3 class="text-lg font-semibold text-center break-words text-black my-auto">{{ $page->title }}</h3>
        
    </div>
    <div class="flex flex-col items-end ml-auto gap-2 ">

        <a href="{{ route('pages.edit', $page->id) }}" onclick="openPageEditModal(event, this.href)"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm my-auto">
            Edit slug
        </a>
        <a href="{{ route('pages.editContent', $page->id) }}"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm my-auto">
            Edit content
        </a>
    </div>
</div>
