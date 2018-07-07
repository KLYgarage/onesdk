<?php

namespace One\Psr7;

/**
 * MessageInterface Implementation
 * Structure of HTTP Message
 * Request line
 * Header(s)
 * Empty line
 * Body
 */
class Message implements \Psr\Http\Message\MessageInterface
{
    /**
     * Header of message
     * @var array
     */
    private $headers = array();

    /**
     * Body content
     * @var \one\Psr7\Stream
     */
    private $body;
    /**
     * Type of message (request or response)
     * @var string
     */
    private $typeOfMessage;

    /**
     * Http Protocol version
     * @var string
     */
    private $protocolVersion;

    /**
     * Header line
     * @var string
     */
    private $headerLine;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
    }

    /**
     * @inheritDoc
     */
    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
    }
}
