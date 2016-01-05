<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Crud;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Tracy;

abstract class AbstractContentForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    protected $translator;

    /** @var Crud\TagCrud */
    protected $tagCrud;

    /** @var Entities\UserEntity */
    protected $user;

    public function __construct(
        ITranslator $translator,
        Crud\TagCrud $tagCrud,
        Entities\UserEntity $user
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->tagCrud    = $tagCrud;
        $this->user       = $user;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $this->configure($form);

        if (isset($this->item) && $this->item) {
            $form->autoFill($this->item);
        }

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.save');

        return $form;
    }

    /**
     * @param  Form $form
     * @throws Exceptions\MissingTagException
     * @return Entities\TagEntity|null
     */
    protected function getSelectedTag(Form $form)
    {
        $tagId = $form->getHttpData(Form::DATA_LINE, 'tagId');
        $tag   = $tagId ? $this->tagCrud->getById($tagId) : null;

        if (!$tag) {
            throw new Exceptions\MissingTagException(
                $this->translator->translate('locale.error.missing_tag')
            );
        }

        return $tag;
    }

    /**
     * @return array
     */
    protected function getTags()
    {
        $tags = array();
        foreach ($this->tagCrud->getAll() as $tag) {
            $tags[$tag->id] = $tag->name;
        }

        return $tags;
    }

    /**
     * @param Form $form
     * @param \Exception $e
     * @param string $output
     */
    protected function addFormError(Form $form, \Exception $e, $output = null)
    {
        Tracy\Debugger::barDump($e->getMessage());
        Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

        $form->addError($output
            ? $this->translator->translate('locale.error.occurred')
            : $e->getMessage()
        );
    }

    public function render()
    {
        $template = $this->getTemplate();

        $exploded = explode('\\', static::class);

        $template->setFile(__DIR__ . '/templates/' . array_pop($exploded) . '.latte');

        $template->tags = $this->getTags();

        $template->render();
    }

    /**
     * @param Form $form
     */
    abstract protected function configure(Form $form);

    /**
     * @param Form $form
     */
    abstract public function formSucceeded(Form $form);
}
