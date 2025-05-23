<?php

namespace Netto\Console\Commands;

use Netto\Console\Commands\Abstract\Command as BaseCommand;
use Netto\Exceptions\NettoException;
use Netto\Services\CurrencyService;

class UpdateExchangeRates extends BaseCommand
{
    protected $signature = 'cms:refresh-currency';
    protected $description = 'Update currency rates';

    /**
     * @return void
     */
    protected function action(): void
    {
        try {
            CurrencyService::update();
        } catch (NettoException $exception) {
            $this->error($exception->getMessage());
        }
    }
}
