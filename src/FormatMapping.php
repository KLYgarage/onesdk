<?php

namespace one;

use One\Model\Article;

class FormatMapping
{

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

            return $article;
        }

        throw new \Exception("Empty or invalid JSON Response", 1);
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
