<?php

namespace AppTests\Smoke;

use AppTests\KdybyHttpServer;
use GuzzleHttp;
use Tester\Assert;
use Tester;

require __DIR__ . '/bootstrap.php';

/**
 * @testCase
 */
class SmokeTest extends Tester\TestCase
{
    /**
     * @dataProvider getUrls
     *
     * @param string $url
     * @param int    $code
     */
    public function testAccess($url, $code)
    {
        $server = new KdybyHttpServer;
        $server->start(__DIR__ . '/../../www/index.php');

        $options = [
            'http_errors'     => false,
            'allow_redirects' => false,
        ];

        $httpClient = new GuzzleHttp\Client;
        $response = $httpClient->get($server->getUrl() . $url, $options);

        Assert::same($code, $response->getStatusCode());

        $server->slaughter();
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return [
            ['/', 200],
            ['/smrt-hrou', 200],
            ['/uzivatelska-sekce', 302],
        ];
    }
}

$testCase = new SmokeTest;
$testCase->run();
