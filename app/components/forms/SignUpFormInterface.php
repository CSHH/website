<?php

namespace App\Components\Forms;

interface SignUpFormInterface
{
    /**
     * @param  string     $appDir
     * @param  string     $contactEmail
     * @return SignUpForm
     */
    public function create($appDir, $contactEmail);
}
