<?php

namespace App\AdminModule\Presenters;

use App\Model\Entities;

abstract class SharedContentPresenter extends PageablePresenter
{
    /** @var string @persistent */
    public $inactiveOnly = 'no';

    /** @var Entities\WikiEntity[] */
    protected $wikis;

    /** @var bool */
    private $displayInactiveOnly = false;

    /** @var bool */
    private $canAccess = false;

    /**
     * @param int    $limit
     * @param string $type
     */
    protected function runActionDefault($limit, $type)
    {
        if ($this->inactiveOnly === 'yes') {
            $this->displayInactiveOnly = true;
        }

        $this->canAccess = $this->canAccess();

        if ($this->canAccess && $this->displayInactiveOnly) {
            $this->wikis = $this->wikiRepository->getAllWithDraftsForPage($this->page, $limit, $type);
        } else {
            $this->wikis = $this->wikiRepository->getAllByUserForPage($this->page, $limit, $this->getLoggedUserEntity(), $type);
        }

        $this->preparePaginator($this->wikis ? $this->wikis->count() : 0, $limit);
    }

    public function renderDefault()
    {
        parent::runRenderDefault();

        $this->template->inactiveOnly = $this->displayInactiveOnly;
        $this->template->canAccess    = $this->canAccess;
        $this->template->items        = $this->wikis;
    }
}