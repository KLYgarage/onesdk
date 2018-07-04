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
        $decodedArticle = $this->jsonToArray($singleJsonArticle) ? $this->jsonToArray($singleJsonArticle) : $singleJsonArticle;

        if (!is_null($this->jsonToArray($singleJsonArticle))) {
            $decodedArticle = $this->jsonToArray($singleJsonArticle);
        }

        $dataArticle = $decodedArticle['data'];

        $title = $this->getValue('title', $dataArticle);

        $body = $this->getValue('body', $dataArticle);

        $source = $this->getValue('source', $dataArticle);

        $uniqueId = $this->getValue('unique_id', $dataArticle);

        $typeId = $this->getValue('type_id', $dataArticle['type']);

        $categoryId = $this->getValue('category_id', $dataArticle['category']);

        $reporter = $this->getValue('reporter', $dataArticle);

        $lead = $this->getValue('lead', $dataArticle);

        $tags = $this->getValue('tag_name', $dataArticle['tags']);

        $publishedAt = $this->getValue('published_at', $dataArticle);

        $identifier = $this->getValue('id', $dataArticle);

        $article = new Article(
            $title,
            $body,
            $source,
            $uniqueId,
            $typeId,
            $categoryId,
            $reporter,
            $lead,
            $tags,
            $publishedAt,
            $identifier
        );

        return $article;
    }

    /**
     * Get value of array based on attributes(keys)
     * @param  supported php variables $attribute
     * @param  array $data
     * @return supported php variables
     */
    private function getValue($attribute, $data)
    {
        if (isset($data[$attribute])) {
            return $data[$attribute];
        }
        return null;
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
