<?php

namespace App\Components\Forms;

interface SignUpFormInterface
{
    /**
     * @param  string     $contactEmail
     * @return SignUpForm
     */
    public function create($contactEmail);
}
