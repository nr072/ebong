<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @livewireStyles

        <link rel="stylesheet" type="text/css" href="css/app.css">

    </head>

    <body>

        <livewire:term-adder />

        <livewire:term-index />

        <livewire:example-adder />

        <livewire:example-index />

        @livewireScripts

    </body>

</html>
