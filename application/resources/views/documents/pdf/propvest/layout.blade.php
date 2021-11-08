<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="author" content="{{ config('app.name') }}"/>
    <meta name="date" content="{{ now()->toDateString() }}"/>

    <meta name="api-token" content="">
    <meta name="csrf-token" content="">

    <!-- Styles -->
    <style>
        body {
            color: #333;
            font-family: Arial,sans-serif;
            font-size: 14px;
            line-height: 1.5;

            text-align: justify;
            hyphens: auto;
        }

        a {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>
@yield('content')
</body>
</html>
