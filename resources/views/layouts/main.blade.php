<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <<<<<<<<< Temporary merge branch 1 <title>@yield('title', 'Prime Logistics Web')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        =========
        <title>
            @yield('title', 'Prime Logistics Web')
        </title>
        @vite(['resources/js/app.js', 'resources/css/app.css'])
        >>>>>>>>> Temporary merge branch 2
</head>

<body>
    <div id="app">
        @yield('content')
    </div>
</body>

</html>
