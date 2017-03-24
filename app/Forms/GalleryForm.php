<?php

namespace App\Forms;

use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Exceptions;
use App\Repositories;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class GalleryForm extends AbstractContentForm
{
    /** @var Repositories\ImageRepository */
    private $imageRepository;

    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\ImageRepository $imageRepository,
        Entities\UserEntity $user
    ) {
        parent::__construct($translator, $tagRepository, $user);

        $this->imageRepository = $imageRepository;
    }

    protected function configure(Form $form)
    {
        parent::configure($form);

        $form->addUpload('images', 'locale.form.images', true)
            ->setRequired('locale.form.images_required');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p   = $this->getPresenter();
            $tag = $this->getSelectedTag($form);

            $images = $form->getHttpData(Form::DATA_FILE, 'images[]');

            $this->imageRepository->uploadImages($tag, $images, $this->user);
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
