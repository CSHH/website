<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Crud;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\InvalidVideoUrlException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class VideoForm extends AbstractContentForm
{
    /** @var Repositories\VideoCrud */
    private $videoCrud;

    /** @var Entities\ArticleEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Repositories\TagCrud $tagCrud,
        Repositories\VideoCrud $videoCrud,
        Entities\UserEntity $user,
        Entities\VideoEntity $item = null
    ) {
        parent::__construct($translator, $tagCrud, $user);

        $this->videoCrud = $videoCrud;
        $this->item      = $item;
    }

    protected function configure(Form $form)
    {
        $form->addText('name', 'locale.form.name')
            ->setRequired('locale.form.name_required');

        $form->addText('url', 'locale.form.video_source_url')
            ->setRequired('locale.form.video_source_url_required');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {
                $ent = $this->videoCrud->update($values, $tag, $this->user, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->videoCrud->create($values, $tag, $this->user, new Entities\VideoEntity);
                $p->flashMessage($this->translator->translate('locale.item.created'));
            }

        } catch (Exceptions\MissingTagException $e) {
            $this->addFormError($form, $e);

        } catch (PossibleUniqueKeyDuplicationException $e) {
            $this->addFormError($form, $e);

        } catch (InvalidVideoUrlException $e) {
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
