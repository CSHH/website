<?php

namespace App\Emails;

use Latte;
use Nette\Http\UrlScript;
use Nette\Http\Request;
use Nette\Localization\ITranslator;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

class AccountActivationEmail
{
    /** @var IMailer */
    private $mailer;

    /** @var Latte\Engine */
    private $latteEngine;

    /** @var Message */
    private $emailMessage;

    /** @var UrlScript */
    private $urlScript;

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
     * @param Request      $request
     * @param string       $emailTemplatePath
     * @param string       $fromEmail
     * @param string       $subject
     */
    public function __construct(ITranslator $translator, IMailer $mailer, Latte\Engine $latteEngine, Message $emailMessage, Request $request, $emailTemplatePath, $fromEmail, $subject)
    {
        $this->mailer            = $mailer;
        $this->latteEngine       = $latteEngine;
        $this->emailMessage      = $emailMessage;
        $this->urlScript         = $request->getUrl();
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
            'baseUri' => $this->urlScript->getHostUrl(),
            'host'    => $this->urlScript->getHost(),
        ];

        $this->emailMessage->setFrom($this->fromEmail)
            ->addTo($to)
            ->setSubject($this->subject)
            ->setHtmlBody($this->latteEngine->renderToString($this->emailTemplatePath, $parameters));

        $this->mailer->send($this->emailMessage);
    }
}
