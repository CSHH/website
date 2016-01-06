<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Repositories;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Entities;
use App\Model\Exceptions;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class GalleryForm extends AbstractContentForm
{
    /** @var Repositories\ImageCrud */
    private $imageCrud;

    public function __construct(
        ITranslator $translator,
        Repositories\TagCrud $tagCrud,
        Repositories\ImageCrud $imageCrud,
        Entities\UserEntity $user
    ) {
        parent::__construct($translator, $tagCrud, $user);

        $this->imageCrud  = $imageCrud;
    }

    protected function configure(Form $form)
    {
        $form->addUpload('images', 'locale.form.images', true)
            ->setRequired('locale.form.images_required');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p   = $this->getPresenter();
            $tag = $this->getSelectedTag($form);

            $images = $form->getHttpData(Form::DATA_FILE, 'images[]');

            $this->imageCrud->uploadImages($tag, $images, $this->user);
            $p->flashMessage($this->translator->translate('locale.item.images_uploaded'));

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

        $p->redirect('this');
    }
}
