@extends('layouts.app')

@section('content')
    @foreach($page->elements as $element)
        @php
            $view = $element->view_path ?? null;  // e.g. 'components.banner'
            $settings = is_array($element->settings) ? $element->settings : json_decode($element->settings ?? '{}', true);
        @endphp

        @if($view && View::exists($view))
            @include($view, $settings)
        @else
            <div class="bg-red-100 text-red-800 p-4 rounded">
                Unknown or missing element view: <strong>{{ $element->view_path ?? 'n/a' }}</strong>
            </div>
        @endif
    @endforeach
@endsection