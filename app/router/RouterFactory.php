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

        $router[] = new Route('', 'Front:Homepage:default');
        $router[] = new Route('hry[/<tagSlug>]', 'Front:Game:default');
        $router[] = new Route('hry/<tagSlug>/<slug>', 'Front:Game:detail');
        $router[] = new Route('filmy[/<tagSlug>]', 'Front:Movie:default');
        $router[] = new Route('filmy/<tagSlug>/<slug>', 'Front:Movie:detail');
        $router[] = new Route('knihy[/<tagSlug>]', 'Front:Book:default');
        $router[] = new Route('knihy/<tagSlug>/<slug>', 'Front:Book:detail');
        $router[] = new Route('clanky[/<tagSlug>]', 'Front:Article:default');
        $router[] = new Route('clanky/<tagSlug>/<slug>', 'Front:Article:detail');
        $router[] = new Route('galerie[/<tagSlug>]', 'Front:Gallery:default');
        $router[] = new Route('videa[/<tagSlug>]', 'Front:Video:default');

        $router[] = new Route('uzivatelska-sekce', 'Admin:Homepage:default');
        $router[] = new Route('uzivatelska-sekce/hry', 'Admin:Homepage:games');
        $router[] = new Route('uzivatelska-sekce/hry/formular', 'Admin:Homepage:gameForm');
        $router[] = new Route('uzivatelska-sekce/filmy', 'Admin:Homepage:movies');
        $router[] = new Route('uzivatelska-sekce/filmy/formular', 'Admin:Homepage:movieForm');
        $router[] = new Route('uzivatelska-sekce/knihy', 'Admin:Homepage:books');
        $router[] = new Route('uzivatelska-sekce/knihy/formular', 'Admin:Homepage:bookForm');
        $router[] = new Route('uzivatelska-sekce/clanky', 'Admin:Homepage:articles');
        $router[] = new Route('uzivatelska-sekce/clanky/formular', 'Admin:Homepage:articleForm');
        $router[] = new Route('uzivatelska-sekce/galerie', 'Admin:Homepage:galleries');
        $router[] = new Route('uzivatelska-sekce/galerie/formular', 'Admin:Homepage:galleryForm');
        $router[] = new Route('uzivatelska-sekce/videa', 'Admin:Homepage:videos');
        $router[] = new Route('uzivatelska-sekce/videa/formular', 'Admin:Homepage:videoForm');
        $router[] = new Route('uzivatelska-sekce/<presenter>/<action>', 'Admin:Homepage:default');

        return $router;
    }
}
