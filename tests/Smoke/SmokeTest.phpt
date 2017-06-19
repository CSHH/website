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
     * @dataProvider getParams
     *
     * @param string $path
     * @param int    $code
     */
    public function testAccess($path, $code)
    {
        $server = new KdybyHttpServer;
        $server->start(__DIR__ . '/../../www/index.php');

        $options = [
            'http_errors'     => false,
            'allow_redirects' => false,
        ];

        $httpClient = new GuzzleHttp\Client;
        $response   = $httpClient->get($server->getUrl() . $path, $options);

        Assert::same($code, $response->getStatusCode());

        $server->slaughter();
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return [
            ['/',                                      200],
            ['/hry',                                   200],
            ['/hry/tag',                               404],
            ['/hry/tag/slug',                          404],
            ['/filmy',                                 200],
            ['/filmy/tag',                             404],
            ['/filmy/tag/slug',                        404],
            ['/knihy',                                 200],
            ['/knihy/tag',                             404],
            ['/knihy/tag/slug',                        404],
            ['/clanky',                                200],
            ['/clanky/tag',                            404],
            ['/clanky/tag/slug',                       404],
            ['/galerie',                               200],
            ['/galerie/tag',                           404],
            ['/videa/tag/slug',                        200],
            ['/videa/tag',                             404],
            ['/videa/tag/slug',                        404],
            ['/smrt-hrou',                             200],
            ['/uzivatelska-sekce',                     302],
            ['/uzivatelska-sekce/hry/formular',        302],
            ['/uzivatelska-sekce/hry',                 302],
            ['/uzivatelska-sekce/hry/1',               302],
            ['/uzivatelska-sekce/hry/aktivovat/1',     302],
            ['/uzivatelska-sekce/hry/smazat/1',        302],
            ['/uzivatelska-sekce/filmy/formular',      302],
            ['/uzivatelska-sekce/filmy',               302],
            ['/uzivatelska-sekce/filmy/1',             302],
            ['/uzivatelska-sekce/filmy/aktivovat/1',   302],
            ['/uzivatelska-sekce/filmy/smazat/1',      302],
            ['/uzivatelska-sekce/knihy/formular',      302],
            ['/uzivatelska-sekce/knihy',               302],
            ['/uzivatelska-sekce/knihy/1',             302],
            ['/uzivatelska-sekce/knihy/aktivovat/1',   302],
            ['/uzivatelska-sekce/knihy/smazat/1',      302],
            ['/uzivatelska-sekce/clanky/formular',     302],
            ['/uzivatelska-sekce/clanky',              302],
            ['/uzivatelska-sekce/clanky/1',            302],
            ['/uzivatelska-sekce/clanky/aktivovat/1',  302],
            ['/uzivatelska-sekce/clanky/smazat/1',     302],
            ['/uzivatelska-sekce/galerie/formular',    302],
            ['/uzivatelska-sekce/galerie',             302],
            ['/uzivatelska-sekce/galerie/aktivovat/1', 302],
            ['/uzivatelska-sekce/galerie/smazat/1',    302],
            ['/uzivatelska-sekce/videa/formular',      302],
            ['/uzivatelska-sekce/videa',               302],
            ['/uzivatelska-sekce/videa/1',             302],
            ['/uzivatelska-sekce/videa/aktivovat/1',   302],
            ['/uzivatelska-sekce/videa/smazat/1',      302],
            ['/uzivatelska-sekce/drafty',              302],
            ['/uzivatelska-sekce/drafty/detail',       302],
            ['/uzivatelska-sekce/drafty/aktivovat',    302],
            ['/uzivatelska-sekce/drafty/smazat',       302],
            ['/ja',                                    302],
            ['/odhlasit',                              302],
            ['/zadat-nove-heslo',                      302],
            ['/aktivovat-ucet',                        302],
        ];
    }
}

$testCase = new SmokeTest;
$testCase->run();
