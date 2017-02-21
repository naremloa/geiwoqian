<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="网站介绍">
        <meta name="author" content="naremloa,naremloa@gmail.com">
        <title>@yield('title')</title>
        @yield('link')
        <script src="{{ asset('/js/jquery-3.1.1.min.js') }}"></script>
        @yield('style')
    </head>
    <body>
        @yield('content')
    </body>
</html>