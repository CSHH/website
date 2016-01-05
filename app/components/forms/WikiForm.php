<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Tracy;

class WikiForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\TagCrud */
    private $tagCrud;

    /** @var Crud\WikiCrud */
    private $wikiCrud;

    /** @var Entities\UserEntity */
    private $user;

    /** @var string */
    private $type;

    /** @var Entities\ArticleEntity */
    private $item;

    /**
     * @param ITranslator $translator
     * @param Crud\TagCrud $tagCrud
     * @param Crud\WikiCrud $wikiCrud
     * @param Entities\UserEntity $user
     * @param string $type
     * @param Entities\ArticleEntity $item
     */
    public function __construct(
        ITranslator $translator,
        Crud\TagCrud $tagCrud,
        Crud\WikiCrud $wikiCrud,
        Entities\UserEntity $user,
        $type,
        Entities\ArticleEntity $item = null
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->tagCrud    = $tagCrud;
        $this->wikiCrud   = $wikiCrud;
        $this->user       = $user;
        $this->type       = $type;
        $this->item       = $item;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addText('name', 'locale.form.name')
            ->setRequired('locale.form.name_required');

        $form->addTextArea('perex', 'locale.form.perex')
            ->setRequired('locale.form.perex_required');

        $form->addTextArea('text', 'locale.form.text')
            ->setRequired('locale.form.text_required');

        if ($this->item) {
            $form->autoFill($this->item);
        }

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.save');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            $tagId = $form->getHttpData(Form::DATA_LINE, 'tagId');
            $tag   = $tagId ? $this->tagCrud->getById($tagId) : null;

            if (!$tag) {
                throw new Exceptions\MissingTagException(
                    $this->translator->translate('locale.error.missing_tag')
                );
            }

            if ($this->item) {
                $ent = $this->wikiCrud->update($values, $tag, $this->user, $this->type, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->wikiCrud->create($values, $tag, $this->user, $this->type, new Entities\WikiEntity);
                $p->flashMessage($this->translator->translate('locale.item.created'));
            }
        } catch (Exceptions\MissingTagException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        } catch (PossibleUniqueKeyDuplicationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());
        } catch (\Exception $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($this->translator->translate('locale.error.occurred'));
        }

        if (!empty($ent)) {
            $p->redirect('this');
        }
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/WikiForm.latte');

        $template->tags = $this->getTags();

        $template->render();
    }

    /**
     * @return array
     */
    private function getTags()
    {
        $tags = array();
        foreach ($this->tagCrud->getAll() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return $tags;
    }
}
