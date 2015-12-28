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

        $router[] = new Route('hry[/<tagSlug>]', 'Game:default');
        $router[] = new Route('hry/<tagSlug>/<slug>', 'Game:detail');

        $router[] = new Route('filmy[/<tagSlug>]', 'Movie:default');
        $router[] = new Route('filmy/<tagSlug>/<slug>', 'Movie:detail');

        $router[] = new Route('knihy[/<tagSlug>]', 'Book:default');
        $router[] = new Route('knihy/<tagSlug>/<slug>', 'Book:detail');

        $router[] = new Route('clanky[/<tagSlug>]', 'Article:default');
        $router[] = new Route('clanky/<tagSlug>/<slug>', 'Article:detail');

        $router[] = new Route('galerie[/<tagSlug>]', 'Gallery:default');

        $router[] = new Route('videa[/<tagSlug>]', 'Video:default');

        $router[] = new Route('<presenter>/<action>', 'Homepage:default');

        return $router;
    }
}
