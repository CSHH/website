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
        $router[] = new Route('hry[/<tagSlug>][/<vp-page>]', 'Front:Game:default');
        $router[] = new Route('hry/<tagSlug>/<slug>[/<vp-page>]', 'Front:Game:detail');
        $router[] = new Route('filmy[/<tagSlug>][/<vp-page>]', 'Front:Movie:default');
        $router[] = new Route('filmy/<tagSlug>/<slug>[/<vp-page>]', 'Front:Movie:detail');
        $router[] = new Route('knihy[/<tagSlug>][/<vp-page>]', 'Front:Book:default');
        $router[] = new Route('knihy/<tagSlug>/<slug>[/<vp-page>]', 'Front:Book:detail');
        $router[] = new Route('clanky[/<tagSlug>][/<vp-page>]', 'Front:Article:default');
        $router[] = new Route('clanky/<tagSlug>/<slug>[/<vp-page>]', 'Front:Article:detail');
        $router[] = new Route('galerie[/<tagSlug>][/<vp-page>]', 'Front:Gallery:default');
        $router[] = new Route('videa[/<tagSlug>][/<vp-page>]', 'Front:Video:default');
        $router[] = new Route('videa[/<tagSlug>]/<slug>[/<vp-page>]', 'Front:Video:detail');

        $router[] = new Route('uzivatelska-sekce', 'Admin:Homepage:default');
        $router[] = new Route('uzivatelska-sekce/hry[/<vp-page>]', 'Admin:Game:default');
        $router[] = new Route('uzivatelska-sekce/hry/formular', 'Admin:Game:form');
        $router[] = new Route('uzivatelska-sekce/filmy[/<vp-page>]', 'Admin:Movie:default');
        $router[] = new Route('uzivatelska-sekce/filmy/formular', 'Admin:Movie:form');
        $router[] = new Route('uzivatelska-sekce/knihy[/<vp-page>]', 'Admin:Book:default');
        $router[] = new Route('uzivatelska-sekce/knihy/formular', 'Admin:Book:form');
        $router[] = new Route('uzivatelska-sekce/clanky[/<vp-page>]', 'Admin:Article:default');
        $router[] = new Route('uzivatelska-sekce/clanky/formular', 'Admin:Article:form');
        $router[] = new Route('uzivatelska-sekce/galerie[/<vp-page>]', 'Admin:Gallery:default');
        $router[] = new Route('uzivatelska-sekce/galerie/formular', 'Admin:Gallery:form');
        $router[] = new Route('uzivatelska-sekce/videa[/<vp-page>]', 'Admin:Video:default');
        $router[] = new Route('uzivatelska-sekce/videa/formular', 'Admin:Video:form');
        $router[] = new Route('uzivatelska-sekce/drafty[/<vp-page>]', 'Admin:WikiDraft:default');
        $router[] = new Route('uzivatelska-sekce/drafty/detail', 'Admin:WikiDraft:detail');

        $router[] = new Route('ja', 'Admin:Settings:me');
        $router[] = new Route('odhlasit', 'Admin:Sign:out');
        $router[] = new Route('zadat-nove-heslo', 'Admin:Sign:password');
        $router[] = new Route('aktivovat-ucet', 'Admin:Sign:unlock');

        return $router;
    }
}
