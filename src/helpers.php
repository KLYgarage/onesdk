<?php

namespace One;

use One\Http\PumpStream;
use One\Http\Stream;
use Psr\Http\Message\StreamInterface;

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
    return FactoryPhoto::create(
        array(
            'url' => $url,
            'ratio' => $ratio,
            'description' => $description,
            'information' => $information,
        )
    );
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
    return FactoryGallery::create(
        array(
            'body' => $body,
            'order' => $order,
            'photo' => $photo,
            'source' => $source,
            'lead' => $lead,
        )
    );
}

/**
 * Create a new stream based on the input type.
 *
 * Options is an associative array that can contain the following keys:
 * - metadata: Array of custom metadata.
 * - size: Size of the stream.
 *
 * @param mixed $resource Entity body data
 * @param array                                                                  $options  Additional options
 *
 * @return StreamInterface
 * @throws \InvalidArgumentException if the $resource arg is not valid.
 */
function stream_for($resource = '', $options = array())
{
    if (is_scalar($resource)) {
        return openStream($resource, $options);
    }
    
    return createStream($resource, $options);
}

/**
 * Helper to create stream based on resource and options
 * @param mixed $resource
 * @param  array $options
 * @return StreamInterface
 * @throws \InvalidArgumentException if the $resource arg is not valid.
 */
function createStream($resource, $options)
{
    switch (gettype($resource)) {
        case 'resource':
            return new Stream($resource, $options);
        case 'object':
            if ($resource instanceof StreamInterface) {
                return $resource;
            } elseif (method_exists($resource, '__toString')) {
                return stream_for((string) $resource, $options);
            }
            return new PumpStream(function () use ($resource) {
                if (!$resource->valid()) {
                    return false;
                }
                $result = $resource->current();
                $resource->next();
                return $result;
            }, $options);
        case 'NULL':
            return new Stream(fopen('php://temp', 'r+'), $options);
    }

    if (is_callable($resource)) {
        return new \One\Http\PumpStream($resource, $options);
    }

    throw new \InvalidArgumentException('Invalid resource type: ' . gettype($resource));
}

/**
 * Copy the contents of a stream into a string until the given number of
 * bytes have been read.
 *
 * @param StreamInterface $stream Stream to read
 * @param int             $maxLen Maximum number of bytes to read. Pass -1
 *                                to read the entire stream.
 * @return string
 * @throws \RuntimeException on error.
 */
function copy_to_string(StreamInterface $stream, $maxLen = -1)
{
    $buffer = '';
    if ($maxLen === -1) {
        while (!$stream->eof()) {
            $buf = $stream->read(1048576);
            // Using a loose equality here to match on '' and false.
            if ($buf == null) {
                break;
            }
            $buffer .= $buf;
        }
        return $buffer;
    }
    
    $len = 0;
    while (!$stream->eof() && $len < $maxLen) {
        $buf = $stream->read($maxLen - $len);
        // Using a loose equality here to match on '' and false.
        if ($buf == null) {
            break;
        }
        $buffer .= $buf;
        $len = strlen($buffer);
    }
    return $buffer;
}
 
/**
 * Open Stream when resource is a scalar type
 * @param mixed $resource
 * @param array $options
 * @return StreamInterface
 */
function openStream($resource, $options)
{
    $stream = fopen('php://temp', 'r+');
    if ($resource !== '' && $stream !== false) {
        fwrite($stream, $resource);
        fseek($stream, 0);
    }
    return new Stream($stream, $options);
}
