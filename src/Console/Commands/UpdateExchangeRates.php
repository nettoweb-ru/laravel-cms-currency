<?php

namespace Netto\Console\Commands;

use Illuminate\Console\Command;
use Netto\Exceptions\NettoException;
use Netto\Services\CurrencyService;

class UpdateExchangeRates extends Command
{
    protected $signature = 'cms:refresh-currency';
    protected $description = 'Update currency rates';

    /**
     * @return void
     */
    public function handle(): void
    {
        try {
            CurrencyService::update();
        } catch (NettoException $exception) {
            $this->error($exception->getMessage());
        }
    }
}
