<?php

namespace One\Test\Unit;

use One\Http\Request;
use function One\createUriFromString;
use function One\stream_for;
use One\Uri;
use One\Http\FnStream;

/**
 *
 */
class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testRequestUriMayBeString()
    {
        $r = new Request('GET', 'http://localhost/');
        $this->assertEquals('http://localhost/', (string) $r->getUri());
    }

    public function testRequestUriMayBeUri()
    {
        $uri = createUriFromString('http://localhost/');
        
        $r   = new Request('GET', $uri);
        
        $this->assertInstanceOf(Uri::class, $r->getUri());
    }

    public function testCanConstructWithBody()
    {
        $r = new Request('GET', '/', [], 'baz');
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertEquals('baz', (string) $r->getBody());
    }

    public function testNullBody()
    {
        $r = new Request('GET', '/', [], null);
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertSame('', (string) $r->getBody());
    }

    public function testFalseyBody()
    {
        $r = new Request('GET', '/', [], '0');
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertSame('0', (string) $r->getBody());
    }

    public function testConstructorDoesNotReadStreamBody()
    {
        $streamIsRead = false;
        $body = FnStream::decorate(stream_for(''), [
            '__toString' => function () use (&$streamIsRead) {
                $streamIsRead = false;
                return '';
            }
        ]);
        $r = new Request('GET', '/', [], $body);
        $this->assertFalse($streamIsRead);
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
    }

    public function testCapitalizesMethod()
    {
        $r = new Request('get', '/');
        $this->assertEquals('GET', $r->getMethod());
    }

    public function testCapitalizesWithMethod()
    {
        $r = new Request('GET', '/');
        $this->assertEquals('PUT', $r->withMethod('put')->getMethod());
    }

    public function testHostIsAddedFirst()
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam', ['Foo' => 'Bar']);
        $this->assertEquals([
            'Host' => ['foo.com'],
            'Foo'  => ['Bar']
        ], $r->getHeaders());
    }

    public function testCanGetHeaderAsCsv()
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam', [
            'Foo' => ['a', 'b', 'c']
        ]);
        $this->assertEquals('a, b, c', $r->getHeaderLine('Foo'));
        $this->assertEquals('', $r->getHeaderLine('Bar'));
    }
}
