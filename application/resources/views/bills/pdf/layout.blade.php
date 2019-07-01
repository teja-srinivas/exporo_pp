<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ config('app.name') }}"/>
    <meta name="date" content="{{ now()->toDateString() }}"/>

    @isset($bill)
    <title>{{ $bill->getFileName() }}</title>
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">

    <!-- Styles -->
    <style>
        {!! @file_get_contents(public_path('css/print.css')) !!}

        footer {
            flow: static(footer);
            font-size: 7pt !important;
            color: #888 !important;
            line-height: 1.2;
        }

        @media screen {
            body {
                margin: 0 auto;
                width: 210mm;
            }

            footer {
                display: none;
            }
        }

        @page {
            size: A4;
            margin: 5mm 10mm 35mm 15mm;
            padding: 0;

            /* setup the footer */
            @bottom {
                content: flow(footer);
            }
        }

        body {
            font-family: 'Open Sans', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.5;
        }

        .sheet {
            page-break-after: always;
        }

        .table-sm td,
        .table-sm th {
            padding-top: 0.125rem;
            padding-bottom: 0.125rem;
            line-height: 1.3;
        }

        h4 {
            prince-bookmark-level: 1;
        }

        h5 {
            font-size: inherit;
            font-family: inherit;
            font-weight: bold;
            line-height: inherit;
        }

        a {
            text-decoration: none !important;
        }
    </style>
</head>
<body class="bg-white mt-5">
    <footer class="bg-transparent text-muted">
        @include('bills.pdf.footer', compact('company'))
    </footer>
    <section class="sheet">
        @yield('cover')
    </section>
    <section class="sheet">
        @yield('content')
    </section>
</body>
</html>
