<?php

namespace App\Components\Forms;

interface SignResetFormInterface
{
    /**
     * @param  string        $appDir
     * @param  string        $contactEmail
     * @return SignResetForm
     */
    public function create($appDir, $contactEmail);
}