<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Repositories;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Utils\DateTime;

class WikiForm extends AbstractContentForm
{
    /** @var Repositories\WikiRepository */
    private $wikiRepository;

    /** @var Repositories\WikiDraftRepository */
    private $wikiDraftRepository;

    /** @var string */
    private $type;

    /** @var Entities\WikiEntity */
    private $item;

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
        parent::__construct($translator, $tagRepository, $user);

        $this->wikiRepository = $wikiRepository;
        $this->wikiDraftRepository = $wikiDraftRepository;
        $this->type     = $type;
        $this->item     = $item;
    }

    protected function configure(Form $form)
    {
        $form->addText('name', 'locale.form.name')
            ->setRequired('locale.form.name_required');

        $form->addTextArea('perex', 'locale.form.perex')
            ->setRequired('locale.form.perex_required');

        $form->addTextArea('text', 'locale.form.text')
            ->setRequired('locale.form.text_required');

        $form->addHidden('startTime', date('Y-m-d H:i:s'));
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {

                if ($this->item->isActive) {

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

                } else {
                    $ent = $this->wikiRepository->update($values, $tag, $this->user, $this->type, $this->item);
                    $p->flashMessage($this->translator->translate('locale.item.updated'));
                }

            } else {
                $ent = $this->wikiRepository->create($values, $tag, $this->type, new Entities\WikiEntity);
                $p->flashMessage($this->translator->translate('locale.item.created'));
            }

        } catch (Exceptions\WikiDraftConflictException $e) {
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
}
