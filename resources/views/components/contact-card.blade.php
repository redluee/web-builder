@props([
    'name' => 'Contact Name',
    'email' => 'contact@example.com',
    'phone' => '123-456-7890',
    'avatar_url' => null,
    'bg_color_class' => '#18181a', 
])

<div class="flex items-center justify-center gap-8 rounded m-4 max-w-4xl mx-auto" style="background-color: {{ $bg_color_class }};">
    @if($avatar_url)
        <img src="{{ $avatar_url }}" class="w-64 h-64 rounded-full object-cover object-top" alt="Avatar">
    @endif

    <div class="border-l-2 border-gray-300 h-24"></div>
    
    <div class="">
        <div class="font-bold text-lg">{{ $name }}</div>
        <div class="text-sm text-gray-400">{{ $email }}</div>
        <div class="text-sm text-gray-400">{{ $phone }}</div>
    </div>
</div>