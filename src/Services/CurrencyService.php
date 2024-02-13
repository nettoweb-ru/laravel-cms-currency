<?php

namespace Netto\Services;

use Netto\Models\Currency;
use Netto\Models\ExchangeRate;
use Netto\Services\RateProviders\CbrRussia;
use Throwable;

abstract class CurrencyService
{
    public const DEFAULT = 'RUB';

    /**
     * @param int|float $amount
     * @param string $from
     * @param string $to
     * @param int $precision
     * @return int|float|null
     */
    public static function convertValue(int|float $amount, string $from, string $to, int $precision = 4): int|float|null
    {
        $rates = self::getRates();

        if ($from == $to) {
            return $amount;
        }

        if (!isset($rates[$from][$to])) {
            return null;
        }

        return round($amount * $rates[$from][$to], $precision);
    }

    /**
     * @return string
     */
    public static function getDefaultCode(): string
    {
        static $return;

        if (is_null($return)) {
            foreach (self::getList() as $code => $item) {
                if ($item['is_default']) {
                    $return = $code;
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * @return int
     */
    public static function getDefaultId(): int
    {
        static $return;

        if (is_null($return)) {
            foreach (self::getList() as $item) {
                if ($item['is_default']) {
                    $return = $item['id'];
                    break;
                }
            }
        }

        return $return;
    }

    /**
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
     * @return array
     */
    public static function getRates(): array
    {
        static $rates;

        if (is_null($rates)) {
            $rates = [];
            foreach (ExchangeRate::with(['source', 'target'])->get() as $model) {
                $rates[$model->target->slug][$model->source->slug] = $model->value;
            }
        }

        return $rates;
    }

    /**
     * @return void
     * @throws Throwable
     */
    public static function loadRates(): void
    {
        $class = config('cms-currency.provider', CbrRussia::class);
        $service = new $class();

        $list = [];
        foreach (self::getList() as $code => $item) {
            $list[$code] = $item['id'];
        }

        if (!array_key_exists(self::DEFAULT, $list)) {
            $model = new Currency();
            $model->setAttribute('name', self::DEFAULT);
            $model->setAttribute('slug', self::DEFAULT);
            $model->save();

            $list[$model->slug] = $model->id;
        }

        $service->load($list);
    }
}
