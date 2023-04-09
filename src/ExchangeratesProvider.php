<?php

/**
 * This file contains the ExchangeratesProvider class.
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
 * Class ExchangeratesProvider
 *
 * Provides exchange rates data using the apilayer API.
 * Implements ExchangerInterface for interfacing with exchange rate providers.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
final class ExchangeratesProvider implements ExchangerInterface
{
    private const BASE_URL = 'https://api.apilayer.com/';
    private ApiProvider $_provider;

    /**
     * ExchangeratesProvider constructor.
     *
     * @param ApiProvider $provider An instance of the ApiProvider class.
     */
    public function __construct(ApiProvider $provider)
    {
        $this->_provider = $provider;
        $this->_provider
            ->setBaseUrl(self::BASE_URL)
            ->setHeaders($this->_headers());
    }

    /**
     * Get exchange rates for a base currency with optional symbols filter.
     *
     * @param string     $base    The base currency for which to get exchange rates.
     * @param array|null $symbols Optional symbols filter for which to get exchange rates.
     * 
     * @return array|null Array of exchange rates or null if failed to get data.
     */
    public function getRates(string $base, ?array $symbols = null): ?array
    {
        $params = compact('base');
        if (!empty($symbols)) {
            $params['symbols'] = implode(',', $symbols);
        }

        $answer = $this->_provider->call('exchangerates_data/latest', $params);
        return empty($answer['success']) || empty($answer['rates']) ? null : $answer['rates'];
    }

    /**
     * Get headers for API request.
     *
     * @return array Headers for API request.
     * 
     * @throws Exception If EXCHANGERATES_APIKEY is not found in the environment variables.
     */
    private function _headers(): array
    {
        if (!$apikey = getenv('EXCHANGERATES_APIKEY')) {
            throw new Exception('EXCHANGERATES_APIKEY not found. Setup it in your .ENV file');
        }
        return
            [
                'Accept: application/json',
                'apikey: ' . $apikey
            ];
    }
}
