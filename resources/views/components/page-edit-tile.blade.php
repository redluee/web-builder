<div class="h-full bg-white rounded-lg shadow-lg p-5 flex items-start border border-gray-200">

    <div class="flex flex-col items-start w-full">
        <h3 class="text-lg font-semibold text-center break-words text-black my-auto">{{ $page->title }}</h3>
        <a href="{{ url('/pages/' . $page->slug) }}" target="_blank" class="text-green-600 underline break-all mt-2">
            {{ '/pages/' . $page->slug }}
        </a>
    </div>
    <a href="{{ route('pages.edit', $page->id) }}" onclick="openPageEditModal(event, this.href)"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm my-auto">
        Edit
    </a>

</div>
