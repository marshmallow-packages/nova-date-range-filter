<?php

namespace Marshmallow\Filters;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'nova-date-range-filter');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/nova-date-range-filter'),
        ], 'nova-date-range-filter-translations');

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-date-range-filter', __DIR__ . '/../dist/js/nova-date-range-filter.js');
            Nova::style('nova-date-range-filter', __DIR__ . '/../dist/css/dark.css');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
