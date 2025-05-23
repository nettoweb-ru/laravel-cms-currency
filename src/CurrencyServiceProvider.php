<?php

namespace Netto;

use Illuminate\Support\Facades\Schedule;
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
            $this->registerPublishedPaths();
        }

        $this->registerScheduledTasks();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadTranslationsFrom(__DIR__.'/../lang');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cms');
    }

    /**
     * @return void
     */
    private function registerPublishedPaths(): void
    {
        $this->publishes([
            __DIR__.'/../config/cms-currency.php' => config_path('cms-currency.php'),
        ], 'nettoweb-laravel-cms-currency');
    }

    /**
     * @return void
     */
    private function registerScheduledTasks(): void
    {
        Schedule::command(UpdateExchangeRates::class)->dailyAt(config('cms.schedule.daily', 1));
    }
}
