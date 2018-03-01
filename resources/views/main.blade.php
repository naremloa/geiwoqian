<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="网站介绍">
        <meta name="author" content="naremloa,naremloa@gmail.com">
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/global/main.1.0.0.css') }}">
        <link rel="stylesheet" href="{{ asset('/js/alertifyjs/css/alertify.min.css') }}">
        <link rel="stylesheet" href="{{ asset('/js/alertifyjs/css/themes/default.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('js/simditor-2.3.6/styles/simditor.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('js/simditor-2.3.6/styles/local_simditor.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome-4.7.0/css/font-awesome.min.css') }}">
        @yield('link')
        <script src="{{ asset('/js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ asset('/js/vue.js') }}"></script>
        <script src="{{ asset('/js/artTemplate.js') }}"></script>
        <script src="{{ asset('/js/alertifyjs/alertify.min.js') }}"></script>
        <script src="{{ asset('/js/upload/jquery.ui.widget.js') }}"></script>
        <script src="{{ asset('/js/upload/jquery.iframe-transport.js') }}"></script>
        <script src="{{ asset('/js/upload/jquery.fileupload.js') }}"></script>
        <script src="{{ asset('/js/simditor-2.3.6/scripts/module.min.js') }}"></script>
        <script src="{{ asset('/js/simditor-2.3.6/scripts/hotkeys.min.js') }}"></script>
        <script src="{{ asset('/js/simditor-2.3.6/scripts/uploader.min.js') }}"></script>
        <script src="{{ asset('/js/simditor-2.3.6/scripts/simditor.min.js') }}"></script>
        @yield('style')
    </head>
    <body>
        @yield('content')
    </body>
</html>