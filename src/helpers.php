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

        $formatter = new \NumberFormatter(setlocale(LC_MONETARY, '0')."@numbers=latn", \NumberFormatter::CURRENCY);
        $formatter->setAttribute(\NumberFormatter::FRACTION_DIGITS, abs($precision));

        return $formatter->formatCurrency($amount, $currency);
    }
}
