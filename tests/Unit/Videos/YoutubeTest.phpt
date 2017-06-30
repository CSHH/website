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
     * @dataProvider getGoodUrls
     *
     * @param string $url
     * @param string $expectedSrc
     */
    public function testGetVideoSrc($url, $expectedSrc)
    {
        $youtube = new Youtube($this->translator);
        $src     = $youtube->getVideoSrc($url);
        Assert::same($expectedSrc, $src);
    }

    /**
     * @return array
     */
    public function getGoodUrls()
    {
        return [
            ['https://www.youtube.com/watch?v=abc', 'https://www.youtube.com/embed/abc'],
            ['https://www.youtube.com/watch?v=abc&list=xyz', 'https://www.youtube.com/embed/abc'],
            ['https://www.youtube.com/watch?list=xyz&v=abc', 'https://www.youtube.com/embed/abc'],
        ];
    }

    /**
     * @dataProvider getBadUrls
     *
     * @param string $url
     */
    public function testGetVideoSrcThrowsInvalidVideoUrlException($url)
    {
        $translator = $this->translator;
        $translator->shouldReceive('translate')
            ->once()
            ->andReturn('');

        Assert::exception(function () use ($url, $translator) {
            $youtube = new Youtube($translator);
            $youtube->getVideoSrc($url);
        }, 'App\Exceptions\InvalidVideoUrlException');
    }

    /**
     * @return array
     */
    public function getBadUrls()
    {
        return [
            ['https://www.BADHOST.com'],
            ['https://www.youtube.com/MISSING_WATCH_IN_PATH'],
            ['https://A_MALFORMED_URL'],
        ];
    }
}

$testCase = new YoutubeTest;
$testCase->run();
