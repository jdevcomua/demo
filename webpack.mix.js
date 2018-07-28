const { mix } = require('laravel-mix');


// FRONT-END ASSETS
mix.js('resources/assets/js/app.js', 'public/js').version();

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.less('node_modules/bootstrap-datepicker/build/build_standalone.less', 'public/css/datepicker.css');
mix.combine([
       'node_modules/selectize/dist/css/selectize.css',
       'public/css/datepicker.css',
       'public/css/app.css'
   ], 'public/css/app.css').version();



// BACK-END ASSETS
mix.js('resources/assets/js/admin.js', 'public/js');

mix.sass('resources/assets/sass/admin/admin.scss', 'public/css');
mix.combine([
    'resources/assets/css/admin/*',
    'public/css/admin.css'
], 'public/css/admin.css');



if (mix.config.inProduction) mix.minify();
