{{-- filepath: /home/stevo/Development/web-builder/resources/views/image/edit.blade.php --}}

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

<form method="POST" action="{{ route('images.update', $image->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Current Image</label>
        <img src="{{ $image->url }}" alt="{{ $image->alt_text ?? $image->name }}" class="w-full h-40 object-cover rounded mb-2 border">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Replace Image (upload new)</label>
        <input type="file" name="image_file" accept="image/*" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Image Name</label>
        <input type="text" name="name" value="{{ old('name', $image->name) }}" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Alt Text</label>
        <input type="text" name="alt_text" value="{{ old('alt_text', $image->alt_text) }}" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
</form>