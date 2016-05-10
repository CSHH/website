<?php

namespace App\Components\Forms;

use Nette\Http\UrlScript;

interface SignUpFormInterface
{
    /**
     * @param  UrlScript  $urlScript
     * @param  string     $appDir
     * @param  string     $contactEmail
     * @return SignUpForm
     */
    public function create(UrlScript $urlScript, $appDir, $contactEmail);
}
