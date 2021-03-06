<?php

namespace AppTests\Unit\Entities;

use App\Entities\FileEntity;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class FileEntityTest extends Tester\TestCase
{
    public function testGetFilePathReturnIncomplete()
    {
        Assert::same('//.', (new FileEntity)->getFilePath());
    }

    public function testGetFilePathReturnCorrect()
    {
        $ent = new FileEntity;

        $year      = '1970';
        $month     = '01';
        $name      = 'abc';
        $extension = 'txt';

        $ent->year      = $year;
        $ent->month     = $month;
        $ent->name      = $name;
        $ent->extension = $extension;

        Assert::same(
            $year . '/' . $month . '/' . $name . '.' . $extension,
            $ent->getFilePath()
        );
    }
}

$testCase = new FileEntityTest;
$testCase->run();
