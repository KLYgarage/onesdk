<?php declare(strict_types=1);

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
 */
class FactoryUri
{
    /**
     * function Create Uri
     */
    public static function create(string $string = ''): \One\Uri
    {
        if (empty($string)) {
            $string = '/';
        }
        return self::createFromString($string);
    }

    /**
     * function for Create Uri From Server
     */
    public static function createFromString(string $uri): \One\Uri
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
     * function for Create Uri From Server
     */
    public static function createFromServer(): \One\Uri
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
        if (empty($user) && empty($pass) && ! empty($_SERVER['HTTP_AUTHORIZATION'])) {
            [$user, $password] = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6), true));
        }
        return self::createUri($scheme, $host, $port, $user, $pass, $path, $query, $fragment);
    }

    /**
     * functionality to check whether a variable is set or not.
     * @param  mixed $key
     * @param  string $default
     * @return mixed
     */
    private static function checkData(array $data, $key, $default = '')
    {
        return $data[$key] ?? $default;
    }

    /**
     * Create Uri Object
     */
    private static function createUri(string $scheme, string $host, ?int $port, string $user, string $password, string $path, string $query, string $fragment): \One\Uri
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
}
