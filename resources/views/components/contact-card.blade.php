@props([
    'name' => 'Contact Name',
    'email' => 'contact@example.com',
    'phone' => '123-456-7890',
    'avatar_url' => null,
])

<div class="flex items-center p-4 bg-white rounded shadow">
    @if($avatar_url)
        <img src="{{ $avatar_url }}" class="w-16 h-16 rounded-full mr-4" alt="Avatar">
    @endif
    <div>
        <div class="font-bold text-lg">{{ $name }}</div>
        <div class="text-sm text-gray-600">{{ $email }}</div>
        <div class="text-sm text-gray-600">{{ $phone }}</div>
    </div>
</div>