@include('layouts.app')

<div class="flex flex-row mx-auto h-screen overflow-hidden">

    {{-- Left: Available elements to drag into the page --}}
    <aside class="w-72 border-r overflow-y-auto">
        @include('page.left-element-selector', ['elements' => $availableElements])
    </aside>

    {{-- Center: Preview area (droppable + sortable) --}}
    <main class="flex-1 overflow-y-auto">
        @include('page.center-preview')
    </main>

    {{-- Right: Settings panel for selected element --}}
    <aside class="w-96 border-l overflow-y-auto">
        @include('page.right-element-settings')
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const csrf = '{{ csrf_token() }}';
    const routes = {
        addElement: '{{ route('pages.addElement', $page->id) }}',
        updateOrder: '{{ route('pages.updateElementOrder', $page->id) }}',
        removeElementBase: '{{ url('/pages/'.$page->id.'/elements') }}',        // + '/{pageElementId}'
        updateSettingsBase: '{{ url('/pages/'.$page->id.'/elements') }}',      // + '/{pageElementId}/settings'
    };

    const listEl = document.getElementById('page-element-list');
    const settingsRoot = document.getElementById('right-element-settings-root');
    const settingsTitle = document.getElementById('right-element-settings-title');
    const settingsForm = document.getElementById('element-settings-form');
    const settingsFields = document.getElementById('element-settings-fields');
    const settingsSaveBtn = document.getElementById('element-settings-save');
    const settingsRemoveBtn = document.getElementById('element-settings-remove');

    let selectedEl = null;
    let draggingEl = null;
    const placeholder = document.createElement('li');
    placeholder.className = 'rounded border-2 border-dashed border-slate-300 h-10 bg-slate-50';
    placeholder.dataset.placeholder = 'true';

    const parseJSON = (str, fallback) => { try { return JSON.parse(str); } catch { return fallback; } };
    const isHexColor = (v) => typeof v === 'string' && /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(v);
    const isColorKey = (k) => /color/i.test(k);

    const selectElement = (li) => {
        if (selectedEl) selectedEl.classList.remove('ring', 'ring-blue-500');
        selectedEl = li;
        if (!selectedEl) return;

        selectedEl.classList.add('ring', 'ring-blue-500');

        const name = selectedEl.dataset.elementName || 'Element';
        const def = parseJSON(selectedEl.dataset.defaultSettings || '{}', {});
        const piv = parseJSON(selectedEl.dataset.settings || '{}', {});
        const merged = Object.assign({}, def, piv);

        renderSettings(name, merged, piv, {
            pageElementId: selectedEl.dataset.pageElementId
        });
    };

    const renderSettings = (title, merged, pivotOnly, ctx) => {
        settingsTitle.textContent = title;
        settingsFields.innerHTML = '';

        const addField = (key, value) => {
            const wrap = document.createElement('div');
            wrap.className = 'space-y-1';
            const label = document.createElement('label');
            label.className = 'text-xs text-slate-600';
            label.textContent = key;
            wrap.appendChild(label);

            // Object -> JSON textarea
            if (value !== null && typeof value === 'object') {
                const ta = document.createElement('textarea');
                ta.className = 'w-full rounded border px-2 py-1 text-sm font-mono';
                ta.rows = 5;
                ta.value = JSON.stringify(pivotOnly[key] ?? value, null, 2);
                ta.dataset.key = key;
                ta.dataset.type = 'json';
                wrap.appendChild(ta);
                settingsFields.appendChild(wrap);
                return;
            }

            // Boolean -> checkbox
            if (typeof value === 'boolean') {
                const cbWrap = document.createElement('div');
                const cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.className = 'h-4 w-4';
                cb.checked = Boolean(pivotOnly[key] ?? value);
                cb.dataset.key = key;
                cbWrap.appendChild(cb);
                wrap.appendChild(cbWrap);
                settingsFields.appendChild(wrap);
                return;
            }

            // style-type -> select with common border styles
            if (key === 'style-type') {
                const select = document.createElement('select');
                select.className = 'w-full rounded border px-2 py-1 text-sm';
                select.dataset.key = key;
                const opts = ['none','hidden','solid','dashed','dotted','double','groove','ridge','inset','outset'];
                const current = (pivotOnly[key] ?? value) ?? 'solid';
                opts.forEach(o => {
                    const option = document.createElement('option');
                    option.value = o;
                    option.textContent = o;
                    if (o === current) option.selected = true;
                    select.appendChild(option);
                });
                wrap.appendChild(select);
                settingsFields.appendChild(wrap);
                return;
            }

            // Color -> show color picker (hex) and a text fallback if not hex
            if (isColorKey(key) || isHexColor(value)) {
                const current = (pivotOnly[key] ?? value) ?? '';
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';

                const color = document.createElement('input');
                color.type = 'color';
                color.className = 'h-8 w-12 p-0 border rounded';
                color.dataset.key = key;
                // If not hex, default to black; keep text in text field
                color.value = isHexColor(current) ? current : '#000000';

                const text = document.createElement('input');
                text.type = 'text';
                text.className = 'flex-1 rounded border px-2 py-1 text-sm';
                text.dataset.key = key;
                text.value = current;

                color.addEventListener('input', () => {
                    text.value = color.value;
                });

                row.appendChild(color);
                row.appendChild(text);
                wrap.appendChild(row);
                settingsFields.appendChild(wrap);
                return;
            }

            // Number -> number input
            if (typeof value === 'number') {
                const input = document.createElement('input');
                input.type = 'number';
                input.step = 'any';
                input.className = 'w-full rounded border px-2 py-1 text-sm';
                input.value = (pivotOnly[key] ?? value) ?? 0;
                input.dataset.key = key;
                wrap.appendChild(input);
                settingsFields.appendChild(wrap);
                return;
            }

            // width/height strings like "80%" / "2px" -> text input
            if (/(^|_)width$|(^|_)height$/i.test(key)) {
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'e.g. 80% or 2px';
                input.className = 'w-full rounded border px-2 py-1 text-sm';
                input.value = (pivotOnly[key] ?? value) ?? '';
                input.dataset.key = key;
                wrap.appendChild(input);
                settingsFields.appendChild(wrap);
                return;
            }

            // Fallback -> text input
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'w-full rounded border px-2 py-1 text-sm';
            input.value = (pivotOnly[key] ?? value) ?? '';
            input.dataset.key = key;
            wrap.appendChild(input);
            settingsFields.appendChild(wrap);
        };

        Object.keys(merged).forEach(k => addField(k, merged[k]));

        settingsForm.dataset.pageElementId = ctx.pageElementId;
        settingsRoot.classList.remove('hidden');
    };

    const collectSettingsFromForm = () => {
        const data = {};
        // Prefer the last control for a given key (e.g., color text overrides)
        settingsFields.querySelectorAll('input,textarea,select').forEach(el => {
            const key = el.dataset.key;
            if (!key) return;
            if (el.type === 'checkbox') {
                data[key] = el.checked;
                return;
            }
            if (el.dataset.type === 'json') {
                data[key] = parseJSON(el.value, null);
                return;
            }
            if (el.type === 'number') {
                data[key] = el.value === '' ? null : Number(el.value);
                return;
            }
            data[key] = el.value;
        });
        return data;
    };

    const saveOrder = () => {
        const order = Array.from(listEl.querySelectorAll('li.page-element'))
            .map((li, idx) => ({
                id: li.dataset.pageElementId,
                sort_order: idx + 1
            }));

        fetch(routes.updateOrder, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ order }),
        }).catch(() => {});
    };

    // Select on click (anywhere on the element)
    listEl.addEventListener('click', (e) => {
        const li = e.target.closest('li.page-element');
        if (!li) return;
        selectElement(li);
    });

    // Drag from center (reorder) — avoid starting drag from inputs/links inside the element
    listEl.addEventListener('dragstart', (e) => {
        const disallow = e.target.closest('input,textarea,select,button,a');
        if (disallow) { e.preventDefault(); return; }

        const li = e.target.closest('li.page-element');
        if (!li) return;
        draggingEl = li;
        e.dataTransfer.effectAllowed = 'move';
        setTimeout(() => {
            li.style.opacity = '0.4';
            if (!placeholder.isConnected) {
                listEl.insertBefore(placeholder, li.nextSibling);
            }
        }, 0);
    });

    listEl.addEventListener('dragover', (e) => {
        e.preventDefault();
        const target = e.target.closest('li.page-element, li[data-placeholder]');
        if (!target || target === placeholder) return;

        const rect = target.getBoundingClientRect();
        const before = (e.clientY - rect.top) < (rect.height / 2);
        if (before) {
            listEl.insertBefore(placeholder, target);
        } else {
            listEl.insertBefore(placeholder, target.nextSibling);
        }
    });

    listEl.addEventListener('drop', (e) => {
        e.preventDefault();
        const payload = e.dataTransfer.getData('application/json');

        // Dropped from left panel (new element)
        if (payload) {
            const data = parseJSON(payload, null);
            if (data && data.id) {
                fetch(routes.addElement, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                    },
                    body: JSON.stringify({ element_id: data.id, settings: data.settings || {} }),
                }).then(() => location.reload());
                return;
            }
        }

        // Internal reorder drop
        if (draggingEl && placeholder.isConnected) {
            listEl.insertBefore(draggingEl, placeholder);
            saveOrder();
        }
    });

    listEl.addEventListener('dragend', () => {
        if (draggingEl) draggingEl.style.opacity = '';
        draggingEl = null;
        if (placeholder.isConnected) placeholder.remove();
    });

    // Save settings
    settingsSaveBtn.addEventListener('click', () => {
        const pageElementId = settingsForm.dataset.pageElementId;
        if (!pageElementId) return;
        const settings = collectSettingsFromForm();

        fetch(`${routes.updateSettingsBase}/${pageElementId}/settings`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ settings }),
        })
        .then(res => res.ok ? res.json() : Promise.reject())
        .then(() => location.reload())
        .catch(() => alert('Failed to save settings.'));
    });

    // Remove via settings panel
    settingsRemoveBtn.addEventListener('click', () => {
        const pageElementId = settingsForm.dataset.pageElementId;
        if (!pageElementId) return;

        fetch(`${routes.removeElementBase}/${pageElementId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf },
        }).then(() => location.reload());
    });
});
</script>

