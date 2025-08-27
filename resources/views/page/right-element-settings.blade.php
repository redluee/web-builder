<div id="right-element-settings-root" class="p-4 space-y-3">
    <h2 id="right-element-settings-title" class="text-lg font-semibold mb-3 text-white">Element settings</h2>

    <style>
        /* Force black text for all inputs/selects/textarea in the settings panel */
        #right-element-settings-root input[type="text"],
        #right-element-settings-root input[type="number"],
        #right-element-settings-root input[type="url"],
        #right-element-settings-root input[type="range"],
        #right-element-settings-root textarea,
        #right-element-settings-root select {
            color: #000 !important;
            background-color: #fff;
        }

        #right-element-settings-root input::placeholder,
        #right-element-settings-root textarea::placeholder {
            color: #475569; /* slate-600 */
        }

        /* Quill editor content */
        #right-element-settings-root .ql-container .ql-editor {
            color: #000;
            background-color: #fff;
        }

        /* Quill toolbar high-contrast overrides (scoped) */
        #right-element-settings-root .ql-toolbar.ql-snow {
            background-color: #ffffff;
            border-color: #cbd5e1; /* slate-300 */
            color: #0f172a; /* slate-900 */
        }
        #right-element-settings-root .ql-container.ql-snow {
            border-color: #cbd5e1; /* match toolbar border */
        }
        /* Icons and text in toolbar */
        #right-element-settings-root .ql-toolbar .ql-stroke {
            stroke: #0f172a !important;
        }
        #right-element-settings-root .ql-toolbar .ql-fill {
            fill: #0f172a !important;
        }
        #right-element-settings-root .ql-toolbar .ql-picker-label,
        #right-element-settings-root .ql-toolbar .ql-picker-item {
            color: #0f172a !important;
        }
        /* Hover/active states */
        #right-element-settings-root .ql-toolbar button:hover,
        #right-element-settings-root .ql-toolbar button.ql-active,
        #right-element-settings-root .ql-toolbar .ql-picker-label:hover,
        #right-element-settings-root .ql-toolbar .ql-picker-label.ql-active {
            background-color: #e2e8f0; /* slate-200 */
        }
        #right-element-settings-root .ql-toolbar button:hover .ql-stroke,
        #right-element-settings-root .ql-toolbar button.ql-active .ql-stroke,
        #right-element-settings-root .ql-toolbar .ql-picker-label:hover .ql-stroke,
        #right-element-settings-root .ql-toolbar .ql-picker-label.ql-active .ql-stroke {
            stroke: #0b1220 !important; /* slightly darker */
        }
        /* Picker dropdown */
        #right-element-settings-root .ql-toolbar .ql-picker-options {
            background-color: #ffffff;
            border-color: #cbd5e1;
        }
        #right-element-settings-root .ql-toolbar .ql-picker-options .ql-picker-item:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
        /* Accessible focus */
        #right-element-settings-root .ql-toolbar button:focus-visible,
        #right-element-settings-root .ql-toolbar .ql-picker-label:focus-visible {
            outline: 2px solid #2563eb; /* blue-600 */
            outline-offset: 1px;
        }
    </style>

    <form id="element-settings-form" class="space-y-3">
        <div id="element-settings-fields" class="space-y-3">
            <div class="text-sm text-slate-500">Select an element in the preview to edit its settings.</div>
        </div>

        <div class="flex items-center gap-2">
            <button type="button" id="element-settings-save"
                class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                Save
            </button>
            <button type="button" id="element-settings-remove" class="text-red-600 text-sm px-3 py-1 hover:underline">
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
            removeElementBase: '{{ url('/pages/' . $page->id . '/elements') }}', // + '/{pageElementId}'
            updateSettingsBase: '{{ url('/pages/' . $page->id . '/elements') }}', // + '/{pageElementId}/settings'
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
        const parseJSON = (str, fallback) => {
            try {
                return JSON.parse(str);
            } catch {
                return fallback;
            }
        };
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

        // Helpers for RTE
        const defaultToolbar = [
            ['bold', 'italic', 'underline', 'strike'],
            [{
                header: [1, 2, 3, false]
            }],
            [{
                list: 'ordered'
            }, {
                list: 'bullet'
            }],
            [{
                align: []
            }],
            ['link', 'blockquote', 'code'],
            ['clean']
        ];
        const shouldUseQuillForKey = (key) => {
            // Skip RTE for technical fields
            if (!key) return false;
            const k = String(key).toLowerCase();
            if (k === 'image_url' || k.endsWith('url')) return false;
            if (/(^|_)color$/.test(k) || k === 'color') return false;
            if (/(^|_)width$/.test(k)) return false; // width kept as text
            if (k === 'height' || k === 'height_vh' || k === 'height_class')
        return false; // handled elsewhere if any
            if (k === 'style-type' || k === 'style_type') return false;
            return true; // headings, subheading, body, caption, title, etc.
        };
        const addQuillField = (wrap, key, initialHtml = '') => {
            const host = document.createElement('div');
            host.className = 'bg-white rounded border';
            host.style.minHeight = '100px';

            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.dataset.key = key;

            wrap.appendChild(host);
            wrap.appendChild(hidden);

            const init = () => {
                if (!window.Quill) return;
                const quill = new Quill(host, {
                    theme: 'snow',
                    modules: {
                        toolbar: defaultToolbar
                    }
                });
                quill.clipboard.dangerouslyPasteHTML(initialHtml || '');
                quill.root.style.color = '#000';
                quill.root.style.backgroundColor = '#fff';
                const update = () => {
                    hidden.value = host.querySelector('.ql-editor')?.innerHTML || '';
                };
                quill.on('text-change', update);
                update();
            };
            requestAnimationFrame(init);
        };

        // Render settings fields with normalized widgets
        const renderSettings = (title, merged, pivotOnly, ctx) => {
            settingsTitle.textContent = title;
            settingsFields.innerHTML = '';

            // Height slider (saves numeric height_vh, shows black number, no "vh")
            const hasHeight = ('height_vh' in merged) || ('height_class' in merged);
            if (hasHeight) {
                const wrapH = document.createElement('div');
                wrapH.className = 'space-y-1';

                const labelH = document.createElement('label');
                labelH.className = 'text-sm font-medium text-slate-800 dark:text-slate-100 truncate';
                labelH.textContent = 'Height';
                wrapH.appendChild(labelH);

                const initialVh = (() => {
                    const raw = (pivotOnly.height_vh ?? merged.height_vh);
                    const n = (raw === undefined || raw === null || raw === '') ? NaN : Number(raw);
                    if (!Number.isNaN(n)) return Math.max(0, Math.min(100, n));
                    const m = typeof merged.height_class === 'string' && merged.height_class.match(
                        /^h-\[(\d+)vh\]$/);
                    return m ? Math.max(0, Math.min(100, Number(m[1]))) : 50;
                })();

                const row = document.createElement('div');
                row.className = 'flex items-center gap-3';

                const slider = document.createElement('input');
                slider.type = 'range';
                slider.min = '20';
                slider.max = '100';
                slider.step = '1';
                slider.value = String(initialVh);
                slider.className = 'flex-1';

                const badge = document.createElement('span');
                badge.className = 'text-xs px-2 py-0.5 rounded bg-slate-200 text-black';
                badge.textContent = `${slider.value}`;

                const hidden = document.createElement('input');
                hidden.type = 'number';
                hidden.style.display = 'none';
                hidden.dataset.key = 'height_vh';
                hidden.value = String(initialVh);

                slider.addEventListener('input', () => {
                    badge.textContent = `${slider.value}`;
                    hidden.value = slider.value;
                });

                row.appendChild(slider);
                row.appendChild(badge);
                wrapH.appendChild(row);
                wrapH.appendChild(hidden);
                settingsFields.appendChild(wrapH);
            }

            const addField = (key, value) => {
                // Hide raw height fields; slider above handles them
                if (key === 'height_vh' || key === 'height_class') return;

                const wrap = document.createElement('div');
                wrap.className = 'space-y-1';

                const label = document.createElement('label');
                label.className = 'text-sm font-medium text-slate-800 dark:text-slate-100 truncate';
                label.textContent = key;
                wrap.appendChild(label);

                const current = (pivotOnly[key] ?? value);

                // Objects/arrays -> JSON textarea
                if (value !== null && typeof value === 'object') {
                    const ta = document.createElement('textarea');
                    ta.className = 'w-full rounded border px-2 py-1 text-sm font-mono';
                    ta.rows = 5;
                    ta.value = JSON.stringify(current, null, 2);
                    ta.dataset.key = key;
                    ta.dataset.type = 'json';
                    wrap.appendChild(ta);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // Boolean -> checkbox
                if (typeof value === 'boolean') {
                    const cb = document.createElement('input');
                    cb.type = 'checkbox';
                    cb.className = 'h-4 w-4';
                    cb.checked = Boolean(current);
                    cb.dataset.key = key;
                    wrap.appendChild(cb);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // style-type -> select
                if (key === 'style-type') {
                    const select = document.createElement('select');
                    select.className = 'w-full rounded border px-2 py-1 text-sm';
                    select.dataset.key = key;
                    const opts = ['none', 'hidden', 'solid', 'dashed', 'dotted', 'double', 'groove',
                        'ridge', 'inset', 'outset'
                    ];
                    const cur = current ?? 'solid';
                    opts.forEach(o => {
                        const option = document.createElement('option');
                        option.value = o;
                        option.textContent = o;
                        if (o === cur) option.selected = true;
                        select.appendChild(option);
                    });
                    wrap.appendChild(select);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // Color (hex) -> color picker + text
                if (isHexColor(current) || /color/i.test(key)) {
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2';

                    const color = document.createElement('input');
                    color.type = 'color';
                    color.className = 'h-8 w-12 p-0 border rounded';
                    color.dataset.key = key;
                    color.value = isHexColor(current) ? current : '#000000';

                    const text = document.createElement('input');
                    text.type = 'text';
                    text.className = 'flex-1 rounded border px-2 py-1 text-sm';
                    text.dataset.key = key;
                    text.value = isHexColor(current) ? current : (current ?? '');

                    color.addEventListener('input', () => {
                        text.value = color.value;
                    });

                    row.appendChild(color);
                    row.appendChild(text);
                    wrap.appendChild(row);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // width/height strings -> text input (height_vh handled by slider)
                if (/(^|_)width$/.test(key)) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = 'e.g. 80% or 2px';
                    input.className = 'w-full rounded border px-2 py-1 text-sm';
                    input.value = current ?? '';
                    input.dataset.key = key;
                    wrap.appendChild(input);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // URL-ish fields -> plain text input
                if (/_?url$/i.test(key)) {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.placeholder = 'https://...';
                    input.className = 'w-full rounded border px-2 py-1 text-sm';
                    input.value = current ?? '';
                    input.dataset.key = key;
                    wrap.appendChild(input);
                    settingsFields.appendChild(wrap);
                    return;
                }

                // Content fields -> Quill RTE
                if (shouldUseQuillForKey(key)) {
                    addQuillField(wrap, key, current ?? '');
                    settingsFields.appendChild(wrap);
                    return;
                }

                // Fallback -> plain text input
                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'w-full rounded border px-2 py-1 text-sm';
                input.value = current ?? '';
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
                    body: JSON.stringify({
                        settings
                    }),
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
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
            }).then(() => location.reload());
        });

        // Restore previously selected element after reload
        const toRestore = sessionStorage.getItem(restoreKey);
        if (toRestore && listEl) {
            const li = listEl.querySelector(`li.page-element[data-page-element-id="${toRestore}"]`);
            if (li) {
                selectElement(li);
                li.scrollIntoView({
                    block: 'nearest',
                    inline: 'nearest'
                });
            }
            sessionStorage.removeItem(restoreKey);
        }
    });
</script>
