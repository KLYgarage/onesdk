<?php declare(strict_types=1);

namespace One\Http;

class Message implements \Psr\Http\Message\MessageInterface
{
    /**
     * Headers
     * @var array<string[]>
     */
    protected $headers = [];

    /**
     * Header names
     * @var array<string[]>
     */
    protected $headerNames = [];

    /**
     * Protocol version
     * @var string
     */
    protected $protocol = '1.1';

    /**
     * Stream
     * @var \Psr\Http\Message\StreamInterface
     */
    protected $stream;

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }

        $new = clone $this;

        $new->protocol = $version;

        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        $lower = strtolower($name);

        if (! isset($this->headerNames[$lower])) {
            return [];
        }

        return $this->headers[$this->headerNames[$lower]];
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @inheritDoc
     */
    public function withHeader($header, $value)
    {
        if (! is_array($value)) {
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header] = $value;
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($header, $value)
    {
        if (! is_array($value)) {
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $header;
            $new->headers[$header] = $value;
        }
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);
        if (! isset($this->headerNames[$normalized])) {
            return $this;
        }
        $header = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);
        return $new;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        if (! $this->stream) {
            $this->stream = \One\stream_for('');
        }
        return $this->stream;
    }

    /**
     * @inheritDoc
     */
    public function withBody(\Psr\Http\Message\StreamInterface $body)
    {
        if ($body === $this->stream) {
            return $this;
        }
        $new = clone $this;
        $new->stream = $body;
        return $new;
    }

    /**
     * Set header
     */
    protected function setHeaders(array $headers): void
    {
        $this->headerNames = $this->headers = [];
        foreach ($headers as $header => $value) {
            if (! is_array($value)) {
                $value = [$value];
            }
            $value = $this->trimHeaderValues($value);
            $normalized = strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }

    /**
     * Trims whitespace from the header values.
     *
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     *
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param string[] $values Header values
     *
     * @return string[] Trimmed header values
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    protected function trimHeaderValues(array $values): array
    {
        return array_map(function ($value) {
            return trim($value, " \t");
        }, $values);
    }
}
