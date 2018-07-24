<?php

namespace One\Test\Unit;

use One\Collection;
use One\Model\Model;

class ModelTest extends \PHPUnit\Framework\TestCase
{
    protected $tableModel;
    protected $dummy;

    public function setUp()
    {
        $this->tableModel = new Model();

        $this->dummy = new Collection(array(
            'name' => 'Prof. Hermann Seip B.Sc.',
            'address' => '688 Anthony Ferry Suite 661
            West Heathermouth, WA 21818',
            'dob' => '1983-02-14',
            'email' => 'jarajoaquin@yahoo.com',
            'atr' => null,
            'age' => 87,
            'thing' => 'Cupiditate libero nulla aperiam culpa. Dolorum consequuntur quae ad. Atque pariatur odio veniam magnam possimus.',
            'posts' => array(
                'title' => 'Veniam libero eveniet molestias cupiditate corporis.',
            ),
        ));
    }

    public function testGetCollection()
    {
        $this->assertTrue(empty($this->tableModel->getCollection()));
    }

    public function testWithCollection()
    {
        $oldDummy = clone $this->dummy;
        $this->dummy->set('name', 'Olga Angulo Reguera');
        $this->assertThat(
            $this->tableModel->withCollection($oldDummy),
            $this->logicalNot(
                $this->equalTo($this->tableModel->withCollection($this->dummy))
            )
        );
    }
}
