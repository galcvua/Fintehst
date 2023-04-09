<?php

/**
 * This file contains the ComissionCalculator class.
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

use Exception;

/**
 * Class ComissionCalculator
 *
 * A commission calculator for processing transactions with different currencies and determining fees based on country codes.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
class ComissionCalculator
{
    private const BASE_CURRENCY = 'EUR';
    private const BIN_CACHE_SIZE = 1000;

    private BinInterface $_binlookup;
    private ExchangerInterface $_exchanger;

    private array $_bins = [];

    private array
        $_euCC = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU',
            'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK'
        ];

    /**
     * ComissionCalculator constructor.
     *
     * @param BinInterface       $binlookup The binlookup object used for country code lookup.
     * @param ExchangerInterface $exchanger The exchanger object used for currency exchange rates.
     */
    public function __construct(BinInterface $binlookup, ExchangerInterface $exchanger)
    {
        $this->_binlookup = $binlookup;
        $this->_exchanger = $exchanger;
    }

    /**
     * Calculate the commission fee for each transaction in the provided iterable.
     *
     * @param iterable $transactions The iterable containing the transactions to calculate the commission fee for.
     * 
     * @return iterable The iterable containing the calculated commission fees for each transaction.
     * 
     * @throws Exception If exchange rates are not available.
     */
    public function calculate(iterable $transactions): iterable
    {
        $rates = [];

        foreach ($transactions as $transaction) {
            $bin = substr($transaction['bin'], 0, 6);
            $cc = $this->_bins[$bin] ?? '';
            if (empty($cc)) {
                $cc = $this->_binlookup->getCountry($bin);
                if (empty($cc)) {
                    yield "*** Can't determine country code for '$bin'";
                    continue;
                } else {
                    if (count($this->_bins) >= self::BIN_CACHE_SIZE) {
                        array_shift($this->_bins);
                    }
                    $this->_bins[$bin] = $cc;
                }
            }

            $currency = $transaction['currency'];
            if ($currency == self::BASE_CURRENCY) {
                $amount_eur = $transaction['amount'];
            } else {
                if (empty($rates)) {
                    // echo 'Get rates...';
                    $rates = $this->_exchanger->getRates(self::BASE_CURRENCY);
                    if (empty($rates)) {
                        throw new Exception('Exchange rates are not available right now');
                    }
                    // echo ' Ok', PHP_EOL;
                }
                if (empty($rates[$currency])) {

                    yield "*** Can't determine rate for $currency";
                    continue;
                } else {
                    $amount_eur = floatval($transaction['amount']) / $rates[$currency];
                }
            }
            $fee = in_array($cc, $this->_euCC) ? 0.01 : 0.02;
            yield ceil($amount_eur * $fee * 100) / 100;
        }
    }
}
