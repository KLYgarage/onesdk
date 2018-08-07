<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\Http\FnStream;
use One\Http\Request;
use One\Uri;
use function One\createUriFromString;
use function One\stream_for;

class RequestTest extends \PHPUnit\Framework\TestCase
{
    public function testRequestUriMayBeString(): void
    {
        $r = new Request('GET', 'http://localhost/');
        $this->assertSame('http://localhost/', (string) $r->getUri());
    }

    public function testRequestUriMayBeUri(): void
    {
        $uri = createUriFromString('http://localhost/');

        $r = new Request('GET', $uri);

        $this->assertInstanceOf(Uri::class, $r->getUri());
    }

    public function testCanConstructWithBody(): void
    {
        $r = new Request('GET', '/', [], 'baz');
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertSame('baz', (string) $r->getBody());
    }

    public function testNullBody(): void
    {
        $r = new Request('GET', '/', [], null);
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertSame('', (string) $r->getBody());
    }

    public function testFalseyBody(): void
    {
        $r = new Request('GET', '/', [], '0');
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
        $this->assertSame('0', (string) $r->getBody());
    }

    public function testConstructorDoesNotReadStreamBody(): void
    {
        $streamIsRead = false;
        $body = FnStream::decorate(stream_for(''), [
            '__toString' => function () use (&$streamIsRead) {
                $streamIsRead = false;
                return '';
            },
        ]);
        $r = new Request('GET', '/', [], $body);
        $this->assertFalse($streamIsRead);
        $this->assertInstanceOf('\Psr\Http\Message\StreamInterface', $r->getBody());
    }

    public function testCapitalizesMethod(): void
    {
        $r = new Request('get', '/');
        $this->assertSame('GET', $r->getMethod());
    }

    public function testCapitalizesWithMethod(): void
    {
        $r = new Request('GET', '/');
        $this->assertSame('PUT', $r->withMethod('put')->getMethod());
    }

    public function testWithUri(): void
    {
        $r1 = new Request('GET', '/');
        $u1 = $r1->getUri();
        $u2 = createUriFromString('http://www.example.com');
        $r2 = $r1->withUri($u2);
        $this->assertNotSame($r1, $r2);
        $this->assertSame($u2, $r2->getUri());
        $this->assertSame($u1, $r1->getUri());
    }

    public function testSameInstanceWhenSameUri(): void
    {
        $r1 = new Request('GET', 'http://foo.com');
        $r2 = $r1->withUri($r1->getUri());
        $this->assertSame($r1, $r2);
    }

    public function testWithRequestTarget(): void
    {
        $r1 = new Request('GET', '/');
        $r2 = $r1->withRequestTarget('*');
        $this->assertSame('*', $r2->getRequestTarget());
        $this->assertSame('/', $r1->getRequestTarget());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequestTargetDoesNotAllowSpaces(): void
    {
        $r1 = new Request('GET', '/');
        $r1->withRequestTarget('/foo bar');
    }

    public function testRequestTargetDefaultsToSlash(): void
    {
        $r1 = new Request('GET', '');
        $this->assertSame('/', $r1->getRequestTarget());
        //  $r2 = new Request('GET', '*');
        //  $this->assertEquals('%2A', $r2->getRequestTarget());
        // $r3 = new Request('GET', 'http://foo.com/bar baz/');
        // $this->assertEquals('/bar%20baz/', $r3->getRequestTarget());
    }

    public function testBuildsRequestTarget(): void
    {
        $r1 = new Request('GET', 'http://foo.com/baz?bar=bam');
        $this->assertSame('/baz?bar=bam', $r1->getRequestTarget());
    }

    public function testBuildsRequestTargetWithFalseyQuery(): void
    {
        $r1 = new Request('GET', 'http://foo.com/baz?0');
        $this->assertSame('/baz?0', $r1->getRequestTarget());
    }

    public function testHostIsAddedFirst(): void
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam', ['Foo' => 'Bar']);
        $this->assertSame([
            'Host' => ['foo.com'],
            'Foo' => ['Bar'],
        ], $r->getHeaders());
    }

    public function testCanGetHeaderAsCsv(): void
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam', [
            'Foo' => ['a', 'b', 'c'],
        ]);
        $this->assertSame('a, b, c', $r->getHeaderLine('Foo'));
        $this->assertSame('', $r->getHeaderLine('Bar'));
    }

    public function testHostIsNotOverwrittenWhenPreservingHost(): void
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam', ['Host' => 'a.com']);
        $this->assertSame(['Host' => ['a.com']], $r->getHeaders());
        $r2 = $r->withUri(createUriFromString('http://www.foo.com/bar'), true);
        $this->assertSame('a.com', $r2->getHeaderLine('Host'));
    }

    public function testOverridesHostWithUri(): void
    {
        $r = new Request('GET', 'http://foo.com/baz?bar=bam');
        $this->assertSame(['Host' => ['foo.com']], $r->getHeaders());
        $r2 = $r->withUri(createUriFromString('http://www.baz.com/bar'));
        $this->assertSame('www.baz.com', $r2->getHeaderLine('Host'));
    }

    public function testAggregatesHeaders(): void
    {
        $r = new Request('GET', '', [
            'ZOO' => 'zoobar',
            'zoo' => ['foobar', 'zoobar'],
        ]);
        $this->assertSame(['ZOO' => ['zoobar', 'foobar', 'zoobar']], $r->getHeaders());
        $this->assertSame('zoobar, foobar, zoobar', $r->getHeaderLine('zoo'));
    }

    public function testAddsPortToHeader(): void
    {
        $r = new Request('GET', 'http://foo.com:8124/bar');
        $this->assertSame('foo.com:8124', $r->getHeaderLine('host'));
    }

    public function testAddsPortToHeaderAndReplacePreviousPort(): void
    {
        $r = new Request('GET', 'http://foo.com:8124/bar');
        $r = $r->withUri(createUriFromString('http://foo.com:8125/bar'));
        $this->assertSame('foo.com:8125', $r->getHeaderLine('host'));
    }
}
