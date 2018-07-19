<?php
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
     *
     * @param Array $data
     * @return object Gallery
     */
    public static function create($data)
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
     *
     * @param String $string
     * @return string
     */
    private static function validateUrl($string)
    {
        if (filter_var($string, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : $string");
        }
        return $string;
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
     * functionality validity for int variables
     *
     * @param int $var
     * @return int
     */
    private static function validateInteger($var)
    {
        if (filter_var($var, FILTER_VALIDATE_INT) === false) {
            throw new \Exception("The variable must be a integer :" . $var);
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

    /**
     * Create Gallery Object
     *
     * @param String $body
     * @param int $order
     * @param String $photo
     * @param String $source
     * @param string $lead
     */
    private static function createGallery($body, $order, $photo, $source, $lead)
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
