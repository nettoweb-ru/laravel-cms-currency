<?php

namespace Netto\Services\ExchangeRateProviders\Abstract;

use Netto\Exceptions\NettoException;
use Netto\Models\ExchangeRate;

abstract class ExchangeRatesProvider
{
    /**
     * @param array $codes
     * @param string $slug
     * @return void
     * @throws NettoException
     */
    public function update(array $codes, string $slug): void
    {
        $update = [];
        foreach (get_currency_list() as $code => $currency) {
            if (in_array($code, $codes)) {
                $update[$code] = $currency['id'];
            }
        }

        if (empty($update)) {
            return;
        }

        $list = $this->get($update);
        if (empty($list)) {
            return;
        }

        ExchangeRate::query()->where('provider', '=', $slug)->delete();

        foreach ($list as $item) {
            $item['provider'] = $slug;
            $rate = new ExchangeRate();
            $rate->fill($item);
            $rate->save();
        }
    }

    /**
     * @param array $codes
     * @return array
     * @throws NettoException
     */
    abstract protected function get(array $codes): array;
}
