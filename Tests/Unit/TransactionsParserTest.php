<?php


use PHPUnit\Framework\TestCase;
use Wowa\Fintehst\TransactionsParser;

class TransactionsParserTest extends TestCase
{
    private $filename;
    private $parser;

    protected function setUp(): void
    {
        $this->filename = __DIR__ . '/test_transactions.txt';
        $this->parser = new TransactionsParser($this->filename);
    }

    protected function tearDown(): void
    {
        unset($this->filename);
        unset($this->parser);
    }

    public function testInvalidFilenameThrowsException()
    {
        $this->expectException(Exception::class);
        $parser = new TransactionsParser('nonexistent_file.txt'); // Not exist file
    }

    public function testInvalidFileContentNoReturnTrnasactions()
    {
        $parser = new TransactionsParser(__DIR__ . '/invalid_transactions.txt');
        $transactions = $parser->getTransactions();
        $this->assertIsIterable($transactions);
        $this->assertEquals([], iterator_to_array($transactions));
    }

    public function testGetTransactionsReturnsValidData()
    {
        $transactions = $this->parser->getTransactions();

        $this->assertIsIterable($transactions);

        foreach ($transactions as $transaction) {
            $this->assertIsArray($transaction);
            $this->assertArrayHasKey('bin', $transaction);
            $this->assertArrayHasKey('amount', $transaction);
            $this->assertArrayHasKey('currency', $transaction);
        }
    }
}
