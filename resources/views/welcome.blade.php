<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>TEJ</title>
    @vite('resources/js/app.ts')
</head>

<body class="antialiased">
    <div id="app"></div>
    <div id="modal"></div>
</body>

</html>