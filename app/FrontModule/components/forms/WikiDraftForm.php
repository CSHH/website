<?php

namespace App\FrontModule\Components\Forms;

use App\Model\Repositories;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Utils\DateTime;
use Tracy;

class WikiDraftForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Repositories\TagRepository */
    private $tagRepository;

    /** @var Repositories\WikiRepository */
    private $wikiRepository;

    /** @var Repositories\WikiDraftRepository */
    private $wikiDraftRepository;

    /** @var Entities\UserEntity */
    private $user;

    /** @var string */
    private $type;

    /** @var Entities\WikiEntity */
    private $item;

    /** @var bool */
    private $newerDraftExists = false;

    /**
     * @param ITranslator $translator
     * @param Repositories\TagRepository $tagRepository
     * @param Repositories\WikiRepository $wikiRepository
     * @param Repositories\WikiDraftRepository $wikiDraftRepository
     * @param Entities\UserEntity $user
     * @param string $type
     * @param Entities\WikiEntity $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\WikiRepository $wikiRepository,
        Repositories\WikiDraftRepository $wikiDraftRepository,
        Entities\UserEntity $user,
        $type,
        Entities\WikiEntity $item = null
    ) {
        $this->translator     = $translator;
        $this->tagRepository  = $tagRepository;
        $this->wikiRepository = $wikiRepository;
        $this->wikiDraftRepository = $wikiDraftRepository;
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

        $form->addHidden('startTime', date('Y-m-d H:i:s'));

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
            $p      = $this->getPresenter();
            $values = $form->getValues();

            $latest = $this->wikiDraftRepository->getLatestByWiki($this->item);
            $start  = DateTime::from($values->startTime);

            if ($latest && $start < $latest->createdAt) {
                throw new Exceptions\WikiDraftConflictException(
                    $this->translator->translate('locale.error.newer_draft_created_meanwhile')
                );
            }

            unset($values->name);
            unset($values->perex);
            unset($values->startTime);

            $this->wikiDraftRepository->create($values, $this->user, $this->item, new Entities\WikiDraftEntity);
            $ent = $this->item;
            $p->flashMessage($this->translator->translate('locale.item.updated'));

        } catch (Exceptions\WikiDraftConflictException $e) {
            $this->newerDraftExists = true;
            $this->addFormError($form, $e);

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
        }
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/WikiDraftForm.latte');

        $template->latestDraft = $this->newerDraftExists
            ? $this->wikiDraftRepository->getLatestByWiki($this->item)
            : null;

        $template->render();
    }

    /**
     * @param Form $form
     * @param \Exception $e
     * @param string $output
     */
    private function addFormError(Form $form, \Exception $e, $output = null)
    {
        Tracy\Debugger::barDump($e->getMessage());
        Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

        $form->addError($output
            ? $this->translator->translate('locale.error.occurred')
            : $e->getMessage()
        );
    }
}
