<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\Uri;
use function One\createUriFromString;

class UriTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Uri::createFromString
     * @covers Uri::getScheme
     * @covers Uri::getUserInfo
     * @covers Uri::getHost
     * @covers Uri::getPort
     * @covers Uri::getPath
     * @covers Uri::getQuery
     * @covers Uri::getFragment
     * @covers Uri::getBaseUrl
     * @covers Uri::withHost
     * @covers Uri::withUserInfo
     * @covers Uri::withPort
     * @covers Uri::withScheme
     * @covers Uri::withPath
     * @covers Uri::withQuery
     * @covers Uri::withString
     * @covers Uri::withFragment
     * @covers Uri::filterScheme
     * @covers Uri::filterPort
     * @covers Uri::filterPath
     * @covers Uri::filterQuery
     * @covers Uri::hasStandardPort
     */
    public function testMethods(): void
    {
        $string = 'https://username:password@www.example.com:85/kerap/254?page=1#idkomentar';

        $uri = createUriFromString($string);

        $this->assertInstanceOf(Uri::class, $uri);

        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('username:password', $uri->getUserInfo());
        $this->assertSame('www.example.com', $uri->getHost());
        $this->assertSame(85, $uri->getPort());
        $this->assertSame('/kerap/254', $uri->getPath());
        $this->assertSame('page=1', $uri->getQuery());
        $this->assertSame('idkomentar', $uri->getFragment());
        $this->assertSame('https://username:password@www.example.com:85', $uri->getBaseUrl());
        $this->assertSame($string, (string) $uri);

        $uri2 = $uri->withHost('www.phpunit.de')
            ->withUserInfo('user2:pass2')
            ->withPort(80)
            ->withScheme('http')
            ->withPath('/path/kerap/258')
            ->withQuery('content=false&delimiter=default')
            ->withFragment('bodynya');

        $this->assertNotSame($uri, $uri2);

        $this->assertSame('http://user2:pass2@www.phpunit.de/path/kerap/258?content=false&delimiter=default#bodynya', (string) $uri2);
    }
}
