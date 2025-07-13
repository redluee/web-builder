{{-- filepath: /home/stevo/Development/web-builder/resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-4rem)]">
    <form method="POST" action="{{ route('login') }}" class="bg-white rounded-lg shadow-lg p-5 w-full max-w-md m-5">
        @csrf
        <h2 class="text-2xl font-bold mb-6 text-center text-black">Login to {{ config('app.name') }}</h2>
        <div class="mb-4">
            <input id="email" type="email" name="email" required autofocus autocomplete="username" placeholder="Enter your email"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary-500 text-black" />
        </div>
        <div class="mb-6">
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"
                class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-primary-500 text-black" />
        </div>
        <button type="submit"
            class="w-full py-2 px-4 bg-[var(--primary-color,#000)] text-[var(--secondary-color,#fff)] rounded font-semibold hover:opacity-90 transition">
            Login
        </button>
    </form>
</div>
@endsection