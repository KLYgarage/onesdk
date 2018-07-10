<?php

namespace One;

/**
 * createUriFromString
 *
 * @param string $uri
 * @return \Psr\Http\Message\UriInterface
 */
function createUriFromString($uri)
{
    $parts = parse_url($uri);
    $scheme = isset($parts['scheme']) ? $parts['scheme'] : '';
    $user = isset($parts['user']) ? $parts['user'] : '';
    $pass = isset($parts['pass']) ? $parts['pass'] : '';
    $host = isset($parts['host']) ? $parts['host'] : '';
    $port = isset($parts['port']) ? $parts['port'] : null;
    $path = isset($parts['path']) ? $parts['path'] : '';
    $query = isset($parts['query']) ? $parts['query'] : '';
    $fragment = isset($parts['fragment']) ? $parts['fragment'] : '';
    return new Uri(
        $scheme,
        $host,
        $port,
        $path,
        $query,
        $fragment,
        $user,
        $pass
    );
}

/**
 * createuriFromServer
 *
 * @return \Psr\Http\Message\UriInterface
 */

function createuriFromServer()
{
    $scheme = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
    $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    $port = empty($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null;
    $path = (string) parse_url('http://www.example.com/' . $_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $query = empty($_SERVER['QUERY_STRING']) ? parse_url('http://example.com' . $_SERVER['REQUEST_URI'], PHP_URL_QUERY) : $_SERVER['QUERY_STRING'];
    $fragment = '';
    $user = !empty($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
    $password = !empty($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : '';
    if (empty($user) && empty($password) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
        list($user, $password) = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
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

function stream_for($resource = '', array $options = [])
{
    if (is_scalar($resource)) {
        $stream = fopen('php://temp', 'r+');
        if ($resource !== '') {
            fwrite($stream, $resource);
            fseek($stream, 0);
        }
        return new \One\Http\Stream($stream, $options);
    }

    switch (gettype($resource)) {
        case 'resource':
            return new \One\Http\Stream($resource, $options);
        case 'object':
            if ($resource instanceof StreamInterface) {
                return $resource;
            } elseif ($resource instanceof \Iterator) {
                return new \One\Http\PumpStream(function () use ($resource) {
                    if (!$resource->valid()) {
                        return false;
                    }
                    $result = $resource->current();
                    $resource->next();
                    return $result;
                }, $options);
            } elseif (method_exists($resource, '__toString')) {
                return stream_for((string) $resource, $options);
            }
            break;
        case 'NULL':
            return new \One\Http\Stream(fopen('php://temp', 'r+'), $options);
    }

    if (is_callable($resource)) {
        return new \One\Http\PumpStream($resource, $options);
    }

    throw new \InvalidArgumentException('Invalid resource type: ' . gettype($resource));
}
