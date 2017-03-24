<?php

namespace AppTests\Unit\Videos;

use App\Utils\PaginatorFactory;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
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
