<?php

namespace One\Model;

use Psr\Http\Message\UriInterface;
use One\Collection;

class Page extends Model
{
    /**
     * constuctor
     *
     * @param string $title
     * @param string $body
     * @param integer $order
     * @param \Psr\Http\Message\UriInterface|string $cover
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        $title,
        $body,
        $source,
        $order,
        $cover = '',
        $lead = ''
    ) {
        $properties = array(
            'title' => $this->filterStringInstance($title),
            'lead' => empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'order' => $order
        );

        if (! empty($cover)) {
            $properties['cover'] = $this->filterUriInstance($cover);
        }

        if (! empty($source)) {
            $properties['source'] = $this->filterUriInstance($source);
        }

        $this->collection = new Collection($properties);
    }
}
