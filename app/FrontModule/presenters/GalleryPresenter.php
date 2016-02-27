<?php

namespace App\FrontModule\Presenters;

use App\Model\Entities;
use App\Model\Repositories;

final class GalleryPresenter extends SingleUserContentPresenter
{
    /** @var Repositories\ImageRepository @inject */
    public $imageRepository;

    /** @var Entities\ImageEntity[] */
    private $images;

    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->images = $this->runActionDefault($this->imageRepository, $tagSlug, 50);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->images    = $this->images;
        $this->template->uploadDir = $this->context->parameters['uploadDir'];
    }

    /**
     * @param int $imageId
     */
    public function handleActivate($imageId)
    {
        $image = $this->getItem($imageId, $this->imageRepository);

        if (!$image) {
            $this->throw404();
        }

        $this->imageRepository->activate($image);

        $this->flashWithRedirect($this->translator->translate('locale.item.activated'));
    }

    /**
     * @param int $imageId
     */
    public function handleDelete($imageId)
    {
        $image = $this->getItem($imageId, $this->imageRepository);

        if (!$image) {
            $this->throw404();
        }

        $this->imageRepository->delete($image);

        $this->flashWithRedirect($this->translator->translate('locale.item.deleted'));
    }
}
