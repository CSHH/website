<?php

namespace App\Components\Controls;

interface VisualPaginatorControlInterface
{
    /**
     * @param  int $page
     * @return VisualPaginatorControl
     */
    public function create($page);
}
