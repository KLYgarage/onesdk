<?php

namespace One\Model;

use Psr\Http\Message\UriInterface;
use One\Uri;
use One\Collection;

/**
 * Article Class
 */
class Article extends Model
{
    const CATEGORY_NASIONAL = 1;
    const CATEGORY_INTERNASIONAL = 2;
    const CATEGORY_BISNIS = 3;
    const CATEGORY_SEPAK_BOLA = 4;
    const CATEGORY_OLAHRAGA = 5;
    const CATEGORY_HIBURAN = 6;
    const CATEGORY_TEKNOLOGI = 7;
    const CATEGORY_TRAVEL = 8;
    const CATEGORY_LIFESTYLE = 9;
    const CATEGORY_WANITA = 10;
    const CATEGORY_HIJAB = 11;
    const CATEGORY_KULINER = 12;
    const CATEGORY_SEHAT = 13;
    const CATEGORY_OTOMOTIF = 14;
    const CATEGORY_INSPIRASI = 15;
    const CATEGORY_UNIK = 16;
    const CATEGORY_EVENT = 17;
    const CATEGORY_KOMUNITAS = 18;

    const TYPE_TEXT = 1;
    const TYPE_PHOTO = 2;
    const TYPE_VIDEO = 3;

    const ATTACHMENT_FIELD_PHOTO = 'photo';
    const ATTACHMENT_FIELD_PAGE = 'page';
    const ATTACHMENT_FIELD_VIDEO = 'video';
    const ATTACHMENT_FIELD_GALLERY = 'gallery';

    const POSSIBLE_ATTACHMENT = array(
        self::ATTACHMENT_FIELD_GALLERY,
        self::ATTACHMENT_FIELD_PAGE,
        self::ATTACHMENT_FIELD_PHOTO,
        self::ATTACHMENT_FIELD_VIDEO
    );

    const ALLOWED_TYPE = array(1, 2, 3);
    const ALLOWED_CATEGORY = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18);

    /**
     * attachment property
     *
     * @var array $attachment
     */
    private $attachment = array();

    /**
     * identifier
     *
     * @var string $identifier
     */
    protected $identifier = null;

    /**
     * constructor
     *
     * @param string $title
     * @param string $body
     * @param Psr\Http\Message\UriInterface|string $source
     * @param string $uniqueId
     * @param integer $typeId
     * @param integer $categoryId
     * @param string $reporter
     * @param string $lead
     * @param string $tags
     * @param \DateTimeInterface|string $publishedAt
     */
    public function __construct(
        string $title,
        string $body,
        $source,
        string $uniqueId,
        int $typeId = self::TYPE_TEXT,
        int $categoryId = self::CATEGORY_NASIONAL,
        string $reporter = '',
        string $lead = '',
        $tags = '',
        $publishedAt = null,
        $identifier = null
    ) {
        $source = $this->filterUriInstance($source);
        $publishedAt = $this->filterDateInstance($publishedAt);

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        if (!in_array($typeId, self::ALLOWED_TYPE)) {
            throw new \InvalidArgumentException("Invalid typeId : $typeId, allowed typeId are ". implode(', ', self::ALLOWED_TYPE));
        }

        if (!in_array($categoryId, self::ALLOWED_CATEGORY)) {
            throw new \InvalidArgumentException("Invalid categoryId : $categoryId, allowed category are ". implode(', ', self::ALLOWED_CATEGORY));
        }

        $this->collection = new Collection(
            array(
                'title' => $title,
                'reporter' => $reporter,
                'lead' => $lead,
                'body' => $body,
                'source' => $source,
                'uniqueId' => $uniqueId,
                'type_id' => $typeId,
                'category_id' => $categoryId,
                'tags' => $tags,
                'published_at' => $publishedAt
            )
        );

        if ($identifier) {
            $this->setId($identifier);
        }
    }

    /**
     * setIdentifier from rest api response
     *
     * @param string $identifier
     * @return void
     */
    public function setId(string $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * getIdentifier set before
     *
     * @return string
     */
    public function getId()
    {
        return $this->identifier;
    }

    /**
     * check if this object has attachment assigned to it
     *
     * @param string $field
     * @return boolean
     */
    public function hasAttachment(string $field)
    {
        return isset($this->attachmentp[$field]);
    }

    /**
     * getAttachment based on fields
     *
     * @param string $field
     * @return array|null
     */
    public function getAttachmentByField(string $field)
    {
        if (isset($this->attachment[$field])) {
            return $this->attachment[$field];
        }

        return array();
    }

    /**
     * get ALL attachment assigned to this object
     *
     * @return array|null
     */
    public function getAttachments()
    {
        return $this->attachment;
    }

    /**
     * add attach an attachment to this model
     *
     * @param string $field
     * @param One\Model\Model $item
     * @return One\Model\Article
     */
    public function attach($field, Model $item)
    {
        $this->attachment[$field][] = $item;

        return $this;
    }

    /**
     * Attach Photo Attachment to article
     *
     * @param Photo $photo
     * @return self
     */
    public function attachPhoto(Photo $photo)
    {
        return $this->attach(
            self::ATTACHMENT_FIELD_PHOTO,
            $photo
        );
    }

    /**
     * Attach Paging
     *
     * @param Page $page
     * @return self
     */
    public function attachPage(Page $page)
    {
        return $this->attach(
            self::ATTACHMENT_FIELD_PAGE,
            $this->ensureOrder(
                $page,
                self::ATTACHMENT_FIELD_PAGE
            )
        );
    }

    /**
     * Attach gallery here
     *
     * @param Gallery $gallery
     * @return self
     */
    public function attachGallery(Gallery $gallery)
    {
        return $this->attach(
            self::ATTACHMENT_FIELD_GALLERY,
            $this->ensureOrder(
                $gallery,
                self::ATTACHMENT_FIELD_GALLERY
            )
        );
    }

    /**
     * attach Video
     *
     * @param Video $video
     * @return self
     */
    public function attachVideo(Video $video)
    {
        return $this->attach(
            self::ATTACHMENT_FIELD_VIDEO,
            $this->ensureOrder(
                $video,
                self::ATTACHMENT_FIELD_VIDEO
            )
        );
    }

    private function ensureOrder($attachment, $type)
    {
        if (empty($attachment->get('order'))) {
            $attachment->set(
                'order',
                count($this->getAttachmentByField($type)) + 1
            );
        }

        return $attachment;
    }
}
