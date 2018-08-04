<?php declare(strict_types=1);

namespace One\Test;

use \One\PublisherNew;

class PublisherNewTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Publisher
     * @var \One\PublisherNew
     */
    private $publisher;

    protected function setUp(): void
    {
        $env = \loadTestEnv();

        if (empty($env)) {
            $this->markTestSkipped('no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on test/.env to run test');
        }

        $this->publisher = new PublisherNew(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );
    }

    public function testInstanceNotNull(): void
    {
        $this->assertNotNull($this->publisher);
    }

    public function testAuthentication(): void
    {
        $jsonResponse = $this->publisher->listArticle();
        $data = json_decode($jsonResponse, true);

        $this->assertArrayNotHasKey('message', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
    }

    public function testRecycleToken(): void
    {
        $env = \loadTestEnv();

        if (empty($env['ACCESS_TOKEN'])) {
            $this->markTestSkipped('no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on /test/.env to run test');
        }

        $tokenProducer = function () use ($env) {
            return $env['ACCESS_TOKEN'];
        };

        $this->publisher->recycleToken($tokenProducer);

        $jsonResponse = $this->publisher->listArticle();
        $data = json_decode($jsonResponse, true);

        $this->assertArrayNotHasKey('message', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
    }

    public function testRestCall(): void
    {
        $this->assertTrue(! empty($this->publisher->listArticle()));
    }
}
