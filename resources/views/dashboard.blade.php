@include('layouts.app')

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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 justify-center items-center">
        <div
            onclick="window.location='{{ url('/pages') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to Pages"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/pages') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Pages 📃</h2>
            <p class="text-black">Design and manage your pages.</p>
            <span class="font-bold text-green-500 hover:underline mt-2 inline-block">View Pages</span>
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
            <p class="text-black">Manage your uploaded images.</p>
            <span class="font-bold text-blue-500 hover:underline mt-2 inline-block">View Images</span>
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
            <p class="text-black">Customize global website colors.</p>
            <span class="font-bold text-cyan-500 hover:underline mt-2 inline-block">View colors</span>
        </div>
        <div
            onclick="window.location='{{ url('/videos') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to videos"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/videos') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Videos 🎥</h2>
            <p class="text-black">Manage your video library.</p>
            <span class="font-bold text-red-500 hover:underline mt-2 inline-block">View videos</span>
        </div>
        <div
            onclick="window.location='{{ url('/colors') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to Settings"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/colors') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Products 🎨</h2>
            <p class="text-black">Manage your products.</p>
            <span class="font-bold text-purple-500 hover:underline mt-2 inline-block">View products</span>
        </div>
        <div
            onclick="window.location='{{ url('/colors') }}'"
            class="bg-[#FAF9F6] rounded-lg shadow-lg p-5 hover:shadow-2xl transition cursor-pointer transform hover:-translate-y-1 hover:bg-white"
            tabindex="0"
            role="link"
            aria-label="Go to Settings"
            onkeydown="if(event.key==='Enter'){window.location='{{ url('/colors') }}'}"
        >
            <h2 class="text-xl font-semibold mb-2 text-black">Navigation menu 🧭</h2>
            <p class="text-black">Reorder and edit the navigation menu.</p>
            <span class="font-bold text-orange-500 hover:underline mt-2 inline-block">Manage navigation</span>
        </div>
    </div>
</div>