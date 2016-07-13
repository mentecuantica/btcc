<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BTCC</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    @stack('scripts')
    @stack('stylesheets')

</head>
<body id="app-layout">
<script src="{{ asset('js/vendor.js') }}"></script>
@include('_partials._navbar');

@include('flash::message');



@yield('content')


<!-- JavaScripts -->
@include('_partials._footer')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
