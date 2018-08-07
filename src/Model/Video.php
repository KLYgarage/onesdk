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
        $source = $this->filterUriInstance($source);

        if (! empty($cover)) {
            $cover = $this->filterUriInstance($cover);
        }

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $lead = $this->filterStringInstance($lead);
        $body = $this->filterStringInstance($body);

        $this->collection = new Collection(
            [
                'lead' => $lead,
                'body' => $body,
                'source' => $source,
                'order' => $order,
                'cover' => $cover,
            ]
        );
    }
}
