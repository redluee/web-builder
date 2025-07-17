@props(['color', 'success' => null])

<form id="color-form-{{ $color->id }}" action="{{ route('colors.update', $color->id) }}" method="POST"
    class="h-full text-black bg-white rounded-lg shadow-lg p-5 flex flex-col items-center gap-4 border border-gray-200">
    @csrf
    @method('PUT')
    <h3 class="text-lg font-semibold mb-2">{{ ucfirst(str_replace('_', ' ', $color->variable_name)) }}</h3>

    

    <div class="flex items-center gap-3">
        <input type="color" id="color_picker_{{ $color->id }}" value="{{ $color->hex_code }}"
            class="w-12 h-12 border-[#808080] border-2 p-0 bg-transparent cursor-pointer rounded-lg hover:shadow-md hover:-translate-y-1 transition duration-200"
            onchange="document.getElementById('hex_input_{{ $color->id }}').value = this.value">
        <input type="text" id="hex_input_{{ $color->id }}" name="hex_code" value="{{ $color->hex_code }}"
            class="border rounded px-2 py-1 w-28 text-center"
            onchange="document.getElementById('color_picker_{{ $color->id }}').value = this.value">
        <input type="hidden" name="color_id" value="{{ $color->id }}">
    </div>
    <div class="flex gap-2 items-center">
        <button type="submit" class="mt-2 px-4 py-2 bg-cyan-500 text-white rounded hover:bg-cyan-600 transition">
            Save
        </button>
        @if (($success ?? false) || (session('success') && session('color_id') == $color->id))
            <div class="w-full mt-2 px-4 py-2 bg-green-100 text-green-800 rounded text-center">
                {{ $success ?? session('success') }}
            </div>
        @endif
        @if ($errors->has('hex_code') && old('color_id') == $color->id)
            <div class="w-full mt-2 px-4 py-2 bg-red-100 text-red-800 rounded text-center">
                {{ $errors->first('hex_code') }}
            </div>
        @endif
    </div>
</form>
