<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Entities;
use App\Model\Exceptions\PossibleUniqueKeyDuplicationException;
use HeavenProject\Utils\FlashType;
use Nette;
use Nette\Application\UI\Form;
use Nette\Localization\ITranslator;
use Tracy;

class SignPasswordForm extends Nette\Application\UI\Control
{
    /** @var ITranslator */
    private $translator;

    /** @var Crud\UserCrud */
    private $userCrud;

    /** @var Entities\UserEntity */
    private $item;

    public function __construct(
        ITranslator $translator,
        Crud\UserCrud $userCrud,
        Entities\UserEntity $item
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->userCrud   = $userCrud;
        $this->item       = $item;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->setTranslator($this->translator);

        $form->addPassword('password', 'locale.form.password_new')
            ->setRequired('locale.form.password_new_required');

        $form->addPassword('password_confirm', 'locale.form.password_new_confirm')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password'])
            ->setRequired('locale.form.password_new_confirm_required')
            ->setOmitted();

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'locale.form.submit_change_password');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p = $this->getPresenter();

            $values = $form->getValues();

            $this->userCrud->updatePassword($this->item, $values->password, true);

            $p->flashMessage($this->translator->translate('locale.sign.password_changed_sign_in'), FlashType::INFO);
        } catch (PossibleUniqueKeyDuplicationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->getPresenter()->flashMessage($e->getMessage(), FlashType::WARNING);
        } catch (\PDOException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->getPresenter()->flashMessage($this->translator->translate('locale.error.occurred'), FlashType::WARNING);
        }

        $p->redirect('Sign:in');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignPasswordForm.latte');

        $template->render();
    }
}
