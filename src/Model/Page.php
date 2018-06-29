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
     * @param Psr\Http\Message\UriInterface|string $cover
     * @param Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        string $title,
        string $body,
        $source,
        int $order,
        $cover,
        string $lead = ''
    ) {
        $cover = $this->filterUriInstance($cover);
        $source = $this->filterUriInstance($source);

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $this->collection = new Collection(
            array(
                'title' => $title,
                'lead' => $lead,
                'body' => $body,
                'source' => $source,
                'order' => $order,
                'cover' => $cover
            )
        );
    }
}
