<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login · Prime Logistics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center"
    style="background: radial-gradient(ellipse at bottom right, #FD7121 0%, transparent 50%),
                         radial-gradient(ellipse at top left, #1766B5 0%, transparent 60%),
                         #0D1E35;">

    <div id="app">
        <login-components></login-components>
    </div>

</body>

</html>
