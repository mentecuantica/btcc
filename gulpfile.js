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
        '../bower/raphael/raphael.min.js',
        '../vendor/treant/treant.js'
        

    ],'public/js/vendor.js');
    
    mix.scripts(['binary-tree-functions.js'],'public/js/app.js');
    
    mix.less(['app.less','landing.less']);

   // mix.style()
    //.version('public/css/app.css');
    //mix.less('landing.less');

});
