<?php

namespace App\AdminModule\Components\Forms;

use App\Components\Forms\AbstractForm;
use App\Model\Repositories;
use App\Model\Entities;
use App\Model\Exceptions\PossibleUniqueKeyDuplicationException;
use HeavenProject\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;

class SignPasswordForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var Entities\UserEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        Entities\UserEntity $item
    ) {
        parent::__construct($translator);

        $this->userRepository = $userRepository;
        $this->item           = $item;
    }

    protected function configure(Form $form)
    {
        $form->addPassword('password', 'locale.form.password_new')
            ->setRequired('locale.form.password_new_required');

        $form->addPassword('password_confirm', 'locale.form.password_new_confirm')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password'])
            ->setRequired('locale.form.password_new_confirm_required')
            ->setOmitted();

        $form->addSubmit('submit', 'locale.form.submit_change_password');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();

            $this->userRepository->updatePassword($this->item, $values->password, true);

            $p->flashMessage(
                $this->translator->translate('locale.sign.password_changed_sign_in'),
                FlashType::INFO
            );

        } catch (PossibleUniqueKeyDuplicationException $e) {
            $this->addFormError($form, $e);

        } catch (\PDOException $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
        }

        $p->redirect('Sign:in');
    }
}
