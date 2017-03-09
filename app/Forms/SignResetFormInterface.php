<?php

namespace App\Forms;

interface SignResetFormInterface
{
    /**
     * @param  string        $appDir
     * @param  string        $contactEmail
     * @return SignResetForm
     */
    public function create($appDir, $contactEmail);
}
