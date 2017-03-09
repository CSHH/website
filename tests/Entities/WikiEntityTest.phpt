<?php

namespace AppTests\Entities;

use App\Entities\WikiEntity;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

/**
 * @testCase
 */
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
