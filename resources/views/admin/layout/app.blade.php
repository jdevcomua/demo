<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>RHA Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ mix('css/admin.css') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">

    @yield('styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body data-spy="scroll" data-target="#nav-spy" data-offset="300">

    <div id="main">
        @include('admin.layout.header')
        @include('admin.layout.sidebar')
        <section id="content_wrapper">
            @yield('content')
        </section>
    </div>

    <script src="{{ mix('js/admin.js') }}"></script>
    <script src="/js/admin/jquery-ui.min.js"></script>
    <script src="/js/admin/datepicker-uk.js"></script>
    <script src="/js/admin/utility.js"></script>
    <script src="/js/admin/main.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            "use strict";
            Core.init();
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    <script src="/js/admin/jquery.dataTables.js"></script>
    <script src="/js/admin/dataTables.tableTools.min.js"></script>
    <script src="/js/admin/dataTables.bootstrap.js"></script>
    @yield('scripts-end')
</body>
</html>