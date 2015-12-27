<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

class RouterFactory
{
    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter()
    {
        $router = new RouteList;

        $router[] = new Route('clanky[/<tag>]', 'Article:default');
        $router[] = new Route('clanky/<tag>/<slug>', 'Article:detail');

        $router[] = new Route('<presenter>/<action>', 'Homepage:default');

        return $router;
    }
}
