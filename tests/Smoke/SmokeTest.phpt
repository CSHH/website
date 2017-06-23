<?php

namespace AppTests\Smoke;

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
        $options = [
            'allow_redirects' => false,
            'http_errors'     => false,
        ];

        $httpClient = new GuzzleHttp\Client($options);
        $response   = $httpClient->get('http://127.0.0.1:8080' . $path);

        Assert::same($code, $response->getStatusCode());
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
            ['/hry/novinky',                           200],
            ['/hry/novinky/lorem-ipsum-game',          200],
            ['/filmy',                                 200],
            ['/filmy/tag',                             404],
            ['/filmy/tag/slug',                        404],
            ['/filmy/novinky',                         200],
            ['/filmy/novinky/lorem-ipsum-movie',       200],
            ['/knihy',                                 200],
            ['/knihy/tag',                             404],
            ['/knihy/tag/slug',                        404],
            ['/knihy/novinky',                         200],
            ['/knihy/novinky/lorem-ipsum-book',        200],
            ['/clanky',                                200],
            ['/clanky/tag',                            404],
            ['/clanky/tag/slug',                       404],
            ['/clanky/novinky',                        200],
            ['/clanky/novinky/lorem-ipsum',            200],
            ['/galerie',                               200],
            ['/galerie/tag',                           404],
            ['/galerie/novinky',                       200],
            ['/videa',                                 200],
            ['/videa/tag',                             404],
            ['/videa/tag/slug',                        404],
            ['/videa/novinky',                         200],
            ['/videa/novinky/lorem-ipsum',             200],
            ['/smrt-hrou',                             200],
            ['/uzivatelska-sekce',                     302],
            ['/uzivatelska-sekce/hry/formular',        302],
            ['/uzivatelska-sekce/hry',                 302],
            ['/uzivatelska-sekce/hry/1',               302],
            ['/uzivatelska-sekce/filmy/formular',      302],
            ['/uzivatelska-sekce/filmy',               302],
            ['/uzivatelska-sekce/filmy/1',             302],
            ['/uzivatelska-sekce/knihy/formular',      302],
            ['/uzivatelska-sekce/knihy',               302],
            ['/uzivatelska-sekce/knihy/1',             302],
            ['/uzivatelska-sekce/clanky/formular',     302],
            ['/uzivatelska-sekce/clanky',              302],
            ['/uzivatelska-sekce/clanky/1',            302],
            ['/uzivatelska-sekce/galerie/formular',    302],
            ['/uzivatelska-sekce/galerie',             302],
            ['/uzivatelska-sekce/videa/formular',      302],
            ['/uzivatelska-sekce/videa',               302],
            ['/uzivatelska-sekce/videa/1',             302],
            ['/uzivatelska-sekce/drafty',              302],
            ['/uzivatelska-sekce/drafty/detail',       302],
            ['/ja',                                    302],
            ['/odhlasit',                              302],
            ['/zadat-nove-heslo',                      302],
            ['/aktivovat-ucet',                        302],
        ];
    }
}

$testCase = new SmokeTest($container);
$testCase->run();
