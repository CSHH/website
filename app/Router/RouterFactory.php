<?php

namespace App\Router;

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
        $router[] = new Route('smrt-hrou', 'Front:SmrtHrou:default');

        $router[] = new Route('uzivatelska-sekce', 'Admin:Homepage:default');
        $router[] = new Route('uzivatelska-sekce/hry/formular', 'Admin:GameDetail:form');
        $router[] = new Route('uzivatelska-sekce/hry', 'Admin:GameListing:default');
        $router[] = new Route('uzivatelska-sekce/filmy/formular', 'Admin:MovieDetail:form');
        $router[] = new Route('uzivatelska-sekce/filmy', 'Admin:MovieListing:default');
        $router[] = new Route('uzivatelska-sekce/knihy/formular', 'Admin:BookDetail:form');
        $router[] = new Route('uzivatelska-sekce/knihy', 'Admin:BookListing:default');
        $router[] = new Route('uzivatelska-sekce/clanky/formular', 'Admin:ArticleDetail:form');
        $router[] = new Route('uzivatelska-sekce/clanky', 'Admin:ArticleListing:default');
        $router[] = new Route('uzivatelska-sekce/clanky/<id>', 'Admin:ArticleDetail:detail');
        $router[] = new Route('uzivatelska-sekce/clanky/aktivovat/<id>', 'Admin:ArticleDetail:activate');
        $router[] = new Route('uzivatelska-sekce/clanky/smazat/<id>', 'Admin:ArticleDetail:delete');
        $router[] = new Route('uzivatelska-sekce/galerie/formular', 'Admin:GalleryDetail:form');
        $router[] = new Route('uzivatelska-sekce/galerie', 'Admin:GalleryListing:default');
        $router[] = new Route('uzivatelska-sekce/galerie/aktivovat/<id>', 'Admin:GalleryDetail:activate');
        $router[] = new Route('uzivatelska-sekce/galerie/smazat/<id>', 'Admin:GalleryDetail:delete');
        $router[] = new Route('uzivatelska-sekce/videa/formular', 'Admin:VideoDetail:form');
        $router[] = new Route('uzivatelska-sekce/videa', 'Admin:VideoListing:default');
        $router[] = new Route('uzivatelska-sekce/videa/<id>', 'Admin:VideoDetail:detail');
        $router[] = new Route('uzivatelska-sekce/videa/aktivovat/<id>', 'Admin:VideoDetail:activate');
        $router[] = new Route('uzivatelska-sekce/videa/smazat/<id>', 'Admin:VideoDetail:delete');
        $router[] = new Route('uzivatelska-sekce/drafty', 'Admin:WikiDraft:default');
        $router[] = new Route('uzivatelska-sekce/drafty/detail', 'Admin:WikiDraft:detail');

        $router[] = new Route('ja', 'Admin:Settings:me');
        $router[] = new Route('odhlasit', 'Admin:Sign:out');
        $router[] = new Route('zadat-nove-heslo', 'Admin:Sign:password');
        $router[] = new Route('aktivovat-ucet', 'Admin:Sign:unlock');

        return $router;
    }
}
