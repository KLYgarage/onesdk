<?php declare(strict_types=1);

namespace One;

use One\Model\Livestreaming;

class FactoryLivestreaming
{
    public static function create(array $data): \One\Model\Livestreaming
    {
        $data = self::validateArray($data);
        $title = self::validateString(
            (string) self::checkData($data, 'title', '')
        );
        $desc = self::validateString(
            (string) self::checkData($data, 'desc', '')
        );
        $urlLive = self::validateString(
            (string) self::checkData($data, 'url_live', '')
        );
        $urlThumbnail = self::validateString(
            (string) self::checkData($data, 'url_thumbnail', '')
        );
        $publishedAt = self::validateString(
            (string) self::checkData($data, 'published_at', '')
        );
        $endAt = self::validateString(
            (string) self::checkData($data, 'end_at', '')
        );
        $publishStatus = (bool) self::checkData($data, 'publish_status', false);
        $uniqueId = self::validateString(
            (string) self::checkData($data, 'unique_id', '')
        );
        $identifier = self::validateInteger(
            (int) self::checkData($data, 'identifier', null)
        );

        return self::createLivestreaming(
            $uniqueId,
            $title,
            $desc,
            $urlLive,
            $urlThumbnail,
            $publishedAt,
            $endAt,
            $publishStatus,
            $identifier
        );
    }

    public static function createLivestreaming(
        string $uniqueId,
        string $title,
        string $desc,
        string $urlLive,
        string $urlThumbnail,
        string $publishedAt,
        string $endAt,
        bool $publishStatus,
        int $identifier
    ): Livestreaming {
        return new Livestreaming(
            $uniqueId,
            $title,
            $desc,
            $urlLive,
            $urlThumbnail,
            $publishedAt,
            $endAt,
            $publishStatus,
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

