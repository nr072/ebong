<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('tab-title')</title>

        @livewireStyles

        <link rel="stylesheet" type="text/css" href="css/app.css">

        <style type="text/css">
            @yield('more-css')
        </style>

    </head>

    <body>

        @yield('main')

        @livewireScripts

        @yield('js')

    </body>

</html>
