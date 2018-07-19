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

/**
 * createAttachmentPhoto
 * @covers FactoryPhoto::create
 * @param String $url
 * @param String $ratio
 * @param String $description
 * @param String $information
 *
 */
function createAttachmentPhoto($url, $ratio, $description, $information)
{
    $data = array(
        'url' => $url,
        'ratio' => $ratio,
        'description' => $description,
        'information' => $information,
    );
    return FactoryPhoto::create($data);
}

/**
 * createAttachmentGallery
 * @covers FactoryGalery::create
 * @param String $body
 * @param Int $order
 * @param String $source
 * @param String $lead
 *
 */
function createAttachmentGallery($body, $order, $photo, $source, $lead = '')
{
    $data = array(
        'body' => $body,
        'order' => $order,
        'photo' => $photo,
        'source' => $source,
        'lead' => $lead,
    );
    return FactoryGallery::create($data);
}
