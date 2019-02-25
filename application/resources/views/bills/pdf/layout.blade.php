<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @isset($bill)
    <title>{{ $bill->getFileName() }}</title>
    @else
    <title>{{ config('app.name') }}</title>
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <style>
        {!! @file_get_contents(public_path('css/app.css')) !!}

        @page {
            size: A4;
            margin: 10mm 0;
        }

        body {
            margin: 0 auto;
            padding-top: 3rem;
            width: 210mm;
        }

        .sheet {
            overflow: hidden;
            position: relative;
            page-break-after: always;
        }

        .table-sm td,
        .table-sm th {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
        }

        h5 {
            font-size: inherit;
            font-family: inherit;
            font-weight: bold;
            line-height: inherit;
        }

        @media print {
            body {
                padding: 0 20mm;
                font-size: 10pt;
            }

            a {
                text-decoration: none !important;
            }
        }
    </style>
</head>
<body class="bg-white mt-5 ">
    <section class="sheet">
        @yield('cover')
    </section>
    <section class="sheet">
        @yield('content')
    </section>
</body>
</html>
