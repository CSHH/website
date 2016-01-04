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

class GalleryForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\TagCrud */
    private $tagCrud;

    /** @var Crud\ImageCrud */
    private $imageCrud;

    /** @var Entities\UserEntity */
    private $user;

    public function __construct(
        ITranslator $translator,
        Crud\TagCrud $tagCrud,
        Crud\ImageCrud $imageCrud,
        Entities\UserEntity $user
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->tagCrud    = $tagCrud;
        $this->imageCrud  = $imageCrud;
        $this->user       = $user;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addUpload('images', 'locale.form.images', true)
            ->setRequired('locale.form.images_required');

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.save');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $tagId = $form->getHttpData(Form::DATA_LINE, 'tagId');
            $tag   = $tagId ? $this->tagCrud->getById($tagId) : null;

            if (!$tag) {
                throw new Exceptions\MissingTagException(
                    $this->translator->translate('locale.error.missing_tag')
                );
            }

            $p = $this->getPresenter();

            $images = $form->getHttpData(Form::DATA_FILE, 'images[]');

            $this->imageCrud->uploadImages($tag, $images, $this->user);
            $p->flashMessage($this->translator->translate('locale.item.images_uploaded'));

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

        $p->redirect('this');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/GalleryForm.latte');

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
