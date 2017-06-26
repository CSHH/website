<?php

namespace AppTests\Unit\Videos;

use App\Videos\Youtube;
use AppTests\UnitMocks;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class YoutubeTest extends Tester\TestCase
{
    use UnitMocks;

    /**
     * @dataProvider getUrls
     *
     * @param string $url
     * @param string $expectedSrc
     */
    public function testGetVideoSrc($url, $expectedSrc)
    {
        $translator = $this->getTranslatorMock();
        $youtube    = new Youtube($translator);
        $src        = $youtube->getVideoSrc($url);
        Assert::same($expectedSrc, $src);
    }

    /**
     * @return array
     */
    public function getUrls()
    {
        return [
            ['https://www.example.com/watch?v=abc', 'https://www.example.com/embed/abc'],
            ['https://www.example.com/watch?v=abc&list=xyz', 'https://www.example.com/embed/abc'],
            ['https://www.example.com/watch?list=xyz&v=abc', 'https://www.example.com/embed/abc'],
        ];
    }

    public function testGetVideoSrcThrowsInvalidVideoUrlException()
    {
        $url = 'https://www.example.com/BAD_KEY=abc';

        $translator = $this->getTranslatorMock();
        $translator->shouldReceive('translate')
            ->once()
            ->andReturn('');

        Assert::exception(function () use ($url, $translator) {
            $youtube = new Youtube($translator);
            $youtube->getVideoSrc($url);
        }, 'App\Exceptions\InvalidVideoUrlException');
    }
}

$testCase = new YoutubeTest;
$testCase->run();
