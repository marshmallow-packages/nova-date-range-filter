let mix = require('laravel-mix');

mix.js('resources/js', 'dist/js/date-range-filter.js').webpackConfig({
  resolve: {
    symlinks: false
  }
});
