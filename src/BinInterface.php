<?php

/**
 * This file contains the BinInterface Interface.
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
 * Interface BinInterface
 *
 * An interface for retrieving country information based on a given BIN (Bank Identification Number).
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
interface BinInterface
{
    /**
     * Get the country information for a given BIN.
     *
     * @param string $bin The BIN for which to retrieve country information.
     *
     * @return string|null The country information for the given BIN, or null if not found.
     */
    public function getCountry(string $bin): ?string;
}
