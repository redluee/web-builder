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
    // friendlier labels: remove underscores and hyphens, Title Case
    const labelize = (k) => k.replace(/[_-]/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    const labelTextFor = (k) => {
        if (k === 'image_url') return 'Image';
        if (k === 'overlay_opacity') return 'Overlay Opacity';
        if (k === 'height_vh' || k === 'height_class') return 'Height';
        if (k === 'text_color' || k === 'text_color_class') return 'Text Color';
        if (k === 'bg_color' || k === 'bg_color_class') return 'Background Color';
        return labelize(k);
    };

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

    // Render settings fields with normalized widgets
    const renderSettings = (title, merged, pivotOnly, ctx) => {
        settingsTitle.textContent = title;
        settingsFields.innerHTML = '';

        const hiddenKeys = new Set(['image_id', 'image_name']); // hide internal/legacy ids
        let heightRendered = false;

        // Render a single height slider if the element has any height-related setting
        const hasVh = ('height_vh' in merged) || ('height_class' in merged);
        const hasPlainHeight = ('height' in merged);

        if (hasVh || hasPlainHeight) {
            heightRendered = true;

            const wrapH = document.createElement('div');
            wrapH.className = 'space-y-1';

            const labelH = document.createElement('label');
            labelH.className = 'text-sm font-medium text-slate-800 dark:text-slate-100 truncate';
            labelH.textContent = 'Height';
            wrapH.appendChild(labelH);

            const row = document.createElement('div');
            row.className = 'flex items-center gap-3';

            const slider = document.createElement('input');
            slider.type = 'range';
            slider.className = 'flex-1';

            const badge = document.createElement('span');
            badge.className = 'text-xs px-2 py-0.5 rounded bg-slate-200 text-black';

            // Hidden value saved back to the server
            const hidden = document.createElement('input');
            hidden.style.display = 'none';

            if (hasVh) {
                // VH slider: saves numeric height_vh
                const initialVh = (() => {
                    const pv = pivotOnly.height_vh ?? merged.height_vh;
                    const n = (pv === undefined || pv === null || pv === '') ? NaN : Number(pv);
                    if (!Number.isNaN(n)) return Math.max(0, Math.min(100, n));
                    const m = typeof merged.height_class === 'string' && merged.height_class.match(/^h-\[(\d+)vh\]$/);
                    return m ? Math.max(0, Math.min(100, Number(m[1]))) : 50;
                })();

                slider.min = '20';
                slider.max = '100';
                slider.step = '1';
                slider.value = String(initialVh);
                badge.textContent = `${slider.value}`;

                hidden.type = 'number';
                hidden.dataset.key = 'height_vh';
                hidden.value = String(initialVh);

                slider.addEventListener('input', () => {
                    badge.textContent = `${slider.value}`;
                    hidden.value = slider.value;
                });
            } else if (hasPlainHeight) {
                // Plain CSS height: infer unit and save string with unit
                const raw = (pivotOnly.height ?? merged.height ?? '').toString().trim();

                let unit = 'px';
                let min = 1, max = 300, step = 1, initial = 2;

                let m;
                if ((m = raw.match(/^(\d+)\s*px$/i))) {
                    unit = 'px';
                    initial = Math.max(1, Math.min(300, Number(m[1])));
                    min = 1; max = 300; step = 1;
                } else if ((m = raw.match(/^(\d+)\s*%$/))) {
                    unit = '%';
                    initial = Math.max(0, Math.min(100, Number(m[1])));
                    min = 0; max = 100; step = 1;
                } else if ((m = raw.match(/^(\d+)\s*vh$/i))) {
                    unit = 'vh';
                    initial = Math.max(20, Math.min(100, Number(m[1])));
                    min = 20; max = 100; step = 1;
                } else {
                    // default for unknown: px
                    unit = 'px';
                    initial = 2; min = 1; max = 300; step = 1;
                }

                slider.min = String(min);
                slider.max = String(max);
                slider.step = String(step);
                slider.value = String(initial);
                badge.textContent = `${slider.value}`;

                hidden.type = 'text';
                hidden.dataset.key = 'height';
                hidden.value = `${initial}${unit}`;

                slider.addEventListener('input', () => {
                    badge.textContent = `${slider.value}`;
                    hidden.value = `${slider.value}${unit}`;
                });
            }

            row.appendChild(slider);
            row.appendChild(badge);
            wrapH.appendChild(row);
            wrapH.appendChild(hidden);
            settingsFields.appendChild(wrapH);
        }

        const addField = (key, value) => {
            // Skip rendering of height-related raw inputs (handled by slider above)
            if (hiddenKeys.has(key)) return;
            if (heightRendered && (key === 'height_vh' || key === 'height_class' || key === 'height')) return;

            const wrap = document.createElement('div');
            wrap.className = 'space-y-1';

            const label = document.createElement('label');
            label.className = 'text-sm font-medium text-slate-800 dark:text-slate-100 truncate';
            label.textContent = labelTextFor(key);
            wrap.appendChild(label);

            const current = (pivotOnly[key] ?? value);

            // Image path: show filename only (read-only)
            if (key === 'image_url') {
                const name = (typeof current === 'string' && current) ? current.split('/').pop() : '(default image)';
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'w-full rounded border px-2 py-1 text-sm text-black bg-slate-50';
                input.value = name;
                input.readOnly = true;
                wrap.appendChild(input);
                settingsFields.appendChild(wrap);
                return;
            }

            // Textareas for long text
            if (key === 'subheading' || key === 'body') {
                const ta = document.createElement('textarea');
                ta.className = 'w-full rounded border px-2 py-1 text-sm text-black';
                ta.rows = 3;
                ta.value = current ?? '';
                ta.dataset.key = key;
                wrap.appendChild(ta);
                settingsFields.appendChild(wrap);
                return;
            }

            // Overlay opacity -> slider 0–100 with black badge
            if (key === 'overlay_opacity') {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-3';

                const range = document.createElement('input');
                range.type = 'range';
                range.min = '0';
                range.max = '100';
                range.step = '1';
                range.value = String((current ?? 50));
                range.className = 'flex-1';
                range.dataset.key = key;

                const badge = document.createElement('span');
                badge.className = 'text-xs px-2 py-0.5 rounded bg-slate-200 text-black';
                badge.textContent = `${range.value}`;

                range.addEventListener('input', () => { badge.textContent = `${range.value}`; });

                row.appendChild(range);
                row.appendChild(badge);
                wrap.appendChild(row);
                settingsFields.appendChild(wrap);
                return;
            }

            // Colors (normalized): overlay_color, text_color, bg_color, color, etc. -> hex picker + hex text
            if (/(^|_)color$/.test(key) || key === 'color') {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';

                const color = document.createElement('input');
                color.type = 'color';
                color.className = 'h-8 w-12 p-0 border rounded';
                color.value = isHexColor(current) ? current : '#000000';

                const text = document.createElement('input');
                text.type = 'text';
                text.placeholder = '#000000';
                text.className = 'flex-1 rounded border px-2 py-1 text-sm text-black';
                text.dataset.key = key;
                text.value = isHexColor(current) ? current : '#000000';

                color.addEventListener('input', () => { text.value = color.value; });
                text.addEventListener('input', () => { if (isHexColor(text.value)) color.value = text.value; });

                row.appendChild(color);
                row.appendChild(text);
                wrap.appendChild(row);
                settingsFields.appendChild(wrap);
                return;
            }

            // Legacy class-based colors -> save under normalized keys
            if (key === 'text_color_class' || key === 'bg_color_class') {
                const normalizedKey = key === 'text_color_class' ? 'text_color' : 'bg_color';

                const row = document.createElement('div');
                row.className = 'flex items-center gap-2';

                const color = document.createElement('input');
                color.type = 'color';
                color.className = 'h-8 w-12 p-0 border rounded';
                color.value = isHexColor(current) ? current : '#000000';

                const text = document.createElement('input');
                text.type = 'text';
                text.placeholder = '#000000';
                text.className = 'flex-1 rounded border px-2 py-1 text-sm text-black';
                text.dataset.key = normalizedKey;
                text.value = isHexColor(current) ? current : '#000000';

                color.addEventListener('input', () => { text.value = color.value; });
                text.addEventListener('input', () => { if (isHexColor(text.value)) color.value = text.value; });

                row.appendChild(color);
                row.appendChild(text);
                wrap.appendChild(row);
                settingsFields.appendChild(wrap);
                return;
            }

            // Style type (normalize to style_type)
            if (key === 'style_type' || key === 'style-type') {
                const select = document.createElement('select');
                select.className = 'w-full rounded border px-2 py-1 text-sm text-black';
                select.dataset.key = 'style_type';
                const opts = ['none','hidden','solid','dashed','dotted','double','groove','ridge','inset','outset'];
                const currentVal = (pivotOnly.style_type ?? pivotOnly['style-type'] ?? value) ?? 'solid';
                opts.forEach(o => {
                    const option = document.createElement('option');
                    option.value = o;
                    option.textContent = o;
                    if (o === currentVal) option.selected = true;
                    select.appendChild(option);
                });
                wrap.appendChild(select);
                settingsFields.appendChild(wrap);
                return;
            }

            // Objects/arrays -> JSON textarea
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

            // Dimensional strings like width only (height handled above)
            if (/(^|_)width$/.test(key)) {
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

        // Render all other keys
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
            } else if (el.type === 'number' || el.type === 'range') {
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

    // Save settings (preserve selection)
    settingsSaveBtn?.addEventListener('click', () => {
        const pageElementId = settingsForm.dataset.pageElementId;
        if (!pageElementId) return;

        const settings = collectSettingsFromForm();
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

    // Restore previously selected element after reload
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