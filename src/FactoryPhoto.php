<?php
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
     *
     * @param String $string
     * @return object Uri
     */
    public static function create($data)
    {
        $url = self::validateUrl((string) self::checkData($data, 'url', ''));
        $ratio = self::validateString((string) self::checkData($data, 'ratio', ''));
        $description = self::validateString((string) self::checkData($data, 'description', ''));

        $information = self::validateString((string) self::checkData($data, 'information', ''));

        return self::createPhoto($url, $ratio, $description, $information);
    }

    /**
     * Make Sure Url in string with correct url format
     *
     * @param String $string
     * @return string
     */
    private static function validateUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \Exception("Invalid url : $url");
        }
        return $url;
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
     * functionality validity for string variables
     *
     * @param String $var
     * @return String
     */
    private static function validateString($var)
    {
        if (is_string($var) == true) {
            return $var;
        }
        throw new \Exception("The variable must be a string :" . $var);
    }

    /**
     * Create Photo Object
     *
     * @param String $url
     * @param String $ratio
     * @param String $description
     * @param String $information
     */
    private static function createPhoto($url, $ratio, $description, $information)
    {
        return new Photo(
            $url,
            $ratio,
            $description,
            $information
        );
    }
}
