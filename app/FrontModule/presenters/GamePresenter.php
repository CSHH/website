<?php

namespace App\FrontModule\Presenters;

use App\Components\Forms;
use App\Model\Entities;

final class GamePresenter extends SharedContentPresenter
{
    /** @var Forms\WikiDraftFormInterface @inject */
    public $wikiDraftForm;

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
        return $this->wikiDraftForm->create(
            $this->getLoggedUserEntity(),
            Entities\WikiEntity::TYPE_GAME,
            $this->wiki
        );
    }
}
