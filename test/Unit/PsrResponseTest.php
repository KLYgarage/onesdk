<?php

namespace One\Test\Unit;

use One\Response;

class PsrResponseTest extends \PHPUnit\Framework\TestCase
{
    private $psrResponse;

    public function setUp()
    {
        $this->psrResponse = new Response();
    }

    public function testInstance()
    {
        $this->assertNotNull($this->psrResponse);
    }
}
