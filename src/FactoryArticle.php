<?php
namespace One;

use One\Model\Article;

/**
 * FactoryArticle Class
 *
 * @method create
 * @method createArticle
 * @method validateArray
 * @method validateUrl
 * @method validateInteger
 * @method validateString
 * @method checkData
 *
 */
class FactoryArticle
{
    public function create(array $data)
    {
        $data = self::checkData($data);
        $title = self::validateString($data['title']);
        $body = self::validateString($data['body']);
        $source = self::validateUrl($data['source']);
        $uniqueId = self::validateString($data['unique_id']);
        $typeId = self::validateInteger($data['type_id']);
        $categoryId = self::validateInteger($data['category_id']);
        $reporter = self::validateString($data['reporter']);
        $lead = self::validateString($data['lead']);
        $tags = self::validateString($data['tags']);
        $publishedAt = $data['published_at'];
        $identifier = $data['identifier'];

        return self::createArticle($title, $body, $source, $uniqueId, $typeId, $categoryId, $reporter, $lead, $tags, $publishedAt, $identifier);
    }

    /**
     * Create Article Object
     *
     * @param String $title
     * @param string $body
     * @param string $source
     * @param int $uniqueId
     * @param int $typeId
     * @param int $categoryId
     * @param string $reporter
     * @param string $lead
     * @param string $tags
     * @param date $publishedAt
     * @param int $identifier
     * @return Article Object
     */
    public function createArticle($title, $body, $source, $uniqueId, $typeId, $categoryId, $reporter, $lead, $tags, $publishedAt, $identifier)
    {
        return new Article(
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
    }

    /**
     * functionality validity for array variables
     *
     * @param int $var
     * @return int
     */
    private function validateArray($var)
    {
        if (is_string($var) == false) {
            throw new \Exception("The variable type must Array :" . $var);
        }
        return $var;
    }

    /**
     * Make Sure Url in string with correct url format
     *
     * @param String $string
     * @return string
     */
    private function validateUrl($var)
    {
        if (filter_var($var, FILTER_VALIDATE_URL) == false) {
            throw new \Exception("Invalid url : $var");
        }
        return $var;
    }
    private function parseUrl(String $string): array
    {
        return parse_url($string);
    }

    /**
     * functionality validity for int variables
     *
     * @param int $var
     * @return int
     */
    private function validateInteger($var)
    {
        if (filter_var($var, FILTER_VALIDATE_INT) == false) {
            throw new \Exception("The variable type must Integer :" . $var);
        }
        return $var;
    }

    /**
     * functionality validity for string variables
     *
     * @param String $var
     * @return String
     */
    private function validateString($var)
    {
        if (is_string($var) == false) {
            throw new \Exception("The variable type must String :" . $var);
        }
        return $var;
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param array $parts
     * @return array
     */
    private function checkData(array $data)
    {
        $data['title'] = isset($data['title']) ? $data['title'] : '';
        $data['body'] = isset($data['body']) ? $data['body'] : '';
        $data['source'] = isset($data['source']) ? $data['source'] : '';
        $data['unique_id'] = isset($data['unique_id']) ? $data['unique_id'] : '';
        $data['type_id'] = isset($data['type_id']) ? $data['type_id'] : null;
        $data['category_id'] = isset($data['category_id']) ? $data['category_id'] : null;
        $data['reporter'] = isset($data['reporter']) ? $data['reporter'] : '';
        $data['lead'] = isset($data['lead']) ? $data['lead'] : '';
        $data['reporter'] = isset($data['reporter']) ? $data['reporter'] : '';
        $data['tags'] = isset($data['tags']) ? $data['tags'] : '';
        $data['published_at'] = isset($data['published_at']) ? $data['published_at'] : null;
        $data['identifier'] = isset($data['identifier']) ? $data['identifier'] : null;
        return $data;
    }
}
