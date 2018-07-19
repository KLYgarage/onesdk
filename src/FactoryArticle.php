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
     * @return object Article
     */
    public static function create($data)
    {
        $data = self::validateArray($data);
        $title = self::validateString((string) self::checkData($data, 'title', ''));
        $body = self::validateString((string) self::checkData($data, 'body', ''));
        $source = self::validateUrl((string) self::checkData($data, 'source', ''));
        $uniqueId = self::validateString((string) self::checkData($data, 'unique_id', ''));
        $typeId = self::validateInteger((int) self::checkData($data, 'type_id', ''));
        $categoryId = self::validateInteger((int) self::checkData($data, 'category_id', ''));
        $reporter = self::validateString((string) self::checkData($data, 'reporter', ''));
        $lead = self::validateString((string) self::checkData($data, 'lead', ''));
        $tags = self::validateString((string) self::checkData($data, 'tags', ''));
        $publishedAt = self::validateString((string) self::checkData($data, 'published_at', ''));
        $identifier = self::validateInteger((int) self::checkData($data, 'identifier', null));
        return self::createArticle($title, $body, $source, $uniqueId, $typeId, $categoryId, $reporter, $lead, $tags, $publishedAt, $identifier);
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param array $parts
     * @return array
     */
    private static function checkData($data, $key, $default = '')
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * Create Article Object
     *
     * @param String $title
     * @param string $body
     * @param string $source
     * @param string $uniqueId
     * @param int $typeId
     * @param int $categoryId
     * @param string $reporter
     * @param string $lead
     * @param string $tags
     * @param string $publishedAt
     * @param int $identifier
     * @return Article Object
     */
    public static function createArticle($title, $body, $source, $uniqueId, $typeId, $categoryId, $reporter, $lead, $tags, $publishedAt, $identifier)
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
    private static function validateArray($var)
    {
        if (gettype($var) === "array") {
            return $var;
        }
        throw new \Exception("The variable type must Array :");
    }

    /**
     * Make Sure Url in string with correct url format
     *
     * @param String $string
     * @return string
     */
    private static function validateUrl($var)
    {
        if (filter_var($var, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : $var");
        }
        return $var;
    }

    /**
     * functionality validity for int variables
     *
     * @param int $var
     * @return int
     */
    private static function validateInteger($var)
    {
        if (filter_var($var, FILTER_VALIDATE_INT) === false) {
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
    private static function validateString($var)
    {
        if (gettype($var) === "string") {
            return $var;
        }
        throw new \Exception("The variable type must String :" . $var);
    }
}
