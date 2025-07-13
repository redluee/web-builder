@include('layouts.app')
@include('layouts.navigation')

{{-- dashboard banner --}}
<div class="bg-cover bg-center h-[25vh] shadow-xl"
    style="background-image: url('{{ asset('/storage/images/Blueprint.jpg') }}');">
    <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-60">
        <h1 class="text-5xl font-bold">
            Dashboard
        </h1>
        <p class="mt-4 text-lg italic max-w-lg text-center">
            Welcome to your dashboard! Here you can manage your account, view statistics, and customize your settings.
        </p>
    </div>
</div>

{{-- dashboard tiles --}}
<div class="max-w-6xl mx-auto p-6 h-[50vh]">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
            onclick="window.location='{{ url('/pages') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to Pages"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/pages') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Pages 📃</h2>
            <p class="text-black">Manage your pages and content.</p>
            <span class="text-blue-500 hover:underline mt-2 inline-block">Go to Pages</span>
        </div>
        <div
            onclick="window.location='{{ url('/images') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="View Images"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/images') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Images 🖼️</h2>
            <p class="text-black">View and manage your uploaded images.</p>
            <span class="text-blue-500 hover:underline mt-2 inline-block">View Images</span>
        </div>
        <div
            onclick="window.location='{{ url('/colors') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to Settings"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/colors') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Colors 🎨</h2>
            <p class="text-black">Customize your account colors and themes.</p>
            <span class="text-blue-500 hover:underline mt-2 inline-block">Go to colors</span>
        </div>
    </div>
</div>