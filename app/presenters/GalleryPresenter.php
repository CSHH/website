<?php

namespace App\Presenters;

use App\Components\Controls;
use App\Model\Crud;
use App\Model\Entities;

class GalleryPresenter extends BasePresenter
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Crud\ImageCrud @inject */
    public $imageCrud;

    /** @var Entities\ImageEntity[] */
    private $images;

    /** @var Entities\TagEntity */
    private $tag;

    /** @var Controls\VisualPaginator */
    private $vp;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $tag = $tagSlug ? $this->tagCrud->getBySlug($tagSlug) : null;

        $limit = 50;

        $images = $tag
            ? $this->imageCrud->getAllByTagForPage($this->page, $limit, $tag)
            : $this->imageCrud->getAllForPage($this->page, $limit);

        $this->preparePaginator($images->count(), $limit);

        if ($tag && !$images || $this->page > $this->vp->getPaginator()->getLastPage()) {
            $this->throw404();
        }

        $this->images = $images;
        $this->tag    = $tag;
    }

    public function renderDefault()
    {
        $this->template->images = $this->images;
        $this->template->tag    = $this->tag;
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
