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
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on $envPath to run test");
        }

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET']
        );

        $this->formatMapping = new FormatMapping();
    }

    public function testArticleWithoutAttachment()
    {
        $newArticle = new Article(
            'Publisher dummy article',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1-ctr',
            Article::TYPE_TEXT,
            Article::CATEGORY_NASIONAL,
            'dummy-1-ctr',
            'dummy-1-ctr',
            'dummy-1-ctr',
            '2018-07-03',
            null
        );

        $newArticleId = $this->publisher->submitArticle($newArticle)->getId();

        $this->assertTrue(!empty($newArticleId));

        $jsonArticle = $this->publisher->getArticle($newArticleId);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($newArticleId, $this->article->getId());

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));

        $this->assertFalse($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }

    public function testArticleWithPhotoAttachment()
    {
        $newArticle = new Article(
            'Publisher dummy article',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1-ctr',
            Article::TYPE_TEXT,
            Article::CATEGORY_NASIONAL,
            'dummy-1-ctr',
            'dummy-1-ctr',
            'dummy-1-ctr',
            '2018-07-03',
            null
        );

        $maxPhotos = 5;

        $ratio = Photo::RATIO_SQUARE;

        for ($i = 0; $i < $maxPhotos; $i++) {
            switch ($i) {
                case 1:
                    $ratio = Photo::RATIO_COVER;
                    break;

                case 2:
                    $ratio = Photo::RATIO_VERTICAL;
                    break;

                case 3:
                    $ratio = Photo::RATIO_HEADLINE;
                    break;

                case 4:
                    $ratio = Photo::RATIO_RECTANGLE;
                    break;

                default:
                    $ratio = Photo::RATIO_SQUARE;
                    break;
            }

            $newArticle->attach(Article::ATTACHMENT_FIELD_PHOTO, new Photo(
                'http://heydrich.com/' . ($i * rand(23, 99)) . '.jpg',
                $ratio,
                'Repellat nesciunt ipsum.',
                'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
            ));
        }

        $newArticleId = $this->publisher->submitArticle($newArticle)->getId();

        $this->assertTrue(!empty($newArticleId));

        $jsonArticle = $this->publisher->getArticle($newArticleId);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($newArticleId, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));
    }

    public function testArticleHasAttachments()
    {
        $newArticle = new Article(
            'Publisher dummy article',
            'Tenetur doloremque impedit id quaerat beatae. Nulla labore earum. Perspiciatis odio nostrum molestias voluptatem quidem error. Laudantium mollitia voluptate velit. Fuga nesciunt in repudiandae voluptate harum quia. Voluptatibus quasi iusto officia omnis nulla illo possimus.',
            'http://example.com/url-detail.html',
            'dummy-1-ctr',
            Article::TYPE_TEXT,
            Article::CATEGORY_NASIONAL,
            'dummy-1-ctr',
            'dummy-1-ctr',
            'dummy-1-ctr',
            '2018-07-03',
            null
        );

        $maxPhotos = 5;

        $ratio = Photo::RATIO_SQUARE;

        for ($i = 0; $i < $maxPhotos; $i++) {
            switch ($i) {
                case 1:
                    $ratio = Photo::RATIO_COVER;
                    break;

                case 2:
                    $ratio = Photo::RATIO_VERTICAL;
                    break;

                case 3:
                    $ratio = Photo::RATIO_HEADLINE;
                    break;

                case 4:
                    $ratio = Photo::RATIO_RECTANGLE;
                    break;

                default:
                    $ratio = Photo::RATIO_SQUARE;
                    break;
            }

            $newArticle->attach(Article::ATTACHMENT_FIELD_PHOTO, new Photo(
                'http://heydrich.com/' . ($i * rand(23, 99)) . '.jpg',
                $ratio,
                'Repellat nesciunt ipsum.',
                'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
            ));
        }

        $page = new Page(
            'Dummy Page',
            'Rsumissop olli allun sinmo aiciffo otsui isauq subitatpuloV .aiuq murah etatpulov eadnaiduper ni tnuicsen aguF .tilev etatpulov aitillom muitnaduaL .rorre mediuq metatpulov saitselom murtson oido sitaicipsreP .murae erobal alluN .eataeb tareauq di tidepmi euqmerolod ruten',
            'http://example.com/dummy1.html',
            1,
            'http://kshlerin.org/cumque-deleniti-ea-qui',
            'dummy-1-ctr'
        );

        $newArticle->attachPage($page);

        $gallery = new Gallery(
            'dummy' . rand(0, 999),
            (string) (1 * rand(12, 76) * rand(1, 99)),
            'http://www.kovacek.org/magni-omnis-consequuntur-sapiente-magni-architecto-soluta-voluptas-corrupti' . $i,
            'http://www.kovacek.org/magni-omnis-consequuntur-sapiente-magni-architecto-soluta-voluptas-corrupti' . $i
        );

        $newArticle->attachGallery($gallery);

        $video = new Video(
            'dummy-video',
            'https://youtuve.com/dummmy',
            2,
            'http://example.com/url-detail.html'
        );

        $newArticle->attachVideo($video);

        $newArticleId = $this->publisher->submitArticle($newArticle)->getId();

        $this->assertTrue(!empty($newArticleId));

        $jsonArticle = $this->publisher->getArticle($newArticleId);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $this->article = $this->formatMapping->article($jsonArticle);

        $this->assertNotNull($this->article);

        $this->assertEquals($newArticleId, $this->article->getId());

        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));
        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));
        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_GALLERY));
        $this->assertTrue($this->article->hasAttachment(Article::ATTACHMENT_FIELD_VIDEO));
    }
}
