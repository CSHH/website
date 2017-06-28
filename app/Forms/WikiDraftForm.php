<?php

namespace App\Forms;

use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Exceptions;
use App\Repositories;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Utils\DateTime;

class WikiDraftForm extends AbstractContentForm
{
    /** @var Repositories\WikiDraftRepository */
    private $wikiDraftRepository;

    /** @var string */
    private $type;

    /** @var Entities\WikiEntity */
    private $item;

    /** @var bool */
    private $newerDraftExists = false;

    /**
     * @param ITranslator                      $translator
     * @param Repositories\TagRepository       $tagRepository
     * @param Repositories\WikiDraftRepository $wikiDraftRepository
     * @param Entities\UserEntity              $user
     * @param string                           $type
     * @param Entities\WikiEntity              $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\WikiDraftRepository $wikiDraftRepository,
        Entities\UserEntity $user,
        $type,
        Entities\WikiEntity $item = null
    ) {
        parent::__construct($translator, $tagRepository, $user);

        $this->wikiDraftRepository = $wikiDraftRepository;
        $this->type                = $type;
        $this->item                = $item;
    }

    protected function configure(Form $form)
    {
        parent::configure($form);

        $form->addTextArea('perex', 'locale.form.perex')
            ->setRequired('locale.form.perex_required');

        $this->addEditor($form);

        $form->addHidden('startTime', date('Y-m-d H:i:s'));

        $this->tryAutoFill($form, $this->item);
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

    protected function insideRender(ITemplate $template)
    {
        $template->latestDraft = $this->newerDraftExists
            ? $this->wikiDraftRepository->getLatestByWiki($this->item)
            : null;
    }
}
