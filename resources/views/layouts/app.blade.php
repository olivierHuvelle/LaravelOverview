<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>LaravelOverview | @yield('title')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        @include('layouts.partials._navigation')
        <main class="container">
            @if(session('success'))
                @include('layouts.partials._flash_alert')
            @endif
            @yield('content')
        </main>
    </body>
</html>
