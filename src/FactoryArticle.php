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

    /**
     * Create Article from array
     *
     * @param array $data
     * @return object Article
     */
    public static function create($data)
    {
        $data = self::validateArray($data);
        $title = self::validateString(self::checkData($data, 'title', ''));
        $body = self::validateString(self::checkData($data, 'body', ''));
        $source = self::validateUrl(self::checkData($data, 'source', ''));
        $uniqueId = self::validateString(self::checkData($data, 'unique_id', ''));
        $typeId = self::validateInteger(self::checkData($data, 'type_id', ''));
        $categoryId = self::validateInteger(self::checkData($data, 'category_id', ''));
        $reporter = self::validateString(self::checkData($data, 'reporter', ''));
        $lead = self::validateString(self::checkData($data, 'lead', ''));
        $tags = self::validateString(self::checkData($data, 'tags', ''));
        $publishedAt = self::checkData($data, 'published_at', '');
        $identifier = self::checkData($data, 'identifier', '');
        return self::createArticle($title, $body, $source, $uniqueId, $typeId, $categoryId, $reporter, $lead, $tags, $publishedAt, $identifier);
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param array $parts
     * @return array
     */
    private function checkData($data, $key, $default = '')
    {
        return isset($data[$key]) ? $data[$key] : $default;
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
        if (is_array($var) == false) {
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
    private function parseUrl($string)
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
}
