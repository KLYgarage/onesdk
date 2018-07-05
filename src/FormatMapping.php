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
            Article::ATTACHMENT_FIELD_PHOTO => array(
                '_id', '_url', '_ratio', '_description', '_information',
            ),
            Article::ATTACHMENT_FIELD_PAGE => array(
                '_id', '_title', '_lead', '_body', '_source', '_order', '_cover',
            ),
            Article::ATTACHMENT_FIELD_GALLERY => array(
                '_id', '_lead', '_body', '_source', '_order', '_photo',
            ),
            Article::ATTACHMENT_FIELD_VIDEO => array(
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

            $attachmentConstants = array(
                Article::ATTACHMENT_FIELD_PHOTO, Article::ATTACHMENT_FIELD_PAGE,
                Article::ATTACHMENT_FIELD_GALLERY, Article::ATTACHMENT_FIELD_VIDEO,
            );

            $attachmentTypes = array(
                self::JSON_PHOTO_FIELD, self::JSON_PAGE_FIELD, self::JSON_GALLERY_FIELD,
                self::JSON_VIDEO_FIELD,
            );

            $attachmentAttributes = $this->lookUp($attachmentConstants);

            $this->generalAttachment($article, $attachmentConstants, $attachmentTypes, $attachmentAttributes, $dataArticle);

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
        $num = count($articleConstant);

        $lists = array();

        for ($i = 0; $i < $num; $i++) {
            $res = $this->listAttributes[$articleConstant[$i]];

            $jumlah = count($res);

            for ($j = 0; $j < $jumlah; $j++) {
                $res[$j] = $articleConstant[$i] . $res[$j];
            }

            array_push($lists, $res);
        }

        return $lists;
    }

    /**
     * Attach attachments to article
     * @param  Article &$article
     * @param  array $attachmentConst
     * @param  array $attachmentype
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
        $numOfAttachments = count($attachmentConst);

        for ($i = 0; $i < $numOfAttachments; $i++) {
            $attachments = $this->attachment($attachmentype[$i], $attributes[$i], $dataArticle);

            for ($j = 0; $j < $attachments['numberOfItems']; $j++) {
                $attachment = $attachments['attachments'][$j];

                $article->attach($attachmentConst[$i], $attachment);
            }
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
            $object = $this->createPhoto($photo_url, $photo_ratio, '', '');
        } elseif ($attachmentType == 'pages') {
            $object = $this->createPage($page_title, $page_body, $page_source, $page_order, $page_cover, $page_lead);
        } elseif ($attachmentType == 'galleries') {
            $object = $this->createGallery($gallery_body, $gallery_order, $gallery_photo, $gallery_source, $gallery_lead);
        } elseif ($attachmentType == 'videos') {
            $object = $this->createVideo($video_body, $video_source, $video_order, $video_cover, $video_lead);
        }

        return $this->filterAttachmentObject($object);
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
            $this->photoRatio($ratio),
            $desc,
            $info
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
