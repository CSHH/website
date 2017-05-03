<?php

namespace App\Components;

use App\Caching\TagSectionCacheInterface;
use Nette\Application\UI\Control;

class TagsControl extends Control
{
    /** @var TagSectionCacheInterface */
    private $cache;

    public function __construct(TagSectionCacheInterface $cache)
    {
        parent::__construct();

        $this->cache = $cache;
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/TagsControl.latte');

        $template->tags = $this->cache->getTags();

        $template->render();
    }
}
