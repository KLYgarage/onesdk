<?php

namespace One\Http;

use \Psr\Http\Message\StreamInterface;

use function One\stream_for;

/**
 *
 */
class Message implements \Psr\Http\Message\MessageInterface
{
    protected $headers = [];

    protected $headerNames = [];

    protected $protocol = '1.1';

    protected $stream;

    /**
     * @inheritdoc
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * @inheritdoc
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol == $version) {
            return $this;
        }

        $new = clone $this;

        $new->protocol = $version;

        return $new;
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @inheritdoc
     */
    public function hasHeader($name)
    {
        return isset($this->headerNames[strtolower($name)]);
    }

    /**
     * @inheritdoc
     */
    public function getHeader($name)
    {
        $lower = strtolower($name);

        if (!isset($this->headerNames[$lower])) {
            return array();
        }

        return $this->headers[$this->headerNames[$lower]];
    }

    /**
     * @inheritdoc
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @inheritdoc
     */
    public function withHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $value      = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new        = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header]         = $value;
        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withAddedHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $value      = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new        = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header                = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $header;
            $new->headers[$header]         = $value;
        }
        return $new;
    }

    /**
     * @inheritdoc
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }
        $header = $this->headerNames[$normalized];
        $new    = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);
        return $new;
    }

    /**
     * @inheritdoc
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = stream_for('');
        }
        return $this->stream;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    protected function setHeaders(array $headers)
    {
        $this->headerNames = $this->headers = [];
        foreach ($headers as $header => $value) {
            if (!is_array($value)) {
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
    protected function trimHeaderValues(array $values)
    {
        return array_map(function ($value) {
            return trim($value, " \t");
        }, $values);
    }
}
