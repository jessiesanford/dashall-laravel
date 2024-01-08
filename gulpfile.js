const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

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
    mix.less([
        'imports.less',
        'base.less',
        'animate.less',
        'global.less',
        'admin.less',
        'order_flow.less',
        'manage.less',
        'driver.less',
        'responsive.less',
        'schedule.less'
    ]);
    mix.webpack('app.js')
    .scripts([
        'plugins/intro.js',
        'plugins/moment.js',
        'plugins/daterangepicker.js',
        'plugins/cookie.js',
        'plugins/mustache.js',
        'plugins/move.js'
    ], 'public/js/plugins.js')
    // lol not all these files should be here, admin and public should be split
    .scripts([
        'global.js',
        'model.js',
        'admin/adminFunctions.js',
        'admin/adminSettings.js',
        'admin/adminOrders.js',
        'admin/adminDrivers.js',
    ], 'public/js/utils.js');
});
