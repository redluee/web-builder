@include('layouts.app')

@php
    $banner = $images->first();
    $bannerText = $textblocks->firstWhere('name', 'welcome.banner');
    $introText = $textblocks->firstWhere('name', 'welcome.intro');
@endphp

@if($banner)
    <div class="bg-cover bg-center h-[50vh] shadow-xl" style="background-image: url('{{ asset($banner->url) }}');">
        <div class="flex flex-col items-center justify-center h-full bg-black bg-opacity-50">
            <h1 class="text-5xl font-bold">
                {{ $bannerText ? $bannerText->content : 'Welcome to Our Website' }}
            </h1>
            <p class="mt-4 text-lg italic max-w-md text-center">
                "{{ $introText ? $introText->content : 'Explore our features and services.' }}"
            </p>
        </div>
    </div>
@else
    <div class="h-[50vh] flex items-center justify-center bg-gray-200">
        <h1 class="text-5xl font-bold">
            {{ $bannerText ? $bannerText->content : 'Welcome to Our Website' }}
        </h1>
    </div>
@endif

@if($introText)
    <div class="max-w-2xl mx-auto mt-8 text-center text-lg">
        <p>{{ $introText->content }}</p>
    </div>
@endif