<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Photo extends Model
{
    public const RATIO_SQUARE = '1:1';

    public const RATIO_RECTANGLE = '2:1';

    public const RATIO_HEADLINE = '3:2';

    public const RATIO_VERTICAL = '9:16';

    public const RATIO_COVER = 'cover';

    /**
     * constructor
     *
     * @param \Psr\Http\Message\UriInterface|string $url
     * @param string $ratio
     * @param string $description
     * @param string $information
     */
    public function __construct(
        $url,
        $ratio,
        $description = '',
        $information = ''
    ) {
        $url = $this->filterUriInstance($url);

        if (! in_array($ratio, $this->getAvailableRatios(), true)) {
            throw new \Exception("ratio ${ratio} not allowed, allowed ratio are " . implode(', ', $this->getAvailableRatios()));
        }

        $description = $this->filterStringInstance($description);
        $information = $this->filterStringInstance($information);

        $this->collection = new Collection(
            [
                'url' => $url,
                'ratio' => $ratio,
                'description' => $description,
                'information' => $information,
            ]
        );
    }

    /**
     * get available ratio for photo attachment
     */
    private function getAvailableRatios(): array
    {
        return [
            self::RATIO_SQUARE,
            self::RATIO_RECTANGLE,
            self::RATIO_HEADLINE,
            self::RATIO_VERTICAL,
            self::RATIO_COVER,
        ];
    }
}
