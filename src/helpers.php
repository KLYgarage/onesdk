<?php

namespace One;

use one\Model\Article;

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
/**
 * createArticleFromArray
 *
 * @param array $data
 */
function createArticleFromArray($data)
{
    $title = isset($data['title']) ? $data['title'] : '';
    $body = isset($data['body']) ? $data['body'] : '';
    $source = isset($data['source']) ? $data['source'] : '';
    $uniqueId = isset($data['uniqueId']) ? $data['uniqueId'] : '';
    $typeId = isset($data['typeId']) ? $data['typeId'] : null;
    $categoryId = isset($data['categoryId']) ? $data['categoryId'] : null;
    $reporter = isset($data['reporter']) ? $data['reporter'] : '';
    $lead = isset($data['lead']) ? $data['lead'] : '';
    $reporter = isset($data['reporter']) ? $data['reporter'] : '';
    $tags = isset($data['tags']) ? $data['tags'] : '';
    $publishedAt = isset($data['publishedAt']) ? $data['publishedAt'] : null;
    $identifier = isset($data['identifier']) ? $data['identifier'] : null;
    return new Article(
        $title,
        $body,
        $source,
        $uniqueId,
        $typeId,
        $categoryId,
        $reporter,
        $lead,
        $reporter,
        $tags,
        $publishedAt,
        $identifier
    );
}
