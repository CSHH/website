<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\InvalidVideoUrlException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Tracy;

class VideoForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\TagCrud */
    private $tagCrud;

    /** @var Crud\VideoCrud */
    private $videoCrud;

    /** @var Entities\UserEntity */
    private $user;

    /** @var Entities\ArticleEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Crud\TagCrud $tagCrud,
        Crud\VideoCrud $videoCrud,
        Entities\UserEntity $user,
        Entities\VideoEntity $item = null
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->tagCrud    = $tagCrud;
        $this->videoCrud  = $videoCrud;
        $this->user       = $user;
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

        $form->addText('url', 'locale.form.video_source_url')
            ->setRequired('locale.form.video_source_url_required');

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
                $ent = $this->videoCrud->update($values, $tag, $this->user, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->videoCrud->create($values, $tag, $this->user, new Entities\VideoEntity);
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
        } catch (InvalidVideoUrlException $e) {
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

        $template->setFile(__DIR__ . '/templates/VideoForm.latte');

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
