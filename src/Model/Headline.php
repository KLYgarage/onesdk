<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Headline extends Model
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
     * @param string $text
     * @param string $url
     * @param bool $active
     */
    public function __construct(
        string $uniqueId,
        string $title,
        string $content,
        string $url,
        $active = false,
        $identifier = null
    ) {
        $properties = [
            'title' => $this->filterStringInstance($title),
            'content' => $this->filterStringInstance($content),
            'url' => $this->filterUriInstance($url),
            'show' => $active ? 1 : 0,
            'uniqueId' => $uniqueId
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

