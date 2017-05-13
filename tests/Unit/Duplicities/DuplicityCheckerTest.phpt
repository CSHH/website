<?php

namespace AppTests\Unit\Duplicities;

use App\Duplicities\DuplicityChecker;
use AppTests\BaseEntityImpl;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class DuplicityCheckerTest extends Tester\TestCase
{
    use UnitMocks;

    public function testFindDuplicityAndReturnsEntity()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, [new BaseEntityImpl]);

        $qb = $this->qb;
        $this->mock($qb, 'select');
        $this->mock($qb, 'from');
        $this->mock($qb, 'where');
        $this->mock($qb, 'setParameter');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $dc = new DuplicityChecker($em);
        Assert::type('App\Entities\BaseEntity', $dc->findDuplicity('EntityClassName', 'attribute', 'value'));
    }

    public function testFindDuplicityAndReturnsNull()
    {
        $query = $this->query;
        $this->mock($query, 'getResult', 1, null);

        $qb = $this->qb;
        $this->mock($qb, 'select');
        $this->mock($qb, 'from');
        $this->mock($qb, 'where');
        $this->mock($qb, 'setParameter');
        $this->mock($qb, 'getQuery', 1, $query);

        $em = $this->em;
        $this->mock($em, 'createQueryBuilder', 1, $qb);

        $dc = new DuplicityChecker($em);
        Assert::null($dc->findDuplicity('EntityClassName', 'attribute', 'value'));
    }
}

$testCase = new DuplicityCheckerTest;
$testCase->run();
