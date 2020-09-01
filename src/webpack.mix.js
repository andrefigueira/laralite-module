require('dotenv').config();

const dotenvExpand = require('dotenv-expand');
dotenvExpand(require('dotenv').config({ path: '../../.env'/*, debug: true*/}));

const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

if (mix.inProduction()) {
    mix.version();
}

mix.js(__dirname + '/Resources/assets/js/admin.js', 'js/admin.js');
mix.sass(__dirname + '/Resources/assets/sass/admin.scss', 'css/admin.css');
