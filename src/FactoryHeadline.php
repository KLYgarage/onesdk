<?php declare(strict_types=1);

namespace One;

use One\Model\Headline;

class FactoryHeadline
{
    public static function create(array $data): \One\Model\Headline
    {
        $data = self::validateArray($data);
        $title = self::validateString(
            (string) self::checkData($data, 'title', '')
        );
        $content = self::validateString(
            (string) self::checkData($data, 'content', '')
        );
        $url = self::validateUrl(
            (string) self::checkData($data, 'url', '')
        );
        $uniqueId = self::validateString(
            (string) self::checkData($data, 'uniqueId', '')
        );
        $active = (bool) self::checkData($data, 'active', false);
        $identifier = self::validateInteger(
            (int) self::checkData($data, 'identifier', null)
        );

        return self::createHeadline(
            $uniqueId,
            $title,
            $content,
            $url,
            $active,
            $identifier
        );
    }

    public static function createHeadline(
        string $uniqueId,
        string $title,
        string $content,
        string $url,
        bool $active,
        int $identifier
    ): Headline {
        return new Headline(
            $uniqueId,
            $title,
            $content,
            $url,
            $active,
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
     * functionality validity for string variables
     */
    private static function validateString(String $var): String
    {
        if (gettype($var) === 'string') {
            return $var;
        }
        throw new \Exception('The variable type must String :' . $var);
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
}

