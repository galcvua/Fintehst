<?php

/**
 * This file contains the ApiProvider class.
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
 * Class ApiProvider
 *
 * A class for making HTTP requests to an API with support for different HTTP methods, query parameters, and headers.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
class ApiProvider
{
    public const REQUEST_GET = 'GET';
    public const REQUEST_POST = 'POST';

    public const HTTP_CODE_SUCCESS = 200;
    private int $_responseCode = 0;

    private int $_timeout = 30;

    private string $_baseUrl = '';
    private array $_headers = [];

    /**
     * Make a request to the API.
     *
     * @param string     $method The API method to call.
     * @param array|null $params The query parameters to include in the request.
     * @param string     $type   The type of HTTP request (GET or POST).
     *
     * @return array|null The decoded response from the API, or null if an error occurred.
     */
    public function call(string $method, array $params = null, string $type = self::REQUEST_GET): ?array
    {
        $options = [
            CURLOPT_URL             => $this->_baseUrl . $method,
            CURLOPT_CUSTOMREQUEST   => $type,
            CURLOPT_CONNECTTIMEOUT  => $this->_timeout,
            CURLOPT_TIMEOUT         => $this->_timeout,
            CURLOPT_RETURNTRANSFER  => true,
        ];

        $ch = curl_init();

        if ($type == self::REQUEST_GET) {
            $options[CURLOPT_URL] = $this->_baseUrl . $method . (empty($params) ? '' : '?' . http_build_query($params));
        } else {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($params);
        }

        $options[CURLOPT_HTTPHEADER] = $this->_headers;

        curl_setopt_array($ch, $options);

        $api_raw = curl_exec($ch);
        $this->_responseCode = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if (is_string($api_raw) && $this->_isCodeSucces($this->_responseCode)) {
            return json_decode($api_raw, true);
        }

        return null;
    }

    /**
     * Check if a given HTTP response code indicates success.
     *
     * @param int $responseCode The HTTP response code to check.
     *
     * @return bool True if the response code indicates success, false otherwise.
     */
    private function _isCodeSucces(int $responseCode): bool
    {
        return round($responseCode, -2) == self::HTTP_CODE_SUCCESS;
    }

    /**
     * Set custom headers to include in the API request.
     *
     * @param array $headers An array of headers to set.
     *
     * @return ApiProvider Returns $this for method chaining.
     */
    public function setHeaders(array $headers): ApiProvider
    {
        $this->_headers = $headers;
        return $this;
    }

    /**
     * Get the last HTTP response code received from the API.
     *
     * @return int The last HTTP response code.
     */
    public function getLastResponseCode(): int
    {
        return $this->_responseCode;
    }

    /**
     * Set the base URL for the API.
     *
     * @param string $baseUrl The base URL for the API.
     *
     * @return ApiProvider Returns $this for method chaining.
     */
    public function setBaseUrl(string $baseUrl): ApiProvider
    {
        $this->_baseUrl = $baseUrl;
        return $this;
    }
}
