var elixir = require('laravel-elixir');

require('laravel-elixir-vueify');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.scripts([

        '../bower/jQuery/dist/jquery.min.js',
        '../bower/lodash/dist/lodash.min.js',
        '../bower/bootstrap/dist/js/bootstrap.js',
        '../bower/raphael/raphael.min.js',
        '../vendor/treant/treant.js',
        '../bower/jquery-cookie/jquery.cookie.js',
        '../bower/jquery-nicescroll/jquery.nicescroll.min.js',
      //  '../bower/jquery-easing-original/jquery.easing.js',
        // '../bower/bootbox/bootbox.js',
        '../bower/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js',

        '../bower/jquery.gritter/js/jquery.gritter.min.js'


    ],'public/js/vendor.js');

    mix.scripts([
    'blankon/apps.js',
        'blankon/blankon.dashboard.js'],'public/js/blankon.js');
    mix.browserify(['main.js'],'public/js/app.js');

    mix.less(['app.less','landing.less']);

    mix.less(['app.less', 'blankon/blankon.less'] ,'public/css/blankon.css');

   // mix.style()
    //.version('public/css/app.css');
    //mix.less('landing.less');

});
