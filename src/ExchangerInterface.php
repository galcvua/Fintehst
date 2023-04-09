<?php

/**
 * This file contains the ExchangerInterface Interface.
 *
 * PHP version 7.4
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */

namespace Wowa\Fintehst;

/**
 * Interface ExchangerInterface
 *
 * An interface for retrieving currency exchange rates.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
interface ExchangerInterface
{
    /**
     * Get the currency exchange rates for a given base currency and optional symbols.
     *
     * @param string     $base    The base currency for which to retrieve exchange rates.
     * @param array|null $symbols Optional. An array of currency symbols for which to retrieve exchange rates.
     *
     * @return array|null An array of currency exchange rates, or null if not available.
     */
    public function getRates(string $base, ?array $symbols = null): ?array;
}
