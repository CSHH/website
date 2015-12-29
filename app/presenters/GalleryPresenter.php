<?php

namespace App\Presenters;

use App\Model\Crud;
use App\Model\Entities;

final class GalleryPresenter extends PageablePresenter
{
    /** @var Crud\ImageCrud @inject */
    public $imageCrud;

    /** @var Entities\ImageEntity[] */
    private $images;

    /** @var Entities\TagEntity */
    private $tag;

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
}
