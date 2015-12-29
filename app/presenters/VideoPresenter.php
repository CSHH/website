<?php

namespace App\Presenters;

use App\Model\Crud;
use App\Model\Entities;

class VideoPresenter extends PageablePresenter
{
    /** @var Crud\VideoCrud @inject */
    public $videoCrud;

    /** @var Entities\VideoEntity[] */
    private $videos;

    /** @var Entities\TagEntity */
    private $tag;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        $limit = 10;

        $videos = $tag
            ? $this->videoCrud->getAllByTagForPage($this->page, $limit, $tag)
            : $this->videoCrud->getAllForPage($this->page, $limit);

        $this->preparePaginator($videos->count(), $limit);

        if ($tag && !$videos || $this->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }

        $this->videos = $videos;
        $this->tag    = $tag;
    }

    public function renderDefault()
    {
        $this->template->videos = $this->videos;
        $this->template->tag    = $this->tag;
    }
}
