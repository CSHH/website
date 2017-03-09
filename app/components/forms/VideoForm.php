<?php

namespace App\Components\Forms;

use App\Repositories;
use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Exceptions\InvalidVideoUrlException;
use App\Entities;
use App\Exceptions;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class VideoForm extends AbstractContentForm
{
    /** @var Repositories\VideoRepository */
    private $videoRepository;

    /** @var Entities\ArticleEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Repositories\TagRepository $tagRepository,
        Repositories\VideoRepository $videoRepository,
        Entities\UserEntity $user,
        Entities\VideoEntity $item = null
    ) {
        parent::__construct($translator, $tagRepository, $user);

        $this->videoRepository = $videoRepository;
        $this->item            = $item;
    }

    protected function configure(Form $form)
    {
        parent::configure($form);

        $form->addText('name', 'locale.form.name')
            ->setRequired('locale.form.name_required');

        $form->addText('url', 'locale.form.video_source_url')
            ->setRequired('locale.form.video_source_url_required');

        $this->tryAutoFill($form, $this->item);
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();
            $tag    = $this->getSelectedTag($form);

            if ($this->item) {
                $ent = $this->videoRepository->update($values, $tag, $this->user, $this->item);
                $p->flashMessage($this->translator->translate('locale.item.updated'));
            } else {
                $ent = $this->videoRepository->create($values, $tag, $this->user, new Entities\VideoEntity);
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
