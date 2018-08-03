<?php declare(strict_types=1);

namespace One;

use One\Model\Photo;

/**
 * FactoryPhoto Class
 *
 * @method create
 * @method createPhoto
 * @method validateUrl
 * @method validateString
 * @method checkData
 */
class FactoryPhoto
{
    /**
     * function Create Photo Attachment
     */
    public static function create(array $data): \One\Model\Photo
    {
        $url = self::validateUrl((string) self::checkData($data, 'url', ''));
        $ratio = self::validateString((string) self::checkData($data, 'ratio', ''));
        $description = self::validateString((string) self::checkData($data, 'description', ''));

        $information = self::validateString((string) self::checkData($data, 'information', ''));

        return self::createPhoto($url, $ratio, $description, $information);
    }

    /**
     * Make Sure Url in string with correct url format
     * @throws \Exception
     */
    private static function validateUrl(string $url): string
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : ${url}");
        }
        return $url;
    }

    /**
     * functionality to check whether a variable is set or not
     * @param array<mixed>
     * @param mixed $key
     * @param string $default
     */
    private static function checkData(array $data, $key, $default = ''): string
    {
        return $data[$key] ?? $default;
    }

    /**
     * functionality validity for string variables
     * @param mixed $var
     * @throws \Exception
     */
    private static function validateString($var): string
    {
        if (gettype($var) === 'string') {
            return $var;
        }
        throw new \Exception('The variable type must String :' . $var);
    }

    /**
     * Create Photo Object
     */
    private static function createPhoto(String $url, String $ratio, String $description, String $information): \One\Model\Photo
    {
        return new Photo(
            $url,
            $ratio,
            $description,
            $information
        );
    }
}
