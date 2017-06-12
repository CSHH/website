<?php

namespace App\Admin;

use App\Entities;
use App\Repositories;

final class WikiDraftListingPresenter extends SharedContentListingPresenter
{
    /** @var Repositories\WikiRepository @inject */
    public $wikiRepository;

    /** @var Entities\WikiEntity */
    private $wiki;

    /**
     * @param int $wikiId
     */
    public function actionDefault($wikiId)
    {
        $this->wiki = $this->getItem($wikiId, $this->wikiRepository);

        if (!$this->wiki) {
            $this->redirect('Homepage:default');
        }
    }

    public function renderDefault()
    {
        $this->template->wiki = $this->wiki;
    }
}
