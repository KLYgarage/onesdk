<?php declare(strict_types=1);

namespace one;

use One\Model\Article;
use One\Model\Gallery;
use One\Model\Model;
use One\Model\Page;
use One\Model\Photo;
use One\Model\Video;

class FormatMapping
{
    /**
     * JSON field constants
     */
    public const JSON_PHOTO_FIELD = 'photos';

    public const JSON_PAGE_FIELD = 'pages';

    public const JSON_GALLERY_FIELD = 'galleries';

    public const JSON_VIDEO_FIELD = 'videos';

    /**
     * Possible attributes of JSON data
     * @var array<string[]>
     */
    private $listAttributes;

    /**
     * Construct JSON attributes
     */
    public function __construct()
    {
        $this->listAttributes = [
            Article::ATTACHMENT_FIELD_PHOTO => [
                '_id', '_url', '_ratio', '_description', '_information',
            ],
            Article::ATTACHMENT_FIELD_PAGE => [
                '_id', '_title', '_lead', '_body', '_source', '_order', '_cover',
            ],
            Article::ATTACHMENT_FIELD_GALLERY => [
                '_id', '_lead', '_body', '_source', '_order', '_photo',
            ],
            Article::ATTACHMENT_FIELD_VIDEO => [
                '_id', '_lead', '_body', '_source', '_order', '_cover',
            ],
        ];
    }

    /**
     * map a single article to main attributes in Article Class
     * @param  string $singleJsonArticle JSON response
     * @throws Exception
     */
    public function article(string $singleJsonArticle): Article
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
                (string) $this->filterInteger(
                    $this->getValue('id', $dataArticle)
                )
            );

            $attachmentConstants = [
                Article::ATTACHMENT_FIELD_PHOTO,
                Article::ATTACHMENT_FIELD_PAGE,
                Article::ATTACHMENT_FIELD_GALLERY,
                Article::ATTACHMENT_FIELD_VIDEO,
            ];

            $attachmentTypes = [
                self::JSON_PHOTO_FIELD, self::JSON_PAGE_FIELD,
                self::JSON_GALLERY_FIELD, self::JSON_VIDEO_FIELD,
            ];

            $attachmentAttributes = $this->lookUp($attachmentConstants);

            return $this->generalAttachment(
                $article,
                $attachmentConstants,
                $attachmentTypes,
                $attachmentAttributes,
                $dataArticle
            );
        }

        throw new \Exception('Empty or invalid JSON Response', 1);
    }

    /**
     * Create list attributes based on Article attachment type
     */
    private function lookUp(array $articleConstant): array
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
     */
    private function generalAttachment(
        Article $article,
        array $attachmentConst,
        array $attachmentype,
        array $attributes,
        array $dataArticle
    ): Article {
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
     * @return array attachments
     */
    private function attachment(string $attachmentType, array $attributes, array $dataArticle): array
    {
        $data = $dataArticle[$attachmentType];

        $encoded = json_encode($data);

        $attachments = [];

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

        return [
            'numberOfItems' => count($attachments),
            'attachments' => $attachments,
        ];
    }

    /**
     * Make attachment object
     * @param  string $attachmentType json field of attachment
     * @param  array<string> $attrReferences
     * @param  array<string> $item
     * @return \One\Model\Photo|\One\Model\Gallery|\One\Model\Video|\One\Model\Page|null
     */
    private function makeAttachmentObject(string $attachmentType, array $attrReferences, array $item)
    {
        $attrValues = [];

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
     */
    private function createPhoto(string $url, string $ratio, string $desc, string $info): \One\Model\Photo
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
     */
    private function createPage(string $title, string $body, string $source, int $order, string $cover, string $lead): \One\Model\Page
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
     */
    private function createGallery(string $body, int $order, string $photo, string $source, string $lead): \One\Model\Gallery
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
     */
    private function createVideo(string $body, string $source, int $order, string $cover, string $lead): \One\Model\Video
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
     * @throws \Exception
     */
    private function filterInteger($int): int
    {
        if (is_int($int)) {
            return $int;
        }
        throw new \Exception('Invalid Integer', 1);
    }

    /**
     * Make sure string is not null or empty
     * @param   mixed $str
     * @return string if it is valid or exception
     * @throws \Exception
     */
    private function filterString($str): string
    {
        if (is_string($str) && strlen($str) > 0 && $str !== null) {
            return $str;
        }
        throw new \Exception('String required', 1);
    }

    /**
     * Handle string when it will throw exception
     * @param  mixed $str
     */
    private function handleString($str): string
    {
        return (is_string($str) &&
            strlen($str) > 0
            && $str !== null) ? $str : '';
    }

    /**
     * Make sure variable is type of array
     * @param  mixed $array
     * @throws Exception
     */
    private function filterArray($array): array
    {
        if (is_array($array)) {
            return $array;
        }
        throw new \Exception('Array required', 1);
    }

    /**
     * Make sure attachment object not null
     * @param mixed $object
     * @throws \Exception
     */
    private function filterAttachmentObject($object): Model
    {
        if ($object !== null) {
            return $object;
        }
        throw new \Exception('Attachment object required', 1);
    }

    /**
     * Get value of array based on attributes(keys)
     * @param  mixed $attribute
     * @param  array $data
     * @return mixed
     */
    private function getValue($attribute, $data)
    {
        return $data[$attribute] ?? null;
    }
}
