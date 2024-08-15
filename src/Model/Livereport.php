<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Livereport extends Model
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
     * @param string $shortDesc
     * @param \DateTimeInterface|string $publishDat
     * @param \DateTimeInterface|string $endDate
     * @param string $tag
     * @param bool $isHeadline
     * @param bool $published
     * @param string $livereportChild
     
     */

    public function __construct(
        string $uniqueId,
        string $title,
        string $shortDesc,
        $publishDate = null,
        $endDate = null,
        string $tag = null,
        $isHeadline = false,
        $published = false,
        string $livereportChild = null,
        $identifier = null

    ) {
        $properties = [
            'title' => $this->filterStringInstance($title),
            'short_desc' => $this->filterStringInstance($desc),
            'publish_date' => $this->filterDateInstance($publishDate),
            'end_date' => $this->filterDateInstance($endDate),
            'tag' => $this->filterStringInstance($tag),
            'is_headline' => $isHeadline ? 1 : 0,
            'published' => $published ? 1 : 0,
            'livereport_child' => $this->filterStringInstance($livereportChild)
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

