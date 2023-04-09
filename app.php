<?php

/**
 * This file is the entry point to the application
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

use Dotenv\Dotenv;
use Throwable;

set_exception_handler(fn (Throwable $exception) => fwrite(STDERR, $exception->getMessage() . PHP_EOL));

require __DIR__ . '/vendor/autoload.php';

if (empty($argv[1])) {
    echo "Usage: php {$argv[0]} filename", PHP_EOL;
    exit;
}

$transactions = new TransactionsParser($argv[1]);

// We need credentials for api providers
Dotenv::createUnsafeImmutable(__DIR__)->safeLoad();

$binlookup = new BinlistProvider(new ApiProvider());
$exchanger = new ExchangeratesProvider(new ApiProvider());

$calculator = new ComissionCalculator($binlookup, $exchanger);
$comissions = $calculator->calculate($transactions->getTransactions());
foreach ($comissions as $comission) {
    echo $comission, PHP_EOL;
}
