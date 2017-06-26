<?php

namespace App\Videos;

use App\Exceptions\InvalidVideoUrlException;
use Nette\Localization\ITranslator;
use Nette\Utils\Strings;

class Youtube
{
    /** @var ITranslator */
    private $translator;

    /**
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param  string                   $pageUrl
     * @throws InvalidVideoUrlException
     * @return string
     */
    public function getVideoSrc($pageUrl)
    {
        if (!Strings::startsWith($pageUrl, 'https://www.youtube.com')) {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_youtube_video_url')
            );
        }

        $key = '/watch';

        if (!Strings::contains($pageUrl, $key)) {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_youtube_video_url')
            );
        }

        $urlParts = parse_url($pageUrl);
        if ($urlParts === false) {
            throw new InvalidVideoUrlException(
                $this->translator->translate('locale.error.invalid_youtube_video_url')
            );
        }
        $query = $urlParts['query'];
        if (Strings::contains($query, '&')) {
            $queryParts = explode('&', $query);
            foreach ($queryParts as $qp) {
                if (Strings::startsWith($qp, 'v=')) {
                    $ytUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $key . '?' . $qp;
                    break;
                }
            }
        } else {
            $ytUrl = $urlParts['scheme'] . '://' . $urlParts['host'] . $key . '?' . $query;
        }

        $embedUrl = str_replace('watch?v=', 'embed/', $ytUrl);
        if (!Strings::contains($embedUrl, '&')) {
            return $embedUrl;
        }

        return Strings::before($embedUrl, '&');
    }
}
