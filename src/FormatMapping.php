<?php

namespace one;

use One\Model\Article;
use one\Model\Gallery;
use One\Model\Page;
use one\Model\Photo;
use One\Model\Video;

class FormatMapping
{

    /**
     * Json Attributes of Photo
     * @var array
     */
    private $photoAttributes;

    /**
     * Json Attributes of Page
     * @var array
     */
    private $pageAttributes;

    /**
     * Json Attributes of gallery
     * @var array
     */
    private $galleryAttributes;

    /**
     * Json Attributes of video
     * @var array
     */
    private $videoAttributes;

    /**
     * Construct Json attribute variables
     */

    const JSON_PHOTO_FIELD = "photos";

    const JSON_PAGE_FIELD = "pages";

    const JSON_GALLERY_FIELD = "galleries";

    const JSON_VIDEO_FIELD = "videos";

    const JSON_ATTRIBUTES = array(
        'ID' => "_id",
        "URL" => '_url',
        "RATIO" => '_ratio',
        "DESCRIPTION" => '_description',
        "INFORMATION" => '_information',
        "TITLE" => '_title',
        "LEAD" => '_lead',
        "BODY" => '_body',
        "SOURCE" => '_source',
        "ORDER" => '_order',
        "COVER" => '_cover',
        "PHOTO" => '_photo',

    );

