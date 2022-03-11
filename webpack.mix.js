const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
]);

mix.styles([
    'resources/assets/css/core/libs.min.css',
    'resources/assets/css/gigz.min.css',
    'resources/assets/css/custom.min.css',
    'resources/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css',
    'resources/assets/plugin/select/css/select2.min.css'
], 'public/css/app-1.css').js([
    /* "resources/assets/js/core/libs.min.js", */
    /* <!-- Library Bundle Script --> */
    /*  "resources/assets/js/core/external.min.js", */
    /* <!-- External Library Bundle Script --> */
    /*  "resources/assets/js/charts/widgetcharts.js", */
    /* <!-- Widgetchart Script --> */
    /* "resources/assets/js/charts/vectore-chart.js", */
    /* <!-- mapchart Script --> */
    /*   "resources/assets/js/charts/dashboard.js", */
    /*  "resources/assets/js/plugins/fslightbox.js", */
    /* <!-- fslightbox Script --> */
    /* <!-- GSAP Animation --> */
    /* "resources/assets/vendor/gsap/gsap.min.js",
    "resources/assets/vendor/gsap/ScrollTrigger.min.js",
    "resources/assets/js/gsap-init.js", */
    /* <!-- Form Wizard Script --> */
    /* "resources/assets/js/plugins/form-wizard.js", */
    /* <!-- App Script --> */
    /*  "resources/assets/js/gigz.js" */
    ,
], 'public/js/app-1.js');
