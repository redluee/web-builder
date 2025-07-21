@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <h2>Edit Content for: {{ $page->title }}</h2>

    <form method="POST" action="{{ route('pages.addElement', $page->id) }}">
        @csrf
        <select name="element_id" required class="text-black">
            @foreach($availableElements as $element)
                <option value="{{ $element->id }}">{{ $element->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary">Add Element</button>
    </form>

    <h3>Current Elements</h3>
    <ul id="element-list" class="m-4">
        @foreach($elements as $element)
            <li class="p-2">
                {{ $element->name }}
                <form method="POST" action="{{ route('pages.removeElement', [$page->id, $element->id]) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger hover:bg-red-900 transition">Remove</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
    {{-- You can add drag-and-drop JS to reorder and call updateElementOrder via AJAX --}}
@endsection