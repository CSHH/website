<?php

namespace App\Components\Controls;

use Nette\Application\UI\Control;
use Nette\Utils\Paginator;

class VisualPaginatorControl extends Control
{
    /** @var int @persistent */
    public $page = 1;

    /** @var Paginator */
    private $paginator;

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        if (!$this->paginator) {
            $this->paginator = new Paginator;
        }

        return $this->paginator;
    }

    public function render()
    {
        $paginator = $this->getPaginator();
        $page      = $paginator->page;

        if ($paginator->pageCount < 2) {
            $steps = array($page);
        } else {
            $arr      = range(max($paginator->firstPage, $page - 3), min($paginator->lastPage, $page + 3));
            $count    = 4;
            $quotient = ($paginator->pageCount - 1) / $count;

            for ($i = 0; $i <= $count; $i++) {
                $arr[] = round($quotient * $i) + $paginator->firstPage;
            }

            sort($arr);
            $steps = array_values(array_unique($arr));
        }

        $this->template->steps     = $steps;
        $this->template->paginator = $paginator;
        $this->template->setFile(__DIR__ . '/templates/VisualPaginatorControl.latte');
        $this->template->render();
    }

    /**
     * @param array $params
     */
    public function loadState(array $params)
    {
        parent::loadState($params);

        $this->getPaginator()->page = $this->page;
    }
}
