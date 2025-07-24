@include('layouts.app')

    <div class="flex flex-row mx-auto">
        <!-- Main Preview Area -->
        <div class="flex-1 border-r-4">
            @foreach ($page->elements as $element)
                @php
                    $view = $element->view_path ?? null;
                    $settings = is_array($element->settings)
                        ? $element->settings
                        : json_decode($element->settings ?? '{}', true);
                @endphp

                @if ($view && View::exists($view))
                    @include($view, $settings)
                @else
                    <div class="bg-red-100 text-red-800 p-4 rounded">
                        Unknown or missing element view: <strong>{{ $element->view_path ?? 'n/a' }}</strong>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Sidebar Editing Form -->
        <div class="p-4">
            <form method="POST" action="{{ route('pages.addElement', $page->id) }}" class="mb-6">
                @csrf
                <select name="element_id" required class="text-black">
                    @foreach ($availableElements as $element)
                        <option value="{{ $element->id }}">{{ $element->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Add Element</button>
            </form>

            <ul id="element-list" class=" rounded-md bg-gray-800" style="user-select: none;">
                @foreach ($elements as $element)
                <li class="p-2 flex items-center justify-between bg-gray-800 group border-b border-black" data-id="{{ $element->pivot->id }}">
                    <div class="flex items-center gap-2">
                        <div class="flex flex-col gap-1">
                            {{-- two buttons to change the order position of the item --}}
                            <button type="button" class="move-up text-gray-400 hover:text-white transition" title="Move up">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button type="button" class="move-down text-gray-400 hover:text-white transition" title="Move down">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <span class="flex-1">{{ $element->name }}</span>
                    </div>
                    <form class="my-auto" method="POST" action="{{ route('pages.removeElement', [$page->id, $element->pivot->id]) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger hover:bg-red-900 transition">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20L18.4199 20.2209C18.3074 21.2337 17.4512 22 16.4321 22H7.56786C6.54876 22 5.69264 21.2337 5.5801 20.2209L4 6Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M7.34491 3.14716C7.67506 2.44685 8.37973 2 9.15396 2H14.846C15.6203 2 16.3249 2.44685 16.6551 3.14716L18 6H6L7.34491 3.14716Z" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M2 6H22" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 11V16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M14 11V16" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </li>
                @endforeach
            </ul>
            <small class="text-gray-500">Use the up and down arrows to reorder elements. Changes are saved automatically.</small>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const elementList = document.getElementById('element-list');

            const saveOrder = () => {
                const order = Array.from(elementList.children).map((item, index) => ({
                    id: item.dataset.id,
                    sort_order: index + 1,
                }));


                fetch('{{ route('pages.updateElementOrder', $page->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ order }),
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.message || 'Network response was not ok');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        console.log('Order updated successfully');
                    } else {
                        console.error('Failed to update order:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                });
            };

            elementList.addEventListener('click', (event) => {
                const button = event.target.closest('button');
                if (!button) return;

                const listItem = button.closest('li');
                if (!listItem) return;

                if (button.classList.contains('move-up')) {
                    const previous = listItem.previousElementSibling;
                    if (previous) {
                        elementList.insertBefore(listItem, previous);
                        saveOrder();
                    }
                } else if (button.classList.contains('move-down')) {
                    const next = listItem.nextElementSibling;
                    if (next) {
                        elementList.insertBefore(next, listItem);
                        saveOrder();
                    }
                }
            });
        });
    </script>

