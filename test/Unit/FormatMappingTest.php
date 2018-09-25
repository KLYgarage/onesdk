<?php

namespace One\Test\Unit;

use One\FormatMapping;
use One\Model\Article;
use One\Model\Gallery;
use One\Model\Page;
use One\Model\Photo;
use One\Model\Video;
use One\Publisher;

class FormatMappingTest extends \PHPUnit\Framework\TestCase
{
    protected $formatMapping;

    private $publisher;

    private $article;

    public function setUp()
    {
        $env = \loadTestEnv();
        if (empty($env)) {
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env to run test");
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );

        $this->formatMapping = new FormatMapping();
    }

    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testArticleAttachment()
    {
        $newArticleId = 26066;

        $jsonArticle = $this->publisher->getArticle($newArticleId);

        if (is_null($jsonArticle) && empty($jsonArticle)) {
            $this->markTestSkipped('Test skipped');
        }

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($newArticleId, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }
}
