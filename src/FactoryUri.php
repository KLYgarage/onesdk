<?php
namespace One;

/**
 * FactoryUri Class
 *
 * @method create
 * @method createArticle
 * @method validateArray
 * @method validateUrl
 * @method validateInteger
 * @method validateString
 * @method checkData
 *
 */
class FactoryUri
{

    /**
     * function Create Uri
     *
     * @param String $string
     * @return object Uri
     */
    public static function create($string = null)
    {
        if (!empty($string)) {
            return self::createFromString($string);
        }
        return self::createFromServer();
    }

    /**
     * function for Create Uri From String
     *
     * @param String $string
     */
    public function createFromString($string)
    {
        $data = self::parseUrl(self::validateUrl($string));
        $scheme = self::validateString(self::checkData($data, 'scheme', ''));
        $host = self::validateString(self::checkData($data, 'host', ''));
        $port = 85;
        //$port = self::validateInteger($data['port']);
        $user = self::validateString(self::checkData($data, 'user', ''));
        $pass = self::validateString(self::checkData($data, 'pass', ''));
        $path = self::validateString(self::checkData($data, 'path', ''));
        $query = self::validateString(self::checkData($data, 'query', ''));
        $fragment = self::validateString(self::checkData($data, 'fragment', ''));
        return self::createUri($scheme, $host, $port, $user, $pass, $path, $query, $fragment);
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
     * function for Create Uri From Server
     *
     */
    public function createFromServer()
    {
        $scheme = self::validateString(self::checkData($_SERVER, 'HTTPS', 'http://'));
        $host = self::validateString(self::checkData($_SERVER, 'HTTP_HOST', isset($_SERVER['SERVER_NAME'])));
        $port = self::validateInteger(self::checkData($_SERVER, 'SERVER_PORT', null));
        $user = self::validateString(self::checkData($_SERVER, 'PHP_AUTH_USER', ''));
        $pass = self::validateString(self::checkData($_SERVER, 'PHP_AUTH_PW', ''));
        $path = self::validateString((string) parse_url('http://www.foobar.com/' . self::checkData($_SERVER, 'REQUEST_URI', ''), PHP_URL_PATH));
        $query = self::validateString(self::checkData($_SERVER, 'QUERY_STRING', ''));
        $fragment = '';
        if (empty($user) && empty($pass) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
            list($user, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }
        return self::createUri($scheme, $host, $port, $user, $pass, $path, $query, $fragment);
    }

    /**
     * Create Uri Object
     *
     * @param String $string
     * @param string $scheme
     * @param string $host
     * @param int $port
     * @param string $user
     * @param string $password
     * @param string $path
     * @param string $query
     * @param string $fragment
     * @return Uri Object
     */
    public function createUri($scheme, $host, $port, $user, $password, $path, $query, $fragment)
    {
        return new Uri(
            $scheme,
            $host,
            $port,
            $path,
            $query,
            $fragment,
            $user,
            $password
        );
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
     * Make Sure Url in string with correct url format
     *
     * @param String
     * @return array
     */
    private function parseUrl($string)
    {
        return parse_url($string);
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
}
