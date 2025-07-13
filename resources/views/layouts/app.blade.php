<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteTitle ?? 'WebBuilder')</title>

    {{-- Load CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: {{ $colors['primary_color'] ?? '#000000' }};
            --secondary-color: {{ $colors['secondary_color'] ?? '#ffffff' }};
            --background-color: {{ $colors['background_color'] ?? '#f8fafc' }};
        }
    </style>
</head>

<body>
    <main>
        @yield('content')
    </main>
</body>

</html>
