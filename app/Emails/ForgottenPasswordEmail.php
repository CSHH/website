<?php

namespace App\Emails;

use Latte;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class ForgottenPasswordEmail
{
    /** @var IMailer */
    private $mailer;

    /** @var Latte\Engine */
    private $latteEngine;

    /** @var Message */
    private $emailMessage;

    /** @var string */
    private $emailTemplatePath;

    /** @var string */
    private $fromEmail;

    /** @var string */
    private $subject;

    /**
     * @param ITranslator  $translator
     * @param IMailer      $mailer
     * @param Latte\Engine $latteEngine
     * @param Message      $emailMessage
     * @param string       $emailTemplatePath
     * @param string       $fromEmail
     * @param string       $subject
     */
    public function __construct(ITranslator $translator, IMailer $mailer, Latte\Engine $latteEngine, Message $emailMessage, $emailTemplatePath, $fromEmail, $subject)
    {
        $this->mailer            = $mailer;
        $this->latteEngine       = $latteEngine;
        $this->emailMessage      = $emailMessage;
        $this->emailTemplatePath = $emailTemplatePath;
        $this->fromEmail         = $fromEmail;
        $this->subject           = $translator->translate($subject);
    }

    /**
     * @param string $to
     * @param string $link
     */
    public function send($to, $link)
    {
        $parameters = [
            'subject' => $this->subject,
            'link'    => $link,
        ];

        $this->emailMessage->setFrom($this->fromEmail)
            ->addTo($to)
            ->setSubject($this->subject)
            ->setHtmlBody($this->latteEngine->renderToString($this->emailTemplatePath, $parameters));

        $this->mailer->send($this->emailMessage);
    }
}
