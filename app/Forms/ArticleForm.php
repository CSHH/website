<?php

namespace App\Forms;

use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Exceptions;
use App\Repositories;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class ArticleForm extends AbstractContentForm
{
    /** @var Repositories\ArticleRepository */
    private $articleRepository;

    /** @var Entities\ArticleEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\ArticleRepository $articleRepository,
        Entities\UserEntity $user,
        Entities\ArticleEntity $item = null
    ) {
        parent::__construct($translator, $tagRepository, $user);

        $this->articleRepository = $articleRepository;
        $this->item              = $item;
    }

    protected function configure(Form $form)
    {
        parent::configure($form);

        $form->addText('name', 'locale.form.name')
            ->setRequired('locale.form.name_required');

        $form->addTextArea('perex', 'locale.form.perex')
            ->setRequired('locale.form.perex_required');

        $this->addEditor($form);

        $this->tryAutoFill($form, $this->item);
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {
                $ent = $this->articleRepository->update($values, $tag, $this->user, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->articleRepository->create($values, $tag, $this->user, new Entities\ArticleEntity);
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
            $p->redirect('form', ['id' => $ent->id]);
        }
    }
}
