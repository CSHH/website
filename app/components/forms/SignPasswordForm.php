<?php

namespace App\Components\Forms;

use App\Model\Crud;
use App\Model\Entities;
use App\Model\Exceptions\PossibleUniqueKeyDuplicationException;
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
    private $e;

    public function __construct(
        ITranslator $translator,
        Crud\UserCrud $userCrud,
        Entities\UserEntity $e
    ) {
        parent::__construct();

        $this->translator = $translator;
        $this->userCrud   = $userCrud;
        $this->e          = $e;
    }

    /**
     * @return Form
     */
    public function createComponentForm()
    {
        $form = new Form;

        $form->addPassword('password', 'Heslo')
            ->setRequired('Zadejte prosím své přihlašovací heslo.');

        $form->addPassword('password_confirm', 'Heslo znovu')
            ->addRule($form::EQUAL, 'Hesla se musí shodovat.', $form['password'])
            ->setRequired('Zadejte prosím Vaše přihlašovací heslo znovu pro ověření.')
            ->setOmitted();

        $form->onSuccess[] = array($this, 'formSucceeded');

        $form->addSubmit('submit', 'Změnit heslo');

        return $form;
    }

    public function formSucceeded(Form $form)
    {
        try {
            $values = $form->getValues();

            $this->userCrud->updatePassword($this->e, $values->password, true);

            $form->getPresenter()->flashMessage(
                'Heslo bylo změněno. Přihlašte se prosím.'
            );
        } catch (PossibleUniqueKeyDuplicationException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->getPresenter()->flashMessage($e->getMessage());
        } catch (\PDOException $e) {
            Tracy\Debugger::barDump($e->getMessage());
            Tracy\Debugger::log($e->getMessage(), Tracy\Debugger::EXCEPTION);

            $form->getPresenter()->flashMessage('Došlo k chybě.');
        }

        $form->getPresenter()->redirect('Sign:in');
    }

    public function render()
    {
        $template = $this->getTemplate();

        $template->setFile(__DIR__ . '/templates/SignPasswordForm.latte');

        $template->render();
    }
}
