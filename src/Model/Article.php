<?php

namespace One\Model;

use Psr\Http\Message\UriInterface;
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
    const CATEGORY_E_SPORTS = 19;

    const TYPE_TEXT = 1;
    const TYPE_PHOTO = 2;
    const TYPE_VIDEO = 3;

    const ATTACHMENT_FIELD_PHOTO = 'photo';
    const ATTACHMENT_FIELD_PAGE = 'page';
    const ATTACHMENT_FIELD_VIDEO = 'video';
    const ATTACHMENT_FIELD_GALLERY = 'gallery';

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
     * @param \Psr\Http\Message\UriInterface|string $source
     * @param string $uniqueId
     * @param integer $typeId
     * @param integer $categoryId
     * @param string $reporter
     * @param string $lead
     * @param string $tags
     * @param \DateTimeInterface|string $publishedAt
     */
    public function __construct(
        $title,
        $body,
        $source,
        $uniqueId,
        $typeId = self::TYPE_TEXT,
        $categoryId = self::CATEGORY_NASIONAL,
        $reporter = '',
        $lead = '',
        $tags = '',
        $publishedAt = null,
        $identifier = null
    ) {
        $source = $this->filterUriInstance($source);
        $publishedAt = $this->filterDateInstance($publishedAt);

        if (empty($lead)) {
            $lead = $this->createLeadFromBody($body);
        }

        $allowedType = array(
            self::TYPE_PHOTO,
            self::TYPE_TEXT,
            self::TYPE_VIDEO
        );

        if (!in_array($typeId, $allowedType)) {
            throw new \InvalidArgumentException("Invalid typeId : $typeId, allowed typeId are " . implode(', ', $allowedType));
        }

        $allowedCategory = array(
            self::CATEGORY_NASIONAL,
            self::CATEGORY_INTERNASIONAL,
            self::CATEGORY_BISNIS,
            self::CATEGORY_SEPAK_BOLA,
            self::CATEGORY_OLAHRAGA,
            self::CATEGORY_HIBURAN,
            self::CATEGORY_TEKNOLOGI,
            self::CATEGORY_TRAVEL,
            self::CATEGORY_LIFESTYLE,
            self::CATEGORY_WANITA,
            self::CATEGORY_HIJAB,
            self::CATEGORY_KULINER,
            self::CATEGORY_SEHAT,
            self::CATEGORY_OTOMOTIF,
            self::CATEGORY_INSPIRASI,
            self::CATEGORY_UNIK,
            self::CATEGORY_EVENT,
            self::CATEGORY_KOMUNITAS,
        );

        if (!in_array($categoryId, $allowedCategory)) {
            throw new \InvalidArgumentException("Invalid categoryId : $categoryId, allowed category are " . implode(', ', $allowedCategory));
        }

        $title    = $this->filterStringInstance($title);
        $reporter = $this->filterStringInstance($reporter);
        $lead     = $this->filterStringInstance($lead);
        $body     = $this->filterStringInstance($body);
        $tags     = $this->filterStringInstance($tags);

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
     * get ALL Possible attachment for an article, return arrays of field name. Used for consistency accross sdk
     * leveraging php version 5.3 cannot use array constant
     *
     * @return string[]
     */
    public static function getPossibleAttachment()
    {
        return array_merge(
            self::getDeleteableAttachment(),
            array(
                self::ATTACHMENT_FIELD_PHOTO
            )
        );
    }

    /**
     * get deleteable attachment for constant usage across sdk
     *
     * @return string[]
     */
    public static function getDeleteableAttachment()
    {
        return array(
            self::ATTACHMENT_FIELD_GALLERY,
            self::ATTACHMENT_FIELD_PAGE,
            self::ATTACHMENT_FIELD_VIDEO
        );
    }

    /**
     * setIdentifier from rest api response
     *
     * @param string $identifier
     * @return void
     */
    public function setId($identifier)
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
    public function hasAttachment($field)
    {
        return isset($this->attachment[$field]);
    }

    /**
     * getAttachment based on fields
     *
     * @param string $field
     * @return array
     */
    public function getAttachmentByField($field)
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
     * @param Model $item
     * @return Article
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

    /**
     * ensuring order
     *
     * @param Model $attachment
     * @param string $type
     * @return Model
     */
    private function ensureOrder($attachment, $type)
    {
        $attachmentOrder = $attachment->get('order');

        if (empty($attachmentOrder)) {
            $attachment->set(
                'order',
                count($this->getAttachmentByField($type)) + 1
            );
        }

        return $attachment;
    }
}
