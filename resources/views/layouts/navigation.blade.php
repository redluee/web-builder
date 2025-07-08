{{-- filepath: /home/stevo/Development/web-builder/resources/views/layouts/navigation.blade.php --}}
<nav class="bg-[var(--primary-color,#000)] text-[var(--secondary-color,#fff)] px-4 py-3 flex items-center justify-between">
    <div class="text-lg font-bold">
        <a href="{{ url('/') }}" class="hover:underline text-[var(--secondary-color,#fff)]">{{ config('app.name', 'web-builder') }}</a>
    </div>
    <button id="nav-toggle" class="md:hidden p-2 rounded focus:outline-none focus:ring-2 focus:ring-[var(--secondary-color,#fff)]">
        <svg id="nav-toggle-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path id="nav-toggle-bar" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <ul id="nav-menu" class="hidden md:flex space-x-6">
        @auth
            <li><a href="{{ url('/dashboard') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Dashboard</a></li>
            <li><a href="{{ url('/profile') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Profile</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:underline bg-transparent border-none cursor-pointer text-[var(--secondary-color,#fff)]">Logout</button>
                </form>
            </li>
        @endauth
        <li><a href="{{ url('/about') }}" class="hover:underline text-[var(--secondary-color,#fff)]">About</a></li>
        <li><a href="{{ url('/contact') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Contact</a></li>
        <li><a href="{{ url('/products') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Products</a></li>
    </ul>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');
        navToggle?.addEventListener('click', function () {
            navMenu.classList.toggle('hidden');
        });
    });
</script>