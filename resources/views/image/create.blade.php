
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

{{-- Display success message --}}
@if (session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Upload Image</label>
        <input type="file" name="image_file" accept="image/*" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Image Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <div class="mb-4">
        <label class="block font-bold mb-1 text-black">Alt Text</label>
        <input type="text" name="alt_text" value="{{ old('alt_text') }}" class="w-full border rounded px-2 py-1 text-black">
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Create</button>
</form>
