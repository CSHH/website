<?php

namespace AppTests\Videos;

use App\Utils\HtmlPurifierFactory;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap-unit.php';

/**
 * @testCase
 */
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
