<div class="p-4">
    <h2 class="text-lg font-semibold mb-3">Preview</h2>

    <ul id="page-element-list" class="space-y-4 min-h-[50vh] rounded p-1"
        aria-label="Drop new elements here. Drag to reorder.">
        @forelse ($elements as $element)
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

            <li class="page-element outline-offset-2"
                draggable="true"
                tabindex="0"
                data-page-element-id="{{ $element->pivot->id }}"
                data-element-id="{{ $element->id }}"
                data-element-name="{{ $element->name }}"
                data-default-settings='@json($defaultSettings ?? [])'
                data-settings='@json($pivotSettings ?? [])'
                aria-label="Element {{ $element->name }}">

                <div class="page-element-content">
                    @if ($view && View::exists($view))
                        {{-- Pass both flattened values and "settings" array for compatibility --}}
                        @include($view, array_merge($mergedSettings, ['settings' => $mergedSettings]))
                    @else
                        <div class="bg-red-100 text-red-800 p-3 rounded">
                            Unknown or missing element view: <strong>{{ $element->view_path ?? 'n/a' }}</strong>
                        </div>
                    @endif
                </div>
            </li>
        @empty
            <li class="text-slate-500">Drop elements here to start building your page.</li>
        @endforelse
    </ul>
</div>