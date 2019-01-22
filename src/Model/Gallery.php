<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

/**
 * Attachment Gallery class
 */
class Gallery extends Model
{
    /**
     * constructor
     *
     * @param \Psr\Http\Message\UriInterface|string $photo
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param string $lead
     */
    public function __construct(
        string $body,
        int $order,
        $photo,
        $source,
        $lead = ''
    ) {
        $this->collection = new Collection([
            'lead' => empty($lead) ? $this->createLeadFromBody($body) : $this->filterStringInstance($lead),
            'body' => $this->filterStringInstance($body),
            'source' => $this->fillSource($source, $photo),
            'order' => $order,
            'photo' => $this->filterUriInstance($photo),
        ]);
    }

    /**
     * source taken from photo url if not available
     *
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param \Psr\Http\Message\UriInterface|string $photo
     */
    private function fillSource($source, $photo): string
    {
        if (! empty($source)) {
            return $this->filterUriInstance($source);
        }

        return (string) $photo;
    }
}
