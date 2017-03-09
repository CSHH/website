<?php

namespace AppTests\Videos;

use AppTests\UnitMocks;
use App\Utils\PaginatorFactory;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

class PaginatorFactoryTest extends Tester\TestCase
{
    use UnitMocks;

    public function testCreatePaginator()
    {
        Assert::type('Doctrine\ORM\Tools\Pagination\Paginator', (new PaginatorFactory)->createPaginator($this->query));
    }
}

$testCase = new PaginatorFactoryTest;
$testCase->run();
