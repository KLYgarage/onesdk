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
     * function Create
     *
     * @param String $string
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
        self::validateUrl($string);
        $data = self::parseUrl($string);
        $data = self::checkData($data);
        $scheme = self::validateString($data['scheme']);
        $host = self::validateString($data['host']);
        $port = $data['port'];
        //$port = self::validateInteger($data['port']);
        $user = self::validateString($data['user']);
        $pass = self::validateString($data['pass']);
        $path = self::validateString($data['path']);
        $query = self::validateString($data['query']);
        $fragment = self::validateString($data['fragment']);
        return self::createUri($scheme, $host, $port, $user, $pass, $path, $query, $fragment);
    }

    /**
     * function for Create Uri From Server
     *
     */
    public function createFromServer()
    {
        $data = self::checkDataServer();
        $scheme = self::validateString($data['scheme']);
        $host = self::validateString($data['host']);
        $port = self::validateInteger($data['port']);
        $user = self::validateString($data['user']);
        $pass = self::validateString($data['pass']);
        $path = self::validateString($data['path']);
        $query = self::validateString($data['query']);
        $fragment = self::validateString($data['fragment']);
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

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param array $parts
     * @return array
     */
    private function checkData($parts)
    {
        $parts['scheme'] = isset($parts['scheme']) ? $parts['scheme'] : '';
        $parts['user'] = isset($parts['user']) ? $parts['user'] : '';
        $parts['pass'] = isset($parts['pass']) ? $parts['pass'] : '';
        $parts['host'] = isset($parts['host']) ? $parts['host'] : '';
        $parts['port'] = isset($parts['port']) ? $parts['port'] : null;
        $parts['path'] = isset($parts['path']) ? $parts['path'] : '';
        $parts['query'] = isset($parts['query']) ? $parts['query'] : '';
        $parts['fragment'] = isset($parts['fragment']) ? $parts['fragment'] : '';
        return $parts;
    }

    /**
     * functionality to check whether a variable is set or not.
     *
     * @param array $parts
     * @return array
     */
    private function checkDataServer()
    {
        $parts = array();
        $parts['scheme'] = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $parts['host'] = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        $parts['port'] = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null;
        $parts['path'] = (string) parse_url('http://www.foobar.com/' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts['query'] = empty($_SERVER['QUERY_STRING']) ? parse_url('http://foobar.com' . $_SERVER['REQUEST_URI'], PHP_URL_QUERY) : $_SERVER['QUERY_STRING'];
        $parts['fragment'] = '';
        $parts['user'] = !empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
        $parts['pass'] = !empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
        return $parts;
    }
}
