<?php declare(strict_types=1);

namespace One\Test;

use \One\Publisher;
use One\Model\Article;
use One\Model\Gallery;
use One\Model\Page;
use One\Model\Photo;
use One\Model\Video;

class PublisherTest extends \PHPUnit\Framework\TestCase
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

        $this->publisher = new Publisher(
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

    /**
     * @expectedException \GuzzleHttp\Exception\ClientException
     */
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

    /**
     * @large
     */
    public function testSubmit(): void
    {
        $article = new Article(
            'Eius ad odit voluptatum occaecati ducimus rerum.',
            'Facilis occaecati sequi animi corrupti. Ex sit voluptates accusamus. Quidem eum magnam veniam odio totam aut. Nobis possimus totam quasi tempora consectetur iste. Repellendus est veritatis quibusdam dicta. Sapiente modi perferendis quidem repudiandae voluptates.',
            'https://www.zahn.de/home/',
            'dummy-' . random_int(0, 999),
            Article::TYPE_TEXT,
            Article::CATEGORY_BISNIS,
            'Hans-Friedrich Hettner B.Sc.',
            'Dolorum expedita repellendus ipsam. Omnis cupiditate enim. Itaque alias doloribus eligendi.',
            'distinctio',
            '2013-05-25'
        );

        $photo = new Photo(
            'https://aubry.fr/',
            Photo::RATIO_RECTANGLE,
            'Rerum asperiores nulla suscipit ex. Eligendi vero optio architecto dignissimos. Omnis autem ab ad hic quaerat omnis.',
            'Eum assumenda ab accusamus quam blanditiis.'
        );

        $page = new Page(
            'Velit neque repellat eos porro non expedita ea.',
            'Maiores ducimus iusto amet modi vitae. Quis dignissimos commodi odio. Minus debitis neque itaque. Aspernatur illo hic neque dolor vero. Ducimus ea id omnis ipsum quod voluptatum. Fuga perspiciatis fugiat minima deserunt ullam enim.',
            'https://www.jaume.com/categories/posts/wp-content/terms.html',
            1,
            'http://www.suessebier.de/app/blog/main/faq/'
        );

        $article->attachPhoto($photo);
        $article->attachPage($page);

        $resultingArticle = $this->publisher->submitArticle($article);

        $this->assertTrue(! empty($resultingArticle->getId()));

        $this->assertTrue(! empty($this->publisher->getArticle($resultingArticle->getId())));

        $this->assertTrue(! empty($this->publisher->deleteArticle($resultingArticle->getId())));
    }

    public function testSubmitArticleWithoutAttachment(): void
    {
        $article = new Article(
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

        $articleCreated = $this->publisher->submitArticle($article);
        $articleCreatedId = $articleCreated->getId();

        $this->assertTrue(! empty($articleCreatedId));

        $responseArticle = $this->publisher->getArticle($articleCreatedId);
        $resArticleDecoded = json_decode($responseArticle, true);
        $keys = ['title', 'reporter', 'lead', 'body', 'source', 'uniqueId', 'type_id', 'category_id', 'tags', 'published_at'];
        $subArticle = array_combine($keys, $article->toArray());
        $subArticleFiltered = array_filter($subArticle, function ($key) {
            return $key === 'lead' || $key === 'body' || $key === 'title' || $key === 'source';
        }, ARRAY_FILTER_USE_KEY);

        $this->assertArraySubset($subArticleFiltered, $resArticleDecoded['data']);

        $this->assertTrue(! empty($responseArticle));

        $articleDeleted = $this->publisher->deleteArticle($articleCreatedId);

        $this->assertTrue(! empty($articleDeleted));

        $this->assertTrue(in_array('Article deleted', json_decode($articleDeleted, true), true));
    }

    /**
     * @large
     */
    public function testSubmitArticleWithPhotos(): void
    {
        $article = new Article(
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
            $article->attach(Article::ATTACHMENT_FIELD_PHOTO, new Photo(
                'http://heydrich.com/' . ($i * random_int(23, 99)) . '.jpg',
                $ratio,
                'Repellat nesciunt ipsum.',
                'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
            ));
        }

        $articleCreated = $this->publisher->submitArticle($article);
        $articleCreatedId = $articleCreated->getId();

        $responseArticle = $this->publisher->getArticle($articleCreatedId);

        $this->assertTrue(! empty($articleCreatedId));

        $this->assertTrue(! empty($responseArticle));

        $resArticleDecoded = json_decode($responseArticle, true);
        $keys = ['title', 'reporter', 'lead', 'body', 'source', 'uniqueId', 'type_id', 'category_id', 'tags', 'published_at'];
        $subArticle = array_combine($keys, $article->toArray());
        $subArticleFiltered = array_filter($subArticle, function ($key) {
            return $key === 'lead' || $key === 'body' || $key === 'title' || $key === 'source';
        }, ARRAY_FILTER_USE_KEY);

        $this->assertArraySubset($subArticleFiltered, $resArticleDecoded['data']);

        $this->assertSame($maxPhotos, count($resArticleDecoded['data']['photos']));

        $resArticlePhotos = array_map(function ($photo) {
            return $photo['photo_url'];
        }, $resArticleDecoded['data']['photos']);

        $articlePhotos = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PHOTO);
        $articlePhotosMapped = array_map(function ($photo) {
            return $photo->getCollection()->toArray()['url'];
        }, $articlePhotos);

        $dataDifferences = array_diff($articlePhotosMapped, $resArticlePhotos);
        $this->assertTrue(empty($dataDifferences));

        $articleDeleted = $this->publisher->deleteArticle($articleCreatedId);

        $this->assertTrue(! empty($articleDeleted));

        $this->assertTrue(in_array('Article deleted', json_decode($articleDeleted, true), true));
    }

    /**
     * @large
     */
    public function testSubmitArticleWithPage(): void
    {
        $article = new Article(
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

        $page = new Page(
            'Dummy Page',
            'Rsumissop olli allun sinmo aiciffo otsui isauq subitatpuloV .aiuq murah etatpulov eadnaiduper ni tnuicsen aguF .tilev etatpulov aitillom muitnaduaL .rorre mediuq metatpulov saitselom murtson oido sitaicipsreP .murae erobal alluN .eataeb tareauq di tidepmi euqmerolod ruten',
            'http://example.com/dummy1.html',
            1,
            'http://kshlerin.org/cumque-deleniti-ea-qui',
            'dummy-1-ctr'
        );

        $article->attach(Article::ATTACHMENT_FIELD_PAGE, $page);

        $articleCreated = $this->publisher->submitArticle($article);
        $articleCreatedId = $articleCreated->getId();

        $responseArticle = $this->publisher->getArticle($articleCreatedId);

        $this->assertTrue(! empty($articleCreatedId));

        $this->assertTrue(! empty($responseArticle));

        $resArticleDecoded = json_decode($responseArticle, true);
        $keys = ['title', 'reporter', 'lead', 'body', 'source', 'uniqueId', 'type_id', 'category_id', 'tags', 'published_at'];
        $subArticle = array_combine($keys, $article->toArray());
        $subArticleFiltered = array_filter($subArticle, function ($key) {
            return $key === 'lead' || $key === 'body' || $key === 'title' || $key === 'source';
        }, ARRAY_FILTER_USE_KEY);

        $this->assertArraySubset($subArticleFiltered, $resArticleDecoded['data']);

        $this->assertTrue(! empty($resArticleDecoded['data']['pages']));

        $resArticlePages = array_map(function ($page) {
            return $page['page_order'];
        }, $resArticleDecoded['data']['pages']);

        $articlePages = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_PAGE);
        $articlePagesMapped = array_map(function ($page) {
            return (int) $page->getCollection()->toArray()['order'];
        }, $articlePages);

        $dataDifferences = array_diff($articlePagesMapped, $resArticlePages);
        $this->assertTrue(empty($dataDifferences));

        $articleDeleted = $this->publisher->deleteArticle($articleCreatedId);

        $this->assertTrue(! empty($articleDeleted));

        $this->assertTrue(in_array('Article deleted', json_decode($articleDeleted, true), true));
    }

    /**
     * @large
     */
    public function testSubmitArticleWithGallery(): void
    {
        $article = new Article(
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

        $maxPhotos = 3;

        for ($i = 0; $i < $maxPhotos; $i++) {
            $gallery = new Gallery(
                'dummy' . random_int(0, 999),
                ($i * random_int(12, 76) * random_int(1, 99)),
                'http://www.kovacek.org/magni-omnis-consequuntur-sapiente-magni-architecto-soluta-voluptas-corrupti' . $i,
                'http://www.kovacek.org/magni-omnis-consequuntur-sapiente-magni-architecto-soluta-voluptas-corrupti' . $i
            );
            $article->attachGallery($gallery);
        }

        $articleCreated = $this->publisher->submitArticle($article);
        $articleCreatedId = $articleCreated->getId();

        $responseArticle = $this->publisher->getArticle($articleCreatedId);

        $this->assertTrue(! empty($articleCreatedId));

        $this->assertTrue(! empty($responseArticle));

        $resArticleDecoded = json_decode($responseArticle, true);
        $keys = ['title', 'reporter', 'lead', 'body', 'source', 'uniqueId', 'type_id', 'category_id', 'tags', 'published_at'];
        $subArticle = array_combine($keys, $article->toArray());
        $subArticleFiltered = array_filter($subArticle, function ($key) {
            return $key === 'lead' || $key === 'body' || $key === 'title' || $key === 'source';
        }, ARRAY_FILTER_USE_KEY);

        $this->assertArraySubset($subArticleFiltered, $resArticleDecoded['data']);

        $this->assertTrue(! empty($resArticleDecoded['data']['galleries']));

        $this->assertSame($maxPhotos, count($resArticleDecoded['data']['galleries']));

        $resArticleGalleries = array_map(function ($gallery) {
            return $gallery['gallery_order'];
        }, $resArticleDecoded['data']['galleries']);

        $articleGalleries = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_GALLERY);
        $artclGalleriesMapped = array_map(function ($gallery) {
            return (int) $gallery->getCollection()->toArray()['order'];
        }, $articleGalleries);

        $dataDifferences = array_diff($artclGalleriesMapped, $resArticleGalleries);
        $this->assertTrue(empty($dataDifferences));

        $articleDeleted = $this->publisher->deleteArticle($articleCreatedId);

        $this->assertTrue(! empty($articleDeleted));

        $this->assertTrue(in_array('Article deleted', json_decode($articleDeleted, true), true));
    }

    /**
     * @large
     */
    public function testSubmitArticleWithVideo(): void
    {
        $article = new Article(
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

        $video = new Video(
            'dummy-video',
            'https://youtuve.com/dummmy',
            1,
            'http://example.com/url-detail.html'
        );

        $video2 = new Video(
            'dummy-video',
            'https://youtuve.com/dummmy',
            2,
            'http://example.com/url-detail.html'
        );

        $article->attachVideo($video);
        $article->attachVideo($video2);

        $articleCreated = $this->publisher->submitArticle($article);
        $articleCreatedId = $articleCreated->getId();

        $responseArticle = $this->publisher->getArticle($articleCreatedId);

        $this->assertTrue(! empty($articleCreatedId));

        $this->assertTrue(! empty($responseArticle));

        $resArticleDecoded = json_decode($responseArticle, true);
        $keys = ['title', 'reporter', 'lead', 'body', 'source', 'uniqueId', 'type_id', 'category_id', 'tags', 'published_at'];
        $subArticle = array_combine($keys, $article->toArray());
        $subArticleFiltered = array_filter($subArticle, function ($key) {
            return $key === 'lead' || $key === 'body' || $key === 'title' || $key === 'source';
        }, ARRAY_FILTER_USE_KEY);

        $this->assertArraySubset($subArticleFiltered, $resArticleDecoded['data']);

        $this->assertTrue(! empty($resArticleDecoded['data']['videos']));

        $resArticleVideos = array_map(function ($video) {
            return $video['video_order'];
        }, $resArticleDecoded['data']['videos']);

        $articleVideos = $article->getAttachmentByField(Article::ATTACHMENT_FIELD_VIDEO);
        $articleVideosMapped = array_map(function ($video) {
            return (int) $video->getCollection()->toArray()['order'];
        }, $articleVideos);

        $dataDifferences = array_diff($articleVideosMapped, $resArticleVideos);
        $this->assertTrue(empty($dataDifferences));

        $articleDeleted = $this->publisher->deleteArticle($articleCreatedId);

        $this->assertTrue(! empty($articleDeleted));

        $this->assertTrue(in_array('Article deleted', json_decode($articleDeleted, true), true));
    }
}
