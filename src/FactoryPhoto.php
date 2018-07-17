<?php
namespace One;

use One\Model\Photo;

/**
 * FactoryPhoto Class
 *
 * @method create
 * @method createPhoto
 * @method validateUrl
 * @method validateInteger
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
        $url = self::validateUrl(self::checkData($data, 'url', ''));
        $ratio = self::checkData($data, 'ratio', '');
        $description = self::validateString(self::checkData($data, 'description', ''));
        $information = self::validateString(self::checkData($data, 'information', ''));

        return self::createPhoto($url, $ratio, $description, $information);
    }

    /**
     * Make Sure Url in string with correct url format
     *
     * @param String $string
     * @return string
     */
    private function validateUrl($string)
    {
        if (filter_var($string, FILTER_VALIDATE_URL) == false) {
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
    private function checkData($data, $key, $default = '')
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * functionality validity for int variables
     *
     * @param int $var
     * @return int
     */
    private function validateInteger($var)
    {
        if (filter_var($var, FILTER_VALIDATE_INT) == false) {
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
    private function validateString($var)
    {
        if (is_string($var) == false) {
            throw new \Exception("The variable must be a string :" . $var);
        }
        return $var;
    }

    /**
     * Create Photo Object
     *
     * @param String $url
     * @param String $ratio
     * @param String $description
     * @return Uri Photo
     */
    public function createPhoto($url, $ratio, $description, $information)
    {
        return new Photo(
            $url,
            $ratio,
            $description,
            $information
        );
    }
}
