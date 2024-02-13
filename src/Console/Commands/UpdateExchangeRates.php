<?php

namespace Netto\Console\Commands;

use Illuminate\Console\Command;
use Netto\Services\CurrencyService;

class UpdateExchangeRates extends Command
{
    protected $signature = 'cms:currency';
    protected $description = 'Updates currency rates.';

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            CurrencyService::loadRates();
        } catch (\Throwable $throwable) {
            $this->error($throwable->getMessage());
        }
    }
}
