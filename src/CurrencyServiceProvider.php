<?php

namespace Netto;

use Illuminate\Support\ServiceProvider;
use Netto\Console\Commands\UpdateExchangeRates;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateExchangeRates::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/cms-currency.php' => config_path('cms-currency.php'),
            ], 'nettoweb-laravel-cms');
        }

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms-currency');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'cms-currency');
    }
}
