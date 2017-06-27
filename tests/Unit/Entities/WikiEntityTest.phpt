<?php

namespace AppTests\Unit\Entities;

use App\Entities\WikiEntity;
use AppTests;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

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

    public function testGetContributorsWithAuthor()
    {
        $author     = new AppTests\UserEntityImpl;
        $author->id = 100;

        $contributor1     = new AppTests\UserEntityImpl;
        $contributor1->id = 1;
        $contributor2     = new AppTests\UserEntityImpl;
        $contributor2->id = 2;

        $wiki            = new WikiEntity;
        $wiki->createdBy = $author;
        $wiki->contributors->add($contributor1);
        $wiki->contributors->add($contributor2);

        $contributors = $wiki->getContributorsWithAuthor();
        Assert::count(3, $contributors);
        Assert::same(100, $contributors->first()->id);
    }

    public function testGetDraftsReturnsArrayCollection()
    {
        Assert::type('Doctrine\Common\Collections\ArrayCollection', (new WikiEntity)->getDrafts());
    }
}

$testCase = new WikiEntityTest;
$testCase->run();
