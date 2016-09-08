<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BTCC</title>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Oswald:700,400" rel="stylesheet">

    {{ Html::style('//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css') }}
    {{ Html::style('css/blankon.css') }}


    {{--   {{ Html::style('/assets/global/plugins/bower_components/bootstrap/dist/css/bootstrap.min.css') }}
       {{ Html::style('/assets/global/plugins/bower_components/fontawesome/css/font-awesome.min.css') }}
       {{ Html::style('/assets/global/plugins/bower_components/animate.css/animate.min.css') }}
       {{ Html::style('/assets/global/plugins/bower_components/jquery.gritter/css/jquery.gritter.css') }}
   --}}


    @stack('scripts')
    @stack('stylesheets')

</head>
<body class="page-session page-sound page-header-fixed page-sidebar-fixed" id="app-layout">
{{ Html::script('js/vendor.js') }}
<section id="wrapper">


    {{--
    @include('_partials._navbar')
    --}}

    @include('layouts._header')

    @if(\Auth::check())
        @include('layouts._sidebar_left')
    @endif


    @include('flash::message')
    @include('_partials._errors')


    <section id="page-content">
        @yield('content')
    </section>
</section>
<div id="back-top" class="animated pulse circle">
    <i class="fa fa-angle-up"></i>
</div>
<!-- JavaScripts -->
@include('_partials._footer')
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/blankon.js') }}"></script>
</body>
</html>
