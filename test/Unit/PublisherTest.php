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
		# code...
		$json_response = $this->publisher->listArticle();

		$data = json_decode($json_response, TRUE);

		$this->assertArrayNotHasKey('message', $data);

		$this->assertArrayHasKey('data', $data);

		$this->assertArrayHasKey('meta', $data);

	}

	public function testRecycleToken() {
		# code...
		$token_producer = function () {

			return 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImZkNDc5YzljYTljNjZhMjQyN2Q0MTE1MzVkODg4OTRkNTM3M2E1OTYyOTI1ODczMjIwZWViOGUzZTIxNjIwZjk5YmYyMDUxN2M3YmEzZmNjIn0.eyJhdWQiOiIxIiwianRpIjoiZmQ0NzljOWNhOWM2NmEyNDI3ZDQxMTUzNWQ4ODg5NGQ1MzczYTU5NjI5MjU4NzMyMjBlZWI4ZTNlMjE2MjBmOTliZjIwNTE3YzdiYTNmY2MiLCJpYXQiOjE1MzA1OTI0ODIsIm5iZiI6MTUzMDU5MjQ4MiwiZXhwIjoxNTYyMTI4NDgyLCJzdWIiOiIiLCJzY29wZXMiOltdfQ.fVs6ftztGItM5LC3axTW-u0jhX9v2aIyrPpYSMyQ1JfbgyyL8WjO9lDh9kCi95uzSXZhteIDEOIzJTUF2aSzsoiJepMhEJAGM7E98GWiIhGSLiKh7H-fL1t7lmoCVNIBVfO3Rd08zHksnjhfOFbhVfmwPJSMN5afspJr8bAsNH-9u0_xHmJQ7H3qyEgJwufBuOD5w0_xNJAShvXr93V3oBBSOKrpAluO8eB_ZZxF01yL7_5UtqgTchjDUXp4mzjUYMy5pwdG2AsSnF2Mt8M4zAC1qtk8u7sajlE31ZLLvaf15JvNpbo3SR4K_iRjmtkz833gC0REOsV_6h26gC9FtrYNhNkVOrBsx1sapB7UO4vhJ3kPJIUvO8fqAHg4cRVsdPUrNeUab35A5bEJD7JnusiF_GV4spsej2bArHI00G-K5QosY4G3BrROGUE6iQRcva9GpDyIuhMKB3SAIatXFD2BlyMs2quRxmT6SQk0qqdlWTWfcnVOt2pC4O0erw1IB76kMOOwKfS3ujNCricvZurQpNCdcPbJAkH0lAUVxYQkiz-jzXsu91L3UFpF9qYYbRou29JoTnw9yPOxjFqIlpD7dOUTKkF4cJztJhbTLUBQEYHwnlfxUtpkjiyulnwIDoOjygEEqIr-SzhXJp4NN6PRWn3NKq8IeHMM8Cll5cA';

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
}
