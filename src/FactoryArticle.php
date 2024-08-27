<?php declare(strict_types=1);

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
 */
class FactoryArticle
{
    /**
     * Create article
     */
    public static function create(array $data): \One\Model\Article
    {
        $data = self::validateArray($data);
        $title = self::validateString(
            (string) self::checkData($data, 'title', '')
        );
        $body = self::validateString(
            (string) self::checkData($data, 'body', '')
        );
        $source = self::validateUrl(
            (string) self::checkData($data, 'source', '')
        );
        $uniqueId = self::validateString(
            (string) self::checkData($data, 'unique_id', '')
        );
        $typeId = self::validateInteger(
            (int) self::checkData($data, 'type_id', '')
        );
        $categoryId = self::validateInteger(
            (int) self::checkData($data, 'category_id', '')
        );
        $reporter = self::validateString(
            (string) self::checkData($data, 'reporter', '')
        );
        $lead = self::validateString(
            (string) self::checkData($data, 'lead', '')
        );
        $tags = self::validateString(
            (string) self::checkData($data, 'tags', '')
        );
        $publishedAt = self::validateString(
            (string) self::checkData($data, 'published_at', '')
        );
        $headline = (bool) self::checkData($data, 'headline', false);
        $headlineLip6 = (bool) self::checkData($data, 'headline_lip6', false);
        $aiType = self::validateInteger(
            (int) self::checkData($data, 'ai_type', 0)
        );
        $identifier = self::validateInteger(
            (int) self::checkData($data, 'identifier', null)
        );
        return self::createArticle(
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
            $headline,
            $headlineLip6,
            $aiType,
            $identifier
        );
    }

    /**
     * Create Article Object
     *
     * @return Article Object
     */
    public static function createArticle(
        string $title,
        string $body,
        string $source,
        string $uniqueId,
        int $typeId,
        int $categoryId,
        string $reporter,
        string $lead,
        string $tags,
        string $publishedAt,
        bool $headline,
        bool $headlineLip6,
        int $aiType,
        int $identifier
    ): Article {
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
            $identifier,
            $headline,
            $headlineLip6,
            $aiType
        );
    }

    /**
     * functionality to check whether a variable is set or not.
     * @param mixed $key
     * @param string $default
     * @return mixed
     */
    private static function checkData(array $data, $key, $default = '')
    {
        return $data[$key] ?? $default;
    }

    /**
     * functionality validity for array variables
     */
    private static function validateArray(array $var): array
    {
        if (gettype($var) === 'array') {
            return $var;
        }
        throw new \Exception('The variable type must Array :');
    }

    /**
     * Make Sure Url in string with correct url format
     */
    private static function validateUrl(string $var): string
    {
        if (filter_var($var, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : ${var}");
        }
        return $var;
    }

    /**
     * functionality validity for int variables
     */
    private static function validateInteger(int $var): int
    {
        if (filter_var($var, FILTER_VALIDATE_INT) === false) {
            throw new \Exception('The variable type must Integer :' . $var);
        }
        return $var;
    }

    /**
     * functionality validity for string variables
     */
    private static function validateString(String $var): String
    {
        if (gettype($var) === 'string') {
            return $var;
        }
        throw new \Exception('The variable type must String :' . $var);
    }
}
