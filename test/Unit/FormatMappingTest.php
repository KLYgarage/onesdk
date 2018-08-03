<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\FormatMapping;
use One\Model\Article;
use One\Publisher;

class FormatMappingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Format mapping
     * @var \One\FormatMapping
     */
    private $formatMapping;

    /**
     * Publisher
     * @var \One\Publisher
     */
    private $publisher;

    /**
     * Article
     * @var \One\Model\Article
     */
    private $article;

    protected function setUp(): void
    {
        $env = \loadTestEnv();
        if (empty($env)) {
            $this->markTestSkipped('no .env defined. Need client ID and secret to continue this test, modify .env.example to .env to run test');
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );

        $this->formatMapping = new FormatMapping();
    }

    public function testArticleAttachment(): void
    {
        $newArticleId = 10998;

        $jsonArticle = $this->publisher->getArticle($newArticleId);

        if ($jsonArticle === null && empty($jsonArticle)) {
            $this->markTestSkipped('Test skipped');
        }

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertSame($newArticleId, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));
        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }
}
