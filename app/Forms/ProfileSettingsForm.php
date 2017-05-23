<?php

namespace App\Forms;

use App\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Entities;
use App\Repositories;
use App\Security\IdentityFactory;
use App\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Security\IUserStorage;

class ProfileSettingsForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var IdentityFactory */
    private $identityFactory;

    /** @var IUserStorage */
    private $userStorage;

    /** @var Entities\UserEntity */
    private $item;

    /**
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     * @param IdentityFactory             $identityFactory
     * @param IUserStorage                $userStorage
     * @param Entities\UserEntity         $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        IdentityFactory $identityFactory,
        IUserStorage $userStorage,
        Entities\UserEntity $item
    ) {
        parent::__construct($translator);

        $this->userRepository  = $userRepository;
        $this->identityFactory = $identityFactory;
        $this->userStorage     = $userStorage;
        $this->item            = $item;
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

        $password        = $form->addPassword('password', 'locale.form.password');
        $passwordConfirm = $form->addPassword('password_confirm', 'locale.form.password_confirm');
        $passwordConfirm->setOmitted();

        $password->addConditionOn($form['password_confirm'], $form::FILLED, 'locale.form.password_required')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password_confirm'])
            ->setRequired(true);

        $passwordConfirm->addConditionOn($form['password'], $form::FILLED, 'locale.form.password_confirm_required')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password'])
            ->setRequired(true);

        $form->addSubmit('submit', 'locale.form.save');

        $this->tryAutoFill($form, $this->item);
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();

            $user = $this->userRepository->updateProfileSettings($values, $this->item);
            $this->userStorage->setIdentity($this->identityFactory->createIdentity($user));

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
