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
        Assert::count(42, $router);
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
        $this->assertRoute($router[14], 'uzivatelska-sekce/hry/formular', 'Admin:GameDetail', 'form');
        $this->assertRoute($router[15], 'uzivatelska-sekce/hry', 'Admin:GameListing', 'default');
        $this->assertRoute($router[16], 'uzivatelska-sekce/filmy/formular', 'Admin:MovieDetail', 'form');
        $this->assertRoute($router[17], 'uzivatelska-sekce/filmy', 'Admin:MovieListing', 'default');
        $this->assertRoute($router[18], 'uzivatelska-sekce/knihy/formular', 'Admin:BookDetail', 'form');
        $this->assertRoute($router[19], 'uzivatelska-sekce/knihy', 'Admin:BookListing', 'default');
        $this->assertRoute($router[20], 'uzivatelska-sekce/clanky/formular', 'Admin:ArticleDetail', 'form');
        $this->assertRoute($router[21], 'uzivatelska-sekce/clanky', 'Admin:ArticleListing', 'default');
        $this->assertRoute($router[22], 'uzivatelska-sekce/clanky/<id>', 'Admin:ArticleDetail', 'detail');
        $this->assertRoute($router[23], 'uzivatelska-sekce/clanky/aktivovat/<id>', 'Admin:ArticleDetail', 'activate');
        $this->assertRoute($router[24], 'uzivatelska-sekce/clanky/smazat/<id>', 'Admin:ArticleDetail', 'delete');
        $this->assertRoute($router[25], 'uzivatelska-sekce/galerie/formular', 'Admin:GalleryDetail', 'form');
        $this->assertRoute($router[26], 'uzivatelska-sekce/galerie', 'Admin:GalleryListing', 'default');
        $this->assertRoute($router[27], 'uzivatelska-sekce/galerie/aktivovat/<id>', 'Admin:GalleryDetail', 'activate');
        $this->assertRoute($router[28], 'uzivatelska-sekce/galerie/smazat/<id>', 'Admin:GalleryDetail', 'delete');
        $this->assertRoute($router[29], 'uzivatelska-sekce/videa/formular', 'Admin:VideoDetail', 'form');
        $this->assertRoute($router[30], 'uzivatelska-sekce/videa', 'Admin:VideoListing', 'default');
        $this->assertRoute($router[31], 'uzivatelska-sekce/videa/<id>', 'Admin:VideoDetail', 'detail');
        $this->assertRoute($router[32], 'uzivatelska-sekce/videa/aktivovat/<id>', 'Admin:VideoDetail', 'activate');
        $this->assertRoute($router[33], 'uzivatelska-sekce/videa/smazat/<id>', 'Admin:VideoDetail', 'delete');
        $this->assertRoute($router[34], 'uzivatelska-sekce/drafty', 'Admin:WikiDraftListing', 'default');
        $this->assertRoute($router[35], 'uzivatelska-sekce/drafty/detail', 'Admin:WikiDraftDetail', 'detail');
        $this->assertRoute($router[36], 'uzivatelska-sekce/drafty/aktivovat', 'Admin:WikiDraftDetail', 'activate');
        $this->assertRoute($router[37], 'uzivatelska-sekce/drafty/smazat', 'Admin:WikiDraftDetail', 'delete');
        $this->assertRoute($router[38], 'ja', 'Admin:Settings', 'me');
        $this->assertRoute($router[39], 'odhlasit', 'Admin:Sign', 'out');
        $this->assertRoute($router[40], 'zadat-nove-heslo', 'Admin:Sign', 'password');
        $this->assertRoute($router[41], 'aktivovat-ucet', 'Admin:Sign', 'unlock');
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
