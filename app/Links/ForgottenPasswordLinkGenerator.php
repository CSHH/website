<?php

namespace App\Links;

use Nette\Application\LinkGenerator;

class ForgottenPasswordLinkGenerator
{
    /** @var LinkGenerator */
    private $linkGenerator;

    public function __construct(LinkGenerator $linkGenerator)
    {
        $this->linkGenerator = $linkGenerator;
    }

    /**
     * @param  string $email
     * @param  string $token
     * @return string
     */
    public function generateLink($email, $token)
    {
        return $this->linkGenerator->link('Admin:Sign:password', ['email' => $email, 'token' => $token]);
    }
}
