<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\Model\Article;
use One\Model\Photo;
use One\Uri;
use function One\createArticleFromArray;
use function One\createAttachmentGallery;
use function One\createAttachmentPhoto;
use function One\createUriFromServer;
use function One\createUriFromString;

class FactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * dummy array
     * @var array<string[]>
     */
    protected $dummy;

    protected function setUp(): void
    {
        $this->dummy = ['title' => 'Recusandae natus ', 'body' => 'Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. ', 'source' => 'http://example.com/url-detail.html', 'unique_id' => 'dummy-1', 'type_id' => 2, 'category_id' => 1, 'reporter' => 'earum'];
    }

    /**
     * @covers Uri::createUriFromString
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
    public function testFactoryUriFromServer(): void
    {
        $_SERVER['HTTPS'] = 'https://';
        $_SERVER['HTTP_HOST'] = 'www.foobar.com';
        $_SERVER['SERVER_PORT'] = 85;
        $_SERVER['REQUEST_URI'] = 'path';
        $_SERVER['QUERY_STRING'] = 'page=1';
        $_SERVER['PHP_AUTH_USER'] = 'username';
        $_SERVER['PHP_AUTH_PW'] = 'password';
        $uri = createUriFromServer();
        $this->assertInstanceOf(Uri::class, $uri);
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('username:password', $uri->getUserInfo());
        $this->assertSame('www.foobar.com', $uri->getHost());
        $this->assertSame(85, $uri->getPort());
        $this->assertSame('/path', $uri->getPath());
        $this->assertSame('page=1', $uri->getQuery());
        $this->assertSame('https://username:password@www.foobar.com:85', $uri->getBaseUrl());
        $uri2 = $uri->withHost('www.phpunit.de')
            ->withUserInfo('user2:pass2')
            ->withPort(80)
            ->withScheme('http')
            ->withPath('/path/kerap/258');
    }

    /**
     * @covers Uri::createUriFromServer
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
    public function testFactoryUriFromString(): void
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
            ->withScheme('http');
    }

    /**
     * @covers Helper::createArticleFromArray
     */
    public function testFactoryArticleFromArray(): void
    {
        $article = createArticleFromArray($this->dummy);
        $data = $article->toJson();
        $data = json_decode($data);
        $this->assertSame($this->dummy['title'], $data->title);
        $this->assertSame($this->dummy['body'], $data->body);
        $this->assertSame($this->dummy['unique_id'], $data->uniqueId);
        $this->assertSame($this->dummy['category_id'], $data->category_id);
        $this->assertSame($this->dummy['reporter'], $data->reporter);
    }

    /**
     * @covers Helper::createArticleFromArray
     * @covers Helper::createAttachmentPhoto
     */
    public function testAttachmentPhoto(): void
    {
        $article = createArticleFromArray($this->dummy);

        $article->attach(Article::ATTACHMENT_FIELD_PHOTO, createAttachmentPhoto('http://test.com/', Photo::RATIO_SQUARE, 'Repellat nesciunt ipsum.', 'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'));

        $photoAttachment = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO)[0];

        $this->assertCount(1, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO));
    }

    /**
     * @covers Helper::createArticleFromArray
     * @covers Helper::createAttachmentGallery
     */
    public function testAttachmentGallery(): void
    {
        $article = createArticleFromArray($this->dummy);

        $article->attachGallery(createAttachmentGallery(
            'Est illum cupiditate quidem alias.',
            1,
            'http://jordan.biz/',
            'https://www.roemer.de/',
            'Ipsam quidem ut tempora incidunt officia sunt.'
        ));

        $this->assertCount(1, $article->getAttachmentByField(Article::ATTACHMENT_FIELD_GALLERY));
    }
}
