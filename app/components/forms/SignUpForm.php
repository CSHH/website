<?php

namespace App\Components\Forms;

use App\Model\Repositories;
use App\Model\Duplicities\PossibleUniqueKeyDuplicationException;
use App\Model\Exceptions\FormSentBySpamException;
use HeavenProject\Utils\FlashType;
use Nette\Application\UI\Form;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class SignUpForm extends AbstractForm
{
    /** @var Repositories\UserRepository */
    private $userRepository;

    /** @var IMailer */
    private $mailer;

    /** @var string */
    private $contactEmail;

    /**
     * @param ITranslator                 $translator
     * @param Repositories\UserRepository $userRepository
     * @param IMailer                     $mailer
     * @param string                      $contactEmail
     */
    public function __construct(
        ITranslator $translator,
        Repositories\UserRepository $userRepository,
        IMailer $mailer,
        $contactEmail
    ) {
        parent::__construct($translator);

        $this->userRepository = $userRepository;
        $this->mailer         = $mailer;
        $this->contactEmail   = $contactEmail;
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

        $form->addPassword('password', 'locale.form.password')
            ->setRequired('locale.form.password_required');

        $form->addPassword('password_confirm', 'locale.form.password_confirm')
            ->addRule($form::EQUAL, 'locale.form.password_equal', $form['password'])
            ->setRequired('locale.form.password_confirm_required')
            ->setOmitted();

        $form->addText('__anti', '__Anti', null)
            ->setAttribute('style', 'display: none;');

        $form->addSubmit('submit', 'locale.form.submit_sign_up');
    }

    public function formSucceeded(Form $form)
    {
        try {
            $p      = $this->getPresenter();
            $values = $form->getValues();

            if (strlen($values->__anti) > 0) {
                throw new FormSentBySpamException(
                    $this->translator->translate('locale.form.spam_attempt_sign_up')
                );
            }
            unset($values->__anti);

            $user = $this->userRepository->createRegistration($values);
            $this->sendEmail($this->contactEmail, $user->email, $user->token, $user->id);

            $p->flashMessage(
                $this->translator->translate('locale.sign.sign_up_email_sent'),
                FlashType::SUCCESS
            );

        } catch (FormSentBySpamException $e) {
            $this->addFormError($form, $e);
            $this->redrawControl('formErrors');

        } catch (PossibleUniqueKeyDuplicationException $e) {
            $this->addFormError($form, $e);
            $this->redrawControl('formErrors');

        } catch (\Exception $e) {
            $this->addFormError(
                $form,
                $e,
                $this->translator->translate('locale.error.occurred')
            );
            $this->redrawControl('formErrors');
        }

        if (!empty($user)) {
            $p->redirect('Homepage:default');
        }
    }

    protected function insideRender(ITemplate $template)
    {
        $template->form = $this->form;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $token
     * @param int    $userId
     */
    private function sendEmail($from, $to, $token, $userId)
    {
        $text = $this->presenter->link(
            '//:Admin:Sign:unlock',
            array(
                'userId' => $userId,
                'token'  => $token,
            ));

        $email = new Message;
        $email->setFrom($from)
            ->addTo($to)
            ->setSubject($this->translator->translate('locale.sign.sign_up_request'))
            ->setBody($text);

        $this->mailer->send($email);
    }
}
