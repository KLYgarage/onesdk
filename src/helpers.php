<?php declare(strict_types=1);

namespace One;

use One\Http\PumpStream;
use One\Http\Stream;
use One\Model\Article;
use One\Model\Gallery;
use One\Model\Photo;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * createUriFromString
 * @covers FactoryUri::create
 */
function createUriFromString(string $uri): UriInterface
{
    return FactoryUri::create($uri);
}

/**
 * createuriFromServer
 * @covers FactoryUri::create
 */
function createUriFromServer(): UriInterface
{
    return FactoryUri::createFromServer();
}

/**
 * createArticleFromArray
 * @covers FactoryArticle::create
 */
function createArticleFromArray(array $data): Article
{
    return FactoryArticle::create($data);
}

/**
 * createAttachmentPhoto
 * @covers FactoryPhoto::create
 */
function createAttachmentPhoto(string $url, string $ratio, string $description, string $information): Photo
{
    return FactoryPhoto::create(
        [
            'url' => $url,
            'ratio' => $ratio,
            'description' => $description,
            'information' => $information,
        ]
    );
}

/**
 * createAttachmentGallery
 * @covers FactoryGalery::create
 */
function createAttachmentGallery(String $body, Int $order, string $photo, String $source, String $lead = ''): Gallery
{
    return FactoryGallery::create(
        [
            'body' => $body,
            'order' => $order,
            'photo' => $photo,
            'source' => $source,
            'lead' => $lead,
        ]
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
 * @param array $options Additional options
 *
 * @throws \InvalidArgumentException if the $resource arg is not valid.
 */
function stream_for($resource = '', $options = []): StreamInterface
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
 * @throws \InvalidArgumentException if the $resource arg is not valid.
 */
function createStream($resource, $options): StreamInterface
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
                if (! $resource->valid()) {
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
        return new PumpStream($resource, $options);
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
 * @throws \RuntimeException on error.
 */
function copy_to_string(StreamInterface $stream, int $maxLen = -1): string
{
    $buffer = '';
    if ($maxLen === -1) {
        while (! $stream->eof()) {
            $buf = $stream->read(1048576);
            // Using a loose equality here to match on '' and false.
            if ($buf === null) {
                break;
            }
            $buffer .= $buf;
        }
        return $buffer;
    }
    $len = 0;
    while (! $stream->eof() && $len < $maxLen) {
        $buf = $stream->read($maxLen - $len);
        // Using a loose equality here to match on '' and false.
        if ($buf === null) {
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
 */
function openStream($resource, $options): StreamInterface
{
    $stream = fopen('php://temp', 'r+');
    if ($resource !== '' && $stream !== false) {
        fwrite($stream, $resource);
        fseek($stream, 0);
    }
    return new Stream($stream, $options);
}
