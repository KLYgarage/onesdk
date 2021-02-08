<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Tag extends Model
{
    /**
     * identifier
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * constructor
     *
     * @param \Psr\Http\Message\UriInterface|string $name
     * @param integer $order
     * @param bool $trending
     */
    public function __construct(
        string $name,
        $trending = false,
        $identifier = null
    ) {
        $properties = [
            'name' => $this->filterStringInstance($name),
            'trending' => $trending ? 1 : 0,
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
