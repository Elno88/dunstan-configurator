const mix = require('laravel-mix');

// Copy directories
mix.copy('resources/assets/img', 'public/img');
mix.copy('resources/assets/fonts', 'public/fonts');
mix.copy('resources/assets/vendor/Rivolicons/WebFont/fonts', 'public/fonts');

// Vendor
mix.styles([
    'resources/assets/vendor/selectric/selectric.css',
    'resources/assets/vendor/jqueryui/jquery-ui.min.css',
    'resources/assets/vendor/jqueryui/jquery-ui-slider-pips.min.css',
    'resources/assets/vendor/jqueryui-flick/jquery-ui.css',
    'resources/assets/vendor/Rivolicons/WebFont/style.css',
], 'public/css/vendor.css');

mix.scripts([
    'resources/assets/vendor/jquery/jquery.min.js',
    'resources/assets/vendor/jqueryui/jquery-ui.min.js',
    'resources/assets/vendor/jqueryui/jquery-ui-slider-pips.min.js',
    'resources/assets/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.min.js',
    'resources/assets/vendor/selectric/jquery.selectric.min.js',
    'resources/assets/vendor/datepicker/jquery.date-dropdowns.js',
    'resources/assets/vendor/jquery-mask/dist/jquery.mask.js'
], 'public/js/vendor.js');

// Konfigurator
mix.styles([
    'resources/assets/css/konfigurator.css'
], 'public/css/konfigurator.css');

mix.scripts([
    'resources/assets/js/konfigurator.js'
], 'public/js/konfigurator.js');


// Auth
mix.js('resources/assets/js/auth.js', 'public/js')
    .postCss('resources/assets/css/auth.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

// Notifications
if(process.env.MIX_NOTIFICATIONS_ENABLED && process.env.MIX_NOTIFICATIONS_ENABLED === 'false'){
    mix.disableNotifications();
}

// Browsersync
if(process.env.MIX_BROWSERSYNC_ENABLED && process.env.MIX_BROWSERSYNC_ENABLED === 'true'){
    mix.browserSync({
        port: process.env.MIX_BROWSERSYNC_PORT || 3000,
        proxy: process.env.MIX_BROWSERSYNC_DOMAIN || 'localhost',
        host: process.env.MIX_BROWSERSYNC_DOMAIN || 'localhost',
        open: process.env.MIX_BROWSERSYNC_NOTIFY === 'true' || false,
        notify: process.env.MIX_BROWSERSYNC_OPEN === 'true' || false
    });
}

// Version files
if (mix.inProduction()) {
    mix.version();
}
