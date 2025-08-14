<div id="right-element-settings-root" class="p-4 space-y-3">
    <h2 id="right-element-settings-title" class="text-lg font-semibold mb-3">Element settings</h2>

    <form id="element-settings-form" class="space-y-3">
        <div id="element-settings-fields" class="space-y-3">
            <div class="text-sm text-slate-500">Select an element in the preview to edit its settings.</div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" id="element-settings-save"
                    class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                Save
            </button>
            <button type="button" id="element-settings-remove"
                    class="text-red-600 text-sm px-3 py-1 hover:underline">
                Remove
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Endpoints
    const csrf = '{{ csrf_token() }}';
    const routes = {
        removeElementBase: '{{ url('/pages/'.$page->id.'/elements') }}',        // + '/{pageElementId}'
        updateSettingsBase: '{{ url('/pages/'.$page->id.'/elements') }}',      // + '/{pageElementId}/settings'
    };

    // DOM
    const listEl = document.getElementById('page-element-list');
    const settingsRoot = document.getElementById('right-element-settings-root');
    const settingsTitle = document.getElementById('right-element-settings-title');
    const settingsForm = document.getElementById('element-settings-form');
    const settingsFields = document.getElementById('element-settings-fields');
    const settingsSaveBtn = document.getElementById('element-settings-save');
    const settingsRemoveBtn = document.getElementById('element-settings-remove');

    // State
    let selectedEl = null;
    const restoreKey = 'wb:selectedPageElementId'; // sessionStorage key

    // Utils
    const parseJSON = (str, fallback) => { try { return JSON.parse(str); } catch { return fallback; } };
    const isHexColor = (v) => typeof v === 'string' && /^#([0-9a-f]{3}|[0-9a-f]{6})$/i.test(v);
    const isColorKey = (k) => /color/i.test(k);

    // Selection
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

    // Render settings fields with appropriate inputs
    const renderSettings = (title, merged, pivotOnly, ctx) => {
        settingsTitle.textContent = title;
        settingsFields.innerHTML = '';

        const addField = (key, value) => {
            const wrap = document.createElement('div');
            wrap.className = 'space-y-1';

            const label = document.createElement('label');
            label.className = 'text-sm font-medium text-slate-800 dark:text-slate-100 truncate';
            label.textContent = key;
            wrap.appendChild(label);

            // Objects -> JSON textarea
            if (value !== null && typeof value === 'object') {
                const ta = document.createElement('textarea');
                ta.className = 'w-full rounded border px-2 py-1 text-sm font-mono text-black';
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
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';
                const cb = document.createElement('input');
                cb.type = 'checkbox';
                cb.className = 'h-4 w-4';
                cb.checked = Boolean(pivotOnly[key] ?? value);
                cb.dataset.key = key;
                row.appendChild(cb);
                wrap.appendChild(row);
                settingsFields.appendChild(wrap);
                return;
            }

            // Specific style-type -> select
            if (key === 'style-type') {
                const select = document.createElement('select');
                select.className = 'w-full rounded border px-2 py-1 text-sm text-black';
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

            // Colors -> color input + text fallback
            if (isColorKey(key) || isHexColor(value)) {
                const current = (pivotOnly[key] ?? value) ?? '';
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';

                const color = document.createElement('input');
                color.type = 'color';
                color.className = 'h-8 w-12 p-0 border rounded';
                color.dataset.key = key;
                color.value = isHexColor(current) ? current : '#000000';

                const text = document.createElement('input');
                text.type = 'text';
                text.className = 'flex-1 rounded border px-2 py-1 text-sm text-black';
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

            // Numbers -> number input
            if (typeof value === 'number') {
                const input = document.createElement('input');
                input.type = 'number';
                input.step = 'any';
                input.className = 'w-full rounded border px-2 py-1 text-sm text-black';
                input.value = (pivotOnly[key] ?? value) ?? 0;
                input.dataset.key = key;
                wrap.appendChild(input);
                settingsFields.appendChild(wrap);
                return;
            }

            // width/height units -> text input with placeholder
            if (/(^|_)width$|(^|_)height$/i.test(key)) {
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'e.g. 80% or 2px';
                input.className = 'w-full rounded border px-2 py-1 text-sm text-black';
                input.value = (pivotOnly[key] ?? value) ?? '';
                input.dataset.key = key;
                wrap.appendChild(input);
                settingsFields.appendChild(wrap);
                return;
            }

            // Fallback -> text input
            const input = document.createElement('input');
            input.type = 'text';
            input.className = 'w-full rounded border px-2 py-1 text-sm text-black';
            input.value = (pivotOnly[key] ?? value) ?? '';
            input.dataset.key = key;
            wrap.appendChild(input);
            settingsFields.appendChild(wrap);
        };

        Object.keys(merged).forEach(k => addField(k, merged[k]));

        settingsForm.dataset.pageElementId = ctx.pageElementId;
        settingsRoot.classList.remove('hidden');
    };

    // Gather values from form
    const collectSettingsFromForm = () => {
        const data = {};
        settingsFields.querySelectorAll('input,textarea,select').forEach(el => {
            const key = el.dataset.key;
            if (!key) return;

            if (el.type === 'checkbox') {
                data[key] = el.checked;
            } else if (el.dataset.type === 'json') {
                data[key] = parseJSON(el.value, null);
            } else if (el.type === 'number') {
                data[key] = el.value === '' ? null : Number(el.value);
            } else {
                data[key] = el.value;
            }
        });
        return data;
    };

    // Listen for selecting an element in the center preview
    if (listEl) {
        document.addEventListener('click', (e) => {
            const li = e.target.closest('li.page-element');
            if (!li || !listEl.contains(li)) return;
            selectElement(li);
        });
    }

    // Save settings
    settingsSaveBtn?.addEventListener('click', () => {
        const pageElementId = settingsForm.dataset.pageElementId;
        if (!pageElementId) return;

        const settings = collectSettingsFromForm();

        // Remember selection across reload
        sessionStorage.setItem(restoreKey, pageElementId);

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
        .catch(() => {
            // Cleanup if save failed
            sessionStorage.removeItem(restoreKey);
            alert('Failed to save settings.');
        });
    });

    // Remove selected element
    settingsRemoveBtn?.addEventListener('click', () => {
        const pageElementId = settingsForm.dataset.pageElementId;
        if (!pageElementId) return;

        fetch(`${routes.removeElementBase}/${pageElementId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf },
        }).then(() => location.reload());
    });

    // Restore previously selected element after reload (from a Save)
    const toRestore = sessionStorage.getItem(restoreKey);
    if (toRestore && listEl) {
        const li = listEl.querySelector(`li.page-element[data-page-element-id="${toRestore}"]`);
        if (li) {
            selectElement(li);
            li.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        }
        sessionStorage.removeItem(restoreKey);
    }
});
</script>