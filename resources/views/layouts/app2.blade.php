<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BTCC</title>
    {{ Html::style('css/app.css') }}


    @stack('scripts')
    @stack('stylesheets')

</head>
<body id="app-layout">
{{ Html::script('js/vendor.js') }}
@include('_partials._navbar')

@include('flash::message')
@include('_partials._errors')



@yield('content')


<!-- JavaScripts -->
@include('_partials._footer')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
