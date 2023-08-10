<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('tab-title')</title>

        @livewireStyles

        <link rel="stylesheet" type="text/css" href="/css/app.css">

        <style type="text/css">
            .topbar {
                background: var(--color-1-dark);
                box-shadow: 0px 3px 6px #555;
                position: fixed;
                width: 100%;
                left: 0;
                top: 0;
                z-index: var(--topbar-z-index);
            }
            .topbar ul {
                padding: 0;
                text-align: center;
            }
            .topbar li {
                list-style: none;
                display: inline-block;
            }
            .topbar li > a {
                font-family: sans-serif;
                font-variant: small-caps;
                padding: 1em 1.5em;
                display: inline-block;
                color: var(--color-1-lightest);
                letter-spacing: 0.1em;
            }
            .topbar li > a:hover {
                color: var(--color-1-link);
                background: var(--color-1-lighter);
            }
            @yield('more-css')
        </style>

    </head>

    <body class="cream">

        <nav class="topbar">
            <ul>
                <li>
                    <a href="{{ route('words-page') }}">words</a>
                </li>
                <li>
                    <a href="{{ route('groups-page') }}">groups</a>
                </li>
                <li>
                    <a href="{{ route('sentence-index-page') }}">sentence index</a>
                </li>
                <li>
                    <a href="{{ route('sentence-add-page') }}">add sentences</a>
                </li>
            </ul>
        </nav>

        @yield('main')

        @livewireScripts

        @yield('js')

    </body>

</html>
