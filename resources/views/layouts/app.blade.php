<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Website')</title>

    {{-- Load CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inject dynamic theme colors from the database --}}
    @php
        $colors = \App\Models\Color::pluck('hex_code', 'variable_name');
    @endphp
    <style>
        :root {
            --primary-color: {{ $colors['primary_color'] ?? '#000000' }};
            --secondary-color: {{ $colors['secondary_color'] ?? '#ffffff' }};
            --background-color: {{ $colors['background_color'] ?? '#f8fafc' }};
        }
    </style>
</head>

<body>

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
</body>

</html>
