<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="网站介绍">
        <meta name="author" content="naremloa,naremloa@gmail.com">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('/js/alertifyjs/css/alertify.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('/js/alertifyjs/css/themes/default.min.css') }}" rel="stylesheet">
        @yield('link')
        <script src="{{ asset('/js/jquery-3.1.1.min.js') }}"></script>
        @yield('style')
    </head>
    <body>
        @yield('content')
        <script src="{{ asset('/js/alertifyjs/alertify.min.js') }}"></script>
    </body>
</html>