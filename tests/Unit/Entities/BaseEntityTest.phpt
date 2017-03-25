<?php

namespace AppTests\Unit\Entities;

use AppTests\BaseEntityImpl;
use Nette\Utils\ArrayHash;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class BaseEntityTest extends Tester\TestCase
{
    /**
     * @dataProvider getArgs
     */
    public function testSetValuesAsArray($a, $b, $c)
    {
        $values = [
            'a' => $a,
            'b' => $b,
            'c' => $c,
        ];

        $this->assert($values, $a, $b, $c);
    }

    /**
     * @dataProvider getArgs
     */
    public function testSetValuesAsTraversable($a, $b, $c)
    {
        $values    = new ArrayHash;
        $values->a = $a;
        $values->b = $b;
        $values->c = $c;

        $this->assert($values, $a, $b, $c);
    }

    /**
     * @return array
     */
    public function getArgs()
    {
        return [['A', 'B', 'C']];
    }

    /**
     * @param array|\Traversable $values
     * @param string             $a
     * @param string             $b
     * @param string             $c
     */
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

        Assert::exception(function () use ($ent) {
            $ent->setValues('');
        }, 'Nette\InvalidArgumentException');
    }
}

$testCase = new BaseEntityTest;
$testCase->run();
