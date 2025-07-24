<div class="bg-white rounded-lg shadow-lg p-5 flex items-start border border-gray-200 gap-2">

    <div class="flex flex-col items-start mr-auto gap-2">
        <h3 class="py-1 px-1 text-lg font-bold text-center break-words text-black my-auto">{{ $page->title }}</h3>
        <form id="delete-page-form-{{ $page->id }}" action="{{ route('pages.destroy', $page->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button"
                onclick="showDeletePagePopup({{ $page->id }})"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm my-auto">
                <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6H20L18.4199 20.2209C18.3074 21.2337 17.4512 22 16.4321 22H7.56786C6.54876 22 5.69264 21.2337 5.5801 20.2209L4 6Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M7.34491 3.14716C7.67506 2.44685 8.37973 2 9.15396 2H14.846C15.6203 2 16.3249 2.44685 16.6551 3.14716L18 6H6L7.34491 3.14716Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M2 6H22" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M10 11V16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M14 11V16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </form>
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

{{-- Custom Delete Confirmation Popup --}}
<div id="delete-page-popup-{{ $page->id }}" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full relative text-center">
        <h2 class="text-lg font-bold mb-4 text-black">Are you sure you want to delete this page?</h2>
        <p class="mb-6 text-gray-700">This action cannot be undone!</p>
        <button onclick="document.getElementById('delete-page-form-{{ $page->id }}').submit()"
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 mr-2">Yes, delete</button>
        <button onclick="closeDeletePagePopup({{ $page->id }})"
            class="px-4 py-2 bg-gray-300 text-black rounded hover:bg-gray-400">Cancel</button>
    </div>
</div>

<script>
    function showDeletePagePopup(id) {
        document.getElementById('delete-page-popup-' + id).classList.remove('hidden');
    }
    function closeDeletePagePopup(id) {
        document.getElementById('delete-page-popup-' + id).classList.add('hidden');
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === "Escape") {
            closeDeletePagePopup({{ $page->id }});
        }
    });
</script>
