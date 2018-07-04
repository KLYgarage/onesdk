<?php

namespace One\Test\Unit;

use One\FormatMapping;
use One\Publisher;

class FormatMappingTest extends \PHPUnit\Framework\TestCase
{
    protected $formatMapping;

    private $publisher;

    public function setUp()
    {
        $env = \loadTestEnv();
        if (empty($env)) {
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on $envPath to run test");
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );

        $this->formatMapping = new FormatMapping();
    }

    public function testMapMainArticle()
    {
        $idArticle = 2859;

        $jsonArticle = $this->publisher->getArticle($idArticle);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($article);

        $this->assertEquals($idArticle, $article->getId());
    }
}
