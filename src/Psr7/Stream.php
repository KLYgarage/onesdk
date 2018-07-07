<?php

namespace One\Psr7;

/**
 * StreamInterface Implementation class
 */
class Stream implements \Psr\Http\Message\StreamInterface
{
    /**
     * Readble prop
     * @var boolean
     */
    private $readable;

    /**
     * Writeable prop
     * @var boolean
     */
    private $writeable;

    /**
     * Seekable prop
     * @var boolean
     */
    private $seekable;

    /**
     * Content of stream
     * @var mixed
     */
    private $contents;

    /**
     * Size of stream
     * @var int
     */
    private $size;

    /**
     * @inheritDoc
     */
    public function __toString()
    {
    }

    /**
     * @inheritDoc
     */
    public function close()
    {
    }

    /**
     * @inheritDoc
     */
    public function detach()
    {
    }
    /**
     * @inheritDoc
     */
    public function getSize()
    {
    }

    /**
     * @inheritDoc
     */
    public function tell()
    {
    }

    /**
     * @inheritDoc
     */
    public function eof()
    {
    }

    /**
     * @inheritDoc
     */
    public function isSeekable()
    {
    }

    /**
     * @inheritDoc
     */
    public function seek($offset, $whence = SEEK_SET)
    {
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
    }

    /**
     * @inheritDoc
     */
    public function isWritable()
    {
    }

    /**
     * @inheritDoc
     */
    public function write($string)
    {
    }

    /**
     * @inheritDoc
     */
    public function isReadable()
    {
    }

    /**
     * @inheritDoc
     */
    public function read($length)
    {
    }

    /**
     * @inheritDoc
     */
    public function getContents()
    {
    }

    /**
     * @inheritDoc
     */
    public function getMetadata($key = null)
    {
    }
}
