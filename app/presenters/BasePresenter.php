<?php

namespace App\Presenters;

use Nette;
use Nette\Localization\ITranslator;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var ITranslator @inject */
    public $translator;

    protected function throw404()
    {
        $this->error(
            $this->translator->translate('locale.error.page_not_found')
        );
    }
}
