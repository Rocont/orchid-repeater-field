<?php

declare(strict_types=1);

namespace Rocont\OrchidRepeaterField\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Orchid\Platform\Dashboard;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    protected Dashboard $dashboard;

    public function boot(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;

        $this->loadViewsFrom(ORCHID_REPEATER_FIELD_PACKAGE_PATH.'/resources/views', 'platform');

        $this->registerResources()
            ->registerRoutes()
            ->registerTranslations();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    public function register()
    {
        if (! defined('ORCHID_REPEATER_FIELD_PACKAGE_PATH')) {
            define('ORCHID_REPEATER_FIELD_PACKAGE_PATH', realpath(__DIR__.'/../../'));
        }
    }

    protected function registerRoutes(): self
    {
        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/systems'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(realpath(ORCHID_REPEATER_FIELD_PACKAGE_PATH.'/routes/systems.php'));

        return $this;
    }

    protected function bootForConsole()
    {
        $this->publishes([
            ORCHID_REPEATER_FIELD_PACKAGE_PATH.'/resources/views' => base_path('resources/views/vendor/platform'),
        ], 'repeater-field.views');

        $this->publishes([
            ORCHID_REPEATER_FIELD_PACKAGE_PATH.'/public' => public_path('vendor/rocont/orchid-repeater-field'),
        ], ['repeater-field.assets', 'laravel-assets']);
    }

    private function registerResources(): self
    {
        View::composer('platform::app', function () {
            $this->dashboard
                ->registerResource('scripts', asset('vendor/rocont/orchid-repeater-field/js/repeater.js'))
                ->registerResource('stylesheets', asset('vendor/rocont/orchid-repeater-field/css/repeater.css'));
        });

        return $this;
    }

    private function registerTranslations(): void
    {
        $this->loadJsonTranslationsFrom(realpath(ORCHID_REPEATER_FIELD_PACKAGE_PATH.'/resources/lang/'));
    }
}
