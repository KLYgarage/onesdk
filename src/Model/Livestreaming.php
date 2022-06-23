<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Livestreaming extends Model
{
    /**
     * identifier
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * constuctor
     *
     * @param string $uniqueId
     * @param string $title
     * @param string $desc
     * @param string $urlLive
     * @param string $urlThumbnail
     * @param \DateTimeInterface|string $publishedAt
     * @param \DateTimeInterface|string $endAt
     * @param bool $publishStatus
     
     */

    public function __construct(
        string $uniqueId,
        string $title,
        string $desc,
        string $urlLive,
        string $urlThumbnail,
        $publishedAt = null,
        $endAt = null,
        $publishStatus = false,
        $identifier = null
    ) {
        $properties = [
            'title' => $this->filterStringInstance($title),
            'desc' => $this->filterStringInstance($desc),
            'url_live' => $this->filterStringInstance($urlLive),
            'url_thumbnail' => $this->filterStringInstance($urlThumbnail),
            'published_at' => $this->filterDateInstance($publishedAt),
            'end_at' => $this->filterDateInstance($endAt),
            'publish_status' => $publishStatus ? 1 : 0,
            'unique_id' => $uniqueId
        ];

        $this->collection = new Collection($properties);

        if ($identifier) {
            $this->setId((string) $identifier);
        }
    }

    /**
     * setIdentifier from rest api response
     */
    public function setId(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * getIdentifier set before
     */
    public function getId(): string
    {
        return $this->identifier;
    }
}
