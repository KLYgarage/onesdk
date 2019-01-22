<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Page extends Model
{
    /**
     * constuctor
     *
     * @param integer $order
     * @param \Psr\Http\Message\UriInterface|string $cover
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        string $title,
        string $body,
        $source,
        $order,
        $cover = '',
        $lead = ''
    ) {
        $properties = [
            'title' => $this->filterStringInstance($title),
            'lead' => empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'order' => $order,
        ];

        if (! empty($cover)) {
            $properties['cover'] = $this->filterUriInstance($cover);
        }

        if (! empty($source)) {
            $properties['source'] = $this->filterUriInstance($source);
        }

        $this->collection = new Collection($properties);
    }
}
