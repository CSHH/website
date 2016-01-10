<?php

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\Forms;
use App\Model\Entities;

final class GamePresenter extends SharedContentPresenter
{
    /**
     * @param string $tagSlug
     */
    public function actionDefault($tagSlug)
    {
        $this->runActionDefault($tagSlug, 10, Entities\WikiEntity::TYPE_GAME);
    }

    /**
     * @return Forms\WikiDraftForm
     */
    protected function createComponentForm()
    {
        return new Forms\WikiDraftForm(
            $this->translator,
            $this->tagRepository,
            $this->wikiRepository,
            $this->getLoggedUser(),
            Entities\WikiEntity::TYPE_GAME,
            $this->wiki
        );
    }
}
