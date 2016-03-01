<?php

namespace AppTests\Model\Videos;

use App\Model\Videos\Youtube;
use Mockery as m;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap-unit.php';

class YoutubeTest extends Tester\TestCase
{
    public function testGetVideoSrc()
    {
        $url         = 'https://www.example.com/watch?v=abc';
        $expectedSrc = 'https://www.example.com/embed/abc';

        $translator = $this->getTranslatorMock();

        $youtube = new Youtube($translator);

        $src = $youtube->getVideoSrc($url);

        Assert::same($expectedSrc, $src);
    }

    public function testGetVideoSrcThrowsInvalidVideoUrlException()
    {
        $url = 'https://www.example.com/BAD_KEY=abc';

        $translator = $this->getTranslatorMock();
        $translator->shouldReceive('translate')
            ->once()
            ->andReturn('');

        Assert::exception(function() use ($url, $translator) {
            $youtube = new Youtube($translator);
            $youtube->getVideoSrc($url);
        }, 'App\Model\Exceptions\InvalidVideoUrlException');
    }

    private function getTranslatorMock()
    {
        return m::mock('Nette\Localization\ITranslator');
    }
}

$testCase = new YoutubeTest;
$testCase->run();