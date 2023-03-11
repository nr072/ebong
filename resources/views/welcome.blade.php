<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @livewireStyles

        <link rel="stylesheet" type="text/css" href="css/app.css">

        <style type="text/css">
            a.temp-nav {
                margin: 1rem;
                padding: 0.5rem 0.75rem;
                border: 1px solid #333;
            }
        </style>

    </head>

    <body>

        <div style="margin: 5rem 0;">
            <a class="temp-nav" href="{{ route('words-page') }}">words</a>
            <a class="temp-nav" href="{{ route('sentences-page') }}">sentences</a>
        </div>

        @livewireScripts

    </body>

</html>
