<?php
namespace One;

class FactoryUri
{
    public function create($type = '', $string = '')
    {
        if ($type == 'string') {
            if (filter_var($string, FILTER_VALIDATE_URL)) {
                $parts = parse_url($string);
                $scheme = isset($parts['scheme']) ? $parts['scheme'] : '';
                $user = isset($parts['user']) ? $parts['user'] : '';
                $password = isset($parts['pass']) ? $parts['pass'] : '';
                $host = isset($parts['host']) ? $parts['host'] : '';
                $port = isset($parts['port']) ? $parts['port'] : null;
                $path = isset($parts['path']) ? $parts['path'] : '';
                $query = isset($parts['query']) ? $parts['query'] : '';
                $fragment = isset($parts['fragment']) ? $parts['fragment'] : '';
            } else {
                throw new \Exception("Invalid url : $string");
            }
        } elseif ($type == 'server') {
            $scheme = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
            $port = !empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null;
            $path = (string) parse_url('http://www.foobar.com/' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $query = empty($_SERVER['QUERY_STRING']) ? parse_url('http://foobar.com' . $_SERVER['REQUEST_URI'], PHP_URL_QUERY) : $_SERVER['QUERY_STRING'];
            $fragment = '';
            $user = !empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
            $password = !empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';

            if (empty($user) && empty($password) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
                list($user, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            }
        } else {
            throw new Exception('Invalid Type.');
        }

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
}
