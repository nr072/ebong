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

        <a style="margin: 1rem; padding: 0.5rem 0.75rem; border: 1px solid #333;" href="/terms">terms</a>

        <a style="margin: 1rem; padding: 0.5rem 0.75rem; border: 1px solid #333;" href="/examples">examples</a>

        <div style="margin: 5em 0;">
            <a style="margin: 1rem; padding: 0.5rem 0.75rem; border: 1px solid #333;" href="/words">words</a>
            <a style="margin: 1rem; padding: 0.5rem 0.75rem; border: 1px solid #333;" href="/sentences">sentences</a>
        </div>

        @livewireScripts

    </body>

</html>
