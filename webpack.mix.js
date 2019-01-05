let mix = require('laravel-mix');
let fs = require('fs');
let argv = require('yargs').argv;
let prod = mix.inProduction();
mix.browserSync({
    proxy: 'budget-pad.lh',
    notify: {
        styles: {
            top: 'auto',
            bottom: '0',
            left: '0',
            right: 'auto'
        }
    }
});

//npm run development -- --env.colors=foobar --env.filename=core.css
let cssFile = '';
if ( argv.env !== undefined && argv.env.colors ) {
    let colors = argv.env.colors.split(',');
    cssFile = argv.env.filename;
    if ( colors.length == 6 ) {
        mix.webpackConfig({
            module: {
                rules: [{
                    test: /\.scss$/,
                    use: [
                        {
                            loader: "@epegzz/sass-vars-loader", options: {
                                syntax: 'scss',
                                vars: {
                                    primaryColor: colors[0],
                                    secondaryColor: colors[1],
                                    successColor: colors[2],
                                    infoColor: colors[3],
                                    warningColor: colors[4],
                                    dangerColor: colors[5]
                                }
                            }
                        }]
                }]
            }
        });
    }
} else {
    cssFile = 'core.css';
}


/*************************************
 JAVASCRIPT
 *************************************/

/*
Vendor Libraries
 */
mix.scripts([
        'node_modules/jquery/dist/jquery.js',
        'node_modules/popper.js/dist/umd/popper.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/jquery-form/dist/jquery.form.min.js',
        'node_modules/noty/lib/noty.js',
        'node_modules/sweetalert2/dist/sweetalert2.js',
        'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'resources/assets/js/vendor/formvalidation/formValidation.popular.min.js',
        'resources/assets/js/vendor/formvalidation/bootstrap4.min.js',
        'resources/assets/js/vendor/jquery.dataTables.js',
        'resources/assets/js/vendor/dataTables.bootstrap4.min.js',
        'resources/assets/js/vendor/jquery.simpler-sidebar.js',
        'resources/assets/js/vendor/jquery-ui.min.js',
        'resources/assets/js/vendor/purl.js'
    ],
    'public/js/vendor.' + (prod ? 'min.' : '') + 'js'
);

/*
Core/Modules
 */
mix.js([
        'resources/assets/js/core.js',
        'resources/assets/js/modules/adminly.js',
    ],
    'public/js/core.' + (prod ? 'min.' : '') + 'js'
);
// js modules
fs.readdirSync('resources/assets/js/modules/').forEach(function(file) {
    let outputFile = prod ? file.replace('.js', '.min.js') : file;
    mix.babel('resources/assets/js/modules/' + file, 'public/assets/js/modules/' + outputFile);
});

/*
Account/React
 */
mix.react('resources/assets/js/react/account.js', 'public/js/account' + (prod ? '.min' : '') + '.js');


/*************************************
 CSS
 *************************************/

/*
Core/Modules
 */
cssFile = prod ? cssFile.replace(/\.css/, '.min.css') : cssFile;
mix.sass('resources/assets/sass/core.scss', 'public/css/' + cssFile);

/*
Account
 */
mix.sass('resources/assets/sass/account.scss', 'public/css/account' + (prod ? '.min' : '') + '.css');


/*************************************
 Copy Files
 *************************************/

mix.copyDirectory('resources/assets/images/', 'public/images/');
mix.copyDirectory('resources/assets/fonts/', 'public/fonts/');