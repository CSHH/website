<?php

namespace App\Forms;

use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Repositories;
use App\Security\Authenticator;
use HeavenProject\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\IUserStorage;

class ProfileSettingsForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var Authenticator */
    private $authenticator;

    /** @var IUserStorage */
    private $userStorage;

    /** @var Entities\UserEntity */
    private $item;

    /**
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     * @param Authenticator               $authenticator
     * @param IUserStorage                $userStorage
     * @param Entities\UserEntity         $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        Authenticator $authenticator,
        IUserStorage $userStorage,
        Entities\UserEntity $item
    ) {
        parent::__construct($translator);

        $this->userRepository = $userRepository;
        $this->authenticator  = $authenticator;
        $this->userStorage    = $userStorage;
        $this->item           = $item;
    }

    protected function configure(Form $form)
    {
        $form->addText('username', 'locale.form.username')
            ->setRequired('locale.form.username_required');

        $form->addText('email', 'locale.form.email')
            ->addRule($form::EMAIL, 'locale.form.email_not_in_order')
            ->setRequired('locale.form.email_address');

        $form->addText('forename', 'locale.form.forename');

        $form->addText('surname', 'locale.form.surname');

        $form->addPassword('password', 'locale.form.password');

        $form->addPassword('password_confirm', 'locale.form.password_confirm')
            ->setOmitted()
            ->addConditionOn($form['password'], $form::FILLED, 'locale.form.password_confirm_required')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password']);

        $form->addSubmit('submit', 'locale.form.save');

        $this->tryAutoFill($form, $this->item);
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();

            $user = $this->userRepository->updateProfileSettings($values, $this->item);
            $this->userStorage->setIdentity(
                $this->authenticator->updateIdentity($user)
            );

            $p->flashMessage(
                $this->translator->translate('locale.settings.changed_successfully'),
                FlashType::SUCCESS
            );
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
