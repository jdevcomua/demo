const { mix } = require('laravel-mix');


mix.js('resources/assets/js/app.js', 'public/js').version();

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.less('node_modules/bootstrap-datepicker/build/build_standalone.less', 'public/css/datepicker.css');
mix.combine([
       'node_modules/selectize/dist/css/selectize.css',
       'public/css/datepicker.css',
       'public/css/app.css'
   ], 'public/css/app.css').version();


if (mix.config.inProduction) mix.minify();

