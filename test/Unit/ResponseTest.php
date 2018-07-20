<?php

namespace One\Test\Unit;

use One\Http\Response;
use One\Http\FnStream;
use function One\stream_for;

class ResponseTest extends \PHPUnit\Framework\TestCase
{
    public function testDefaultConstructor()
    {
        $response = new Response();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame('OK', $response->getReasonPhrase());
        $this->assertSame([], $response->getHeaders());
        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $response->getBody());
        $this->assertSame('', (string) $response->getBody());
    }

    public function testCanConstructWithStatusCode()
    {
        $response = new Response(404);
        
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
    }

    public function testConstructorDoesNotReadStreamBody()
    {
        $streamIsRead = false;
        $body = FnStream::decorate(stream_for(''), [
            '__toString' => function () use (&$streamIsRead) {
                $streamIsRead = true;
                return '';
            }
        ]);

        $response = new Response(200, [], $body);
        
        $this->assertFalse($streamIsRead);
        $this->assertSame($body, $response->getBody());
    }

    public function testStatusCanBeNumericString()
    {
        $response = new Response('404');
        $response2 = $response->withStatus('201');
        
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
        $this->assertSame(201, $response2->getStatusCode());
        $this->assertSame('Created', $response2->getReasonPhrase());
    }

    public function testCanConstructWithHeaders()
    {
        $response = new Response('200', ['Foo'=>'Bar']);
        
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Bar', $response->getHeaderLine('Foo'));
        $this->assertSame(['Bar'], $response->getHeader('Foo'));
    }

    public function testCanConstructWithHeadersAsArray()
    {
        $response = new Response('200', ['Foo'=>['Bar'=>'Baz']]);
        
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['Foo'=>['Bar'=>'Baz']], $response->getHeaders());
        $this->assertSame('Baz', $response->getHeaderLine('Foo'));
        $this->assertSame(['Bar'=>'Baz'], $response->getHeader('Foo'));
    }

    public function canConstructWithBody()
    {
        $response = new Response(200, [], 'baz');
        $body = $response->getBody();
        
        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $body);
        $this->assertSame('baz', (string) $body);
    }

    public function testNullBody()
    {
        $response = new Response(200, [], null);

        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $response->getBody());
        $this->assertSame('', (string) $response->getBody());
    }

    public function testCanConstructWithReason()
    {
        $response = new Response(200, [], null, '1.1', 'bar');
        
        $this->assertSame('bar', $response->getReasonPhrase());
    }

    public function testCanConstructWithProtocol()
    {
        $response = new Response(200, [], null, '1.22', 'bar');
        
        $this->assertSame('1.22', $response->getProtocolVersion());
    }

    public function testWithStatusCodeAndNoReason()
    {
        $response = new Response();
        $newResponse = $response->withStatus(201);

        $this->assertSame(201, $newResponse->getStatusCode());
        $this->assertSame('Created', $newResponse->getReasonPhrase());
    }

    public function testWithStatusCodeAndReason()
    {
        $response = new Response();
        $newResponse = $response->withStatus(201, 'Success');

        $this->assertSame(201, $newResponse->getStatusCode());
        $this->assertSame('Success', $newResponse->getReasonPhrase());
    }

    public function testWithProtocolVersion()
    {
        $response = new Response();
        $newResponse = $response->withProtocolVersion('1.23');

        $this->assertSame('1.23', $newResponse->getProtocolVersion());
    }

    public function testSameInstanceWhenSameProtocol()
    {
        $response = new Response();
        
        $this->assertSame($response, $response->withProtocolVersion('1.1'));
    }

    public function testWithBody()
    {
        $body = stream_for('0');
        $response = (new Response())->withBody($body);
        
        $this->assertInstanceOf('Psr\Http\Message\StreamInterface', $response->getBody());
        $this->assertSame('0', (string) $response->getBody());
    }

    public function testWithHeaderAsArray()
    {
        $response = new Response(200, ['Foo'=>'Bar']);
        $newResponse = $response->withHeader('Baz', ['Bam', 'Bar']);

        $this->assertSame(['Foo'=>['Bar']], $response->getHeaders());
        $this->assertSame(['Foo'=>['Bar'],'Baz'=>['Bam', 'Bar']], $newResponse->getHeaders());
        $this->assertSame('Bam, Bar', $newResponse->getHeaderLine('Baz'));
        $this->assertSame(['Bam', 'Bar'], $newResponse->getHeader('Baz'));
    }

    public function testWithHeaderReplaced()
    {
        $response = new Response(200, ['Foo' => 'Bar']);
        $newResponse = $response->withHeader('FoO', 'Bzz');

        $this->assertSame(['Foo' => ['Bar']], $response->getHeaders());
        $this->assertSame(['FoO' => ['Bzz']], $newResponse->getHeaders());
        $this->assertSame('Bzz', $newResponse->getHeaderLine('Foo'));
        $this->assertSame(['Bzz'], $newResponse->getHeader('Foo'));
    }

    public function testWithAddedHeader()
    {
        $response = new Response(200, ['Foo'=>'Bar']);
        $newResponse = $response->withAddedHeader('foO', 'Baz');

        $this->assertSame(['Foo' => ['Bar']], $response->getHeaders());
        $this->assertSame(['Foo' => ['Bar', 'Baz']], $newResponse->getHeaders());
        $this->assertSame('Bar, Baz', $newResponse->getHeaderLine('FoO'));
        $this->assertSame(['Bar', 'Baz'], $newResponse->getHeader('FOo'));
    }

    public function testWithAddedHeaderAsArray()
    {
        $response = new Response(200, ['Foo'=>'Bar']);
        $newResponse = $response->withAddedHeader('fOO', ['Baz', 'Bam']);

        $this->assertSame(['Foo'=>['Bar']], $response->getHeaders());
        $this->assertSame(['Foo'=>['Bar', 'Baz', 'Bam']], $newResponse->getHeaders());
        $this->assertSame('Bar, Baz, Bam', $newResponse->getHeaderLine('Foo'));
        $this->assertSame(['Bar', 'Baz', 'Bam'], $newResponse->getHeader('Foo'));
    }

    public function testWithAddedHeaderThatDoesntExists()
    {
        $response = new Response(200, ['Foo' => 'Bar']);
        $newResponse = $response->withAddedHeader('new', 'Baz');

        $this->assertSame(['Foo'=>['Bar'], 'new'=>['Baz']], $newResponse->getHeaders());
        $this->assertSame('Baz', $newResponse->getHeaderLine('new'));
    }

    public function testWithoutHeaderThatExists()
    {
        $response = new Response(200, ['Foo' => 'Bar', 'Baz' => 'Bam']);
        $newResponse = $response->withoutHeader('Foo');

        $this->assertSame(['Foo' => ['Bar'], 'Baz' => ['Bam']], $response->getHeaders());
        $this->assertSame(['Baz' => ['Bam']], $newResponse->getHeaders());
    }

    public function testWithoutHeaderThatDoesntExists()
    {
        $response = new Response(200, ['Baz' => 'Bam']);
        $newResponse = $response->withoutHeader('Foo');

        $this->assertEquals($response, $newResponse);
        $this->assertFalse($newResponse->hasHeader('Foo'));
        $this->assertSame(['Baz' => ['Bam']], $newResponse->getHeaders());
    }

    public function testSameInstanceWhenRemovingMissingHeader()
    {
        $response = new Response();

        $this->assertSame($response, $response->withoutHeader('Baz'));
    }

    public function testHeaderValuesAreTrimmed()
    {
        $response1 = new Response(200, ['OWS' => " \t \tFoo\t \t "]);
        $response2 = (new Response())->withHeader('OWS', " \t \tFoo\t \t ");
        $response3 = (new Response())->withAddedHeader('OWS', " \t \tFoo\t \t ");
        
        foreach ([$response1, $response2, $response3] as $response) {
            $this->assertSame(['OWS' => ['Foo']], $response->getHeaders());
            $this->assertSame('Foo', $response->getHeaderLine('OWS'));
            $this->assertSame(['Foo'], $response->getHeader('OWS'));
        }
    }
}
