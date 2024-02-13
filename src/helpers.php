<?php

use Netto\Services\CurrencyService;

if (!function_exists('format_currency')) {
    /**
     * @param int|float $amount
     * @param string|null $currency
     * @param int $precision
     * @return string
     */
    function format_currency(int|float $amount, ?string $currency = null, int $precision = 2): string {
        if (is_null($currency)) {
            $currency = CurrencyService::getDefaultCode();
        }

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $precision);

        return $formatter->formatCurrency($amount, $currency);
    }
}
