<?php

namespace Netto\Services;

use Netto\Exceptions\NettoException;
use Netto\Models\{Currency, ExchangeRate};
use Netto\Services\ExchangeRateProviders\Abstract\ExchangeRatesProvider;

abstract class CurrencyService
{
    /**
     * Convert currency value.
     *
     * @param int|float $amount
     * @param string $from
     * @param string|null $to
     * @param int $precision
     * @return float
     */
    public static function convert(int|float $amount, string $from, ?string $to = null, int $precision = 2): float
    {
        if (is_null($to)) {
            $to = get_default_currency_code();
        }

        $rates = self::getRates();

        if ($from == $to) {
            return $amount;
        }

        return round(($amount * $rates[$from][$to]['value']), $precision);
    }

    /**
     * Return the list of currencies.
     *
     * @return array
     */
    public static function getList(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [];

            foreach (Currency::all() as $item) {
                $return[$item->slug] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'is_default' => $item->is_default,
                ];
            }
        }

        return $return;
    }

    /**
     * Return list of currency rates.
     *
     * @return array
     */
    public static function getRates(): array
    {
        static $rates;
        if (is_null($rates)) {
            $rates = [];

            // direct
            foreach (ExchangeRate::with(['source', 'target'])->get() as $model) {
                /** @var ExchangeRate $model */
                $rates[$model->source->getAttribute('slug')][$model->target->getAttribute('slug')] = [
                    'date' => $model->getAttribute('date'),
                    'provider' => $model->getAttribute('provider'),
                    'value' => $model->getAttribute('value') / $model->getAttribute('base')
                ];
            }

            foreach ($rates as $key => $value) {
                foreach ($value as $key2 => $value2) {
                    // reverse
                    if (!isset($rates[$key2][$key])) {
                        $source = $rates[$key][$key2];
                        $source['value'] = 1 / $source['value'];
                        $rates[$key2][$key] = $source;
                    }


                    // cross
                    foreach (get_currency_list() as $code => $currency) {
                        if (in_array($code, [$key, $key2])) {
                            continue;
                        }

                        if (!isset($rates[$code][$key])) {
                            $source = $rates[$code][$key2];
                            $source['value'] *= $rates[$key2][$key]['value'];
                            $rates[$code][$key] = $source;
                        }
                    }
                }
            }
        }

        return $rates;
    }

    /**
     * Update currency rates from configured providers.
     *
     * @return void
     * @throws NettoException
     */
    public static function update(): void
    {
        $config = config('cms-currency', []);
        if (empty($config)) {
            return;
        }

        foreach ($config as $item) {
            try {
                /** @var ExchangeRatesProvider $provider */
                $provider = new $item['provider']();
                $provider->update($item['codes'], $item['slug']);
            } catch (\Throwable $throwable) {
                throw new NettoException($throwable->getMessage());
            }
        }
    }
}
