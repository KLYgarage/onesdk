<?php

namespace One\Model;

use Psr\Http\Message\UriInterface;
use One\Collection;

class Video extends Model
{
    /**
     * constructor
     *
     * @param string $body
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param integer $order
     * @param \Psr\Http\Message\UriInterface|string $cover
     * @param string $lead
     */
    public function __construct(
        $body,
        $source,
        $order,
        $cover = null,
        $lead = ''
    ) {
        $properties = array(
            'lead' =>  empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'source' => $this->filterUriInstance($source),
            'order' => $order
        );

        if (! empty($cover)) {
            $properties['cover'] = $this->filterUriInstance($cover);
        }

        $this->collection = new Collection($properties);
    }
}
