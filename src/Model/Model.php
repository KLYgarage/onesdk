<?php

namespace One\Model;

use function One\createUriFromString;
use function One\createuriFromServer;
use One\Collection;

/**
 * Model base class
 * @method self add()
 */
class Model
{
    const LEAD_SENTENCES_LIMIT = 5;

    /**
     * data variable to that work as One\Collection
     *
     * @var \One\Collection
     */
    protected $collection = null;

    /**
     * get Data Collection
     *
     * @return \One\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * immutable, return CLONE of original object with changed collection
     *
     * @param \One\Collection $collection
     * @return self
     */
    public function withCollection(Collection $collection)
    {
        $clone = clone $this;
        $clone->collection = $collection;

        return $clone;
    }

    /**
     * Make Sure Uri is a Psr\Http\Message\UriInterface instance
     *
     * @param \Psr\Http\Message\UriInterface|string|null $uri
     * @return \Psr\Http\Message\UriInterface
     */
    protected function filterUriInstance($uri)
    {
        if (is_subclass_of($uri, 'Psr\Http\Message\UriInterface')) {
            return $uri;
        }

        if (is_string($uri)) {
            return createUriFromString($uri);
        }

        return createuriFromServer();
    }

    /**
     * Make Sure Date in string with correct format state
     *
     * @param \DateTimeInterface|string|int|null $date
     * @return string
     */
    protected function filterDateInstance($date)
    {
        if (empty($date)) {
            $date = new \DateTime("now", new \DateTimeZone("Asia/Jakarta"));
        }

        if (is_string($date) || is_int($date)) {
            $date = new \DateTime($date);
        }

        return $this->formatDate($date);
    }

    /**
     * format date into required format
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function formatDate(\DateTimeInterface $date)
    {
        return $date->format("Y-m-d H:i:s");
    }

    /**
     * create lead/synopsis from body content if not available
     *
     * @param string $body
     * @return string
     */
    protected function createLeadFromBody($body)
    {
        $body = strip_tags($body);
        $sentences = array_filter(
            explode(".", $body),
            function ($item) {
                return trim($item);
            }
        );

        return implode(
            '. ',
            array_slice(
                $sentences,
                0,
                self::LEAD_SENTENCES_LIMIT
            )
        );
    }

    /**
     * proxy method to chain it to Collection class
     *
     * @param string $name
     * @param array $arguments
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->getCollection(), $name)) {
            return call_user_func_array([$this->getCollection(), $name], $arguments);
        }

        throw new \Exception("Method $name not exist");
    }
}
