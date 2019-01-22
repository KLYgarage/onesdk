<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Video extends Model
{
    /**
     * constructor
     *
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param integer $order
     * @param \Psr\Http\Message\UriInterface|string $cover
     * @param string $lead
     */
    public function __construct(
        string $body,
        $source,
        $order,
        $cover = null,
        $lead = ''
    ) {
        $properties = [
            'lead' => empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'order' => $order,
        ];

        if (! empty($source)) {
            $properties['source'] = $this->filterUriInstance($source);
        }

        if (! empty($cover)) {
            $properties['cover'] = $this->filterUriInstance($cover);
        }

        $this->collection = new Collection($properties);
    }
}
