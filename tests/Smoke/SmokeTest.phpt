<?php

namespace AppTests\Smoke;

use AppTests\KdybyHttpServer;
use GuzzleHttp;
use Tester;
use Tester\Assert;

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
        $response   = $httpClient->get($server->getUrl() . $url, $options);

        Assert::same($code, $response->getStatusCode());

        $server->slaughter();
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return [
            ['/',                  200],
            ['/hry',               200],
            ['/filmy',             200],
            ['/knihy',             200],
            ['/clanky',            200],
            ['/galerie',           200],
            ['/videa',             200],
            ['/smrt-hrou',         200],
            ['/uzivatelska-sekce', 302],
            ['/ja',                302],
            ['/odhlasit',          302],
            ['/zadat-nove-heslo',  302],
            ['/aktivovat-ucet',    302],
        ];
    }
}

$testCase = new SmokeTest;
$testCase->run();
