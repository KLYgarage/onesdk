<?php

namespace One\Test\Unit;

use One\Model\Article;
use One\Model\Page;
use One\Model\Photo;
use One\Publisher;

class PublisherTest extends \PHPUnit\Framework\TestCase {
	/**
	 * @var One\Publisher
	 */
	private $publisher;

	public function setUp() {
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
				'access_token' => $env['ACCESS_TOKEN'],
			) : array()
		);
	}

	public function testAuthentication() {

		$json_response = $this->publisher->listArticle();

		$data = json_decode($json_response, TRUE);

		$this->assertArrayNotHasKey('message', $data);

		$this->assertArrayHasKey('data', $data);

		$this->assertArrayHasKey('meta', $data);

	}

	public function testRecycleToken() {

		$envPath = realpath(__DIR__ . '/../.env');

		if (!file_exists($envPath)) {
			$this->markTestSkipped("no .env defined. Need client ID and secret to continue this test, modify .env.example to .env on $envPath to run test");
		}

		$token_producer = function () use ($envPath) {

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

			return $env['ACCESS_TOKEN'];

		};

		$this->publisher->recycleToken($token_producer);

		$json_response = $this->publisher->listArticle();

		$data = json_decode($json_response, TRUE);

		$this->assertArrayNotHasKey('message', $data);

		$this->assertArrayHasKey('data', $data);

		$this->assertArrayHasKey('meta', $data);

	}

	public function testRestCall() {

		$this->assertTrue(!empty($this->publisher->listArticle()));
	}

	public function testSubmit() {
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

	public function testSubmitArticleWithoutAttachment() {
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

		$this->assertTrue(!empty($articleCreatedId));

		$this->assertTrue(!empty($this->publisher->getArticle($articleCreatedId)));

		$this->assertTrue(!empty($this->publisher->deleteArticle($articleCreatedId)));
	}

	public function testSubmitArticleWithPhotos() {
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

		$max_photos = 5;

		for ($i = 0; $i < $max_photos; $i++) {
			$article->attach(Article::ATTACHMENT_FIELD_PHOTO, new Photo(
				'http://heydrich.com/',
				Photo::RATIO_SQUARE,
				'Repellat nesciunt ipsum.',
				'Quos atque quaerat recusandae modi reprehenderit magnam expedita.'
			));
		}

		$articleCreated = $this->publisher->submitArticle($article);
		$articleCreatedId = $articleCreated->getId();

		$this->assertTrue(!empty($articleCreatedId));

		$this->assertTrue(!empty($this->publisher->getArticle($articleCreatedId)));

		$this->assertTrue($articleCreated->hasAttachment(Article::ATTACHMENT_FIELD_PHOTO));

		$this->assertEquals($max_photos, count($articleCreated->getAttachments()['photo']));

		$this->assertTrue(!empty($this->publisher->deleteArticle($articleCreatedId)));
	}

	public function testSubmitArticleWithPage() {
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

		$this->assertTrue(!empty($articleCreatedId));

		$this->assertTrue(!empty($this->publisher->getArticle($articleCreatedId)));

		$this->assertTrue($articleCreated->hasAttachment(Article::ATTACHMENT_FIELD_PAGE));

		$this->assertTrue(!empty($this->publisher->deleteArticle($articleCreatedId)));
	}
}
