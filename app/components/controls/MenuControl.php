<?php

namespace App\Components\Controls;

use App\Model\Caching\MenuCache;
use Nette\Application\UI\Control;

class MenuControl extends Control
{
    /** @var MenuCache */
    private $menuCache;

    public function __construct(MenuCache $menuCache)
    {
        parent::__construct();

        $this->menuCache = $menuCache;
    }

    public function render()
    {
        $template = $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/MenuControl.latte');

        $template->menuItems = $this->menuCache->getAll();

        $template->render();
    }
}
