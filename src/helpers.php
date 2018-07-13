<?php

namespace One;

/**
 * createUriFromString
 * @covers FactoryUri::create
 * @param string $uri
 *
 */
function createUriFromString($uri)
{
    return FactoryUri::create($uri);
}

/**
 * createuriFromServer
 * @covers FactoryUri::create
 *
 */
function createUriFromServer()
{
    return FactoryUri::create();
}

/**
 * createArticleFromArray
 * @covers FactoryArticle::create
 * @param array $data
 *
 */
function createArticleFromArray($data)
{
    return FactoryArticle::create($data);
}
