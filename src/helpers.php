<?php

namespace One;

/**
 * createUriFromString
 *
 * @param string $uri
 */
function createUriFromString($uri)
{
    return FactoryUri::create('string', $uri);
}

/**
 * createuriFromServer
 *
 */
function createUriFromServer()
{
    return FactoryUri::create('server');
}

/**
 * createArticleFromArray
 *
 * @param array $data
 */
function createArticleFromArray($data)
{
    return FactoryArticle::create($data);
}
