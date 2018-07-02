<?php

namespace One\Test\Unit;

use One\Publisher;
use One\Model\Article;
use One\Model\Photo;
use One\Model\Page;

class PublisherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var One\Publisher
     */
    private $publisher;

    public function setUp()
    {
        $envPath = realpath(__DIR__ . '/../.env');
        if (!file_exists($envPath)) {
            $this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on $envPath to run test");
        }

        $env = array_reduce(
            array_filter(
                explode(
                    "\n",
                    file_get_contents($envPath)
                )
            ),
            function ($carry, $item) {
                list($key, $value) = explode('=', $item, 2);
                $carry[$key] = $value;
                return $carry;
            },
            array()
        );

        $this->publisher = new Publisher(
            $env['CLIENT_ID'],
            $env['CLIENT_SECRET'],
            !empty($env['ACCESS_TOKEN']) ?
            array(
                'access_token' => $env['ACCESS_TOKEN']
            ) : array()
        );
    }

    public function testRestCall()
    {
        $this->assertTrue(!empty($this->publisher->listArticle()));
    }

    public function testSubmit()
    {
        $article = new Article(
            'Eius ad odit voluptatum occaecati ducimus rerum.',
            'Facilis occaecati sequi animi corrupti. Ex sit voluptates accusamus. Quidem eum magnam veniam odio totam aut. Nobis possimus totam quasi tempora consectetur iste. Repellendus est veritatis quibusdam dicta. Sapiente modi perferendis quidem repudiandae voluptates.',
            'https://www.zahn.de/home/',
            'dummy-' . rand(0, 999),
            Article::TYPE_TEXT,
            Article::CATEGORY_BISNIS,
            "Hans-Friedrich Hettner B.Sc.",
            "Dolorum expedita repellendus ipsam. Omnis cupiditate enim. Itaque alias doloribus eligendi.",
            "distinctio",
            "2013-05-25"
        );

        $photo = new Photo(
            'https://aubry.fr/',
            Photo::RATIO_RECTANGLE,
            "Rerum asperiores nulla suscipit ex. Eligendi vero optio architecto dignissimos. Omnis autem ab ad hic quaerat omnis.",
            "Eum assumenda ab accusamus quam blanditiis."
        );

        $page = new Page(
            'Velit neque repellat eos porro non expedita ea.',
            "Maiores ducimus iusto amet modi vitae. Quis dignissimos commodi odio. Minus debitis neque itaque. Aspernatur illo hic neque dolor vero. Ducimus ea id omnis ipsum quod voluptatum. Fuga perspiciatis fugiat minima deserunt ullam enim.",
            "https://www.jaume.com/categories/posts/wp-content/terms.html",
            1,
            "http://www.suessebier.de/app/blog/main/faq/"
        );

        $article->attachPhoto($photo);
        $article->attachPage($page);

        $resultingArticle = $this->publisher->submitArticle($article);

        $this->assertTrue(!empty($resultingArticle->getId()));

        $this->assertTrue(!empty($this->publisher->getArticle($resultingArticle->getId())));

        $this->assertTrue(!empty($this->publisher->deleteArticle($resultingArticle->getId())));
    }
}
