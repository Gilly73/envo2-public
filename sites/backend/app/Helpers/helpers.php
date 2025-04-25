<?php
use App\Enums\Currency;

if (! function_exists('symbol_from_currency_code')) {
    /**
     * Get currency symbol.
     *
     * @param  string $currency
     * @return string
     */
    function symbol_from_currency_code(string $currency): string
    {
        $enum = Currency::fromName($currency);
        return $enum ? $enum->value : null;
    }
}
if (! function_exists('amount_divided_by_100')) {
    /**
     * Amount divided by 100.
     *
     * @param  string $currency
     * @return string
     */
    function amount_divided_by_100(int $amount): float
    {
        return $amount / 100;
    }
}