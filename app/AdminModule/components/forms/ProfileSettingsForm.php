<?php

namespace App\AdminModule\Components\Forms;

use App\Model\Repositories;
use App\Model\Entities;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Security\Authenticator;
use HeavenProject\Utils\FlashType;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Nette\Http\UserStorage;
use Tracy;

class ProfileSettingsForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var Authenticator */
    private $authenticator;

    /** @var UserStorage */
    private $userStorage;

    /** @var Entities\UserEntity */
    private $item;

    /**
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     * @param Authenticator               $authenticator
     * @param UserStorage                 $userStorage
     * @param Entities\UserEntity         $item
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        Authenticator $authenticator,
        UserStorage $userStorage,
        Entities\UserEntity $item
    ) {
        parent::__construct();

        $this->translator     = $translator;
        $this->userRepository = $userRepository;
        $this->authenticator  = $authenticator;
        $this->userStorage    = $userStorage;
        $this->item           = $item;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

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

        $form->autoFill($this->item);

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.save');

        return $form;
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
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($e->getMessage());

        } catch (\Exception $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->addError($this->translator->translate('locale.error.occurred'));
        }

        $p->redirect('this');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/ProfileSettingsForm.latte');

        $template->render();
    }
}
