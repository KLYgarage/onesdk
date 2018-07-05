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
    public function __construct()
    {
        $this->photoAttributes = array(
            "photo_id",
            "photo_url",
            "photo_ratio",
            "photo_description",
            "photo_information",
        );

        $this->pageAttributes = array(
            "page_id",
            "page_title",
            "page_lead",
            "page_body",
            "page_source",
            "page_order",
            "page_cover",
        );

        $this->galleryAttributes = array(
            "gallery_id",
            "gallery_lead",
            "gallery_body",
            "gallery_source",
            "gallery_order",
            "gallery_photo",
        );

        $this->videoAttributes = array(
            "video_id",
            "video_lead",
            "video_body",
            "video_source",
            "video_order",
            "video_cover",
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

            $photo_attachments = $this->attachment('photos', $this->photoAttributes, $dataArticle);

            $page_attachments = $this->attachment('pages', $this->pageAttributes, $dataArticle);

            $gallery_attachments = $this->attachment('galleries', $this->galleryAttributes, $dataArticle);

            $video_attachments = $this->attachment('videos', $this->videoAttributes, $dataArticle);

            for ($i = 0; $i < $photo_attachments['numberOfItems']; $i++) {
                $article->attachPhoto($photo_attachments['attachments'][$i]);
            }

            for ($i = 0; $i < $page_attachments['numberOfItems']; $i++) {
                $article->attachPage($page_attachments['attachments'][$i]);
            }

            for ($i = 0; $i < $gallery_attachments['numberOfItems']; $i++) {
                $article->attachGallery($gallery_attachments['attachments'][$i]);
            }

            for ($i = 0; $i < $video_attachments['numberOfItems']; $i++) {
                $article->attachVideo($video_attachments['attachments'][$i]);
            }

            return $article;
        }

        throw new \Exception("Empty or invalid JSON Response", 1);
    }

    /**
     * Attachment(s) of a single article
     * @param  const $attachmentType
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
     * Return photo(s) attachment of an article
     * @param  Associative array $dataArticle
     * @return Array of photos
     */
    private function photo($dataArticle)
    {
        $dataPhotos = $dataArticle['photos'];

        $encoded = json_encode($dataPhotos);

        $photos = array();

        if ($this->filterArray($dataPhotos)) {
            $decodedDataPhotos = json_decode($encoded, true);

            for ($i = 0; $i < count($decodedDataPhotos); $i++) {
                $dataPhoto = $decodedDataPhotos[$i];

                $photo = new Photo(
                    $this->getValue('photo_url', $dataPhoto),
                    $this->photoRatio($this->getValue('photo_ratio', $dataPhoto)),
                    '',
                    ''
                );

                array_push($photos, $photo);
            }
        }

        return $photos;
    }

    /**
     * Return page(s) attachment in an article
     * @param  Associative array $dataArticle
     * @return Array of pages
     */
    private function page($dataArticle)
    {
        $dataPages = $dataArticle['pages'];

        $encoded = json_encode($dataPages);

        $pages = array();

        if ($this->filterArray($dataPages)) {
            $decodedDataPages = json_decode($encoded, true);

            for ($i = 0; $i < count($decodedDataPages); $i++) {
                $dataPage = $decodedDataPages[$i];

                $page = new Page(
                    $this->getValue('page_title', $dataPage),
                    $this->getValue('page_body', $dataPage),
                    $this->getValue('page_source', $dataPage),
                    $this->getValue('page_order', $dataPage),
                    $this->getValue('page_cover', $dataPage),
                    $this->getValue('page_lead', $dataPage)
                );

                array_push($pages, $page);
            }
        }

        return $pages;
    }

    /**
     * Return gallery(ies) attachment
     * @param  Associative arrray $dataArticle
     * @return Array of galleries
     */
    private function gallery($dataArticle)
    {
        $dataGalleries = $dataArticle['galleries'];

        $encoded = json_encode($dataGalleries);

        $galleries = array();

        if ($this->filterArray($dataGalleries)) {
            $decodedDataGalleries = json_decode($encoded, true);

            for ($i = 0; $i < count($decodedDataGalleries); $i++) {
                $dataGallery = $decodedDataGalleries[$i];

                $gallery = new Gallery(
                    $this->getValue('gallery_body', $dataGallery),
                    $this->getValue('gallery_order', $dataGallery),
                    $this->getValue('gallery_photo', $dataGallery),
                    $this->getValue('gallery_source', $dataGallery),
                    $this->getValue('gallery_lead', $dataGallery)
                );

                array_push($galleries, $gallery);
            }
        }

        return $galleries;
    }

    /**
     * Return videos attachment
     * @param  Associative arrray $dataArticle
     * @return Array of videos
     */
    private function video($dataArticle)
    {
        $dataVideos = $dataArticle['videos'];

        $encoded = json_encode($dataVideos);

        $videos = array();

        if ($this->filterArray($dataVideos)) {
            $decodedDataVideos = json_decode($encoded, true);

            for ($i = 0; $i < count($decodedDataVideos); $i++) {
                $dataVideo = $decodedDataVideos[$i];

                $video = new Video(
                    $this->getValue('video_body', $dataVideo),
                    $this->getValue('video_source', $dataVideo),
                    $this->getValue('video_order', $dataVideo),
                    $this->getValue('video_cover', $dataVideo),
                    $this->getValue('video_lead', $dataVideo)
                );

                array_push($videos, $video);
            }
        }

        return $videos;
    }

    /**
     * Map ratio to photo ratio constants
     * @param   string $ratio
     * @return  constant ratio
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
        if (is_int((int) $int)) {
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
     * @param  supported php variables $attribute
     * @param  array $data
     * @return supported php variables
     */
    private function getValue($attribute, $data)
    {
        return isset($data[$attribute]) ? $data[$attribute] : null;
    }
}
