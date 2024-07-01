<?php declare(strict_types=1);

namespace One\Model;

use One\Collection;

class Breakingnews extends Model
{
    /**
     * identifier
     *
     * @var string
     */
    protected $identifier = null;

    /**
     * constuctor
     *
     * @param string $uniqueId
     * @param string $title
     * @param string $content
     * @param string $tag
     * @param bool $status
     * @param bool $isPushed
     * @param string $startDate
     * @param string $endDate
     * @param string $url
     * @param string $type
     * @param string $video
     * @param string $logo
     */
    public function __construct(
        string $uniqueId,
        string $title,
        string $content,
        string $tag,
        $status = false,
        $isPushed = false,
        string $startDate,
        string $endDate,
        $identifier = null,
        string $url = '',
        string $type = '',
        string $video = '',
        string $logo = ''
    ) {
        $properties = [
            'title' => $this->filterStringInstance($title),
            'content' => $this->filterStringInstance($content),
            'tag' => $this->filterStringInstance($tag),
            'status' => $status ? 1 : 0,
            'is_pushed' => $isPushed ? 1 : 0,
            'start_date' => $this->filterDateInstance($startDate),
            'end_date' => $this->filterDateInstance($endDate),
            'uniqueId' => $uniqueId
        ];

        $this->collection = new Collection($properties);
        
        if (!empty($this->filterStringInstance($url))) {
            $this->collection->offsetSet('url', $this->filterStringInstance($url));
        }
        if (!empty($this->filterStringInstance($type))) {
            $this->collection->offsetSet('type', $this->filterStringInstance($type));
        }
        if (!empty($this->filterStringInstance($video))) {
            $this->collection->offsetSet('video', $this->filterStringInstance($video));
        }
        if (!empty($this->filterStringInstance($logo))) {
            $this->collection->offsetSet('logo', $this->filterStringInstance($logo));
        }

        if ($identifier) {
            $this->setId((string) $identifier);
        }
    }

    /**
     * setIdentifier from rest api response
     */
    public function setId(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * getIdentifier set before
     */
    public function getId(): string
    {
        return $this->identifier;
    }
}

