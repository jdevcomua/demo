<!doctype html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
</head>
<body>
    <div class="container-fluid">
        @include('layout.header')

        @if(isset($auth) && $auth && $auth->hasNotification() &&
            $notificationBody = $auth->getNotification())
            <a href="{{ route('animals.verify') }}" class="notification">
                <i class="fa fa-bell" aria-hidden="true"></i>
                <div class="notification-content">{!! $notificationBody !!}</div>
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        @endif

        <div class="content-body">
            @yield('content')
        </div>
        @include('layout.footer')
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    @yield('scripts-end')
</body>
</html>