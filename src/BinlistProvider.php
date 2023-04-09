<?php

/**
 * This file contains the BinlistProvider class.
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
 * Class BinlistProvider
 *
 * A class for retrieving country information for a given BIN using the BinInterface.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
class BinlistProvider implements BinInterface
{
    private const BASE_URL = 'https://lookup.binlist.net/';
    private ApiProvider $_provider;

    /**
     * BinlistProvider constructor.
     *
     * @param ApiProvider $provider An instance of ApiProvider used for making API requests.
     */
    public function __construct(ApiProvider $provider)
    {
        $this->_provider = $provider;
        $this->_provider->setBaseUrl(self::BASE_URL);
    }

    /**
     * Get the country information for a given BIN.
     *
     * @param string $bin The BIN for which to retrieve country information.
     *
     * @return string|null The country information for the given BIN, or null if not found.
     */
    public function getCountry(string $bin): ?string
    {
        $answer = $this->_provider->call($bin);
        return empty($answer['country']['alpha2']) ? null : $answer['country']['alpha2'];
    }
}
