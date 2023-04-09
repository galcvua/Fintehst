<?php

/**
 * This file contains the TransactionsParser class.
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
 * Class TransactionsParser
 *
 * A class for parsing transactions from a file.
 *
 * @category PHP
 * @package  Wowa\Fintehst
 * @author   Wowa <vova@gal.cv.ua>
 * @license  https://github.com/galcvua/fintehst/license.txt Candidate Assessment License
 * @link     https://github.com/galcvua/fintehst
 */
class TransactionsParser
{
    private string $_filename;

    /**
     * TransactionsParser constructor.
     *
     * @param string $filename The filename of the transactions file.
     *
     * @throws Exception If the file is not readable or does not exist.
     */
    public function __construct(string $filename)
    {
        if (empty($filename) || !is_file($filename) || !is_readable($filename)) {
            throw new Exception("Invalid file '$filename'");
        }

        $this->_filename = $filename;
    }

    /**
     * Get the transactions from the file.
     *
     * @return iterable An iterable of transactions.
     *
     * @throws Exception If the file cannot be opened.
     */
    public function getTransactions(): iterable
    {
        if (!$file = fopen($this->_filename, 'r')) {
            throw new Exception("Can not open the file '{$this->_filename}'");
        }

        while (($line = fgets($file)) !== false) {
            $transaction = json_decode($line, true);
            if (empty($transaction)) {
                continue;
            }
            if ($this->_transactionValidator($transaction)) {
                yield $transaction;
            }
            // else: log, throw or other strategy
        }
        fclose($file);
    }

    /**
     * Validate a transaction array against predefined rules.
     *
     * @param array $transaction The transaction array to validate.
     *
     * @return bool True if the transaction is valid, false otherwise.
     */
    private function _transactionValidator(array $transaction): bool
    {
        $rules = [
            'bin' => fn ($x): bool => is_string($x) && preg_match('/^\d{6,}$/', $x),
            'amount' => fn ($x): bool => is_string($x) && preg_match('/^\d+(\.\d{1,2})?$/', $x),
            'currency' => fn ($x): bool => is_string($x)
        ];

        foreach ($rules as $field => $rule) {
            if (!array_key_exists($field, $transaction) || !$rule($transaction[$field])) {
                return false;
            }
        }

        return true;
    }
}
