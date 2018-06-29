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
     * @param Psr\Http\Message\UriInterface|string $photo
     * @param Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        string $body,
        int $order,
        $photo,
        $source,
        string $lead = ''
    ) {
        $photo = $this->filterUriInstance($photo);
        $source = $this->fillSource($source, $photo);

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $this->collection = new Collection(
            array(
                'lead' =>  $lead,
                'body' => $body,
                'source' => $source,
                'order' => $order,
                'photo' => $photo
            )
        );
    }

    /**
     * source taken from photo url if not available
     *
     * @param Psr\Http\Message\UriInterface|string $source
     * @param Psr\Http\Message\UriInterface|string|null $photo
     * @return void
     */
    private function fillSource($source, $photo)
    {
        if (!empty($source)) {
            return $this->filterUriInstance($source);
        }

        return $photo;
    }
}
