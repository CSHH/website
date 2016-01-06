<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Crud;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class WikiForm extends AbstractContentForm
{
    /** @var Crud\WikiCrud */
    private $wikiCrud;

    /** @var string */
    private $type;

    /** @var Entities\WikiEntity */
    private $item;

    /**
     * @param ITranslator $translator
     * @param Crud\TagCrud $tagCrud
     * @param Crud\WikiCrud $wikiCrud
     * @param Entities\UserEntity $user
     * @param string $type
     * @param Entities\WikiEntity $item
     */
    public function __construct(
        ITranslator $translator,
        Crud\TagCrud $tagCrud,
        Crud\WikiCrud $wikiCrud,
        Entities\UserEntity $user,
        $type,
        Entities\WikiEntity $item = null
    ) {
        parent::__construct($translator, $tagCrud, $user);

        $this->wikiCrud = $wikiCrud;
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
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {
                $ent = $this->wikiCrud->update($values, $tag, $this->user, $this->type, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->wikiCrud->create($values, $tag, $this->user, $this->type, new Entities\WikiEntity);
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
        }
    }
}
