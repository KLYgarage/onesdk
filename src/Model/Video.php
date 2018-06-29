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
     * @param Psr\Http\Message\UriInterface|string $source
     * @param integer $order
     * @param Psr\Http\Message\UriInterface|string $cover
     * @param string $lead
     */
    public function __construct(
        $body,
        $source,
        $order,
        $cover = null,
        $lead = ''
    ) {
        $source = $this->filterUriInstance($source);

        if (!empty($cover)) {
            $cover = $this->filterUriInstance($cover);
        }

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $this->collection = new Collection(
            array(
                'lead' =>  $lead,
                'body' => $body,
                'source' => $source,
                'order' => $order,
                'cover' => $cover
            )
        );
    }
}
