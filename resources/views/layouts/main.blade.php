<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Prime Logistics Web')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center"
    style="background: radial-gradient(ellipse at bottom right, #FD7121 0%, transparent 50%),
                       radial-gradient(ellipse at top left, #1766B5 0%, transparent 60%),
                       #0D1E35;">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
