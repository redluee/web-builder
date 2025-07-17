{{-- filepath: /home/stevo/Development/web-builder/resources/views/page/create.blade.php --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pages.store') }}">
    @csrf
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Title</label>
        <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-2 py-1 text-black" required>
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Content</label>
        <textarea name="content" class="w-full border rounded px-2 py-1 text-black" required>{{ old('content') }}</textarea>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
</form>