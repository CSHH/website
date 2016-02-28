<?php

namespace AppTests\Model\Entities;

use App\Model\Entities\BaseEntity;
use Nette\Utils\ArrayHash;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class BaseEntityTest extends Tester\TestCase
{
    /**
     * @dataProvider getArgs
     */
    public function testSetValuesAsArray($a, $b, $c)
    {
        $values = array(
            'a' => $a,
            'b' => $b,
            'c' => $c,
        );

        $this->assert($values, $a, $b, $c);
    }

    /**
     * @dataProvider getArgs
     */
    public function testSetValuesAsTraversable($a, $b, $c)
    {
        $values = new ArrayHash;
        $values->a = $a;
        $values->b = $b;
        $values->c = $c;

        $this->assert($values, $a, $b, $c);
    }

    public function getArgs()
    {
        return array(['A', 'B', 'C']);
    }

    private function assert($values, $a, $b, $c)
    {
        $ent = new BaseEntityImpl;
        $ent->setValues($values);

        Assert::same($a, $ent->a);
        Assert::same($b, $ent->b);
        Assert::same($c, $ent->c);
    }

    public function testSetValuesInvalidArgumentException()
    {
        $ent = new BaseEntityImpl;

        Assert::exception(function() use ($ent) {
            $ent->setValues('');
        }, 'Nette\InvalidArgumentException');
    }
}

class BaseEntityImpl extends BaseEntity
{
    protected $a;
    protected $b;
    protected $c;
}

$testCase = new BaseEntityTest;
$testCase->run();
