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
        $cover,
        $lead = ''
    ) {
        $cover = $this->filterUriInstance($cover);
        $source = $this->filterUriInstance($source);

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $title = $this->filterStringInstance($title);
        $lead = $this->filterStringInstance($lead);
        $body = $this->filterStringInstance($body);

        $this->collection = new Collection(
            [
                'title' => $title,
                'lead' => $lead,
                'body' => $body,
                'source' => $source,
                'order' => $order,
                'cover' => $cover,
            ]
        );
    }
}
