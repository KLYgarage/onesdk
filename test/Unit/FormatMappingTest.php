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

        $this->formatMapping = new FormatMapping();
    }

    public function testMapMainArticle()
    {
        $idArticle = 2859;

        $jsonArticle = $this->publisher->getArticle($idArticle);

        $this->assertArrayHasKey('data', json_decode($jsonArticle, true));

        $article = $this->formatMapping->mapMainArticle($jsonArticle);

        $this->assertNotNull($article);

        $this->assertEquals($idArticle, $article->getId());
    }

    public function testJsonToArray()
    {
        $json = '
				{
  				"nama":"kenny karnama"
  				}
  				';

        $isValid = $this->formatMapping->jsonToArray($json);

        $this->assertNotNull($isValid);

        $json = array(
            'nama' => 'kenny karnama',
        );

        $isValid = $this->formatMapping->jsonToArray($json);

        $this->assertNull($isValid);
    }
}
