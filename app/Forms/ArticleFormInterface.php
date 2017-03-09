<?php

namespace App\Forms;

use App\Entities;

interface ArticleFormInterface
{
    /**
     * @param  Entities\UserEntity    $user
     * @param  Entities\ArticleEntity $item
     * @return ArticleForm
     */
    public function create(Entities\UserEntity $user, Entities\ArticleEntity $item = null);
}
