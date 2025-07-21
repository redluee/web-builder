@include('layouts.app')

@php
    $colors = $colors ?? [];
@endphp

<div class="bg-cover bg-center h-[25vh] shadow-xl" style="background-image: url('{{ asset('/storage/images/color-banner.jpg') }}');">
    <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-50">
        <h1 class="text-5xl font-bold">
            Color Management
        </h1>
        <p class="mt-4 text-lg italic max-w-md text-center">
            Change the global colors of your website to match your brand or personal style.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 items-stretch">
    @foreach($colors as $color)
        <div id="color-tile-{{ $color->id }}">
            <x-color-edit-tile :color="$color" />
        </div>
    @endforeach
</div>

@include('layouts.footer')

<script>
function attachColorFormListeners() {
    document.querySelectorAll('[id^="color-form-"]').forEach(function(form) {
        form.onsubmit = function(e) {
            e.preventDefault();
            let formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.tile && data.color_id) {
                    document.getElementById('color-tile-' + data.color_id).innerHTML = data.tile;
                    // Re-attach the listener to the new form
                    attachColorFormListeners();
                }
            });
        };
    });
}
document.addEventListener('DOMContentLoaded', attachColorFormListeners);
</script>
