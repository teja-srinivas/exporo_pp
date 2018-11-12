<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <style>
        @page {
            margin: 20mm 0;
        }

        body {
            width: 240mm;
            margin: 0 auto;
            font-size: 12pt;
            padding-top: 3rem;
        }

        .sheet {
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        @media print {
            body {
                padding: 0 12mm;
                width: 210mm;
            }

            a {
                text-decoration: none !important;
            }
        }
    </style>
</head>
<body class="bg-white">
    <section class="sheet">
        @yield('cover')
    </section>
    <section class="sheet">
        @yield('projects')
        @yield('registrations')
        @yield('footer')
    </section>
</body>
</html>
