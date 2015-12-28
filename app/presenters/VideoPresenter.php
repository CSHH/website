<?php

namespace App\Presenters;

use App\Components\Controls;
use App\Model\Crud;
use App\Model\Entities;

class VideoPresenter extends BasePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Crud\VideoCrud @inject */
    public $videoCrud;

    /** @var Entities\VideoEntity[] */
    private $videos;

    /** @var Controls\VisualPaginator */
    private $vp;

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
    }

    public function renderDefault()
    {
        $this->template->videos = $this->videos;
    }

    /**
     * @return Controls\VisualPaginator
     */
    protected function createComponentVp()
    {
        return $this->vp;
    }

    /**
     * @param int $itemCount
     * @param int $limit
     */
    private function preparePaginator($itemCount, $limit)
    {
        $this->vp = new Controls\VisualPaginator($this->page);
        $p        = $this->vp->getPaginator();
        $p->setItemCount($itemCount);
        $p->setItemsPerPage($limit);
        $p->setPage($this->page);
    }
}
