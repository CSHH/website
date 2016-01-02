<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Entities;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Tracy;

class ArticleForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\ArticleCrud */
    private $articleCrud;

    /** @var Entities\ArticleEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Crud\ArticleCrud $articleCrud,
        Entities\ArticleEntity $item = null
    ) {
        parent::__construct();

        $this->translator  = $translator;
        $this->articleCrud = $articleCrud;
        $this->item        = $item;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->addText('name', 'Název')
            ->setRequired('');

        $form->addTextArea('perex', 'Perex')
            ->setRequired('');

        $form->addTextArea('text', 'Text')
            ->setRequired('');

        if ($this->item) {
            $form->autoFill($this->item);
        }

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'Přihlásit');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            if ($this->item) {
                $ent = $this->articleCrud->update($values, $this->item);
            } else {
                $ent = $this->articleCrud->create($values);
            }

        } catch (\Exception $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        }

        if ($this->item) {
            $p->flashMessage('Upraveno');
        } else {
            $p->flashMessage('Vytvořeno');
        }

        $p->redirect('this');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/ArticleForm.latte');

        $template->render();
    }
}
