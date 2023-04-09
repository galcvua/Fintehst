<?php

use PHPUnit\Framework\TestCase;
use Wowa\Fintehst\BinlistProvider;
use Wowa\Fintehst\ApiProvider;

class BinlistProviderTest extends TestCase
{
    public function testGetCountry()
    {
        $apiProvider = $this->createMock(ApiProvider::class);

        $apiProvider->expects($this->once())
            ->method('call')
            ->with('516874')
            ->willReturn([
                'number' => [],
                'scheme' => 'mastercard',
                'type' => 'debit',
                'brand' => 'Debit Gold',
                'country' => [
                    'numeric' => '804',
                    'alpha2' => 'UA',
                    'name' => 'Ukraine',
                    'emoji' => '🇺🇦',
                    'currency' => 'UAH',
                    'latitude' => 49,
                    'longitude' => 32,
                ],
            ]);

        $binlistProvider = new BinlistProvider($apiProvider);

        $result = $binlistProvider->getCountry('516874');
        $this->assertEquals('UA', $result);
    }
}
