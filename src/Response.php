<?php

namespace One;

/**
 * Psr\ResponseInterface implementation
 */
class Response implements \Psr\Http\Message\ResponseInterface
{

    /**
     * Psr7 Response
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $resp;

    /**
     * Response constructor
     * @param null|\Psr\Http\Message\ResponseInterface $responseInterface
     */
    public function __construct($responseInterface = null)
    {
        $this->resp = $responseInterface;
    }

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
    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = '')
    {
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase()
    {
    }
}
