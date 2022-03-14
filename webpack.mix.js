// webpack.mix.js
const mix = require('laravel-mix');
const path = require('path');

mix.setPublicPath('assets/dist');

mix.js('assets/src/scripts/field.js', 'scripts/');

mix.sass('assets/src/styles/field.scss', 'styles/');

mix.extract();

mix.alias({
  '@node_modules': path.join(__dirname, '/node_modules'),
});

if (mix.inProduction()) {
  mix.version();
}
