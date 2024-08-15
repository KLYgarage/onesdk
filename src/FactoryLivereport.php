<?php declare(strict_types=1);

namespace One;

use One\Model\Livereport;

class FactoryLivereport 
{
    public static function create(array $data): \One\Model\Livereport
    {
        $data = self::validateArray($data);
        $uniqueId = self::validateString(
            (string) self::checkData($data, 'unique_id', '')
        );
        $title = self::validateString(
            (string) self::checkData($data, 'title', '')
        );
        $shortDesc = self::validateString(
            (string) self::checkData($data, 'short_desc', '')
        );
        $publishDate = self::validateString(
            (string) self::checkData($data, 'publish_date', '')
        );
        $endDate = self::validateString(
            (string) self::checkData($data, 'end_date', '')
        );
        $tag = self::validateString(
            (string) self::checkData($data, 'tag', '')
        );
        $isHeadline = (bool) self::checkData($data, 'is_headline', false);
        $published = (bool) self::checkData($data, 'published', false);
        $livereportChild = self::validateString(
            (string) self::checkData($data, 'livereport_child', '')
        );
        $identifier = self::validateInteger(
            (int) self::checkData($data, 'identifier', null)
        );

        return self::createLivereport(
            $uniqueId,
            $title,
            $shortDesc,
            $publishDate,
            $endDate,
            $tag,
            $isHeadline,
            $published,
            $livereportChild,
            $identifier
        );
    }

    public static function createLivereport(
        string $uniqueId,
        string $title,
        string $shortDesc,
        string $publishDate,
        string $endDate,
        string $tag,
        bool $isHeadline,
        bool $published,
        string $livereportChild,
        int $identifier
    ): Livereport {
        return new Livereport(
            $uniqueId,
            $title,
            $shortDesc,
            $publishDate,
            $endDate,
            $tag,
            $isHeadline,
            $published,
            $livereportChild,
            $identifier
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

