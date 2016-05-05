<?php

namespace AppTests\Model\Entities;

use App\Model\Entities\WikiEntity;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class WikiEntityTest extends Tester\TestCase
{
    public function testGetRelatedReturnsArrayCollection()
    {
        Assert::type('Doctrine\Common\Collections\ArrayCollection', (new WikiEntity)->getRelated());
    }

    public function testGetContributorsReturnsArrayCollection()
    {
        Assert::type('Doctrine\Common\Collections\ArrayCollection', (new WikiEntity)->getContributors());
    }

    public function testGetDraftsReturnsArrayCollection()
    {
        Assert::type('Doctrine\Common\Collections\ArrayCollection', (new WikiEntity)->getDrafts());
    }
}

$testCase = new WikiEntityTest;
$testCase->run();
