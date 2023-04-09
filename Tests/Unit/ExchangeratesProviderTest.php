<?php

use PHPUnit\Framework\TestCase;
use Wowa\Fintehst\ExchangeratesProvider;
use Wowa\Fintehst\ApiProvider;

class ExchangeratesProviderTest extends TestCase
{
    protected function setUp(): void
    {
        putenv('EXCHANGERATES_APIKEY=1');
    }

    public function testGetRatesReturnsNullWhenApiCallFails()
    {
        $apiProviderMock = $this->createMock(ApiProvider::class);
        $apiProviderMock->expects($this->once())
            ->method('call')
            ->willReturn([]);

        $exchangeratesProvider = new ExchangeratesProvider($apiProviderMock);

        $result = $exchangeratesProvider->getRates('EUR', ['USD']);

        $this->assertNull($result);
    }

    public function testGetRatesReturnsNullWhenApiCallReturnsEmptyRates(): void
    {
        // Mock the ApiProvider class
        $apiProvider = $this->createMock(ApiProvider::class);
        $apiProvider->method('call')->willReturn(['success' => true, 'rates' => null]);

        // Create an instance of ExchangeratesProvider with the mocked ApiProvider
        $exchangeratesProvider = new ExchangeratesProvider($apiProvider);

        // Call the getRates method with test data
        $base = 'EUR';
        $symbols = ['USD', 'GBP', 'JPY'];
        $result = $exchangeratesProvider->getRates($base, $symbols);

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testGetRatesReturnsArrayOfRatesWhenApiCallReturnsRates(): void
    {
        // Mock the ApiProvider class
        $apiProvider = $this->createMock(ApiProvider::class);
        $apiProvider->method('call')->willReturn([
            'success' => true,
            'rates' => [
                'USD' => 1.2,
                'GBP' => 0.9,
                'JPY' => 130.0
            ]
        ]);

        // Create an instance of ExchangeratesProvider with the mocked ApiProvider
        $exchangeratesProvider = new ExchangeratesProvider($apiProvider);

        // Call the getRates method with test data
        $base = 'EUR';
        $symbols = ['USD', 'GBP', 'JPY'];
        $result = $exchangeratesProvider->getRates($base, $symbols);

        // Assert that the result is an array
        $this->assertIsArray($result);

        // Assert that the result has the correct keys
        $this->assertArrayHasKey('USD', $result);
        $this->assertArrayHasKey('GBP', $result);
        $this->assertArrayHasKey('JPY', $result);

        // Assert that the result has the correct values
        $this->assertEquals(1.2, $result['USD']);
        $this->assertEquals(0.9, $result['GBP']);
        $this->assertEquals(130.0, $result['JPY']);
    }

    public function testGetRatesReturnsNullWhenApiCallReturnsError(): void
    {
        // Mock the ApiProvider class
        $apiProvider = $this->createMock(ApiProvider::class);
        $apiProvider->method('call')->willReturn(['success' => false, 'rates' => null]);

        // Create an instance of ExchangeratesProvider with the mocked ApiProvider
        $exchangeratesProvider = new ExchangeratesProvider($apiProvider);

        // Call the getRates method with test data
        $base = 'EUR';
        $symbols = ['USD', 'GBP', 'JPY'];
        $result = $exchangeratesProvider->getRates($base, $symbols);

        // Assert that the result is null
        $this->assertNull($result);
    }

    public function testGetRatesThrowsExceptionWhenApikeyNotSet(): void
    {
        // Remove the EXCHANGERATES_APIKEY environment variable
        putenv('EXCHANGERATES_APIKEY');

        // Create an instance of ExchangeratesProvider without the API key
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('EXCHANGERATES_APIKEY not found. Setup it in your .ENV file');

        // Call the getRates method with test data
        $base = 'EUR';
        $symbols = ['USD', 'GBP', 'JPY'];
        $exchangeratesProvider = new ExchangeratesProvider(new ApiProvider());
        $exchangeratesProvider->getRates($base, $symbols);
    }
}
