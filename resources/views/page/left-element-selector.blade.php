{{-- Expects: $elements (Collection of models with: id, name, view_path, settings) --}}
<div id="left-element-selector" class="w-full h-full overflow-y-auto p-4 space-y-2">
    <h2 class="text-lg font-semibold mb-3 text-white">Elements</h2>

    @forelse($elements as $element)
        @php
            // Ensure settings is a JSON string for data attribute
            $settingsJson = is_string($element->settings) ? $element->settings : json_encode($element->settings);
        @endphp

        <div
            class="element-item flex items-center justify-between rounded-md border border-slate-200 bg-white px-3 py-2 shadow-sm hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700"
            draggable="true"
            role="button"
            aria-label="Drag {{ $element->name }}"
            title="Drag {{ $element->name }}"
            data-element-id="{{ $element->id }}"
            data-element-name="{{ $element->name }}"
            data-element-view="{{ $element->view_path }}"
            data-element-settings="{{ e($settingsJson) }}"
        >
            <div class="min-w-0 flex items-center gap-2">
                <div class="h-2 w-2 rounded-full bg-slate-300 dark:bg-slate-500"></div>
                <div class="truncate">
                    <div class="text-sm font-medium text-slate-800 dark:text-slate-100 truncate">
                        {{ $element->name }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 truncate">
                        {{ $element->view_path }}
                    </div>
                </div>
            </div>

            <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-400 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-200"
                 data-drag-handle="true"
                 aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <circle cx="6" cy="6" r="1.5"></circle>
                    <circle cx="6" cy="10" r="1.5"></circle>
                    <circle cx="6" cy="14" r="1.5"></circle>
                    <circle cx="12" cy="6" r="1.5"></circle>
                    <circle cx="12" cy="10" r="1.5"></circle>
                    <circle cx="12" cy="14" r="1.5"></circle>
                </svg>
            </div>
        </div>
    @empty
        <div class="text-sm text-slate-500 dark:text-slate-400">
            No elements found.
        </div>
    @endforelse
</div>

{{-- Optional: minimal helper script to ensure only the handle initiates the drag --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#left-element-selector .element-item').forEach(item => {
        let allowDrag = false;

        item.addEventListener('mousedown', (e) => {
            allowDrag = e.target.closest('[data-drag-handle="true"]') !== null;
        });
        item.addEventListener('dragstart', (e) => {
            if (!allowDrag) {
                e.preventDefault();
                return;
            }
            // Pass data payload
            const payload = {
                id: item.dataset.elementId,
                name: item.dataset.elementName,
                view: item.dataset.elementView,
                settings: (() => {
                    try { return JSON.parse(item.dataset.elementSettings || '{}'); }
                    catch { return {}; }
                })(),
            };
            e.dataTransfer.setData('application/json', JSON.stringify(payload));
            e.dataTransfer.effectAllowed = 'copy';
        });
        item.addEventListener('dragend', () => { allowDrag = false; });
    });
});
</script>