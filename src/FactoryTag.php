<?php declare(strict_types=1);

namespace One;

use One\Model\Tag;

class FactoryTag
{
    public static function create(array $data): Tag
    {
        $data = self::validateArray($data);
        $name = self::validateString(
            (string) self::checkData($data, 'name', '')
        );
        $trending = (bool) self::checkData($data, 'trending', false);

        return self::createTag(
            $name,
            $trending
        );
    }

    public static function createTag(
        string $name,
        bool $trending
    ): Tag {
        return new Tag(
            $name,
            $trending
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

