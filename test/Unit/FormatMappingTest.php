<?php

namespace One\Test\Unit;

use One\FormatMapping;
use One\Model\Article;
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
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on $envPath to run test");
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );

        $this->formatMapping = new FormatMapping();
    }

    public function testMapMainArticleHasAttachment()
    {
        $idArticle = 10249;

        $jsonArticle = $this->publisher->getArticle($idArticle);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($idArticle, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }

    public function testMapMainArticleSomeAttachmentMissing()
    {
        $idArticle = 10998;

        $jsonArticle = $this->publisher->getArticle($idArticle);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($idArticle, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }
}
