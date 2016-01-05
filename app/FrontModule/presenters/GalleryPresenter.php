<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;

final class GalleryPresenter extends SingleUserContentPresenter
{
    /** @var Entities\ImageEntity[] */
    private $images;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->images = $this->runActionDefault($this->imageCrud, $tagSlug, 50);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->images = $this->images;
    }
}
