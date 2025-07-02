<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>

    {{-- Load CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Load Fonts --}}

    {{-- Inject dynamic theme colors --}}
    @php
        $theme = auth()->check() ? auth()->user()->themeSetting : null;
    @endphp
    <style>
        :root {
            --primary-color: {{ $theme->primary_color ?? '#000000' }};
            --secondary-color: {{ $theme->secondary_color ?? '#ffffff' }};
            --background-color: {{ $theme->background_color ?? '#f8fafc' }};
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <header>
        <h1>@yield('header', 'Hello')</h1>
    </header>

    {{-- Main Content --}}
    <main class="container">
        @yield('content')
    </main>
</body>

</html>
