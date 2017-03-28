<?php

namespace AppTests\Unit\Router;

use App\Router\RouterFactory;
use Nette\Application\Routers\Route;
use Tester;
use Tester\Assert;

/**
 * @testCase
 */
require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class RouterFactoryTest extends Tester\TestCase
{
    public function testCreateRouter()
    {
        $router = RouterFactory::createRouter();
        Assert::type('Nette\Application\IRouter', $router);
        Assert::count(34, $router);
        $this->assertRoute($router[0], '', 'Front:Homepage', 'default');
        $this->assertRoute($router[1], 'hry[/<tagSlug>]', 'Front:Game', 'default');
        $this->assertRoute($router[2], 'hry/<tagSlug>/<slug>', 'Front:Game', 'detail');
        $this->assertRoute($router[3], 'filmy[/<tagSlug>]', 'Front:Movie', 'default');
        $this->assertRoute($router[4], 'filmy/<tagSlug>/<slug>', 'Front:Movie', 'detail');
        $this->assertRoute($router[5], 'knihy[/<tagSlug>]', 'Front:Book', 'default');
        $this->assertRoute($router[6], 'knihy/<tagSlug>/<slug>', 'Front:Book', 'detail');
        $this->assertRoute($router[7], 'clanky[/<tagSlug>]', 'Front:Article', 'default');
        $this->assertRoute($router[8], 'clanky/<tagSlug>/<slug>', 'Front:Article', 'detail');
        $this->assertRoute($router[9], 'galerie[/<tagSlug>]', 'Front:Gallery', 'default');
        $this->assertRoute($router[10], 'videa[/<tagSlug>]', 'Front:Video', 'default');
        $this->assertRoute($router[11], 'videa[/<tagSlug>]/<slug>', 'Front:Video', 'detail');
        $this->assertRoute($router[12], 'smrt-hrou', 'Front:SmrtHrou', 'default');
        $this->assertRoute($router[13], 'uzivatelska-sekce', 'Admin:Homepage', 'default');
        $this->assertRoute($router[14], 'uzivatelska-sekce/hry/formular', 'Admin:Game', 'form');
        $this->assertRoute($router[15], 'uzivatelska-sekce/hry', 'Admin:Game', 'default');
        $this->assertRoute($router[16], 'uzivatelska-sekce/filmy/formular', 'Admin:Movie', 'form');
        $this->assertRoute($router[17], 'uzivatelska-sekce/filmy', 'Admin:Movie', 'default');
        $this->assertRoute($router[18], 'uzivatelska-sekce/knihy/formular', 'Admin:Book', 'form');
        $this->assertRoute($router[19], 'uzivatelska-sekce/knihy', 'Admin:Book', 'default');
        $this->assertRoute($router[20], 'uzivatelska-sekce/clanky/formular', 'Admin:Article', 'form');
        $this->assertRoute($router[21], 'uzivatelska-sekce/clanky', 'Admin:Article', 'default');
        $this->assertRoute($router[22], 'uzivatelska-sekce/clanky/<id>', 'Admin:Article', 'detail');
        $this->assertRoute($router[23], 'uzivatelska-sekce/galerie/formular', 'Admin:Gallery', 'form');
        $this->assertRoute($router[24], 'uzivatelska-sekce/galerie', 'Admin:Gallery', 'default');
        $this->assertRoute($router[25], 'uzivatelska-sekce/videa/formular', 'Admin:Video', 'form');
        $this->assertRoute($router[26], 'uzivatelska-sekce/videa', 'Admin:Video', 'default');
        $this->assertRoute($router[27], 'uzivatelska-sekce/videa/<id>', 'Admin:Video', 'detail');
        $this->assertRoute($router[28], 'uzivatelska-sekce/drafty', 'Admin:WikiDraft', 'default');
        $this->assertRoute($router[29], 'uzivatelska-sekce/drafty/detail', 'Admin:WikiDraft', 'detail');
        $this->assertRoute($router[30], 'ja', 'Admin:Settings', 'me');
        $this->assertRoute($router[31], 'odhlasit', 'Admin:Sign', 'out');
        $this->assertRoute($router[32], 'zadat-nove-heslo', 'Admin:Sign', 'password');
        $this->assertRoute($router[33], 'aktivovat-ucet', 'Admin:Sign', 'unlock');
    }

    /**
     * @param Route  $route
     * @param string $mask
     * @param string $presenter
     * @param string $action
     */
    private function assertRoute(Route $route, $mask, $presenter, $action)
    {
        Assert::type('Nette\Application\Routers\Route', $route);
        Assert::same($mask, $route->getMask());
        $defaults = $route->getDefaults();
        Assert::same($presenter, $defaults['presenter']);
        Assert::same($action, $defaults['action']);
    }
}

$testCase = new RouterFactoryTest;
$testCase->run();
