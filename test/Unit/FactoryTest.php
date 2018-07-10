<?php

namespace One\Test\Unit;

use function One\createArticleFromArray;
use function One\createUriFromServer;
use function One\createUriFromString;
use One\Uri;

class FactoryTest extends \PHPUnit\Framework\TestCase {

	public function testFactoryArticleFromArray() {
		$dummy = array('title' => 'Recusandae natus ', 'body' => 'Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. ', 'source' => 'http://example.com/url-detail.html', 'unique_id' => 'dummy-1', 'type_id' => 2, 'category_id' => 1, 'reporter' => 'earum');
		$article = createArticleFromArray($dummy);
		$data = $article->toJson();
		$data = json_decode($data);
		$this->assertEquals($dummy['title'], $data->title);
		$this->assertEquals($dummy['body'], $data->body);
		$this->assertEquals($dummy['source'], $data->source);
		$this->assertEquals($dummy['unique_id'], $data->uniqueId);
		$this->assertEquals($dummy['category_id'], $data->category_id);
		$this->assertEquals($dummy['reporter'], $data->reporter);
	}

	public function testFactoryUriFromServer() {

		$_SERVER['HTTPS'] = 'https://';
		$_SERVER['HTTP_HOST'] = 'www.foobar.com';
		$_SERVER['SERVER_PORT'] = '85';
		$_SERVER['REQUEST_URI'] = 'path';
		$_SERVER['QUERY_STRING'] = 'page=1';
		$_SERVER['PHP_AUTH_USER'] = 'username';
		$_SERVER['PHP_AUTH_PW'] = 'password';
		$uri = createUriFromServer();
		$this->assertInstanceOf(Uri::class, $uri);
		$this->assertEquals('https', $uri->getScheme());
		$this->assertEquals('username:password', $uri->getUserInfo());
		$this->assertEquals('www.foobar.com', $uri->getHost());
		$this->assertEquals('85', $uri->getPort());
		$this->assertEquals('/path', $uri->getPath());
		$this->assertEquals('page=1', $uri->getQuery());
		$this->assertEquals('https://username:password@www.foobar.com:85', $uri->getBaseUrl());
		$uri2 = $uri->withHost('www.phpunit.de')
			->withUserInfo('user2:pass2')
			->withPort(80)
			->withScheme('http')
			->withPath('/path/kerap/258')
			->withQuery('content=false&delimiter=default');
	}
	public function testFactoryUriFromString() {
		$string = 'https://username:password@www.example.com:85/kerap/254?page=1#idkomentar';
		$uri = createUriFromString($string);
		$this->assertInstanceOf(Uri::class, $uri);
		$this->assertEquals('https', $uri->getScheme());
		$this->assertEquals('username:password', $uri->getUserInfo());
		$this->assertEquals('www.example.com', $uri->getHost());
		$this->assertEquals('85', $uri->getPort());
		$this->assertEquals('/kerap/254', $uri->getPath());
		$this->assertEquals('page=1', $uri->getQuery());
		$this->assertEquals('idkomentar', $uri->getFragment());
		$this->assertEquals('https://username:password@www.example.com:85', $uri->getBaseUrl());
		$this->assertEquals($string, (string) $uri);
		$uri2 = $uri->withHost('www.phpunit.de')
			->withUserInfo('user2:pass2')
			->withPort(80)
			->withScheme('http')
			->withPath('/path/kerap/258')
			->withQuery('content=false&delimiter=default');
	}
}
