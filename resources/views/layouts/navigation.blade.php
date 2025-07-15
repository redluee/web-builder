{{-- filepath: /home/stevo/Development/web-builder/resources/views/layouts/navigation.blade.php --}}
<nav
    class="bg-[var(--primary-color,#000)] text-[var(--secondary-color,#fff)] px-4 py-3 flex items-center justify-between">
    <div class="text-lg font-bold flex items-center gap-2">
        <a href="{{ url('/') }}"
            class="hover:underline text-[var(--secondary-color,#fff)]">{{ config('app.name', 'web-builder') }}</a>

        @auth
            <button onclick="window.location.reload();" title="Refresh page" class="ml-2 p-1 rounded hover:rotate-[360deg] transition duration-500">
                {{-- Refresh icon --}}
                <svg fill="var(--secondary-color, #fff)" height="24px" width="24px" version="1.1" id="XMLID_302_"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                    xml:space="preserve" border-width="2">
                    <g id="refresh">
                        <g>
                            <path d="M12,24C5.4,24,0,18.6,0,12S5.4,0,12,0c4,0,7.8,2.1,10,5.4V1h2v8h-8V7h4.7c-1.8-3.1-5.1-5-8.7-5C6.5,2,2,6.5,2,12
                            s4.5,10,10,10s10-4.5,10-10h2C24,18.6,18.6,24,12,24z" />
                        </g>
                    </g>
                </svg>
            </button>
        @endauth
    </div>
    <button id="nav-toggle"
        class="md:hidden p-2 rounded focus:outline-none focus:ring-2 focus:ring-[var(--secondary-color,#fff)]">
        <svg id="nav-toggle-icon" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path id="nav-toggle-bar" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
    <ul id="nav-menu" class="hidden md:flex space-x-6">
        @auth
            <li><a href="{{ url('/dashboard') }}" class="hover:underline text-[#aaa]">Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="hover:underline bg-transparent border-none cursor-pointer text-[#aaa]">Logout</button>
                </form>
            </li>
            <div class="inline-block w-0.5 self-stretch bg-[#aaa] rounded-lg"></div>
        @endauth
        <li><a href="{{ url('/about') }}" class="hover:underline text-[var(--secondary-color,#fff)]">About</a></li>
        <li><a href="{{ url('/contact') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Contact</a></li>
        <li><a href="{{ url('/products') }}" class="hover:underline text-[var(--secondary-color,#fff)]">Products</a>
        </li>
    </ul>
</nav>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navToggle = document.getElementById('nav-toggle');
        const navMenu = document.getElementById('nav-menu');
        navToggle?.addEventListener('click', function() {
            navMenu.classList.toggle('hidden');
        });
    });
</script>
