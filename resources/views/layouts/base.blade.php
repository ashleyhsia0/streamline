<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Tasks</title>

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        @yield('stylesheets')

        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>

    <body>
        <div class="container" id="app">
            @yield('content')
        </div>

        <script src="/js/app.js"></script>
        @yield('scripts')
    </body>
</html>
