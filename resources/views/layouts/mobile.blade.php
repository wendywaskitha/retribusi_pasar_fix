<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes" />
    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    @vite(['resources/css/app.css'])

    <!-- Scripts -->
    <script type="module" src="https://unpkg.com/@ionic/pwa-elements@latest/dist/ionicpwaelements/ionicpwaelements.esm.js"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div id="app">
        @yield('content')
    </div>
</body>
</html>
