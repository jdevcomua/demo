<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        @font-face {
            font-family: "Dojo Sans Serif";
            font-style: normal;
            font-weight: normal;
            src: url(http://example.com/fonts/dojosans.ttf) format('truetype');
        }
        * {
            font-family: "Dojo Sans Serif", "DejaVu Sans", sans-serif;
        }
        .page_break { page-break-before: always; }
        .container {
            margin: 2% 1% 0 6%;
        }
        thead tr th:first-child,
        tbody tr td:first-child {
            width: 4%;
            min-width: 4%;
            max-width: 4%;
            word-break: break-all;
        }
        th, td {
            font-size: 14px;
            line-height: 1.4;
        }
        thead tr th:nth-child(2),
        tbody tr td:nth-child(2) {
            width: 15%;
            min-width: 15%;
            max-width: 15%;
            word-break: break-all;
        }
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #000000;
            text-align: left;
            padding: 8px;
        }
        th {
            font-weight: normal;
        }
        table.sign-block {
            margin-top: 20px;
            margin-bottom: 20px;
        }
        table.sign-block th, table.sign-block td {
            border: none;
        }
        table.sign-block .sign-line {
            height:1px;
            border-bottom: 1px solid #000;
        }
    </style>
    @yield('style')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>