<?php

use PHPUnit\Framework\TestCase;
use Wowa\Fintehst\ComissionCalculator;
use Wowa\Fintehst\BinInterface;
use Wowa\Fintehst\ExchangerInterface;

class ComissionCalculatorTest extends TestCase
{
    private $comissionCalculator;

    protected function setUp(): void
    {
        $exchanger = $this->createMock(ExchangerInterface::class);
        $binlookup = $this->createMock(BinInterface::class);

        $exchanger->method('getRates')->willReturn([
            'EUR' => 1.00,
            'USD' => 1.099621,
            'GBP' => 0.885292,
        ]);

        $binlookup->method('getCountry')->willReturnMap([
            ['123456', 'FR'],
            ['987654', 'LT'],
            ['333666', 'US'],
        ]);
        // Set up dependencies for ComissionCalculator
        $this->comissionCalculator = new ComissionCalculator($binlookup, $exchanger);
    }

    public function testCalculateWithValidTransactions()
    {
        // Create an iterable of valid transactions with different countries
        $transactions = [
            ['bin' => '123456789012', 'currency' => 'EUR', 'amount' => 100.0],
            ['bin' => '987654321098', 'currency' => 'USD', 'amount' => 50.0],
            ['bin' => '33366600', 'currency' => 'USD', 'amount' => 50.0]
        ];

        // Call the calculate method and store the results in an array
        $result = iterator_to_array($this->comissionCalculator->calculate($transactions));

        // Assert that the result has the expected values
        $this->assertEquals(1.00, $result[0]); // 1% fee for EUR transaction with European country
        $this->assertEquals(0.46, $result[1]); // 1% fee for USD transaction with European country
        $this->assertEquals(0.91, $result[2]); // 2% fee for USD transaction with non-European country
    }

    public function testCalculateWithInvalidTransactions()
    {
        // Create an iterable of invalid transactions
        $transactions = [
            ['bin' => '9234567890', 'currency' => 'EUR', 'amount' => 100.0], // Invalid bin
            ['bin' => '987654321098', 'currency' => 'JPY', 'amount' => 50.0] // Invalid currency
        ];

        // Call the calculate method and store the results in an array
        $result = iterator_to_array($this->comissionCalculator->calculate($transactions));

        // Assert that the result has the expected values
        $this->assertEquals("*** Can't determine country code for '923456'", $result[0]); // Invalid bin error message
        $this->assertEquals("*** Can't determine rate for JPY", $result[1]); // Invalid currency error message
    }
}
