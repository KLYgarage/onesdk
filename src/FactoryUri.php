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
     * function for Create Uri From Server
     *
     */
    public static function createFromString($uri)
    {
        $data = parse_url($uri);
        $scheme = self::validateString((string) self::checkData($data, 'scheme', ''));
        $user = self::validateString((string) self::checkData($data, 'user', ''));
        $pass = self::validateString((string) self::checkData($data, 'pass', ''));
        $host = self::validateString((string) self::checkData($data, 'host', ''));
        $port = self::checkData($data, 'port', null);
        $path = self::validateString((string) self::checkData($data, 'path', ''));
        $query = self::validateString((string) self::checkData($data, 'query', ''));
        $fragment = self::validateString((string) self::checkData($data, 'fragment', ''));
        return self::createUri($scheme, $host, $port, $user, $pass, $path, $query, $fragment);
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @return array
     */
    private static function checkData($data, $key, $default = '')
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }

    /**
     * function for Create Uri From Server
     *
     */
    public static function createFromServer()
    {
        $scheme = self::validateString((string) self::checkData($_SERVER, 'HTTPS', 'http://'));
        $host = self::validateString((string) self::checkData($_SERVER, 'HTTP_HOST', isset($_SERVER['SERVER_NAME'])));
        $port = self::checkData($_SERVER, 'SERVER_PORT', null);
        $user = self::validateString((string) self::checkData($_SERVER, 'PHP_AUTH_USER', ''));
        $pass = self::validateString((string) self::checkData($_SERVER, 'PHP_AUTH_PW', ''));
        $path = (string) self::checkData($_SERVER, 'REQUEST_URI', '');
        $path = self::validateString(parse_url('http://www.foobar.com/' . $path, PHP_URL_PATH));
        $query = self::validateString((string) self::checkData($_SERVER, 'QUERY_STRING', ''));
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
     * @param string $user
     * @param string $password
     * @param string $path
     * @param string $query
     * @param string $fragment
     * @return Uri Object
     */
    private static function createUri($scheme, $host, $port, $user, $password, $path, $query, $fragment)
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
}
