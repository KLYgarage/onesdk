<?php

namespace one;

use One\Model\Article;

class FormatMapping
{

    /**
     * map a single article to main attributes in Article Class
     * @param  string $singleJsonArticle JSON response
     * @return \One\Model\Article                   An Article Object
     */
    public function mapMainArticle($singleJsonArticle)
    {
        $decoded_article = $singleJsonArticle;

        if (!is_null($this->jsonToArray($singleJsonArticle))) {
            $decoded_article = $this->jsonToArray($singleJsonArticle);
        }

        $data_article = $decoded_article['data'];

        $title = function () use ($data_article) {
            if (isset($data_article['title'])) {
                return $data_article['title'];
            }
            return null;
        };

        $body = function () use ($data_article) {
            if (isset($data_article['body'])) {
                return $data_article['body'];
            }
            return null;
        };

        $source = function () use ($data_article) {
            if (isset($data_article['source'])) {
                return $data_article['source'];
            }
            return null;
        };

        $uniqueId = function () use ($data_article) {
            if (isset($data_article['uniqueId'])) {
                return $data_article['uniqueId'];
            }
            return null;
        };

        $typeId = function () use ($data_article) {
            if (isset($data_article['type']['type_id'])) {
                return $data_article['type']['type_id'];
            }
            return null;
        };

        $categoryId = function () use ($data_article) {
            if (isset($data_article['category']['category_id'])) {
                return $data_article['category']['category_id'];
            }
            return null;
        };

        $reporter = function () use ($data_article) {
            if (isset($data_article['reporter'])) {
                return $data_article['reporter'];
            }
            return null;
        };

        $lead = function () use ($data_article) {
            if (isset($data_article['lead'])) {
                return $data_article['lead'];
            }
            return null;
        };

        $tags = function () use ($data_article) {
            if (isset($data_article['tags']['tag_name'])) {
                return $data_article['tags']['tag_name'];
            }
            return null;
        };

        $publishedAt = function () use ($data_article) {
            if (isset($data_article['published_at'])) {
                return $data_article['published_at'];
            }
            return null;
        };

        $identifier = function () use ($data_article) {
            if (isset($data_article['id'])) {
                return $data_article['id'];
            }
            return null;
        };

        $article = array();

        $article = new Article(

            $title(),

            $body(),

            $source(),

            $uniqueId(),

            $typeId(),

            $categoryId(),

            $reporter(),

            $lead(),

            $tags(),

            $publishedAt(),

            $identifier()

        );

        return $article;
    }

    /**
     * Convert JSON string to associative array
     * @param  string $jsonResponse
     * @return array if it is valid json, null otherwise
     */
    public function jsonToArray($jsonResponse)
    {
        try {
            return json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            return null;
        }
    }
}
