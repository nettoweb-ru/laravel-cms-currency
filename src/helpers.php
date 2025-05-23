<?php

use Netto\Exceptions\NettoException;
use Netto\Services\CurrencyService;

if (!function_exists('convert_currency')) {
    /**
     * Convert currency value.
     *
     * @param int|float $amount
     * @param string $from
     * @param string|null $to
     * @param int $precision
     * @return float
     */
    function convert_currency(int|float $amount, string $from, ?string $to = null, int $precision = 2): float
    {
        return CurrencyService::convert($amount, $from, $to, $precision);
    }
}

if (!function_exists('find_currency_code')) {
    /**
     * Return slug for currency ID.
     *
     * @param int $id
     * @return string|null
     */
    function find_currency_code(int $id): ?string
    {
        foreach (get_currency_list() as $code => $item) {
            if ($item['id'] == $id) {
                return $code;
            }
        }

        return null;
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format currency using locale settings.
     *
     * @param int|float $amount
     * @param string|null $currency
     * @param int $precision
     * @return string
     */
    function format_currency(int|float $amount, ?string $currency = null, int $precision = 2): string {
        if (is_null($currency)) {
            $currency = get_default_currency_code();
        }

        $formatter = new \NumberFormatter(config('locale_full')."@numbers=latn", \NumberFormatter::CURRENCY);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, abs($precision));

        return $formatter->formatCurrency($amount, $currency);
    }
}

if (!function_exists('get_currency_list')) {
    /**
     * Return the list of currencies.
     *
     * @return array
     */
    function get_currency_list(): array
    {
        return CurrencyService::getList();
    }
}

if (!function_exists('get_currency_symbol')) {
    /**
     * @param string $currency
     * @return string
     */
    function get_currency_symbol(string $currency): string
    {
        $formatter = new \NumberFormatter(config('locale_full')."@currency={$currency}", \NumberFormatter::CURRENCY);
        if ($return = $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL)) {
            return $return;
        }

        return '¤';
    }
}

if (!function_exists('get_default_currency_code')) {
    /**
     * Return slug of default currency.
     *
     * @return string
     */
    function get_default_currency_code(): string
    {
        static $return;
        if (is_null($return)) {
            foreach (get_currency_list() as $code => $item) {
                if ($item['is_default']) {
                    $return = $code;
                    break;
                }
            }
        }

        return $return;
    }
}

if (!function_exists('get_default_currency_id')) {
    /**
     * Return ID of default currency.
     *
     * @return int
     */
    function get_default_currency_id(): int
    {
        static $return;
        if (is_null($return)) {
            foreach (get_currency_list() as $code => $item) {
                if ($item['is_default']) {
                    $return = $item['id'];
                    break;
                }
            }
        }

        return $return;
    }
}

if (!function_exists('get_labels_currency')) {
    /**
     * Returns associative array [$id => $slug] for currency list.
     *
     * @return array
     */
    function get_labels_currency(): array
    {
        static $return;
        if (is_null($return)) {
            $return = [];
            foreach (get_currency_list() as $slug => $currency) {
                $return[$currency['id']] = $slug;
            }
        }

        return $return;
    }
}
