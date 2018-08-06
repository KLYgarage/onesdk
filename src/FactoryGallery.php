<?php declare(strict_types=1);

namespace One;

use One\Model\Gallery;

/**
 * FactoryGallery Class
 *
 * @method create
 * @method createGallery
 * @method validateUrl
 * @method validateInteger
 * @method validateString
 * @method checkData
 */
class FactoryGallery
{
    /**
     * function Create attachment Gallery
     */
    public static function create(array $data): \One\Model\Gallery
    {
        $body = self::validateString((string) self::checkData($data, 'body', ''));
        $order = self::validateInteger((int) self::checkData($data, 'order', null));
        $photo = self::validateUrl((string) self::checkData($data, 'photo', ''));
        $source = self::validateUrl((string) self::checkData($data, 'source', ''));
        $lead = self::validateString((string) self::checkData($data, 'lead', ''));
        return self::createGallery($body, $order, $photo, $source, $lead);
    }

    /**
     * Make Sure Url in string with correct url format
     * @throws \Exception
     */
    private static function validateUrl(string $string): string
    {
        if (filter_var($string, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : ${string}");
        }
        return $string;
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param mixed $key
     * @param string $default
     * @return mixed
     */
    private static function checkData(array $data, $key, $default = '')
    {
        return $data[$key] ?? $default;
    }

    /**
     * functionality validity for int variables
     * @param mixed $var
     * @throws \Exception
     */
    private static function validateInteger($var): int
    {
        if (filter_var($var, FILTER_VALIDATE_INT) === false) {
            throw new \Exception('The variable must be a integer :' . $var);
        }
        return $var;
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
     * Create Gallery Object
     */
    private static function createGallery(String $body, int $order, String $photo, String $source, string $lead): \One\Model\Gallery
    {
        return new Gallery(
            $body,
            $order,
            $photo,
            $source,
            $lead
        );
    }
}
