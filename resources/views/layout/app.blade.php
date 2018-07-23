<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
    <div class="container-fluid">
        @include('layout.header')
        <div class="content">
            @yield('content')
        </div>
        @include('layout.footer')
    </div>
    <script src="{{ mix('js/main.js') }}"></script>
    @yield('scripts-end')
</body>
</html>