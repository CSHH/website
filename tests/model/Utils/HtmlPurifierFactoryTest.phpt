<?php

namespace AppTests\Model\Videos;

use App\Model\Utils\HtmlPurifierFactory;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class HtmlPurifierFactoryTest extends Tester\TestCase
{
    public function testCreateHtmlPurifier()
    {
        Assert::type('HTMLPurifier', (new HtmlPurifierFactory)->createHtmlPurifier());
    }

    public function testPurify()
    {
        Assert::matchFile(
            RESOURCES_DIR . '/purifier-expected.html',
            (new HtmlPurifierFactory)->createHtmlPurifier()->purify(file_get_contents(RESOURCES_DIR . '/purifier-source.html'))
        );
    }
}

$testCase = new HtmlPurifierFactoryTest;
$testCase->run();
