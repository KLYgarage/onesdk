<?php

namespace One\Model;

use Psr\Http\Message\UriInterface;
use One\Collection;

/**
 * Attachment Gallery class
 */
class Gallery extends Model
{
    /**
     * constructor
     *
     * @param string $body
     * @param integer $order
     * @param \Psr\Http\Message\UriInterface|string $photo
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        $body,
        $order,
        $photo,
        $source,
        $lead = ''
    ) {
        $properties = array(
            'lead' =>  empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'source' => $this->fillSource($source, $photo),
            'order' => $order,
            'photo' => $this->filterUriInstance($photo)
        );

        $this->collection = new Collection($properties);
    }

    /**
     * source taken from photo url if not available
     *
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param \Psr\Http\Message\UriInterface|string $photo
     * @return string
     */
    private function fillSource($source, $photo)
    {
        if (!empty($source)) {
            return $this->filterUriInstance($source);
        }

        return (string) $photo;
    }
}
