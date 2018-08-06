<?php declare(strict_types=1);

namespace One\Http;

use One\Uri;

class Request extends Message implements \Psr\Http\Message\RequestInterface
{
    /**
     * Method
     * @var string
     */
    private $method;

    /**
     * Request Target
     * @var string
     */
    private $requestTarget;

    /**
     * Uri
     * @var \Psr\Http\Message\UriInterface
     */
    private $uri;

    /**
     * Default Constructor
     * @param string|\Psr\Http\Message\UriInterface $uri
     * @param mixed $body
     * @param string $version
     */
    public function __construct(
        string $method,
        $uri = null,
        array $headers = [],
        $body = null,
        $version = '1.1'
    ) {
        if (! ($uri instanceof \Psr\Http\Message\UriInterface)) {
            $uri = \One\createUriFromString($uri);
        }
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->setHeaders($headers);
        $this->protocol = $version;
        if (! $this->hasHeader('Host')) {
            $this->updateHostFromUri();
        }
        if ($body !== '' && $body !== null) {
            $this->stream = \One\stream_for($body);
        }
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        if (! empty($this->requestTarget)) {
            return $this->requestTarget;
        }
        $target = $this->uri->getPath();
        if ($target === '') {
            $target = '/';
        }
        if ($this->uri->getQuery() !== '') {
            $target .= '?' . $this->uri->getQuery();
        }
        return $target;
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new \InvalidArgumentException(
                'Invalid request target provided; cannot contain whitespace'
            );
        }
        $new = clone $this;
        $new->requestTarget = $requestTarget;
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        $new = clone $this;
        $new->method = strtoupper($method);
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @inheritDoc
     */
    public function withUri(\Psr\Http\message\UriInterface $uri, $preserveHost = false)
    {
        if ($uri === $this->uri) {
            return $this;
        }
        $new = clone $this;
        $new->uri = $uri;
        if (! $preserveHost) {
            $new->updateHostFromUri();
        }
        return $new;
    }

    /**
     * Ensure Host is the first header
     * See: http://tools.ietf.org/html/rfc7230#section-5.4
     */
    private function updateHostFromUri(): void
    {
        $host = $this->uri->getHost();
        $port = $this->uri->getPort();
        if ($host === '') {
            return;
        }
        if ($port !== null) {
            $host .= ':' . $port;
        }
        if (isset($this->headerNames['host'])) {
            $header = $this->headerNames['host'];
        } else {
            $header = 'Host';
            $this->headerNames['host'] = 'Host';
        }

        $this->headers = [$header => [$host]] + $this->headers;
    }
}
