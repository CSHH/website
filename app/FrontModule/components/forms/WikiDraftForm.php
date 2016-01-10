<?php

namespace App\FrontModule\Components\Forms;

use App\Model\Repositories;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class WikiDraftForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Repositories\TagRepository */
    private $tagRepository;

    /** @var Repositories\WikiRepository */
    private $wikiRepository;

    /** @var Entities\UserEntity */
    private $user;

    /** @var string */
    private $type;

    /** @var Entities\WikiEntity */
    private $item;

    /**
     * @param ITranslator $translator
     * @param Repositories\TagRepository $tagRepository
     * @param Repositories\WikiRepository $wikiRepository
     * @param Entities\UserEntity $user
     * @param string $type
     * @param Entities\WikiEntity $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\WikiRepository $wikiRepository,
        Entities\UserEntity $user,
        $type,
        Entities\WikiEntity $item = null
    ) {
        $this->translator     = $translator;
        $this->tagRepository  = $tagRepository;
        $this->wikiRepository = $wikiRepository;
        $this->user           = $user;
        $this->type           = $type;
        $this->item           = $item;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addTextArea('text', 'locale.form.text')
            ->setRequired('locale.form.text_required');

        $form->addHidden('start_time', date('Y-m-d H:i:s'));

        if ($this->item) {
            $form->autoFill($this->item);
        }

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.save');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        dump($form->getValues());exit;
        /*try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {
                $ent = $this->wikiRepository->update($values, $tag, $this->user, $this->type, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->wikiRepository->create($values, $tag, $this->user, $this->type, new Entities\WikiEntity);
                $p->flashMessage($this->translator->translate('locale.item.created'));
            }

        } catch (Exceptions\MissingTagException $e) {
            $this->addFormError($form, $e);

        } catch (PossibleUniqueKeyDuplicationException $e) {
            $this->addFormError($form, $e);

        } catch (\Exception $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
        }

        if (!empty($ent)) {
            $p->redirect('this');
        }*/
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/WikiDraftForm.latte');

        $template->render();
    }
}
