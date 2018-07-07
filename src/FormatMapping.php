<?php

namespace One;

use One\Model\Article;
use One\Model\Gallery;
use One\Model\Page;
use One\Model\Photo;
use One\Model\Video;

class FormatMapping
{

    /**
     * Possible attributes of JSON data
     * @var array
     */
    private $listAttributes;

    /**
     * JSON field constants
     */
    const JSON_PHOTO_FIELD = "photos";

    const JSON_PAGE_FIELD = "pages";

    const JSON_GALLERY_FIELD = "galleries";

    const JSON_VIDEO_FIELD = "videos";

    /**
     * Construct JSON attributes
     */
    public function __construct()
    {
        $this->listAttributes = array(
            Article::ATTACHMENT_FIELD_PHOTO   => array(
                '_id', '_url', '_ratio', '_description', '_information',
            ),
            Article::ATTACHMENT_FIELD_PAGE    => array(
                '_id', '_title', '_lead', '_body', '_source', '_order', '_cover',
            ),
            Article::ATTACHMENT_FIELD_GALLERY => array(
                '_id', '_lead', '_body', '_source', '_order', '_photo',
            ),
            Article::ATTACHMENT_FIELD_VIDEO   => array(
                '_id', '_lead', '_body', '_source', '_order', '_cover',
            ),
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

                $this->filterString(
                    $this->getValue('title', $dataArticle)
                ),

                $this->filterString(
                    $this->getValue('body', $dataArticle)
                ),

                $this->filterString(
                    $this->getValue('source', $dataArticle)
                ),

                $this->getValue('unique_id', $dataArticle),

                $this->filterInteger(
                    $this->getValue(
                        'type_id',
                        $dataArticle['type']
                    )
                ),

                $this->filterInteger(
                    $this->getValue(
                        'category_id',
                        $dataArticle['category']
                    )
                ),

                $this->getValue('reporter', $dataArticle),

                $this->filterString(
                    $this->getValue('lead', $dataArticle)
                ),

                $this->getValue('tag_name', $dataArticle['tags']),

                $this->filterString(
                    $this->getValue('published_at', $dataArticle)
                ),

                $this->filterInteger(
                    $this->getValue('id', $dataArticle)
                )

            );

            $attachmentConstants = array(
                Article::ATTACHMENT_FIELD_PHOTO,
                Article::ATTACHMENT_FIELD_PAGE,
                Article::ATTACHMENT_FIELD_GALLERY,
                Article::ATTACHMENT_FIELD_VIDEO,
            );

            $attachmentTypes = array(
                self::JSON_PHOTO_FIELD, self::JSON_PAGE_FIELD,
                self::JSON_GALLERY_FIELD, self::JSON_VIDEO_FIELD,
            );

            $attachmentAttributes = $this->lookUp($attachmentConstants);

            $article = $this->generalAttachment(
                $article,
                $attachmentConstants,
                $attachmentTypes,
                $attachmentAttributes,
                $dataArticle
            );

            return $article;
        }

        throw new \Exception("Empty or invalid JSON Response", 1);
    }

    /**
     * Create list attributes based on Article attachment type
     * @param  array $articleConstant
     * @return array
     */
    private function lookUp($articleConstant)
    {
        $copyListAttributes = $this->listAttributes;

        $lists = array_map(function ($singleConst) use ($copyListAttributes) {
            $res = $copyListAttributes[$singleConst];
            return array_map(function ($str) use ($singleConst) {
                return $singleConst . $str;
            }, $res);
        }, $articleConstant);

        return $lists;
    }

    /**
     * Attach attachments to article
     * @param  Article $article
     * @param  array $attachmentConst
     * @param  array $attachmentype
     * @param  array $attributes
     * @param  array $dataArticle
     * @return Article
     */
    private function generalAttachment(
        $article,
        $attachmentConst,
        $attachmentype,
        $attributes,
        $dataArticle
    ) {
        $numOfAttachments = count($attachmentConst);

        for ($i = 0; $i < $numOfAttachments; $i++) {
            $attachments = $this->attachment($attachmentype[$i], $attributes[$i], $dataArticle);

            for ($j = 0; $j < $attachments['numberOfItems']; $j++) {
                $attachment = $attachments['attachments'][$j];

                $article->attach($attachmentConst[$i], $attachment);
            }
        }

        return $article;
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

                $attachment = $this->filterAttachmentObject(
                    $this->makeAttachmentObject(
                        $attachmentType,
                        $attributes,
                        $item
                    )
                );

                array_push($attachments, $attachment);
            }
        }

        $structure = array(
            'numberOfItems' => count($attachments),
            'attachments'   => $attachments,
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
    private function makeAttachmentObject($attachmentType, $attrReferences, $item)
    {
        $attrValues = array();

        foreach ($attrReferences as $attrReference) {
            $attrValues[$attrReference] = $this->getValue($attrReference, $item);
        }

        switch ($attachmentType) {
            case self::JSON_PHOTO_FIELD:
                return $this->createPhoto(
                    $attrValues['photo_url'],
                    $attrValues['photo_ratio'],
                    '',
                    ''
                );
            case self::JSON_PAGE_FIELD:
                return $this->createPage(
                    $attrValues['page_title'],
                    $attrValues['page_body'],
                    $attrValues['page_source'],
                    $attrValues['page_order'],
                    $attrValues['page_cover'],
                    $attrValues['page_lead']
                );
            case self::JSON_GALLERY_FIELD:
                return $this->createGallery(
                    $attrValues['gallery_body'],
                    $attrValues['gallery_order'],
                    $attrValues['gallery_photo'],
                    $attrValues['gallery_source'],
                    $attrValues['gallery_lead']
                );
            case self::JSON_VIDEO_FIELD:
                return $this->createVideo(
                    $attrValues['video_body'],
                    $attrValues['video_source'],
                    $attrValues['video_order'],
                    $attrValues['video_cover'],
                    $attrValues['video_lead']
                );
            default:
                return null;
        }
    }

    /**
     * Create photo object
     * @param  array $values
     * @return Photo
     */
    private function createPhoto($url, $ratio, $desc, $info)
    {
        return new Photo(
            $url,
            $ratio,
            $this->handleString($desc),
            $this->handleString($info)
        );
    }

    /**
     * Create page object
     * @param  array $values
     * @return Page
     */
    private function createPage($title, $body, $source, $order, $cover, $lead)
    {
        return new Page(
            $title,
            $body,
            $source,
            $order,
            $cover,
            $lead
        );
    }

    /**
     * Create Gallery object
     * @param  array $values
     * @return Gallery
     */
    private function createGallery($body, $order, $photo, $source, $lead)
    {
        return new Gallery(
            $body,
            $order,
            $photo,
            $source,
            $lead
        );
    }

    /**
     * Create Video object
     * @param  array $values
     * @return Video
     */
    private function createVideo($body, $source, $order, $cover, $lead)
    {
        return new Video(

            $body,
            $source,
            $order,
            $cover,
            $lead
        );
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
     * Handle string when it will throw exception
     * @param  mixed $str
     * @return string
     */
    private function handleString($str)
    {
        return (is_string($str) &&
            strlen($str) > 0
            && !is_null($str)) ? $str : "";
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
