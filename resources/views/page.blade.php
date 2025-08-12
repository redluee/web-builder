@extends('layouts.app')

@section('content')
    @foreach($page->elements as $element)
        @php
            $view = $element->view_path ?? null;

            $defaultSettings = is_array($element->settings)
                ? $element->settings
                : json_decode($element->settings ?? '{}', true);

            $pivotSettings = is_array($element->pivot->settings ?? null)
                ? $element->pivot->settings
                : json_decode($element->pivot->settings ?? '{}', true);

            // Pivot overrides defaults
            $mergedSettings = array_replace_recursive($defaultSettings ?? [], $pivotSettings ?? []);
        @endphp

        @if ($view && View::exists($view))
            {{-- Pass both flattened values and "settings" array for compatibility --}}
            @include($view, array_merge($mergedSettings, ['settings' => $mergedSettings]))
        @else
            <div class="bg-red-100 text-red-800 p-4 rounded">
                Unknown or missing element view: <strong>{{ $element->view_path ?? 'n/a' }}</strong>
            </div>
        @endif
    @endforeach
@endsection