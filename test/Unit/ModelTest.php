<?php declare(strict_types=1);

namespace One\Test\Unit;

use One\Collection;
use One\Model\Model;

class ModelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * table model
     * @var \One\Model\Model
     */
    protected $tableModel;

    /**
     * Collection
     * @var \One\Collection
     */
    protected $dummy;

    protected function setUp(): void
    {
        $this->tableModel = new Model();

        $this->dummy = new Collection([
            'name' => 'Prof. Hermann Seip B.Sc.',
            'address' => '688 Anthony Ferry Suite 661
            West Heathermouth, WA 21818',
            'dob' => '1983-02-14',
            'email' => 'jarajoaquin@yahoo.com',
            'atr' => null,
            'age' => 87,
            'thing' => 'Cupiditate libero nulla aperiam culpa. Dolorum consequuntur quae ad. Atque pariatur odio veniam magnam possimus.',
            'posts' => [
                'title' => 'Veniam libero eveniet molestias cupiditate corporis.',
            ],
        ]);
    }

    public function testGetCollection(): void
    {
        $this->assertTrue(empty($this->tableModel->getCollection()));
    }

    public function testWithCollection(): void
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
