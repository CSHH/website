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
     * @param  string $usernameCanonical
     * @param  string $token
     * @return string
     */
    public function generateLink($usernameCanonical, $token)
    {
        return $this->linkGenerator->link('Admin:Sign:password', ['usernameCanonical' => $usernameCanonical, 'token' => $token]);
    }
}