    /**
     * Construct JSON attributes
     */
    public function __construct()
    {
        $this->photoAttributes = array(
            Article::ATTACHMENT_FIELD_PHOTO . self::JSON_ATTRIBUTES['ID'],
            Article::ATTACHMENT_FIELD_PHOTO . self::JSON_ATTRIBUTES['URL'],
            Article::ATTACHMENT_FIELD_PHOTO . self::JSON_ATTRIBUTES['RATIO'],
            Article::ATTACHMENT_FIELD_PHOTO . self::JSON_ATTRIBUTES['DESCRIPTION'],
            Article::ATTACHMENT_FIELD_PHOTO . self::JSON_ATTRIBUTES['INFORMATION'],

        );

        $this->pageAttributes = array(
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['ID'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['TITLE'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['LEAD'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['BODY'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['SOURCE'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['ORDER'],
            Article::ATTACHMENT_FIELD_PAGE . self::JSON_ATTRIBUTES['COVER'],
        );

        $this->galleryAttributes = array(
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['ID'],
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['LEAD'],
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['BODY'],
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['SOURCE'],
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['ORDER'],
            Article::ATTACHMENT_FIELD_GALLERY . self::JSON_ATTRIBUTES['PHOTO'],
        );

        $this->videoAttributes = array(
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['ID'],
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['LEAD'],
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['BODY'],
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['SOURCE'],
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['ORDER'],
            Article::ATTACHMENT_FIELD_VIDEO . self::JSON_ATTRIBUTES['COVER'],
        );
    }
    /**
     * map a single article to main attributes in Article Class
     * @param  string $singleJsonArticle JSON response
     * @return Article
     * @throws Exception
     */
    public function article($singleJsonArticle)
    {
        if (json_decode($singleJsonArticle, true)) {
            $dataArticle = json_decode($singleJsonArticle, true)['data'];

            $article = new Article(

                $title = $this->filterString($this->getValue('title', $dataArticle)),

                $body = $this->filterString($this->getValue('body', $dataArticle)),

                $source = $this->filterString($this->getValue('source', $dataArticle)),

                $uniqueId = $this->getValue('unique_id', $dataArticle),

                $typeId = $this->filterInteger($this->getValue('type_id', $dataArticle['type'])),

                $categoryId = $this->filterInteger($this->getValue(
                    'category_id',
                    $dataArticle['category']
                )),

                $reporter = $this->getValue('reporter', $dataArticle),

                $lead = $this->filterString($this->getValue('lead', $dataArticle)),

                $tags = $this->getValue('tag_name', $dataArticle['tags']),

                $publishedAt = $this->filterString($this->getValue('published_at', $dataArticle)),

                $identifier = $this->filterInteger($this->getValue('id', $dataArticle))

            );

            $this->generalAttachment(
                $article,
                Article::ATTACHMENT_FIELD_PHOTO,
                self::JSON_PHOTO_FIELD,
                $this->photoAttributes,
                $dataArticle
            );

            $this->generalAttachment(
                $article,
                Article::ATTACHMENT_FIELD_PAGE,
                self::JSON_PAGE_FIELD,
                $this->pageAttributes,
                $dataArticle
            );

            $this->generalAttachment($article, Article::ATTACHMENT_FIELD_GALLERY, self::JSON_GALLERY_FIELD, $this->galleryAttributes, $dataArticle);

            $this->generalAttachment(
                $article,
                Article::ATTACHMENT_FIELD_VIDEO,
                self::JSON_VIDEO_FIELD,
                $this->videoAttributes,
                $dataArticle
            );

            return $article;
        }

        throw new \Exception("Empty or invalid JSON Response", 1);
    }

    /**
     * Attach attachments to article
     * @param  Article &$article
     * @param  string $attachmentConst
     * @param  string $attachmentype
     * @param  array $attributes
     * @param  array $dataArticle
     * @return void
     */
    private function generalAttachment(
        &$article,
        $attachmentConst,
        $attachmentype,
        $attributes,
        $dataArticle
    ) {
        $attachments = $this->attachment($attachmentype, $attributes, $dataArticle);

        for ($i = 0; $i < $attachments['numberOfItems']; $i++) {
            $attachment = $attachments['attachments'][$i];

            $article->attach($attachmentConst, $attachment);
        }
    }

    /**
     * Attachment(s) of a single article
     * @param  string $attachmentType
     * @param  array $attributes
     * @param  assoc array $dataArticle
     * @return array attachments
     */
    private function attachment($attachmentType, $attributes, $dataArticle)
    {
        $data = $dataArticle[$attachmentType];

        $encoded = json_encode($data);

        $attachments = array();

        if ($this->filterArray($data)) {
            $decodedData = json_decode($encoded, true);

            $numberOfItems = count($decodedData);

            for ($i = 0; $i < $numberOfItems; $i++) {
                $item = $decodedData[$i];

                $attachment = $this->makeAttachmentObject($attachmentType, $attributes, $item);

                array_push($attachments, $attachment);
            }
        }

        $structure = array(
            'numberOfItems' => count($attachments),
            'attachments' => $attachments,
        );

        return $structure;
    }

    /**
     * Make attachment object
     * @param  string $attachmentType json field of attachment
     * @param  array  $attributes     attributes of attachment
     * @param  assoc array $item
     * @return object
     */
    private function makeAttachmentObject($attachmentType, $attributes, $item)
    {
        $numOfAttributes = count($attributes);

        $values = array();

        $object = null;

        for ($i = 0; $i < $numOfAttributes; $i++) {
            $val = $this->getValue($attributes[$i], $item);
            $values[$attributes[$i]] = $val;
        }

        extract($values);

        if ($attachmentType == 'photos') {
            $object = new Photo(
                $photo_url,
                $this->photoRatio($photo_ratio),
                '',
                ''
            );
        } elseif ($attachmentType == 'pages') {
            $object = new Page(
                $page_title,
                $page_body,
                $page_source,
                $page_order,
                $page_cover,
                $page_lead
            );
        } elseif ($attachmentType == 'galleries') {
            $object = new Gallery(
                $gallery_body,
                $gallery_order,
                $gallery_photo,
                $gallery_source,
                $gallery_lead
            );
        } elseif ($attachmentType == 'videos') {
            $object = new Video(

                $video_body,
                $video_source,
                $video_order,
                $video_cover,
                $video_lead
            );
        }

        return $this->filterAttachmentObject($object);
    }

    /**
     * Map ratio to photo ratio constants
     * @param   string $ratio
     * @return   string
     * @throws Exception
     */
    private function photoRatio($ratio)
    {
        if ($ratio == "1:1") {
            return Photo::RATIO_SQUARE;
        } elseif ($ratio == "2:1") {
            return Photo::RATIO_RECTANGLE;
        } elseif ($ratio == "3:2") {
            return Photo::RATIO_HEADLINE;
        } elseif ($ratio == "9:16") {
            return Photo::RATIO_VERTICAL;
        } elseif ($ratio == 'cover') {
            return Photo::RATIO_COVER;
        } else {
            throw new \Exception("Unknown ratio", 1);
        }
    }

    /**
     * Make sure value is integer
     * @param  mixed $int
     * @return int
     * @throws Exception
     */
    private function filterInteger($int)
    {
        if (is_int($int)) {
            return $int;
        }
        throw new \Exception("Invalid Integer", 1);
    }

    /**
     * Make sure string is not null or empty
     * @param   mixed $str
     * @return string if it is valid or exception
     * @throws Exception
     */
    private function filterString($str)
    {
        if (is_string($str) && strlen($str) > 0 && !is_null($str)) {
            return $str;
        }
        throw new \Exception("String required", 1);
    }

    /**
     * Make sure variable is type of array
     * @param  mixed $array
     * @return array
     * @throws Exception
     */
    private function filterArray($array)
    {
        if (is_array($array)) {
            return $array;
        }
        throw new \Exception("Array required", 1);
    }

    /**
     * Make sure attachment object not null
     * @param  mixed $object
     * @return object
     * @throws Exception
     */
    private function filterAttachmentObject($object)
    {
        if (!is_null($object)) {
            return $object;
        }
        throw new \Exception("Attachment object required", 1);
    }

    /**
     * Get value of array based on attributes(keys)
     * @param  mixed $attribute
     * @param  array $data
     * @return mixed
     */
    private function getValue($attribute, $data)
    {
        return isset($data[$attribute]) ? $data[$attribute] : null;
    }
}
