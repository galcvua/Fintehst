<?php

use PHPUnit\Framework\TestCase;
use phpmock\phpunit\PHPMock;
use Wowa\Fintehst\ApiProvider;

class ApiProviderTest extends TestCase
{
    use PHPMock;

    public function testGetRequestSuccess()
    {
        // Mock the curl_exec function to return a sample JSON response
        $curlExecMock = $this->getFunctionMock('Wowa\Fintehst', 'curl_exec');
        $curlExecMock->expects($this->once())
            ->willReturn(json_encode(['data' => 'example']));

        // Mock the curl_getinfo function to return a sample response code
        $curlGetInfoMock = $this->getFunctionMock('Wowa\Fintehst', 'curl_getinfo');
        $curlGetInfoMock->expects($this->once())
            ->willReturn(200);

        // Instantiate ApiProvider
        $apiProvider = new ApiProvider();
        $apiProvider->setBaseUrl('https://api.example.com/');

        // Call the call method with GET request
        $response = $apiProvider->call('endpoint', ['param1' => 'value1'], ApiProvider::REQUEST_GET);

        // Assert the response is not null and has expected data
        $this->assertNotNull($response);
        $this->assertEquals(['data' => 'example'], $response);
    }
}
