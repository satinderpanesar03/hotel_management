<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @vite(['resources/css/app.scss', 'resources/js/main.js'])
    </head>
    <body>
        <div id="app"></div>
        
    </body>
</html>
