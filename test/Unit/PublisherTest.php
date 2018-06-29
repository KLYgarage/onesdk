<?php

namespace One\Test\Unit;

use One\Publisher;

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
        //must write submitting test
    }
}
