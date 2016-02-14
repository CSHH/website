<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\Forms\WikiForm;
use App\FrontModule\Components\Forms\WikiDraftForm;
use App\Model\Entities;

final class MoviePresenter extends SharedContentPresenter
{
    /** @var Entities\BaseEntity */
    private $item;

    /**
     * @param int $id
     */
    public function actionForm($id = null)
    {
        $this->runActionForm(Entities\WikiEntity::TYPE_MOVIE, 'Movie:default', $id);
    }

    public function renderForm()
    {
        $this->template->item = $this->item;
    }

    public function actionDefault()
    {
        $this->runActionDefault(10, Entities\WikiEntity::TYPE_MOVIE);
    }

    /**
     * @return WikiForm
     */
    protected function createComponentWikiForm()
    {
        return new WikiForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->getLoggedUserEntity(),
            Entities\WikiEntity::TYPE_MOVIE,
            $this->item
        );
    }

    /**
     * @return WikiDraftForm
     */
    protected function createComponentWikiDraftForm()
    {
        return new WikiDraftForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->wikiDraftRepository,
            $this->getLoggedUserEntity(),
            Entities\WikiEntity::TYPE_MOVIE,
            $this->item
        );
    }
}
