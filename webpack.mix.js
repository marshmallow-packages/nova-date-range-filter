let mix = require("laravel-mix");

mix.setPublicPath("dist")
    .js("resources/js", "js/nova-date-range-filter.js")
    .vue();