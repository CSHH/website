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
        $router[] = new Route('videa[/<tagSlug>]/<slug>', 'Front:Video:detail');

        $router[] = new Route('uzivatelska-sekce', 'Admin:Homepage:default');
        $router[] = new Route('uzivatelska-sekce/hry', 'Admin:Game:default');
        $router[] = new Route('uzivatelska-sekce/hry/formular', 'Admin:Game:form');
        $router[] = new Route('uzivatelska-sekce/filmy', 'Admin:Movie:default');
        $router[] = new Route('uzivatelska-sekce/filmy/formular', 'Admin:Movie:form');
        $router[] = new Route('uzivatelska-sekce/knihy', 'Admin:Book:default');
        $router[] = new Route('uzivatelska-sekce/knihy/formular', 'Admin:Book:form');
        $router[] = new Route('uzivatelska-sekce/clanky', 'Admin:Article:default');
        $router[] = new Route('uzivatelska-sekce/clanky/formular', 'Admin:Article:form');
        $router[] = new Route('uzivatelska-sekce/galerie', 'Admin:Gallery:default');
        $router[] = new Route('uzivatelska-sekce/galerie/formular', 'Admin:Gallery:form');
        $router[] = new Route('uzivatelska-sekce/videa', 'Admin:Video:default');
        $router[] = new Route('uzivatelska-sekce/videa/formular', 'Admin:Video:form');
        $router[] = new Route('uzivatelska-sekce/drafty', 'Admin:WikiDraft:default');
        $router[] = new Route('uzivatelska-sekce/drafty/detail', 'Admin:WikiDraft:detail');
        $router[] = new Route('uzivatelska-sekce/ja', 'Admin:Settings:me');
        $router[] = new Route('uzivatelska-sekce/odhlasit', 'Admin:Sign:out');

        $router[] = new Route('zadat-nove-heslo', 'Admin:Sign:password');
        $router[] = new Route('aktivovat-ucet', 'Admin:Sign:unlock');

        return $router;
    }
}
