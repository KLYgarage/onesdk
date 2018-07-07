<?php

namespace One\Psr7;

use One\Psr7\Message;

class Request implements \Psr\Http\Message\RequestInterface
{
    private $requestTarget;

    private $method;

    private $uri;
    
    public function getRequestTarget()
    {
    }
    public function withRequestTarget($requestTarget)
    {
    }
    public function getMethod()
    {
    }
    public function withMethod($method)
    {
    }
    public function getUri()
    {
    }
    public function withUri(
        \Psr\Http\Message\UriInterface $uri,
        $preserveHost = false
    ) {
    }
}
